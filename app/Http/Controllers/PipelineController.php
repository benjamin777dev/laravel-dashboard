<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\DatabaseService;
use App\Services\ZohoCRM;
use Carbon\Carbon;
use App\Services\Helper;
use Illuminate\Http\Response;



class PipelineController extends Controller
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
        $search = request()->query('search');
        $sortField = $request->input('sort');
        $sortType = $request->input('sortType');
        $filter = $request->input('filter');
        $deals = $db->retrieveDeals($user, $accessToken, $search, $sortField, $sortType, null, $filter);

        // configure the necessary pipeline stats
        $totalSalesVolume = 0;
        $totalCommission = 0;
        $averageCommission = 0;
        $totalPotentialGCI = 0;
        $totalProbability = 0;
        $averageProbability = 0;
        $totalProbableGCI = 0;
        $dealCount = count($deals);

        // Calculate stats from deals
        // Calculate stats from deals
        foreach ($deals as $deal) {
            $totalSalesVolume += $deal->sale_price ?? 0; // Updated field name
            $totalCommission += $deal->commission ?? 0;       // Directly mapped
            $totalPotentialGCI += $deal->potential_gci ?? 0;  // Directly mapped
            $totalProbability += $deal->pipeline_probability ?? 0; // Updated field name
            $totalProbableGCI += (($deal->sale_price ?? 0) * ($deal->commission ?? 0) * (($deal->pipeline_probability ?? 0) / 100)); // Calculate based on percentage
        }

        // Calculate averages
        $averageCommission = $dealCount > 0 ? $totalCommission / $dealCount : 0;
        $averageProbability = $dealCount > 0 ? $totalProbability / $dealCount : 0;
        
        $allstages = config('variables.dealStages');
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken, "Deals");
        $userContact = $db->retrieveContactDetailsByZohoId($user, $accessToken,$user->zoho_id);
        $getdealsTransaction = $db->retrieveDeals($user, $accessToken, $search = null, $sortField = null, $sortType = null, "");
        if (request()->ajax()) {
            // If it's an AJAX request, return the pagination HTML
            return view('pipeline.pipelineload', compact('deals', 'allstages', 'retrieveModuleData', 'getdealsTransaction', 'totalSalesVolume', 'averageCommission', 'totalPotentialGCI', 'averageProbability', 'totalProbableGCI'))->render();
        }
        return view('pipeline.index', compact('deals','userContact', 'allstages', 'retrieveModuleData', 'getdealsTransaction', 'totalSalesVolume', 'averageCommission', 'totalPotentialGCI', 'averageProbability', 'totalProbableGCI'))->render();
    }

    public function getDeals(Request $request)
    {
        $db = new DatabaseService();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        LOG::info('Access Token Decrypted'.$accessToken);
        $search = request()->query('search');
        $sortField = $request->input('sort');
        $sortType = $request->input('sortType');
        $filter = $request->input('filter');
        $deals = $db->retrieveDeals($user, $accessToken, $search, $sortField, $sortType, null, $filter);
        $allstages = config('variables.dealStages');
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken, "Deals");
        $getdealsTransaction = $db->retrieveDeals($user, $accessToken, $search = null, $sortField = null, $sortType = null, "");
        // return response()->json($deals);
        return view('pipeline.transaction', compact('deals', 'allstages', 'retrieveModuleData', 'getdealsTransaction'))->render();
    }

    public function showViewPipelineForm(Request $request)
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
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        $tab = request()->query('tab') ?? 'In Progress';
        $dealId = request()->route('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        Log::info("deals and tab data " . $tab . $dealId);
        $tasks = $db->retreiveTasksFordeal($user, $accessToken, $tab, $deal->zoho_deal_id);
        Log::info("Task Details: " . print_r($tasks, true));
        $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        $dealContacts = $db->retrieveDealContactFordeal($user, $accessToken, $deal->zoho_deal_id);
        $getdealsTransaction = $db->retrieveDeals($user, $accessToken, $search = null, $sortField = null, $sortType = null, "");
        $dealaci = $db->retrieveAciFordeal($user, $accessToken, $dealId);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken, "Deals");
        $attachments = $db->retreiveAttachment($deal->zoho_deal_id);
        $nontms = $db->retreiveNonTm($deal->zoho_deal_id);
        $contacts = $db->retreiveContactsJson($user, $accessToken);
        $users = User::all();
        $contactRoles = $db->retrieveRoles($user);
        $submittals = $db->retreiveSubmittals($deal->zoho_deal_id);
        $allStages = config('variables.dealStages');
        $closingDate = Carbon::parse($helper->convertToMST($deal['closing_date']));
        return view('pipeline.view', compact('tasks', 'notesInfo', 'tab','users','contacts', 'pipelineData', 'getdealsTransaction', 'deal', 'closingDate', 'dealContacts', 'dealaci', 'retrieveModuleData', 'attachments', 'nontms', 'submittals', 'allStages','contactRoles'));

    }

    public function showCreatePipelineForm(Request $request)
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
        $dealId = request()->route('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);

        $tab = request()->query('tab') ?? 'In Progress';
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
        $contactRoles = $db->retrieveRoles($user);
        return view('pipeline.create', compact('tasks','contactRoles','users', 'tab', 'notesInfo','contacts', 'pipelineData', 'getdealsTransaction', 'deal', 'closingDate', 'dealContacts', 'dealaci', 'dealId', 'retrieveModuleData', 'attachments', 'nontms', 'submittals', 'allStages'));
    }

    public function getDeal(Request $request)
    {
        $db = new DatabaseService();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $dealId = request()->params('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        return response()->json($deal);
        // return view('pipeline.index', compact('deals'));
    }

    public function createPipeline(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $jsonData = $request->json()->all();
        $contact = null;
        if(isset($jsonData['data'][0]['Client_Name_Primary'])){
            $contact = $jsonData['data'][0]['Client_Name_Primary'];
        }
        $isIncompleteDeal = $db->getIncompleteDeal($user, $accessToken,$contact);
        if ($isIncompleteDeal) {
            return response()->json($isIncompleteDeal);
        } else {
            
            $zohoDeal = $zoho->createZohoDeal($jsonData);
            if (!$zohoDeal->successful()) {
                return "error somthing" . $zohoDeal;
            }
            $zohoDealArray = json_decode($zohoDeal, true);
            $data = $zohoDealArray['data'][0]['details'];
            $dealData = $jsonData['data'][0];
            $deal = $db->createDeal($user, $accessToken, $data,$dealData);
            return response()->json($deal);
        }


    }

    public function updatePipeline(Request $request, $id, DB $db, ZohoCRM $zoho)
    {
        try {
            $user = auth()->user();
        
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
            
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;

            $jsonData = $request->json()->all();
            $zohoDeal = $zoho->updateZohoDeal($jsonData, $id);
            
            if (!$zohoDeal->successful()) {
                return response()->json(['error' => 'Zoho Deal update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            $zohoDealArray = json_decode($zohoDeal, true);
            $zohoDealData = $zohoDealArray['data'][0]['details'];
            $resp = $zoho->getZohoDeal($zohoDealData['id']);
            
            if (!$resp->successful()) {
                return response()->json(['error' => 'Zoho Deal retrieval failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            $zohoDeal_Array = json_decode($resp, true);
            $zohoDealValues = $zohoDeal_Array['data'][0];
            $data = $jsonData['data'];
            $deal = $db->updateDeal($user, $accessToken, $zohoDealValues, $id);
            
            return response()->json($zohoDealArray);
        } catch (\Throwable $th) {
            // Handle the exception here
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    

    public function getClosedDeals(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $closedDeals = $this->retrieveClosedDealsFromZoho($user->root_user_id, $accessToken);

        return view('pipeline.closed', compact('closedDeals')); // Assume you'll create a view for closed deals
    }

    private function retrieveClosedDealsFromZoho($rootUserId, $accessToken)
    {
        $url = 'https://www.zohoapis.com/crm/v6/Deals/search';
        $criteria = "(Owner:equals:$rootUserId)and((Stage:starts_with:Dead)or(Stage:equals:Sold))";
        $fields = "Address,Amount,City,Primary_Contact,Client_Name_Primary,Client_Name_Only,Closing_Date,Created_By,Created_Time,Commission,Contact_Name,Contract,Create_Date,Created_By,Double_Ended,Lender_Company,Lender_Company_Name,Lender_Name,Loan_Amount,Loan_Type,MLS_No,Needs_New_Date,Needs_New_Date1,Needs_New_Date2,Ownership_Type,Personal_Transaction,Pipeline_Probability,Potential_GCI,Primary_Contact_Email,Probability,Pipeline1,Probable_Volume,Property_Type,Representing,Sale_Price,Stage,State,TM_Name,TM_Preference,Deal_Name,Owner,Transaction_Type,Type,Under_Contract,Using_TM,Z_Project_Id,Zip";

        $params = [
            'page' => 1,
            'per_page' => 200,
            'criteria' => $criteria,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get($url, $params);

            if ($response->successful()) {
                $responseData = $response->json();
                $closedDeals = collect($responseData['data'] ?? []);
                return $closedDeals;
            } else {
                Log::error("Error fetching closed deals: {$response->body()}");
                return collect();
            }
        } catch (\Exception $e) {
            Log::error("Exception when fetching closed deals: {$e->getMessage()}");
            return collect();
        }
    }

    private function retrieveDealsFromZoho($rootUserId, $accessToken)
    {
        $url = 'https://www.zohoapis.com/crm/v6/Deals/search';
        $criteria = "(Contact_Name:equals:$rootUserId)and(Stage:in:Potential,Pre-Active,Active,Under Contract)";
        $fields = "Address,Amount,City,Primary_Contact,Client_Name_Primary,Client_Name_Only,Closing_Date,Created_By,Created_Time,Commission,Contact_Name,Contract,Create_Date,Created_By,Double_Ended,Lender_Company,Lender_Company_Name,Lender_Name,Loan_Amount,Loan_Type,MLS_No,Needs_New_Date,Needs_New_Date1,Needs_New_Date2,Ownership_Type,Personal_Transaction,Pipeline_Probability,Potential_GCI,Primary_Contact_Email,Probability,Pipeline1,Probable_Volume,Property_Type,Representing,Sale_Price,Stage,State,TM_Name,TM_Preference,Deal_Name,Owner,Transaction_Type,Type,Under_Contract,Using_TM,Z_Project_Id,Zip";

        Log::info("Fetching deals from Zoho");
        Log::info("URL: $url");
        Log::info("Criteria: $criteria");
        Log::info("Fields: $fields");

        try {

            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get($url, [
                        'page' => 1,
                        'per_page' => 200,
                        'criteria' => $criteria,
                        'fields' => $fields,
                    ]);
            Log::info("Response: " . print_r($response, true));

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info("Response data: " . print_r($responseData, true));
                $deals = collect($responseData['data'] ?? []);
                Log::info("Deals: " . print_r($deals, true));
                // You might want to transform or enrich the deals data here
                return $deals;
            } else {
                Log::error("Error fetching deals: {$response->body()}");
                return collect();
            }
        } catch (\Exception $e) {
            Log::error("Exception when fetching deals: {$e->getMessage()}");
            return collect();
        }
    }
    public function retriveNotesForDeal()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $dealId = request()->route('dealId');
        $accessToken = $user->getAccessToken();
        $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken, "Deals");
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        return view('common.notes.listPopup',  compact('notesInfo','retrieveModuleData','deal'))->render();
    }

    public function addContactRole(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $zoho = new ZohoCRM();
        $dealId = request()->route('dealId');
        $jsonData = $request->json()->all();
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;

        $contactRole = $zoho->addContactRoleForDeal($dealId, $jsonData);
        foreach ($contactRole as $response) {
            if (!$response->successful()) {
                // Log the error for debugging
                Log::error('Error adding contact role: ' . $response->body());
                return response()->json(['error' => 'Error adding contact role', 'details' => $response->body()], 500);
            }
        }

        $contactRoleArray = json_decode($contactRole, true);
        return $contactRoleArray;
    }
}
