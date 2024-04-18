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
        $zoho = new ZohoCRM();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $zoho->access_token = $accessToken;
        $contacts = $zoho->retrieveGroupsFromZoho($user->root_user_id, $accessToken);

        return view('group.index', compact('contacts'));
    }

}
