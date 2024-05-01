<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Services\Helper;
use App\Services\ZohoCRM;
use Carbon\Carbon;
use App\Services\DB;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $db = new DB();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        
        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $contacts = $db->retrieveContactGroups($user, $accessToken);
        $groups = $db->retrieveGroups($user, $accessToken);
        
        $shownGroups = $db->retrieveGroups($user, $accessToken,"shownGroups");
        
        return view('group.index', compact('contacts','groups','shownGroups'));
    }

    public function filterGroups(Request $request)
    {
        $db = new DB();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $columnShow = $request->query('columnShow');
        $columnShowArray = json_decode($columnShow, true);
        $filter = $request->query('filter');
        if($columnShowArray!=[]){
            $db->updateGroups($user, $accessToken,$columnShowArray);
        }
        $shownGroups = $db->retrieveGroups($user, $accessToken,"shownGroups");
        $contacts = $db->retrieveContactGroups($user, $accessToken,$filter);
        return response()->json(['shownGroups' => $shownGroups, 'contacts' => $contacts]);
    }

}
