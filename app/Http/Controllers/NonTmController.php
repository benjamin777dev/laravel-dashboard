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
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $dealId = request()->params('id');
       
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        return view('nontm.index',compact('deal'))->render();
    }


    public function createNontmView(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $dealId = request()->route('id');
        $deal = $db->retrieveDealDataById($user, $accessToken, $dealId);
        return view('nontm.create',compact('deal'))->render();
    }
    
    public function createNontm(Request $request) {

        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $jsonData = $request->json()->all();
        $nontm = null;
        if(isset($jsonData['data'][0]['Related_Transaction']['id'])){
            $nontm = $jsonData['data'][0]['Related_Transaction']['id'];
        }
        $isIncompleteDeal = $db->getIncompleteNonTm($user, $accessToken,$nontm);
        if ($isIncompleteDeal) {
            return response()->json($isIncompleteDeal);
        } else {
            
            $dealData = $jsonData['data'][0];
            $zohoDeal = $zoho->createZohoNonTm($jsonData);
            if (!$zohoDeal->successful()) {
                return "error somthing" . $zohoDeal;
                }
                $zohoDealArray = json_decode($zohoDeal, true);
                $deal = $db->createNonTmData($user, $accessToken,$dealData ,$zohoDealArray);
            return response()->json($deal);
        }
    }
}
