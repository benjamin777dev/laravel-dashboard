<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Aci;
use App\Models\Deal;
use App\Models\User;
use App\Services\DatabaseService;
use App\Services\Helper;
use App\Services\ZohoCRM;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PipelineController extends Controller
{
    public function index(Request $request, DatabaseService $db, ZohoCRM $zoho)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;

        $inputs = $request->only(['search', 'sort', 'sortType', 'filter']);

        $deals = $db->retrieveDeals(
            $user, // user
            $inputs['search'] ?? null, // search filters if exist
            $inputs['sort'] ?? null,  // sort filter if exist
            $inputs['sortType'] ?? null,  // sort type if exist
            null,  // date filter (none)
            $inputs['filter'] ?? null // filter
        ); 

        $allDeals = $db->retrieveDeals(
            $user, // user
            null, // search filters if exist
            null,  // sort filter if exist
            null,  // sort type if exist
            null,  // date filter (none)
            null, // filter
            true
        ); 

        $submittalDeals = $db->retrieveSubmittalDeals(
            $user, // user
            $accessToken,  // access token 
        ); 

        $stats = $this->calculateDealStatistics($allDeals);

        $allstages = config('variables.dealStages');
        $retrieveModuleData = $db->retrieveModuleDataDB( $accessToken, "Deals");
        $userContact = $db->retrieveContactDetailsByZohoId($user, $accessToken, $user->zoho_id);

        $viewData = array_merge($stats, [
            'deals' => $deals,
            'userContact' => $userContact,
            'allstages' => $allstages,
            'retrieveModuleData' => $retrieveModuleData,
            'getdealsTransaction' => $deals,
            'allDeals' => $allDeals,
            'submittalDeals' => $submittalDeals,
        ]);

        if ($request->ajax()) {
            return view('pipeline.pipelineload', $viewData)->render();
        }

        return view('pipeline.index', $viewData)->render();
    }

    private function calculateDealStatistics($deals)
    {
        $totalSalesVolume = 0;
        $totalCommission = 0;
        $totalPotentialGCI = 0;
        $totalProbability = 0;
        $totalProbableGCI = 0;
        $dealCount = count($deals);

        foreach ($deals as $deal) {
            $salePrice = $deal->sale_price ?? 0;
            $commission = $deal->commission ?? 0;
            $pipelineProbability = $deal->pipeline_probability ?? 0;

            $totalSalesVolume += $salePrice;
            $totalCommission += $commission;
            $totalPotentialGCI += $salePrice * ($commission / 100);
            $totalProbability += $pipelineProbability;
            $totalProbableGCI += ($salePrice * ($commission / 100)) * ($pipelineProbability / 100);
        }

        return [
            'totalSalesVolume' => $totalSalesVolume,
            'averageCommission' => $dealCount > 0 ? $totalCommission / $dealCount : 0,
            'totalPotentialGCI' => $totalPotentialGCI,
            'averageProbability' => $dealCount > 0 ? $totalProbability / $dealCount : 0,
            'totalProbableGCI' => $totalProbableGCI,
        ];
    }

    public function getDeals(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        LOG::info('Access Token Decrypted' . $accessToken);
        $search = request()->query('search');
        $sortField = $request->input('sort');
        $sortType = $request->input('sortType');
        $filter = $request->input('filter');
        $deals = $db->retrieveDeals($user, $search, $sortField, $sortType, null, $filter);
        $allstages = config('variables.dealStages');
        $retrieveModuleData = $db->retrieveModuleDataDB( $accessToken, "Deals");
        $getdealsTransaction = $db->retrieveDeals($user, $search = null, $sortField = null, $sortType = null, "");
        // return response()->json($deals);
        return view('pipeline.transaction', compact('deals', 'allstages', 'retrieveModuleData', 'getdealsTransaction'))->render();
    }

    public function getDealsJson(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        LOG::info('Access Token Decrypted' . $accessToken);
        $search = request()->query('search');
        $stage = request()->query('stage');
        $deals = $db->retrieveDeals($user, $search, null, null, null, $stage);
        return Datatables::of($deals)->make(true);
       
    }
    

    public function createACI(Request $request)
    {
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
                throw new Exception($response->body());
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

    public function showViewPipeline(Request $request)
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
        $zoho = new ZohoCRM();
        $users = User::all();
        $zoho->access_token = $accessToken;
        $tab = request()->query('tab') ?? 'In Progress';
        $dealId = request()->route('dealId');
        $contacts = $db->retreiveContactsJson($user, $accessToken);
        $deals = $db->retrieveDeals(
            $user, // user
            $inputs['search'] ?? null, // search filters if exist
            $inputs['sort'] ?? null,  // sort filter if exist
            $inputs['sortType'] ?? null,  // sort type if exist
            null,  // date filter (none)
            $inputs['filter'] ?? null // filter
        );
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        $closingDate = isset($deal['closing_date'])??Carbon::parse($helper->convertToMST($deal['closing_date']));
        $allStages = config('variables.dealStages');
        $submittals = $db->retreiveSubmittals($deal->zoho_deal_id);
        $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        $retrieveModuleData = $db->retrieveModuleDataDB( $accessToken, "Deals");
        $nontms = $db->retreiveNonTm($deal->zoho_deal_id);
        
        return view('pipeline.view', compact('deal','users','deals', 'dealId','contacts','closingDate', 'notesInfo','allStages', 'retrieveModuleData', 'tab','submittals','nontms'))->render();
    }

    public function showViewPipelineForm(Request $request)
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
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        $dealId = request()->route('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        $contacts = $db->retreiveContactsJson($user, $accessToken);
        $users = User::all();
        $submittals = $db->retreiveSubmittals($deal->zoho_deal_id);
        $nontms = $db->retreiveNonTm($deal->zoho_deal_id);
        $allStages = config('variables.dealStages');
        $closingDate = Carbon::parse($helper->convertToMST($deal['closing_date']));
        return view('pipeline.detail', compact('users', 'contacts', 'deal', 'closingDate', 'allStages', 'dealId', 'submittals', 'nontms'))->render();

    }

    public function showCreatePipeline(Request $request)
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
        $dealId = request()->route('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        // $deals = $db->retrieveDeals($user, null, null, null, null, null);
        // $tab = request()->query('tab') ?? 'In Progress';
        // $tasks = $db->retreiveTasksFordeal($user, $accessToken, $tab, $deal->zoho_deal_id);
        // Log::info("Task Details: " . print_r($tasks, true));
        // $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        // $dealContacts = $db->retrieveDealContactFordeal($user, $accessToken, $deal->zoho_deal_id);
        // $getdealsTransaction = $db->retrieveDeals($user, $search = null, $sortField = null, $sortType = null, "");
        // $dealaci = $db->retrieveAciFordeal($user, $accessToken, $dealId);
        // $attachments = $db->retreiveAttachment($dealId);
        // $nontms = $db->retreiveNonTm($deal->zoho_deal_id);
        // $submittals = $db->retreiveSubmittals($deal->zoho_deal_id);
        // $contacts = $db->retreiveContactsJson($user, $accessToken);
        // $closingDate = Carbon::parse($helper->convertToMST($deal['closing_date']));
        // $users = User::all();
        $retrieveModuleData = $db->retrieveModuleDataDB( $accessToken, "Deals");
        // $allStages = config('variables.dealCreateStages');
        // $contactRoles = $db->retrieveRoles($user);
        return view('pipeline.create', compact('dealId', 'deal', 'retrieveModuleData'));
    }

    public function showCreatePipelineForm(Request $request)
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
        $dealId = request()->route('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        if (!$deal) {
            return response()->json(["redirect" => "/pipeline"]);
        }
        $deals = $db->retrieveDeals($user, null, null, null, null, null);
        // $tab = request()->query('tab') ?? 'In Progress';
        // $tasks = $db->retreiveTasksFordeal($user, $accessToken, $tab, $deal->zoho_deal_id);
        // Log::info("Task Details: " . print_r($tasks, true));
        // $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        // $dealContacts = $db->retrieveDealContactFordeal($user, $accessToken, $deal->zoho_deal_id);
        // $getdealsTransaction = $db->retrieveDeals($user, $search = null, $sortField = null, $sortType = null, "");
        // $dealaci = $db->retrieveAciFordeal($user, $accessToken, $dealId);
        // $attachments = $db->retreiveAttachment($dealId);
        // $nontms = $db->retreiveNonTm($deal->zoho_deal_id);
        // $submittals = $db->retreiveSubmittals($deal->zoho_deal_id);
        $contacts = $db->retreiveContactsJson($user, $accessToken);
        // $closingDate = Carbon::parse($helper->convertToMST($deal['closing_date']));
        $users = User::all();
        // $retrieveModuleData = $db->retrieveModuleDataDB( $accessToken, "Deals");
        $allStages = config('variables.dealCreateStages');
        // $contactRoles = $db->retrieveRoles($user);
        return view('pipeline.create-form', compact('deal', 'contacts', 'allStages', 'users'))->render();
    }

    public function createPipeline(Request $request)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $jsonData = $request->json()->all();
        $contact = null;
        if (isset($jsonData['data'][0]['Client_Name_Primary'])) {
            $contact = $jsonData['data'][0]['Client_Name_Primary'];
        }
        $isIncompleteDeal = $db->getIncompleteDeal($user, $accessToken, $contact);
        if ($isIncompleteDeal) {
            return response()->json($isIncompleteDeal);
        } else {
            $dealData = $jsonData['data'][0];
            $deal = $db->createDeal($user, $accessToken, $dealData);
            return response()->json($deal);
        }

    }

    public function updatePipeline(Request $request, $id, DatabaseService $db, ZohoCRM $zoho)
    {
        try {
            $user = $this->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;

            $jsonData = $request->json()->all();
            Log::info("zoho deal Request on creation", [$jsonData]);
            $deal = $db->retrieveDealById($user, $accessToken, $id);
            if (!$deal) {
                $deal = $db->retrieveDealByZohoId($user, $accessToken, $id);
                if (!$deal) {
                    return response()->json(['error' => 'Deal Id Not Found'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            if ($deal->isDealCompleted) {
                $zohoDeal = $zoho->updateZohoDeal($jsonData, $deal->zoho_deal_id);
            } else {
                $zohoDeal = $zoho->createZohoDeal($jsonData);
            }
            Log::info("zoho deal response", [$zohoDeal]);
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
            $deal = $db->updateDeal($user, $accessToken, $zohoDealValues, $deal);
            return response()->json($zohoDealArray);
        } catch (\Throwable $th) {
            // Handle the exception here
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateDeals(Request $request)
    {
        try {
            $user = $this->user();
            $accessToken = $user->getAccessToken();
            $db = new DatabaseService();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
            $id = $request->input('id');
            $dbfield = $request->input('field');
            $value = $request->input('value');
            $rules = [
                'id' => 'required|exists:deals,id',
                'field' => 'required|in:deal_name,address,stage,representing,sale_price,closing_date,commission,pipeline_probability,client_name_primary,pipeline_probability',
                'value' => 'nullable', // Allow the value to be nullable (empty)
            ];
    
            $messages = [
                'id.required' => 'Contact ID is required.',
                'id.exists' => 'Invalid contact ID.',
                'field.required' => 'Field type is required.',
                'field.in' => 'Invalid field type.',
                'value.email' => 'Invalid email format.',
                'value.regex' => 'Invalid ' . $dbfield . ' phone format.',
                'value.numeric' => ' number must be numeric.',
            ];
    
            // Add custom validation for email format and phone number format if value is not empty
            if (!empty($request->input('value'))) {
                if (in_array($request->input('field'), ['sale_price', 'commission', 'pipeline_probability'])) {
                    $rules['value'] .= '|regex:/^\d+(\.\d{1})?$/'; // Numeric with optional one decimal place
                }
            }
    
            // Validate request inputs
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }
            $deal = $db->retrieveDealById($user, $accessToken, $id);
            if (!$deal) {
                $deal = $db->retrieveDealByZohoId($user, $accessToken, $id);
                if (!$deal) {
                    return response()->json(['error' => 'Deal Id Not Found'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            
            $zoho = new ZohoCRM();
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $field = "";
            if ($dbfield === "deal_name") {
                $field = "Deal_Name";
            }
            if ($dbfield === "pipeline_probability") {
                $field = "Pipeline_Probability";
            }
            if ($dbfield === "client_name_primary") {
                $field = "Client_Name_Primary";
            }
            if ($dbfield === "stage") {
                $field = "Stage";
            }
            if ($dbfield === "representing") {
                $field = "Representing";
            }

            if ($dbfield === "sale_price") {
                $field = "Sale_Price";
            }
            if ($dbfield === "closing_date") {
                $field = "Closing_Date";
            }

            if ($dbfield === "commission") {
                $field = "Commission";
            }
            if ($dbfield === "address") {
                $field = "Address";
            }
            if (empty($field)) {
                return response()->json([
                    'error' => 'Cannot accept empty'
                ], 400); // Use 400 Bad Request for client-side errors
            }
            
            $jsonData = [
                'data' => [
                    [
                      $field => $value,
                    ],
                ],
                'skip_mandatory' => true,
            ];

            if (!empty($dbfield) && empty($value)) {
                // Transform $dbfield to uppercase the first letters and remove underscores
                $formattedField = str_replace('_', ' ', $dbfield); // Replace underscores with spaces
                $formattedField = ucwords($formattedField); // Capitalize the first letter of each word
                // Return the formatted error message
                return response()->json(['error' => $formattedField . ' cannot be empty'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            $zohoDeal = $zoho->updateZohoDeal($jsonData, $deal->zoho_deal_id);

            if (!$zohoDeal->successful()) {
                return response()->json(['error' => 'Zoho Deal update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
             // If the stage is "Under Contract", update the locked_s field separately in the database
            if ($dbfield === "stage" && $value === "Under Contract") {
                Deal::where('id', $id)->update(['locked_s' => 1]);
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
            $dealDatas = $db->updateDeal($user, $accessToken, $zohoDealValues, $deal);
            return response()->json(['data' => $dealDatas, 'message' => "Successfully Updated"]);
        } catch (\Throwable $th) {
            // Handle the exception here
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getClosedDeals(Request $request)
    {
        $user = $this->user();
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
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $dealId = request()->route('dealId');
        $accessToken = $user->getAccessToken();
        $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        $retrieveModuleData = $db->retrieveModuleDataDB( $accessToken, "Deals");
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        return view('common.notes.listPopup', compact('notesInfo', 'retrieveModuleData', 'deal'))->render();
    }

    public function createNotesForDeal()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $dealId = request()->route('dealId');
        $accessToken = $user->getAccessToken();
        $type = "Deals";
        $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        $retrieveModuleData = $db->retrieveModuleDataDB( $accessToken, "Deals");
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        return view('common.notes.create', compact("retrieveModuleData", "deal", "type"))->render();
    }

    public function createTasksForDeal()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $dealId = request()->route('dealId');
        $accessToken = $user->getAccessToken();
        $type = "Deals";
        $notesInfo = $db->retrieveNotesFordeal($user, $accessToken, $dealId);
        $retrieveModuleData = $db->retrieveModuleDataDB( $accessToken, "Deals");
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        return view('common.tasks.create', compact("retrieveModuleData", "deal", "type"))->render();
    }


    public function addContactRole(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $zoho = new ZohoCRM();
        $db = new DatabaseService();
        $dealId = request()->route('dealId');
        $jsonData = $request->json()->all();
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;

        $contactRole = $zoho->addContactRoleForDeal($dealId, $jsonData);
        $response = $zoho->getDealContact($dealId);
        $contactRoleArray = collect($response->json()['data'] ?? []);
        $contactroles = $db->storeDealContactIntoDB($contactRoleArray, $dealId);
        return $contactroles;
    }

    public function removeContactRole(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $zoho = new ZohoCRM();
        $db = new DatabaseService();
        $jsonData = $request->json()->all();
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;

        $contactRole = $zoho->removeContactRoleForDeal($jsonData);
        $contactroles = $db->removeDealContactfromDB($jsonData);
        return $contactroles;
    }
    public function getContactRole(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $zoho = new ZohoCRM();
        $db = new DatabaseService();
        $jsonData = $request->json()->all();
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        $dealId = request()->route('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        $dealContacts = $db->retrieveDealContactFordeal($user, $accessToken, $deal->zoho_deal_id);
        $contacts = $db->retreiveContactsJson($user, $accessToken);
        //$contactRoles = $db->retrieveRoles($user);

        // Ensure relationships are eager loaded
        $deal->load('contactName', 'leadAgent', 'tmName');

        // Fetch contact roles
        $contactRoles = $deal->getContactRoles();
        return Datatables::of($contactRoles)->make(true);


        // return view('contactRole.index', compact('dealContacts', 'deal', 'contacts', 'contactRoles'))->render();
    }
    public function piplineCardUpdate(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $accessToken = $user->getAccessToken();
        $allDeals = $db->retrieveDeals(
            $user, // user
            null, // search filters if exist
            null,  // sort filter if exist
            null,  // sort type if exist
            null,  // date filter (none)
            null, // filter
            true
        ); 

        $stats = $this->calculateDealStatistics($allDeals);
        if ($request->ajax()) {
            return view('components.pipe-cards', $stats)->render();
        }
    }
}


