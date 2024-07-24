<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
// use App\Models\Contact;
// use App\Models\ContactGroups;
// use App\Models\Groups;
// use App\Models\User;
// use App\Services\DatabaseService;
// use App\Services\Helper;
// use App\Services\ZohoCRM;
// use Illuminate\Support\Facades\Log;
// use DataTables;
// use Illuminate\Support\Facades\Validator;
// use App\Rules\ValidMobile;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        
        return view('emails.email-inbox');
    }

    public function emailList(Request $request)
    {
        
        return view('emails.email-list')->render();
    }
}
