<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DatabaseService;
use App\Services\ZohoCRM;
use Illuminate\Http\Response;

class NonTmController extends Controller
{
    public function index(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;

        return view('nontm.index')->render();
    }
}
