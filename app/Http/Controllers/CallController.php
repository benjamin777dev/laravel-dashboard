<?php

namespace App\Http\Controllers;

use App\Models\CallRecord;
use App\Services\ZohoCRM;
use Illuminate\Http\Request;
use App\Services\DatabaseService;

class CallController extends Controller
{
    //
    public function listCallRecord(Request $request) {
        $user = $this->user();
        if( !$user ) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        return "index";
    }

    public function saveCallRecord(Request $request) {
        $user = $this->user();
        if( !$user ) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
    }
}