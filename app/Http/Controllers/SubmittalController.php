<?php

namespace App\Http\Controllers;

use App\Models\Aci;
use App\Models\Deal;
use App\Models\User;
use App\Services\DatabaseService;
use App\Services\Helper;
use App\Services\ZohoCRM;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DataTables;



class SubmittalController extends Controller
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
            $submittals = $db->retreiveSubmittals($deal->zoho_deal_id);
            // echo "<>"
            // return view('submittals.index', compact('deal','submittals'))->render(); 
            return Datatables::of($submittals)->make(true);

        } catch (\Throwable $th) {
            return $th;
            throw $th;
        }
        
    }
    public function createACI(Request $request){
       $aci = $request->data;
        if (isset($aci['CHR_Agent'])) {
            $user = User::where('zoho_id', $aci['CHR_Agent']['id'])->first();
        }
        if (isset($aci['Transaction'])) {
            $deal = Deal::where('zoho_deal_id', $aci['Transaction']['id'])->first();
        }
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        try {
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        $response = $zoho->createAciData($aci);
        if (!$response->successful()) {
            Log::error("Error retrieving aci: " . $response->body());
            throw $response->body();
        }
        $helper = new Helper();
        Aci::updateOrCreate(['zoho_aci_id' => $aci['id']], [
            "closing_date" => isset($aci['Closing_Date']) ? $helper->convertToUTC($aci['Closing_Date']) : null,
            "current_year" => isset($aci['Current_Year']) ? $aci['Current_Year'] : null,
            "agent_check_amount" => isset($aci['Agent_Check_Amount']) ? $aci['Agent_Check_Amount'] : null,
            "userId" => isset($user['id']) ? $user['id'] : null,
            "irs_reported_1099_income_for_this_transaction" => isset($aci['IRS_Reported_1099_Income_For_This_Transaction']) ? $aci['IRS_Reported_1099_Income_For_This_Transaction'] : null,
            "stage" => isset($aci['Stage']) ? $aci['Stage'] : null,
            "total" => isset($aci['Total']) ? $aci['Total'] : null,
            "zoho_aci_id" => isset($aci['id']) ? $aci['id'] : null,
            'dealId' => isset($deal['id']) ? $deal['id'] : null,
            'agentName' => isset($aci['Name']) ? $aci['Name'] : null,
            'less_split_to_chr' => isset($aci['Less_Split_to_CHR']) ? $aci['Less_Split_to_CHR'] : null,
        ]);
        return $response;
     } catch (\Exception $e) {
            Log::error("Error retrieving aci: " . $e->getMessage());
            return $e;
        }
    }  

    public function showSubmittalView(Request $request)
    {
        Log::info('Showing create pipeline form' . $request);
        $db = new DatabaseService();
        $helper = new Helper();
        // Retrieve user data from the session
        $pipelineData = session('pipeline_data');
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $submittalType = request()->route('type');
        $submittalId = request()->route('submittalId');
        $listingSubmittaltype = request()->query('formType');
        $deals = $db->retrieveDeals($user, $accessToken, null, null, null, null, null);
        $submittal = $db->retrieveSubmittal($user, $accessToken, $submittalId);
        $broucherPrint = config('variables.broucherPrint');
        $broucherLines = config('variables.broucherLines');
        $stickyDots = config('variables.stickyDots');
        $qrCodeSheets = config('variables.qrCodeSheet');
        $featuresCard = config('variables.featuresCard');
        /*$tab = request()->query('tab') ?? 'In Progress';
        $tasks = $db->retreiveTasksFordeal($user, $accessToken, $tab, $deal->zoho_deal_id);
        Log::info("Task Details: " . print_r($tasks, true));
        $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        $dealContacts = $db->retrieveDealContactFordeal($user, $accessToken, $deal->zoho_deal_id);
        $getdealsTransaction = $db->retrieveDeals($user, $accessToken, $search = null, $sortField = null, $sortType = null, "");
        $dealaci = $db->retrieveAciFordeal($user, $accessToken, $dealId);
        $attachments = $db->retreiveAttachment($dealId);
        $nontms = $db->retreiveNonTm($deal->zoho_deal_id);
        $submittals = $db->retreiveSubmittals($deal->zoho_deal_id);
        $contacts = $db->retreiveContactsJson($user, $accessToken);
        $closingDate = Carbon::parse($helper->convertToMST($deal['closing_date']));
        $users = User::all();
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken, "Deals");
        $allStages = config('variables.dealCreateStages');
        $contactRoles = $db->retrieveRoles($user); */
        return view('submittals.view', compact('deals','submittalType','listingSubmittaltype','submittal','broucherPrint','qrCodeSheets','featuresCard','broucherLines','stickyDots','submittalId'));
    }

    public function showSubmittalCreate(Request $request)
    {
        Log::info('Showing create pipeline form' . $request);
        $db = new DatabaseService();
        $helper = new Helper();
        // Retrieve user data from the session
        $pipelineData = session('pipeline_data');
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $submittalType = request()->route('type');
        $submittalId = request()->route('submittalId');
        $listingSubmittaltype = request()->query('formType');
        // $deals = $db->retrieveDeals($user, $accessToken, null, null, null, null, null);
        $submittal = $db->retrieveSubmittal($user, $accessToken, $submittalId);
        // $broucherPrint = config('variables.broucherPrint');
        // $featuresCard = config('variables.featuresCard');
        return view('submittals.create', compact('submittal','submittalType','listingSubmittaltype','submittalId'));
    }

    public function showListingSubmittalForm(Request $request)
    {
        Log::info('Showing create pipeline form' . $request);
        $db = new DatabaseService();
        $helper = new Helper();
        // Retrieve user data from the session
        $pipelineData = session('pipeline_data');
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $submittalType = request()->route('type');
        $submittalId = request()->route('submittalId');
        $listingSubmittaltype = request()->query('formType');
        $resubmit = request()->query('resubmit');
        $deals = $db->retrieveDeals($user, $accessToken, null, null, null, null, null);
        $submittal = $db->retrieveSubmittal($user, $accessToken, $submittalId);
        $broucherPrint = config('variables.broucherPrint');
        $broucherLines = config('variables.broucherLines');
        $stickyDots = config('variables.stickyDots');
        $qrCodeSheets = config('variables.qrCodeSheet');
        $featuresCard = config('variables.featuresCard');
        return view('submittals.listingsubmittalcourusal', compact('deals','submittalType','listingSubmittaltype','submittal','broucherPrint','qrCodeSheets','broucherLines','stickyDots','featuresCard','resubmit'))->render();
    }

    public function showBuyerSubmittalForm(Request $request)
    {
        Log::info('Showing create pipeline form' . $request);
        $db = new DatabaseService();
        $helper = new Helper();
        // Retrieve user data from the session
        $pipelineData = session('pipeline_data');
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $submittalType = request()->route('type');
        $submittalId = request()->route('submittalId');
        $listingSubmittaltype = request()->query('formType');
        $deals = $db->retrieveDeals($user, $accessToken, null, null, null, null, null);
        $submittal = $db->retrieveSubmittal($user, $accessToken, $submittalId);
        $broucherPrint = config('variables.broucherPrint');
        $featuresCard = config('variables.featuresCard');
        return view('submittals.buyersubmittalcourusal', compact('deals','submittalType','listingSubmittaltype','submittal','broucherPrint','featuresCard'))->render();
    }

    public function createListingSubmittal(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $dealId = $request->route('dealId');
        $jsonData = $request->json()->all();
        $formType = $jsonData['data'][0]['formType'];
        $isIncompleteSubmittal = $db->getIncompleteSubmittal($user, $accessToken,$dealId,'listing-submittal',$formType);
        // $isIncompleteSubmittal =null;
        if ($isIncompleteSubmittal) {
            return response()->json($isIncompleteSubmittal);
        } else {
            // $submittal = $zoho->createListingSubmittal($jsonData);
            // if (!$submittal->successful()) {
            //     return "error something" . $submittal;
            // }
            // $submittalArray = json_decode($submittal, true);
            // $data = $submittalArray['data'][0]['details'];
            $submittalData = $jsonData['data'][0];
            $deal = $db->createListingSubmittal($user, $accessToken, null,$submittalData,$dealId,'listing-submittal');
            return response()->json($deal);
        }


    }

    public function updateListingSubmittal(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $submittalId = $request->route('submittalId');
        $isNew = $request->query('isNew');
        $jsonData = $request->json()->all();
        $submittalData =$db->retrieveSubmittal($user, $accessToken, $submittalId);
        if(!$submittalData){
                $submittalData = $db->retrieveSubmittal($user, $accessToken, $submittalId);;
            }
        if($submittalData['isSubmittalComplete']=="true"){
            $submittal = $zoho->updateListingSubmittal($submittalData['zoho_submittal_id'],$jsonData);
        }else{
            $submittal = $zoho->createListingSubmittal($jsonData);
        }
        if (!$submittal->successful()) {
            return "error something" . $submittal;
        }
        $submittalArray = json_decode($submittal, true);
        $data = $submittalArray['data'][0]['details'];
        $submittalData = $jsonData['data'][0];
        $submittalData['id']=$submittalId;
        $deal = $db->updateListingSubmittal($user, $accessToken, $data,$submittalData,$isNew);
        return response()->json($deal);
    }

    public function createBuyerSubmittal(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $dealId = $request->route('dealId');
        $jsonData = $request->json()->all();
        $isIncompleteSubmittal = $db->getIncompleteSubmittal($user, $accessToken,$dealId,'buyer-submittal',null);
        // $isIncompleteSubmittal =null;
        if ($isIncompleteSubmittal) {
            return response()->json($isIncompleteSubmittal);
        } else {
            // $submittal = $zoho->createBuyerSubmittal($jsonData);
            // if (!$submittal->successful()) {
            //     return "error something" . $submittal;
            // }
            // $submittalArray = json_decode($submittal, true);
            // $data = $submittalArray['data'][0]['details'];
            $submittalData = $jsonData['data'][0];
            $deal = $db->createListingSubmittal($user, $accessToken, null,$submittalData,$dealId,'buyer-submittal');
            return response()->json($deal);
        }


    }

    public function updateBuyerSubmittal(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $submittalId = $request->route('submittalId');
        $isNew = $request->query('isNew');
        $jsonData = $request->json()->all();
        $submittalData =$db->retrieveSubmittal($user, $accessToken, $submittalId);
        if(!$submittalData){
                $submittalData = $db->retrieveSubmittal($user, $accessToken, $submittalId);;
            }
        if($submittalData['isSubmittalComplete']=="true"){
            $submittal = $zoho->updateBuyerSubmittal($submittalData['zoho_submittal_id'],$jsonData);
        }else{
            $submittal = $zoho->createBuyerSubmittal($jsonData);
        }
        if (!$submittal->successful()) {
            return "error something" . $submittal;
        }
        $submittalArray = json_decode($submittal, true);
        $data = $submittalArray['data'][0]['details'];
        $submittalData = $jsonData['data'][0];
        $submittalData['id']=$submittalId;
        $deal = $db->updateBuyerSubmittal($user, $accessToken, $data,$submittalData,$isNew);
        return response()->json($deal);
    }
}