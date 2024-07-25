<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DatabaseService;
use App\Services\ZohoCRM;
use Illuminate\Http\Response;
use App\Models\NonTm;
use App\Services\Helper;
use DataTables;

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
            // return view('nontm.index', compact('deal','nontms'))->render(); 
            return Datatables::of($nontms)->make(true);

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
        $dealData = $db->retrieveNonTmById($user, $accessToken, $dealId);
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
        $dealData = $db->retrieveNonTmById($user, $accessToken, $dealId);
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
            // $zohoDeal = $zoho->createZohoNonTm($jsonData);
            // if (!$zohoDeal->successful()) {
            //     return "error somthing" . $zohoDeal;
            // }
            // $zohoDealArray = json_decode($zohoDeal, true);
            $deal = $db->createNonTmData($user, $accessToken,$dealData ,null);
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
        $nonTm = $db->retrieveNonTmById($user,$accessToken,$nontmId);
        if(!$nonTm){
            return response()->json(['error' => 'Non TM Id Not Found'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if($nonTm->isNonTmCompleted){
            $zohoNonTm = $zoho->updateZohoNonTm($jsonData,$nonTm->zoho_nontm_id);
        }else{
            $zohoNonTm= $zoho->createZohoNonTm($jsonData);
        }
        if (!$zohoNonTm->successful()) {
            return "error somthing" . $zohoNonTm;
        }

        $updateNonTmData = [
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
            'referralFeeAmount' => isset($jsonData['data'][0]['Referral_Fee_Amount']) ? $jsonData['data'][0]['Referral_Fee_Amount'] : null,
            'referralFeeBrokerage' => isset($jsonData['data'][0]['Referral_Fee_Brokerage_Name']) ? $jsonData['data'][0]['Referral_Fee_Brokerage_Name'] : null,
            'referralAgreement' => isset($jsonData['data'][0]['Referral_Fee_Agreement_Executed']) ? $jsonData['data'][0]['Referral_Fee_Agreement_Executed'] : null,
            'hasW9Provided' => isset($jsonData['data'][0]['Has_the_W-9_been_provided']) ? $jsonData['data'][0]['Has_the_W-9_been_provided'] : null,
            'homeWarrentyAmount' => isset($jsonData['data'][0]['Home_Warranty_Amount']) ? $jsonData['data'][0]['Home_Warranty_Amount'] : null,
            'homeWarrentyDescription' => isset($jsonData['data'][0]['Home_Warranty_Description']) ? $jsonData['data'][0]['Home_Warranty_Description'] : null,
            'additionalFeesAmount' => isset($jsonData['data'][0]['Additonal_Fees_Amount']) ? $jsonData['data'][0]['Additonal_Fees_Amount'] : null,
            'additionalFeesDescription' => isset($jsonData['data'][0]['Additional_Fees_Description']) ? $jsonData['data'][0]['Additional_Fees_Description'] : null,
            'isNonTmCompleted' => ($status == true) ? true : false,
            "dealId" => isset($jsonData['data'][0]['Related_Transaction']['id']) ? $jsonData['data'][0]['Related_Transaction']['id'] : null,
        ];
        if($nonTm->zoho_nontm_id === null){
            $updateNonTmData['zoho_nontm_id'] = $zohoNonTm['data']['0']['details']['id'];
        }
        NonTm::updateOrCreate(['id' => $nontmId],$updateNonTmData );
        return $zohoNonTm;
    }
}
