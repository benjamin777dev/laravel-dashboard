<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\DatabaseService;
use App\Services\ZohoCRM;
use App\Services\SendGrid;
use App\Models\Contact;
use App\Models\User;
// use App\Models\ContactGroups;
// use App\Models\Groups;
// use App\Services\Helper;
// use DataTables;
// use Illuminate\Support\Facades\Validator;
// use App\Rules\ValidMobile;

class EmailController extends Controller
{
    protected function guard()
    {
        return Auth::guard();
    }
    public function index(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('emails.email-inbox',compact('contacts'));
    }

    public function emailList(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        $accessToken = $user->getAccessToken();
        $filter = $request->query('filter');
        $emails = $db->getEmails($user,$filter);
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);          
        return view('emails.email-list',compact('contacts','emails'))->render();
    }

    public function sendEmail(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
        $sendgrid = new SendGrid();
        if(!$user){
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $inputData = $request->json()->all();
        $userVerified = $sendgrid->verifySender($user['email']);
        Log::info('User Verification', ['userVerified' => $userVerified]);
        $inputData['toDetail'] = $db->getContactsByMultipleId($inputData['to']);
        Log::info("TOEMAILDETAIL",[$inputData['toDetail']]);
        $inputData['ccDetail'] = $db->getContactsByMultipleId($inputData['cc']);
        $inputData['bccDetail'] = $db->getContactsByMultipleId($inputData['bcc']);
        $inputData['toData'] = [];
        $inputData['ccData'] = [];
        $inputData['bccData'] = [];
        foreach ($inputData['toDetail'] as $currValue) {
            $inputData['toData'][] = [
                'user_name' => $currValue['name'],
                'email' => $currValue['email'],
            ];
        }
        foreach ($inputData['ccDetail'] as $currValue) {
            $inputData['ccData'][] = [
                'user_name' => $currValue['name'],
                'email' => $currValue['email'],
            ];
        }

        // Process 'bcc' details
        foreach ($inputData['bccDetail'] as $currValue) {
            $inputData['bccData'][] = [
                'user_name' => $currValue['name'],
                'email' => $currValue['email'],
            ];
        }
        $zohoInput =[
            "data"=>[
                [
                    'to' => $inputData['toData'],
                    'cc' => $inputData['ccData'],
                    'bcc' => $inputData['bccData'],
                    'from' => [
                        "user_name"=> $user['name'],
                        'email'=> $user['email'],
                    ],
                    'subject' => $inputData['subject'],
                    'content' => $inputData['content'],
                    "consent_email"=>false,
                ]
            ]
        ]; 
            
        $contact = $db->retrieveContactByEmail($user,$accessToken,$user['email']);
        if($userVerified){
            $sendGridInput = 
            [
                'personalizations' => [
                    [
                        'to' => $inputData['toDetail'],
                       
                        'subject' => $inputData['subject']
                    ]
                ],
                'from' => [
                    "name"=> $user['name'],
                    'email'=> $user['email'],
                ],
                'content' => [
                    [
                        'type' => 'text/html',
                        'value' => $inputData['content']
                    ]
                ]
            ];
            $associateZohoInput = [
                "Emails"=>[
                [
                    'to' => $inputData['toData'],
                    'cc' => $inputData['ccData'],
                    'bcc' => $inputData['bccData'],
                    'from' => [
                        "user_name"=> $user['name'],
                        'email'=> $user['email'],
                    ],
                    'subject' => $inputData['subject'],
                    'content' => $inputData['content'],
                    "consent_email"=>false,
                ]
            ]
                ];
            $associateZohoInput['Emails'][0]['sent'] = true;
            $associateZohoInput['Emails'][0]['date_time'] = now();
            $associateZohoInput['Emails'][0]['original_message_id'] =  Str::uuid();
            $associateEmail = $zoho->assoiciateEmail($associateZohoInput,$contact['zoho_contact_id']);
            if($associateEmail=="AUTHENTICATION_FAILURE"){
                $this->guard()->logout();
                return response()->json([
                    'status' => 'process',
                    'message' => 'AUTHENTICATION_FAILURE, Please Re-signup in ZOHO',
                    'redirect_url' => route('login')
                ]);
            }
            $sendEmail = $sendgrid->sendSendGridEmail($sendGridInput);
            $inputData['sendEmailFrom'] = "SendGrid";
            $inputData['message_id'] = $associateEmail['Emails'][0]['details']['message_id'];
        }else{
            $sendEmail = $zoho->sendZohoEmail($zohoInput,$contact['zoho_contact_id']);
            if($sendEmail=="AUTHENTICATION_FAILURE"){
                $this->guard()->logout();
                return response()->json([
                    'status' => 'process',
                    'message' => 'AUTHENTICATION_FAILURE, Please Re-signup in ZOHO',
                    'redirect_url' => route('login')
                ]);
            }
            $inputData['sendEmailFrom'] = "Zoho";
            $inputData['message_id']=$sendEmail['data'][0]['details']['message_id'];
        }
        // return $sendEmail;
        $response = $db->saveEmail($user,$accessToken,$inputData);    
        return response()->json($response);
    }

    public function emailDetail(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailId = $request->route('emailId');
        $email = $db->getEmailDetail($emailId);    
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('emails.email-read',compact('contacts','email'))->render();
    }

    public function emailDetailDraft(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailId = $request->route('emailId');
        $email = $db->getEmailDetail($emailId);    
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('emails.email-draft',compact('contacts','email'))->render();
    }

    public function emailTemplate(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailId = $request->route('emailId');
        $email = $db->getEmailDetail($emailId);    
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('emails.email-template')->render();
    }

    public function emailMoveToTrash(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailIds = $request->input('emailIds');
        $email = $db->moveToTrash($emailIds);    
        return response()->json(['success' => true]);
    }

       public function getEmailModal()
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $emailId = request()->route('emailId');
        $accessToken = $user->getAccessToken();
        $email = $db->getEmailDetail($emailId);    
        return view('emails.email-read', compact('email'))->render();
    }
}
