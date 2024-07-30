<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
        $inputData = $request->json()->all();
        $userVerified = $sendgrid->verifySender($inputData['from']['email']);
        Log::info('User Verification', ['userVerified' => $userVerified]);
        $zohoInput = 
            [
                'to' => [
                    $inputData['to'],
                ],
                'from' => [
                    "user_name"=> $inputData['from']['name'],
                    'email'=> $inputData['from']['email'],
                ],
                'subject' => $inputData['subject'],
                'content' => $inputData['content']
            ];
        $contact = $db->retrieveContactByEmail($user,$accessToken,$inputData['from']['email']);
        if($userVerified){
            $sendGridInput = 
            [
                'personalizations' => [
                    [
                        'to' => $inputData['to'],
                        'subject' => $inputData['subject']
                    ]
                ],
                'from' => $inputData['from'],
                'content' => [
                    [
                        'type' => 'text/html',
                        'value' => $inputData['content']
                    ]
                ]
            ];
            $sendEmail = $sendgrid->sendSendGridEmail($sendGridInput);
            $associateEmail = $zoho->assoiciateEmail($zohoInput,$contact['zoho_contact_id']);
            $inputData['sendEmailFrom'] = "SendGrid";
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
}
