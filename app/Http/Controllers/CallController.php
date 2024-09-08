<?php

namespace App\Http\Controllers;

use App\Models\CallRecord;
use App\Services\ZohoCRM;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallController extends Controller
{
    //
    public function listCallRecord(Request $request) {
        $user = $this->user();
        if( !$user ) {
            return redirect('/login');
        }
        $contactId = request()->route('contactId');
        $callRecords = CallRecord::where('user_id', $user->id)->where('contact_id', $contactId)->get();
        return Datatables::of($callRecords)->make(true);
    }

    public function saveCallRecord(Request $request) {
        try {
            $user = $this->user();
            if( !$user ) {
                return redirect('/login');
            }
            $contact_id = $request->contact_id;
            $phone_number = $request->phone_number;
            $accessToken = $user->getAccessToken();
            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            CallRecord::create([
                "user_id" => $user->id,
                "contact_id" => $contact_id,
                "phone_number" => $phone_number,
            ]);
            $zoho->saveCallRecord($contact_id, $phone_number);
        } catch (\Exception $e) {
            Log::error("Error when add call record: {$e->getMessage()}");
            return $e->getMessage();
        }
        
    }
}