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
use DataTables;
// use App\Models\ContactGroups;
// use App\Models\Groups;
// use App\Services\Helper;
// use DataTables;
// use Illuminate\Support\Facades\Validator;
// use App\Rules\ValidMobile;

class TemplateController extends Controller
{
    
    public function createTemplate(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if(!$user){
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $inputData = $request->json()->all();
        $response = $db->createTemplate($user,$accessToken,$inputData);    
        return response()->json($response);
    }

    public function getTemplatesFromZoho(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
        if(!$user){
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $inputData = $request->json()->all();
        $response = $zoho->getZohoTemplates();
        $templates = $response['email_templates'];
        $templatesInDB = $db->saveZohoTemplatesInDB($templates);
        return response()->json($response);
    }

    public function getTemplates(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if(!$user){
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        // $inputData = $request->json()->all();
        // $templates = $response['email_templates'];
        $templatesInDB = $db->getTemplatesFromDB($user);
        return response()->json($templatesInDB);
    }

    public function getTemplatesJSON(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
        if(!$user){
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        // $inputData = $request->json()->all();
        // $response = $zoho->getZohoTemplates();
        // $templates = $response['email_templates'];
        $templatesInDB = $db->getTemplatesFromDB($user);
        return Datatables::of($templatesInDB)->make(true);
    }

    public function getTemplateDetail(Request $request)
    {
        try {   
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            if(!$user){
                return redirect('/login');
            }
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $templateId = $request->route('templateId');
            Log::info("TEMPLATEID",[$templateId]);
            $template = $db->getTemplateDetailFromDB($templateId);
            if( $template && $template['content']!=null){
                $templateDetail = $template;
            }else{
                $response = $zoho->getZohoTemplateDetail($template['zoho_template_id']);
                Log::info("Template detail",[$response]);
                if($response){
                    $db->updateZohoTemplate($template['zoho_template_id'],$response['email_templates'][0]);
                    $templateDetail = $db->getTemplateDetailFromDB($templateId);
                }else{
                    throw new \Exception("Template Not Found");
                }
            }
            return response()->json($templateDetail);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function readTemplateDetail(Request $request)
    {
        try {   
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            if(!$user){
                return redirect('/login');
            }
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $templateId = $request->route('templateId');
            Log::info("TEMPLATEID",[$templateId]);
            $template = $db->getTemplateDetailFromDB($templateId);
            if( $template && $template['content']!=null){
                $templateDetail = $template;
            }else{
                $response = $zoho->getZohoTemplateDetail($template['zoho_template_id']);
                Log::info("Template detail",[$response]);
                if($response){
                    $db->updateZohoTemplate($template['zoho_template_id'],$response['email_templates'][0]);
                    $templateDetail = $db->getTemplateDetailFromDB($templateId);
                }else{
                    throw new \Exception("Template Not Found");
                }
            }
            return view('emails.email_templates.email-template-update',compact('templateDetail'))->render();
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function deleteTemplates(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
        if(!$user){
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $inputData = $request->json()->all();
        // $response = $zoho->getZohoTemplates();
        // $templates = $response['email_templates'];
        $templatesInDB = $db->deleteTemplatesFromDB($inputData);
       return response()->json($templatesInDB);
    }

    public function updateTemplate(Request $request)
    {
        try {   
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            if(!$user){
                return redirect('/login');
            }
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $templateId = $request->route('templateId');
            $inputData= $request->json()->all();
            $db->updateTemplate($user,$accessToken,$templateId,$inputData);
            return response()->json(true);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

}
