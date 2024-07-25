<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Services\DatabaseService;
use App\Services\ZohoCRM;
use App\Models\Contact;
// use App\Models\ContactGroups;
// use App\Models\Groups;
// use App\Models\User;
// use App\Services\Helper;
// use Illuminate\Support\Facades\Log;
// use DataTables;
// use Illuminate\Support\Facades\Validator;
// use App\Rules\ValidMobile;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $db = new DatabaseService();
        $user = auth()->user();
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
        $user = auth()->user();
        $filter = $request->query('filter');
        $emails = $db->getEmails($user,$filter);          
        return view('emails.email-list',compact('emails'))->render();
    }

    public function sendEmail(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = auth()->user();
        $accessToken = $user->getAccessToken();
        $inputData = $request->json()->all();
        $responses=[];
        foreach ($inputData['toEmail'] as $to) {
            $responses[] = $db->saveEmail($inputData,$to);    
        }
        return response()->json([
            'Response'=>$responses
        ]);
    }

    public function draftEmail(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = auth()->user();
        $accessToken = $user->getAccessToken();
        $inputData = $request->json()->all();
        $response = $db->draftEmail($inputData);    
        return response()->json([
            'Response'=>$response
        ]);
    }

    

    public function emailDetail(Request $request)
    {
        $db = new DatabaseService();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $emailId = $request->route('emailId');
        $email = $db->getEmailDetail($emailId);    
        $contacts = $db->retreiveContactsHavingEmail($user, $accessToken);
        return view('email-read',compact('contacts','email'))->render();
    }
}
