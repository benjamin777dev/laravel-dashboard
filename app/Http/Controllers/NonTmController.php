<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DatabaseService;
use App\Services\ZohoCRM;
use Illuminate\Http\Response;
use App\Models\NonTm;
use App\Services\Helper;
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
        try{
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $dealId = request()->route('dealId');
            $deal = $db->retrieveDealById($user, $accessToken, $dealId);
            $nontms = $db->retreiveNonTm($deal->zoho_deal_id);
           /*  print_r($nontms);
            die; */
            return view('nontm.index', compact('deal','nontms'))->render();
        } catch (\Throwable $th) {
            return $th;
            throw $th;
        }
        
    }

    public function getNonTm(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $dealId = request()->route('id');
        $dealData = $db->retrieveDealDataById($user, $accessToken, $dealId);
        $deals = $db->retrieveDeals($user, $accessToken, null, null, null, null, null);
        return view('nontm.view',compact('dealData','deals'))->render();
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
        $dealData = $db->retrieveDealDataById($user, $accessToken, $dealId);
        $deals = $db->retrieveDeals($user, $accessToken, null, null, null, null, null);
        return view('nontm.create',compact('dealData','deals'))->render();
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

    public function updateNonTm(Request $request){
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $helper = new Helper();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $nontmId = request()->route('id');
        $status = request()->query('status');
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $jsonData = $request->json()->all();
        $zohoNonTm = $zoho->updateZohoNonTm($jsonData,$nontmId);
        if (!$zohoNonTm->successful()) {
            return "error somthing" . $zohoNonTm;
         }
         NonTm::updateOrCreate(['zoho_nontm_id' => $nontmId], [
            "closed_date" => isset($jsonData['data'][0]['Close_Date']) ? $helper->convertToUTC($jsonData['data'][0]['Close_Date']) : null,
            "email" => isset($jsonData['data'][0]['Additional_Email_for_Confirmation']) ? $jsonData['data'][0]['Additional_Email_for_Confirmation'] : null,
            "Commission" => isset($jsonData['data'][0]['Commission']) ? $jsonData['data'][0]['Commission'] : null,
            "final_purchase_price" => isset($jsonData['data'][0]['Final_Purchase_Price']) ? $jsonData['data'][0]['Final_Purchase_Price'] : null,
            "referral_fee_paid_out" => isset($jsonData['data'][0]['Referral_Fee_Paid_Out']) ? $jsonData['data'][0]['Referral_Fee_Paid_Out'] : null,
            "home_warranty_paid_out_agent" => isset($jsonData['data'][0]['Home_Warranty_Paid_by_Agent']) ? $jsonData['data'][0]['Home_Warranty_Paid_by_Agent'] : null,
            "any_additional_fees_charged" =>isset($jsonData['data'][0]['Any_Additional_Fees_Charged']) ? $jsonData['data'][0]['Any_Additional_Fees_Charged'] : null,
            "amount_to_chr_gives" =>isset($jsonData['data'][0]['CHR_Gives_Amount_to_Give']) ? $jsonData['data'][0]['CHR_Gives_Amount_to_Give'] : null,
            'agent_comments' => isset($jsonData['data'][0]['Agent_Comments_Remarks_Instructions']) ? $jsonData['data'][0]['Agent_Comments_Remarks_Instructions'] : null,
            'other_commission_notes' => isset($jsonData['data'][0]['Other_Commission_Notes']) ? $jsonData['data'][0]['Other_Commission_Notes'] : null,
          'isNonTmCompleted' => ($status == true) ? true : false,
          "dealId" => isset($jsonData['data'][0]['Related_Transaction']['id']) ? $jsonData['data'][0]['Related_Transaction']['id'] : null,
        ]);
        return $zohoNonTm;
    }
}
