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

}
