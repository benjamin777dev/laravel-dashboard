<?php

namespace App\Services;

use App\Models\Aci;
use App\Models\Attachment; // Import the User model
use App\Models\BulkJob; // Import the Deal model
use App\Models\Contact; // Import the Deal model
use App\Models\ContactGroups; // Import the Deal model
use App\Models\ContactRole; // Import the Deal model
use App\Models\Deal; // Import the Deal model
use App\Models\DealContact; // Import the Module model
use App\Models\Groups;
use App\Models\Module;
use App\Models\NonTm;
use App\Models\Note;
use App\Models\Submittals;
use App\Models\Task;
use App\Models\User;
use App\Services\Helper;
use App\Services\ZohoCRM;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class DatabaseService
{
    public function storeDealsIntoDB($dealsData, $user)
    {
        $helper = new Helper();
        $zoho = new ZohoCRM();
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        Log::info("Storing the following deals into the database:", ['deals' => $dealsData]);
        $dealCount = count($dealsData);
        for ($i = 0; $i < $dealCount; $i++) {
            $deal = $dealsData[$i];

            if ($deal['Contact_Name']) {
                $contact = Contact::where('zoho_contact_id', $deal['Contact_Name']['id'])->first();
            }
            if ($deal['Owner']) {
                $userInstance = User::where('root_user_id', $deal['Owner']['id'])->first();
            }
            if ($deal['Lead_Agent']) {
                $lead_agent = User::where('root_user_id', $deal['Lead_Agent']['id'])->first();

            }
            if ($deal['TM_Name']) {
                $tm_name = User::where('root_user_id', $deal['TM_Name']['id'])->first();
            }
            if ($deal['Client_Name_Only']) {
                $clientId = explode("||", $deal['Client_Name_Only']);
                Log::info("clientId: " . implode(", ", $clientId));
                $client_name = Contact::where('zoho_contact_id', trim($clientId[1]))->first();
            }
            if (!$userInstance) {
                // Log an eeor if the user is not found
                Log::error("User with Zoho ID {$deal['Contact_Name']['id']} not found.");
                continue; // Skip to the next deal
            }

            // Fetching deal contacts
            $response = $zoho->getDealContact($deal['id']);
            if (!$response->successful()) {
                Log::error("Error retrieving deal contacts: " . $response->body());
                continue; // Skip to the next deal
            }
            $dealContacts = collect($response->json()['data'] ?? []);
            $this->storeDealContactIntoDB($dealContacts, $deal['id']);

            // Fetching deal attachments
            $attachmentResponse = $zoho->getAttachmentData($deal['id']);
            if (!$attachmentResponse->successful()) {
                Log::error("Error retrieving deal contacts: " . $attachmentResponse->body());
                continue; // Skip to the next deal
            }
            $attachments = collect($attachmentResponse->json()['data'] ?? []);
            Log::info("USERSARA" . $user);
            $this->storeAttachmentIntoDB($attachments, $userInstance, $deal['id']);

            // Fetching deal nonTM
            $nonTmResponse = $zoho->getNonTmData($deal['id']);
            if (!$nonTmResponse->successful()) {
                Log::error("Error retrieving deal contacts: " . $nonTmResponse->body());
                continue; // Skip to the next deal
            }
            $nonTm = collect($nonTmResponse->json()['data'] ?? []);
            Log::info("USERSARA" . $user);
            $this->storeNonTmIntoDB($nonTm, $userInstance, $deal['id']);

            // Update or create the deal
            Deal::updateOrCreate(['zoho_deal_id' => $deal['id']], [
                'zip' => isset($deal['Zip']) ? $deal['Zip'] : null,
                'personal_transaction' => isset($deal['Personal_Transaction']) ? ($deal['Personal_Transaction'] == true ? 1 : 0) : null,
                'double_ended' => isset($deal['Double_Ended']) ? ($deal['Double_Ended'] == true ? 1 : 0) : null,
                'userID' => isset($userInstance) ? $userInstance->id : null,
                'address' => isset($deal['Address']) ? $deal['Address'] : null,
                'representing' => isset($deal['Representing']) ? $deal['Representing'] : null,
                'client_name_only' => isset($deal['Client_Name_Only']) ? $deal['Client_Name_Only'] : null,
                'commission' => isset($deal['Commission']) ? $deal['Commission'] : null,
                'commission_flat_free' => isset($deal['Commission_Flat_Fee']) ? $deal['Commission_Flat_Fee'] : null,
                'probable_volume' => isset($deal['Probable_Volume']) ? $deal['Probable_Volume'] : null,
                'lender_company' => isset($deal['Lender_Company']) ? $deal['Lender_Company'] : null,
                'closing_date' => isset($deal['Closing_Date']) ? $helper->convertToUTC($deal['Closing_Date']) : null,
                'ownership_type' => isset($deal['Ownership_Type']) ? $deal['Ownership_Type'] : null,
                'needs_new_date2' => isset($deal['Needs_New_Date2']) ? $deal['Needs_New_Date2'] : null,
                'deal_name' => isset($deal['Deal_Name']) ? $deal['Deal_Name'] : null,
                'tm_preference' => isset($deal['TM_Preference']) ? $deal['TM_Preference'] : null,
                'tm_name' => isset($tm_name) ? $tm_name->name : null,
                'tm_name_id' => isset($tm_name) ? $tm_name->root_user_id : null,
                'stage' => isset($deal['Stage']) ? $deal['Stage'] : null,
                'sale_price' => isset($deal['Sale_Price']) ? $deal['Sale_Price'] : null,
                'zoho_deal_id' => $deal['id'],
                'pipeline1' => isset($deal['Pipeline1']) ? $deal['Pipeline1'] : null,
                'pipeline_probability' => isset($deal['Pipeline_Probability']) ? $deal['Pipeline_Probability'] : null,
                'zoho_deal_createdTime' => $helper->convertToUTC($deal['Created_Time']),
                'property_type' => isset($deal['Property_Type']) ? $deal['Property_Type'] : null,
                'city' => isset($deal['City']) ? $deal['City'] : null,
                'state' => isset($deal['State']) ? $deal['State'] : null,
                'lender_company_name' => isset($deal['Lender_Company_Name']) ? $deal['Lender_Company_Name'] : null,
                'client_name_primary' => isset($deal['Client_Name_Primary']) ? $deal['Client_Name_Primary'] : null,
                'lender_name' => isset($deal['Lender_Name']) ? $deal['Lender_Name'] : null,
                'potential_gci' => isset($deal['Potential_GCI']) ? $deal['Potential_GCI'] : null,
                'review_gen_opt_out' => isset($deal['Review_Gen_Opt_Out']) ? $deal['Review_Gen_Opt_Out'] : false,
                'deadline_em_opt_out' => isset($deal['Deadline_Emails']) ? $deal['Deadline_Emails'] : false,
                'status_rpt_opt_out' => isset($deal['Status_Reports']) ? $deal['Status_Reports'] : false,
                'contractId' => null,
                'contactId' => isset($client_name) ? $client_name->id : null,
                'contact_name' => isset($contact) ? $contact->name : null,
                'contact_name_id' => isset($contact) ? $contact->zoho_contact_id : null,
                'lead_agent' => isset($lead_agent) ? $lead_agent->name : null,
                'lead_agent_id' => isset($lead_agent) ? $lead_agent->id : null,
                'financing' => isset($deal['Financing']) ? $deal['Financing'] : null,
                'modern_mortgage_lender' => isset($deal['Modern_Mortgage_Lender']) ? $deal['Modern_Mortgage_Lender'] : null,
                'isDealCompleted' => true,
                'isInZoho' => true,
            ]);
        }

        Log::info("Deals stored into database successfully.");
    }

    public function storeDealContactIntoDB($dealContacts, $dealId)
    {
        Log::info("Storing Deal Contacts Into Database");

        $storedDealContacts = [];

        foreach ($dealContacts as $dealContact) {
            Log::info("dealContact", $dealContact);

            // Fetch the contact and user from the database
            $contact = Contact::where('zoho_contact_id', $dealContact['id'])->first();
            $user = User::where('zoho_id', $dealContact['id'])->first();

            // Update or create the deal contact
            $dealContactEntry = DealContact::updateOrCreate([
                'zoho_deal_id' => $dealId,
                'contactId' => $contact ? $contact->id : null,
                'userId' => $user ? $user->id : null,
            ], [
                'zoho_deal_id' => $dealId,
                'contactId' => $contact ? $contact->id : null,
                'userId' => $user ? $user->id : null,
                'contactRole' => $dealContact['Contact_Role']['name'],
            ]);

            $storedDealContacts[] = $dealContactEntry;
        }

        Log::info("Deal Contacts stored into database successfully.");
        return response()->json(['message' => 'Deal contacts stored successfully', 'dealContacts' => $storedDealContacts]);
    }

    /**
     * Store contacts into the database.
     *
     * @param  \Illuminate\Support\Collection  $contacts
     * @return void
     */
    public function storeContactsIntoDB($contacts)
    {
        $helper = new Helper();
        Log::info("Storing Contacts Into Database");

        foreach ($contacts as $contact) {
            $user = User::where('root_user_id', $contact['Owner']['id'])->first();

            // Map the data correctly
            $mappedData = [
                'contact_owner' => $user ? $user->root_user_id : null,
                'zoho_contact_id' => $contact['id'] ?? null,
                'email' => $contact['Email'] ?? null,
                'first_name' => $contact['First_Name'] ?? null,
                'last_name' => $contact['Last_Name'] ?? null,
                'phone' => $contact['Phone'] ?? null,
                'business_name' => $contact['Business_Name'] ?? null,
                'business_information' => $contact['Business_Info'] ?? null,
                'secondory_email' => $contact['Secondary_Email'] ?? null,
                'relationship_type' => $contact['Relationship_Type'] ?? null,
                'market_area' => $contact['Market_Area'] ?? null,
                'envelope_salutation' => $contact['Salutation'] ?? null,
                'mobile' => $contact['Mobile'] ?? null,
                'created_time' => isset($contact['Created_Time']) ? $helper->convertToUTC($contact['Created_Time']) : null,
                'abcd' => $contact['ABCD'] ?? null,
                'mailing_address' => $contact['Mailing_Street'] ?? null,
                'mailing_city' => $contact['Mailing_City'] ?? null,
                'mailing_state' => $contact['Mailing_State'] ?? null,
                'mailing_zip' => $contact['Mailing_Zip'] ?? null,
                'isContactCompleted' => isset($contact['Is_Active']) ? (bool) $contact['Is_Active'] : 1,
                'isInZoho' => isset($contact['$state']) && $contact['$state'] == 'save' ? 1 : 0,
                'Lead_Source' => $contact['Lead_Source'] ?? null,
                'referred_id' => $contact['Referred_By']['id'] ?? null,
                'lead_source_detail' => $contact['Lead_Source_Detail'] ?? null,
                'spouse_partner' => $contact['Spouse_Partner']['id'] ?? null,
                'last_called' => isset($contact['Last_Called']) ? $helper->convertToUTC($contact['Last_Called']) : null,
                'last_emailed' => isset($contact['Last_Emailed']) ? $helper->convertToUTC($contact['Last_Emailed']) : null,
                'email_blast_opt_in' => $contact['Email_Blast_Opt_In'] ?? null,
                'twitter_url' => $contact['Twitter_URL'] ?? null,
                'emergency_contact_phone' => $contact['Emergency_Contact_Phone'] ?? null,
                'print_qr_code_sheet' => $contact['Print_QR_Code_Sheet'] ?? null,
                'invalid_address_usps' => $contact['Invalid_Address_USPS'] ?? null,
                'mls_recolorado' => $contact['MLS_REColorado'] ?? null,
                'mls_navica' => $contact['MLS_Navica'] ?? null,
                'perfect' => $contact['Perfect'] ?? null,
                'realtor_board' => $contact['Realtor_Board'] ?? null,
                'initial_split' => $contact['Initial_Split'] ?? null,
                'has_missing_important_date' => $contact['HasMissingImportantDate'] ?? null,
                'need_o_e' => $contact['Need_O_E'] ?? null,
                'culture_index' => $contact['Culture_Index'] ?? null,
                'sticky_dots' => $contact['Sticky_Dots'] ?? null,
                'strategy_group' => $contact['Strategy_Group'] ?? null,
                'weekly_email' => $contact['Weekly_Email'] ?? null,
                'number_of_chats' => $contact['Number_Of_Chats'] ?? null,
                'notepad_mailer_opt_in' => $contact['Notepad_Mailer_Opt_In'] ?? null,
                'chr_gives_amount' => $contact['CHR_Gives_Amount'] ?? null,
                'other_zip' => $contact['Other_Zip'] ?? null,
                'market_mailer_opt_in' => $contact['Market_Mailer_Opt_In'] ?? null,
                'groups' => $contact['Groups'] ?? null,
                'closer_name_phone' => $contact['Closer_Name_Phone'] ?? null,
                'unsubscribe_from_reviews' => $contact['Unsubscribe_From_Reviews'] ?? null,
                'outsourced_mktg_onsite_video' => $contact['Outsourced_Mktg_Onsite_Video'] ?? null,
                'random_notes' => $contact['Random_Notes'] ?? null,
                'residual_cap' => $contact['Residual_Cap'] ?? null,
                'email_blast_to_reverse_prospect_list' => $contact['Email_Blast_to_Reverse_Prospect_List'] ?? null,
                'review_generation' => $contact['Review_Generation'] ?? null,
                'zillow_url' => $contact['Zillow_URL'] ?? null,
                'agent_assistant' => $contact['Agent_Assistant'] ?? null,
                'social_media_ads' => $contact['Social_Media_Ads'] ?? null,
                'referred_by' => $contact['Referred_By'] ?? null,
                'peer_advisor' => $contact['Peer_Advisor'] ?? null,
                'agent_name_on_marketing' => $contact['Agent_Name_on_Marketing'] ?? null,
                'other_street' => $contact['Other_Street'] ?? null,
                'qr_code_sign_rider' => $contact['QR_Code_Sign_Rider'] ?? null,
                'google_business_page_url' => $contact['Google_Business_Page_URL'] ?? null,
                'has_email' => $contact['Has_Email'] ?? null,
                'has_address' => $contact['Has_Address'] ?? null,
                'salesforce_id' => $contact['Salesforce_ID'] ?? null,
                'mls_ires' => $contact['MLS_IRES'] ?? null,
                'outsourced_mktg_floorplans' => $contact['Outsourced_Mktg_Floorplans'] ?? null,
                'income_goal' => $contact['Income_Goal'] ?? null,
                'chr_relationship' => $contact['CHR_Relationship'] ?? null,
                'locked_s' => $contact['Locked__s'] ?? null,
                'tag' => isset($contact['Tag']) ? json_encode($contact['Tag']) : null,
                'import_batch' => $contact['Import_Batch'] ?? null,
                'termination_date' => isset($contact['Termination_Date']) ? $helper->convertToUTC($contact['Termination_Date']) : null,
                'license_start_date' => isset($contact['License_Start_Date']) ? $helper->convertToUTC($contact['License_Start_Date']) : null,
                'brokermint_id' => $contact['Brokermint_ID'] ?? null,
                'residual_split' => $contact['Residual_Split'] ?? null,
                'visitor_score' => $contact['Visitor_Score'] ?? null,
                'sign_vendor' => $contact['Sign_Vendor'] ?? null,
                'other_state' => $contact['Other_State'] ?? null,
                'last_activity_time' => isset($contact['Last_Activity_Time']) ? $helper->convertToUTC($contact['Last_Activity_Time']) : null,
                'unsubscribed_mode' => $contact['Unsubscribed_Mode'] ?? null,
                'license_number' => $contact['License_Number'] ?? null,
                'exchange_rate' => $contact['Exchange_Rate'] ?? null,
                'email_to_cc_on_all_marketing_comms' => $contact['Email_to_CC_on_All_Marketing_Comms'] ?? null,
                'tm_preference' => $contact['TM_Preference'] ?? null,
                'salutation_s' => $contact['Salutation_s'] ?? null,
                '$locked_for_me' => $contact['$locked_for_me'] ?? null,
                '$approved' => $contact['$approved'] ?? null,
                'email_cc_1' => $contact['Email_CC_1'] ?? null,
                'google_business' => $contact['Google_Business'] ?? null,
                'email_cc_2' => $contact['Email_CC_2'] ?? null,
                'days_visited' => $contact['Days_Visited'] ?? null,
                'pipeline_stage' => $contact['Pipeline_Stage'] ?? null,
                'social_media_images' => $contact['Social_Media_Images'] ?? null,
                'fees_charged_to_seller_at_closing' => $contact['Fees_Charged_to_Seller_at_Closing'] ?? null,
                'realtor_com_url' => $contact['Realtor_com_URL'] ?? null,
                'title_company' => $contact['Title_Company'] ?? null,
                'select_your_prints' => $contact['Select_your_prints'] ?? null,
                'role' => $contact['Role'] ?? null,
                'missing' => $contact['Missing'] ?? null,
                'groups_tags' => $contact['Groups_Tags'] ?? null,
                'lender_company_name' => $contact['Lender_Company_Name'] ?? null,
                '$zia_owner_assignment' => $contact['$zia_owner_assignment'] ?? null,
                'secondary_email' => $contact['Secondary_Email'] ?? null,
                'current_annual_academy' => $contact['Current_Annual_Academy'] ?? null,
                'transaction_status_reports' => $contact['Transaction_Status_Reports'] ?? null,
                'non_tm_assignment' => $contact['Non_TM_Assignment'] ?? null,
                'user' => $contact['User']['id'] ?? null,
                'lender_email' => $contact['Lender_Email'] ?? null,
                'sign_install' => $contact['Sign_Install'] ?? null,
                'team_name' => $contact['Team_Name'] ?? null,
                'pintrest_url' => $contact['Pintrest_URL'] ?? null,
                'youtube_url' => $contact['Youtube_URL'] ?? null,
                'include_insights_in_intro' => $contact['Include_Insights_in_Intro'] ?? null,
                'import_id' => $contact['Import_ID'] ?? null,
                'business_info' => $contact['Business_Info'] ?? null,
                'email_signature' => $contact['Email_Signature'] ?? null,
                'property_website_qr_code' => $contact['Property_Website_QR_Code'] ?? null,
                'draft_showing_instructions' => $contact['Draft_Showing_Instructions'] ?? null,
                'additional_email_for_confirmation' => $contact['Additional_Email_for_Confirmation'] ?? null,
                'important_date_added' => $contact['Important_Date_Added'] ?? null,
                'emergency_contact_name' => $contact['Emergency_Contact_Name'] ?? null,
                'initial_cap' => $contact['Initial_Cap'] ?? null,
                'unsubscribed_time' => isset($contact['Unsubscribed_Time']) ? $helper->convertToUTC($contact['Unsubscribed_Time']) : null,
                'mls_ppar' => $contact['MLS_PPAR'] ?? null,
                'outsourced_mktg_3d_zillow_tour' => $contact['Outsourced_Mktg_3D_Zillow_Tour'] ?? null,
                'marketing_specialist' => $contact['Marketing_Specialist'] ?? null,
                'default_commission_plan_id' => $contact['Default_Commission_Plan_Id'] ?? null,
                'feature_cards_or_sheets' => $contact['Feature_Cards_or_Sheets'] ?? null,
                'termination_reason' => $contact['Termination_Reason'] ?? null,
                'transaction_manager' => $contact['Transaction_Manager'] ?? null,
                'auto_address' => $contact['Auto_Address'] ?? null,
            ];

            // Update or create the contact
            Contact::updateOrCreate(['zoho_contact_id' => $contact['id']], $mappedData);
        }

        Log::info("Contacts stored into database successfully.");
    }

    public function storeTasksIntoDB($tasks)
    {
        $helper = new Helper();
        Log::info("Storing Tasks Into Database");

        foreach ($tasks as $task) {
            if (isset($task['Owner'])) {
                $user = User::where('root_user_id', $task['Owner']['id'])->first();
            }
            if (isset($task['Who_Id'])) {
                $contact = Contact::where('zoho_contact_id', $task['Who_Id']['id'])->first();
            }
            if (isset($task['What_Id'])) {
                $deal = Deal::where('zoho_deal_id', $task['What_Id']['id'])->first();
                Log::info("Tasks For loop: " . $deal);
            }
            // if (!$user) {
            //     // Log an error if the user is not found
            //     Log::error("User with Zoho ID {$deal['Contact_Name']['id']} not found.");
            //     continue; // Skip to the next deal
            // }

            // Update or create the deal
            Task::updateOrCreate(['zoho_task_id' => $task['id']], [
                "closed_time" => isset($task['Closed_Time']) ? $helper->convertToUTC($task['Closed_Time']) : null,
                "who_id" => isset($task['Who_id']) ? $task['Who_id']['id'] : null,
                "created_by" => isset($contact['id']) ? $contact['id'] : null,
                "description" => isset($task['Description']) ? $task['Description'] : null,
                "due_date" => isset($task['Due_Date']) ? $helper->convertToUTC($task['Due_Date']) : null,
                "priority" => isset($task['Priority']) ? $task['Priority'] : null,
                "what_id" => isset($task['What_Id']) ? $task['What_Id']['id'] : null,
                "status" => isset($task['Status']) ? $task['Status'] : null,
                "subject" => isset($task['Subject']) ? $task['Subject'] : null,
                "owner" => isset($user['id']) ? $user['id'] : null,
                "created_time" => isset($task['Created_Time']) ? $helper->convertToUTC($task['Created_Time']) : null,
                "zoho_task_id" => isset($task['id']) ? $task['id'] : null,
                "related_to" => isset($task['$se_module']) ? $task['$se_module'] : null,
            ]);
        }

        Log::info("Tasks stored into database successfully.");
    }

    public function storeModuleIntoDB($modules)
    {
        $helper = new Helper();
        Log::info("Storing Module Into Database");

        foreach ($modules as $module) {
            // Update or create the Module
            Module::updateOrCreate(['zoho_module_id' => $module['id']], [
                "api_name" => isset($module['api_name']) ? $module['api_name'] : null,
                "modified_time" => isset($module['modified_time']) ? $helper->convertToUTC($module['modified_time']) : null,
                "zoho_module_id" => isset($module['id']) ? $module['id'] : null,
            ]);
        }

        Log::info("Module stored into database successfully.");
    }

    public function retrieveModuleDataDB(User $user, $accessToken, $filter = null)
    {
        try {
            // Validate user token (pseudo-code, replace it with your actual validation logic)
            if (!$accessToken) {
                throw new \Exception("Invalid user token");
            }

            // Retrieve module data from MySQL
            if ($filter) {
                $allModules = Module::where('api_name', $filter)->get();
            } else {
                $allModules = Module::all(); // Assuming you want to retrieve all modules
            }
            // dd(json_encode($allModules));
            // Log the total number of module records
            Log::info("Total Module records: " . $allModules->count());

            // Log module records
            Log::info("Module Records: ", $allModules->toArray());

            return $allModules; // Return the fetched module data
        } catch (\Exception $e) {
            Log::error("Error retrieving module data: " . $e->getMessage());
            return []; // Return an empty array or handle the error as per your application's logic
        }
    }

    public function retrieveDeals(User $user, $accessToken, $search = null, $sortValue = null, $sortType = null, $dateFilter = null, $filter = null, $all = false)
    {

        try {
            Log::info("Retrieve Deals From Database");

            $conditions = [
                ['userID', $user->id],
                ['isDealCompleted',true]
            ];

            $deals = Deal::where($conditions)
                ->whereNotIn('stage', config('variables.dealPipelineStages')
                );

            if ($search !== "") {
                $searchTerms = urldecode($search);
                $deals->where(function ($query) use ($searchTerms) {
                    $query->where('deal_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('client_name_primary', 'like', '%' . $searchTerms . '%');
                });
            }

            if ($sortValue != '' && $sortType != '') {
                $sortField = $sortValue;
                if ($sortField === 'contactName.first_name') {
                    $sortField = 'contacts.first_name';
                }
                // Add sorting logic based on the field and type
                switch ($sortType) {
                    case 'asc':
                        $deals->orderBy($sortField, 'asc');
                        break;
                    case 'desc':
                        $deals->orderBy($sortField, 'desc');
                        break;
                    default:

                        break;
                }
            } else {
                $deals->orderBy('updated_at', 'desc');
            }

            if ($dateFilter && $dateFilter != '') {
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $startOfNext30Days = Carbon::now()->startOfDay();
                $endOfNext30Days = Carbon::now()->addDays(30)->endOfDay();
                $deals->whereBetween('closing_date', [$startOfWeek, $endOfWeek])->orWhere(function ($query) use ($startOfNext30Days, $endOfNext30Days) {
                    $query->whereBetween('closing_date', [$startOfNext30Days, $endOfNext30Days])->where('stage', '!=', 'Under Contract');
                });
            }

            if ($filter) {
                $conditions[] = ['stage', $filter];
            }
            Log::info("Deal Conditions", ['deals' => $conditions]);

            // Retrieve deals based on the conditions
            if ($all) {
                $deals = $deals->where($conditions)->get();
            } else {
                $deals = $deals->where($conditions)->paginate(10);
            }
            return $deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }

    }

    public function retrieveDealById(User $user, $accessToken, $dealId)
    {

        try {
            Log::info("Retrieve Deals From Database");

            $conditions = [['userID', $user->id], ['id', $dealId]];

            // Adjust query to include contactName table using join
            $deals = Deal::with('userData', 'contactName', 'leadAgent');

            Log::info("Deal Conditions", ['deals' => $conditions]);

            // Retrieve deals based on the conditions
            $deals = $deals->where($conditions)->first();
            return $deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }

    }

    public function retrieveNonTmById(User $user, $accessToken, $dealId)
    {
        try {
            Log::info("Retrieve Deals From Database");

            $conditions = [['userID', $user->id], ['id', $dealId]];

            // Adjust query to include contactName table using join
            $deals = NonTm::with('userData', 'dealData')->where($conditions)->first();
            Log::info("Deal Conditions", ['deals' => $conditions]);

            return $deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }
    }


    public function retrieveContactById(User $user, $accessToken, $contactId)
    {

        try {
            Log::info("Retrieve contact From Database");

            $conditions = [['contact_owner', $user->root_user_id], ['id', $contactId]];

            // Adjust query to include contactName table using join
            $contacts = Contact::with('userData', 'contactName','spouseContact','groupsData');

            Log::info("Contacts Conditions", ['contacts' => $conditions]);

            // Retrieve deals based on the conditions
            $contacts = $contacts->where($conditions)->first();
            Log::info("Retrieved Contact From Database", ['contacts' => $contacts]);
            return $contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving Contacts: " . $e->getMessage());
            throw $e;
        }

    }

    public function retrieveDealByZohoId(User $user, $accessToken, $dealId)
    {

        try {
            Log::info("Retrieve Deals From Database");

            $conditions = [['userID', $user->id], ['zoho_deal_id', $dealId]];

            // Adjust query to include contactName table using join
            $deals = Deal::with('userData', 'contactName');

            Log::info("Deal Conditions", ['deals' => $conditions]);

            // Retrieve deals based on the conditions
            $deals = $deals->where($conditions)->first();
            return $deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }

    }

    public function retrieveContactByZohoId(User $user, $accessToken, $contactId)
    {

        try {
            Log::info("Retrieve Contact From Database");

            $conditions = [['contact_owner', $user->root_user_id], ['zoho_contact_id', $contactId]];

            // Adjust query to include contactName table using join
            $contacts = Contact::with('userData', 'contactName','spouseContact');

            Log::info("Deal Conditions", ['contacts' => $conditions]);

            // Retrieve contacts based on the conditions
            $contacts = $contacts->where($conditions)->first();
            return $contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;
        }

    }

    public function retrieveContactDetailsByZohoId(User $user, $accessToken, $contactId)
    {

        try {
            Log::info("Retrieve Contact From Database");

            $conditions = [['zoho_contact_id', $contactId]];

            // Adjust query to include contactName table using join
            $contacts = Contact::with('userData', 'contactName');

            Log::info("Deal Conditions", ['contacts' => $conditions]);

            // Retrieve contacts based on the conditions
            $contacts = $contacts->where($conditions)->first();
            return $contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;
        }

    }

    public function retreiveTasks(User $user, $accessToken, $tab = '')
    {
        try {
            Log::info("Retrieve Tasks From Database");
            $tasks = Task::where('owner', $user->id)->with(['dealData', 'contactData']);

            if ($tab == 'Overdue') {
                // These are any tasks that have a due date less than today and the task status isn't completed
                $tasks->where([['due_date', '<', now()->startOfDay()], ['status', '!=', 'Completed']])
                      ->orderBy('due_date', 'asc');
            } elseif ($tab == 'Upcoming') {
                // These are any tasks that have a due date greater than or equal to today and are not complete
                $tasks->where([['due_date', '>=', now()->startOfDay()], ['status', '!=', 'Completed']])
                      ->orderBy('due_date', 'asc');
            } elseif ($tab == 'Due Today') {
                // These are any tasks that are due today and are not complete
                $tasks
                // ->whereDate('due_date', now()->toDateString())
                      ->where('status', '!=', 'Completed')
                      ->orderBy('due_date', 'asc');
            } elseif ($tab == 'Completed') {
                // These are tasks that are completed
                $tasks->where('status', 'Completed')
                      ->orderBy('updated_at', 'desc');
            }

            // This will apply the updated_at ordering only if it's not already ordered by due_date
            if ($tab != 'Upcoming' && $tab != 'Overdue' && $tab != 'Due Today') {
                $tasks = $tasks->orderBy('updated_at', 'desc');
            }

            $tasks = $tasks->paginate(10);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveTasksJson(User $user, $accessToken, $dealId = null, $contactId = null)
    {
        try {

            Log::info("Retrieve Tasks From Database");
            $condition = [
                ['owner', $user->id],
            ];
            if ($dealId) {
                $condition[] = ['what_id', $dealId];
            }
            if ($contactId) {
                $condition[] = ['who_id', $contactId];
            }
            $tasks = Task::where($condition)->orderBy('updated_at', 'desc')->get();
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveDealsJson(User $user, $accessToken, $dealId = null, $contactId = null)
    {
        try {

            Log::info("Retrieve Deals From Database");
            $condition = [
                ['userID', $user->id],

            ];
            if ($dealId) {
                $condition[] = ['zoho_deal_id', $dealId];
            }
            if ($contactId) {
                $condition[] = ['zoho_deal_id', $contactId];
            }
            $Deals = Deal::where($condition)->orderBy('updated_at', 'desc')->get();
            return $Deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveContacts(User $user, $accessToken, $search = null, $sortValue = null, $sortType = null, $dateFilter = null, $filter = null, $missingField = null)
    {
        try {
            Log::info("Retrieve Contact From Database");

            $conditions = [['contact_owner', $user->root_user_id]];
            $contacts = Contact::where($conditions); // Initialize the query with basic conditions

            if ($search !== null && $search !== '') {
                $searchTerms = urldecode($search);
                $contacts->where(function ($query) use ($searchTerms) {
                    $query->where('first_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerms . '%')
                        ->orWhere('mobile', 'like', '%' . $searchTerms . '%')
                        ->orWhere('mailing_address', 'like', '%' . $searchTerms . '%')
                        ->orWhere('email', 'like', '%' . $searchTerms . '%');
                });
            }

            if ($filter) {
                $conditions[] = ['abcd', $filter];
            }
            // Apply missing field conditions
            if ($missingField) {
                if (isset($missingField['email']) && $missingField['email'] !== false) {
                    $contacts->whereNull('email')->orWhere('email','==',' ');
                }
                if (isset($missingField['mobile']) && $missingField['mobile'] !== false) {
                    $contacts->whereNull('phone')->orWhere('phone','==',' ');
                }
                if (isset($missingField['abcd']) && $missingField['abcd'] !== false) {
                    $contacts->whereNull('abcd')->orWhere('abcd','==',' ');
                }
            }

            // Apply additional filter conditions
            if ($filter) {
                $contacts->where($conditions);
            }

            // Apply sorting if specified
            if ($sortValue && $sortType) {
                if ($sortValue == 'first_name,last_name') {
                   $contacts->orderByRaw("CONCAT_WS(' ', first_name, last_name) $sortType");
                } else {
                    $contacts->orderBy($sortValue, $sortType);
                }
            } else {
                $contacts->orderBy('updated_at', 'desc');
            }

            // Paginate the results
            $contacts = $contacts->paginate(50);
            return $contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveContactsJson(User $user, $accessToken)
    {
        try {

            Log::info("Retrieve contacts From Database");
            $Contacts = Contact::where('contact_owner', $user->root_user_id)->with('userData')->orderBy('updated_at', 'desc')->get();
            return $Contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;

        }
    }
    public function retreiveTasksFordeal(User $user, $accessToken, $tab = '', $dealId = '')
    {
        try {
            Log::info("Retrieve Tasks From Database");
            $condition = [];
            $tasks = Task::where('what_id', $dealId)->with(['dealData']);
            if ($tab == 'Overdue') {
                $tasks
                    ->where('due_date', '<', now());
            } elseif ($tab == 'Upcoming') {
                $tasks
                    ->where('due_date', '>=', now());
            } elseif ($tab == 'In Progress') {
                $tasks->where([['due_date', null],['status','!=','Completed']]);
            } elseif ($tab == 'Completed') {
                $tasks->where('status', 'Completed');
            }
            $tasks = $tasks->orderBy('updated_at', 'desc')->paginate(10);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveTasksForContact(User $user, $accessToken, $tab = '', $dealId = '')
    {
        try {
            Log::info("Retrieve Tasks From Database");
            $condition = [];
            $tasks = Task::where('who_id', $dealId)->with(['contactData']);
            if ($tab == 'Overdue') {
                $tasks
                    ->where('due_date', '<', now());
            } elseif ($tab == 'Upcoming') {
                $tasks
                    ->where('due_date', '>=', now());
            } elseif ($tab == 'In Progress') {
                $tasks->where([['due_date', null],['status','!=','Completed']]);
            } elseif ($tab == 'Completed') {
                $tasks->where('status', 'Completed');
            }
            $tasks = $tasks->orderBy('updated_at', 'desc')->paginate(10);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function storeNotesIntoDB($notes)
    {
        try {
            Log::info("Storing Notes Into Database");
            $helper = new Helper();
            foreach ($notes as $note) {
                if (isset($note['Owner'])) {
                    $user = User::where('root_user_id', $note['Owner']['id'])->first();
                }
                $related_to = null;
                $related_to_type = null;
                $apiNames = Module::getApiName();
                if (isset($note['Parent_Id'])) {
                    $result = $helper->getValue($apiNames, $note['Parent_Id']['module']['api_name']);
                    Log::info("resultHelper" . $result);
                    switch ($result) {
                        case 'Deals':
                            $related_to = Deal::where('zoho_deal_id', $note['Parent_Id']['id'])->first();
                            $related_to_type = 'Deal';
                            break;
                        case 'Contacts':
                            $related_to = Contact::where('zoho_contact_id', $note['Parent_Id']['id'])->first();
                            $related_to_type = 'Contact';
                            break;
                        case 'Tasks':
                            $related_to = Task::where('zoho_task_id', $note['Parent_Id']['id'])->first();
                            $related_to_type = 'Tasks';
                            break;
                        default:
                            Log::info("resultHelper" . $result);
                            break;
                    }
                    // if (!$user) {
                    //     // Log an error if the user is not found
                    //     Log::error("User with Zoho ID {$deal['Contact_Name']['id']} not found.");
                    //     continue; // Skip to the next deal
                    // }

                    // Update or create the deal
                    Note::where('zoho_note_id', $note['id'])->update(['related_to_type' => $related_to_type]);
                    Note::updateOrCreate(['zoho_note_id' => $note['id']], [
                        'owner' => isset($user['id']) ? $user['id'] : null,
                        'related_to' => isset($related_to['id']) ? $related_to['id'] : null,
                        'related_to_module_id' => isset($note['Parent_Id']['module']['id']) ? $note['Parent_Id']['module']['id'] : null,
                        'related_to_parent_record_id' => isset($note['Parent_Id']['id']) ? $note['Parent_Id']['id'] : null,
                        'note_content' => isset($note['Note_Content']) ? $note['Note_Content'] : null,
                        'created_time' => isset($note['Created_Time']) ? $helper->convertToUTC($note['Created_Time']) : null,
                        'zoho_note_id' => isset($note['id']) ? $note['id'] : null,
                        '$related_to_type' => isset($related_to_type) ? $related_to_type : null,
                    ]);
                }
            }

            Log::info("Notes stored into database successfully.");
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveNotes(User $user, $accessToken,$paginate)
    {

        try {
            Log::info("Retrieve Notes From Database");
            $tasks = Note::with('userData')
                ->with('dealData')
                ->where('owner', $user->id)
                ->orderBy('updated_at', 'desc')->paginate($paginate);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveNotesFordeal(User $user, $accessToken, $dealId)
    {

        try {
            Log::info("Retrieve Notes From Database");
            $notes = Note::where([['owner', $user->id], ['related_to_type', 'Deals'], ['related_to', $dealId]])->get();
            return $notes;
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveNotesForContact(User $user, $accessToken, $contactId)
    {

        try {
            Log::info("Retrieve Notes From Database");
            $notes = Note::with('userData')->with('ContactData')->where([['owner', $user->id], ['related_to_type', 'Contacts'], ['related_to', $contactId]])->get();
            return $notes;
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveDealContactFordeal(User $user, $accessToken, $dealId)
    {

        try {
            Log::info("Retrieve Deal Contact From Database" . $dealId);
            $dealContacts = DealContact::with('userData')->with('contactData')->with('roleData')->where('zoho_deal_id', $dealId)->orderBy('updated_at', 'desc')->paginate(10);
            Log::info("Retrieved Deal Contact From Database", ['notes' => $dealContacts->toArray()]);
            return $dealContacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveDealContactForContact(User $user, $accessToken, $contactId)
    {

        try {
            Log::info("Retrieve Deal Contact From Database" . $contactId);
            $dealContact = Contact::with('userData')->with('contactName')->with('roleData')->where('zoho_contact_id', $contactId)->get();
            Log::info("Retrieved Deal Contact From Database", ['notes' => $dealContact->toArray()]);
            return $dealContact;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function storeACIIntoDB($acis)
    {
        $helper = new Helper();
        Log::info("Storing ACI Into Database");

        foreach ($acis as $aci) {
            if (is_array($aci)) {
                if (isset($aci['CHR_Agent'])) {
                    $user = User::where('zoho_id', $aci['CHR_Agent']['id'])->first();
                }
                if (isset($aci['Transaction'])) {
                    $deal = Deal::where('zoho_deal_id', $aci['Transaction']['id'])->first();
                }

                // Update or create the deal
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
            }
        }

        Log::info("ACI stored into database successfully.");
    }

    public function retrieveAciFordeal(User $user, $accessToken, $dealId)
    {

        try {
            Log::info("Retrieve Deal Contact From Database" . $dealId);
            $dealContacts = Aci::with('userData')->with('dealData')->where('dealId', $dealId)->get();
            Log::info("Retrieved Deal Contact From Database", ['notes' => $dealContacts->toArray()]);
            return $dealContacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function getIncompleteDeal(User $user, $accessToken, $contact = null)
    {
        try {
            Log::info("Retrieve Deal Contact From Database");
            $condition = [['isDealCompleted', false], ['Client_Name_Primary', $contact], ['userID', $user->id]];
            $deal = Deal::where($condition)->first();
            Log::info("Retrieved Deal Contact From Database", ['deal' => $deal]);
            return $deal;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function getIncompleteSubmittal(User $user, $accessToken, $dealId = null,$submittalType = null,$formType = null)
    {
    try {
    Log::info("Retrieve Submittal Contact From Database",[['isSubmittalComplete', "false"],['submittalType', $submittalType], ['dealId', $dealId], ['userId', $user->id],['formType', $formType]]);
    $condition = [['isSubmittalComplete', "false"],['submittalType', $submittalType], ['dealId', $dealId], ['userId', $user->id],['formType', $formType]];
    $submittal = Submittals::where($condition)->first();
    Log::info("Retrieved Submittal Contact From Database", ['submittal' => $submittal]);
    return $submittal;
    } catch (\Exception $e) {
    Log::error("Error retrieving submittal contacts: " . $e->getMessage());
    throw $e;
    }
}
    public function getIncompleteNonTm(User $user, $accessToken, $nontm = null)
    {
        try {
            Log::info("Retrieve Deal Contact From Database");
            $condition = [['isNonTmCompleted', false], ['dealId', $nontm], ['userId', $user->id]];
            $nontmdata = NonTm::where($condition)->first();
            Log::info("Retrieved Deal Contact From Database", ['deal' => $nontmdata]);
            return $nontmdata;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function getIncompleteContact(User $user, $accessToken)
    {
        try {
            Log::info("Retrieve Deal Contact From Database");
            $contact = Contact::where([['isContactCompleted', false], ['contact_owner', $user->root_user_id]])->first();
            Log::info("Retrieved Deal Contact From Database", ['contact' => $contact]);
            return $contact;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function createDeal(User $user, $accessToken, $dealData)
    {
        try {
            Log::info("User Deatils" . json_encode($dealData));
            if (isset($dealData['Client_Name_Only'])) {
                $clientId = explode("||", $dealData['Client_Name_Only']);
                Log::info("clientId: " . implode(", ", $clientId));

                $contact = Contact::where('zoho_contact_id', trim($clientId[1]))->first();
            }
            if ($dealData['Contact_Name']) {
                $contact_name = Contact::where('zoho_contact_id', $dealData['Contact_Name']['id'])->first();
            }
            $deal = Deal::create([
                'deal_name' => config('variables.dealName'),
                'isDealCompleted' => false,
                'userID' => $user->id,
                'isInZoho' => false,
                // 'zoho_deal_id' => $zohoDeal['id'],
                'client_name_primary' => isset($dealData['Client_Name_Primary']) ? $dealData['Client_Name_Primary'] : null,
                'client_name_only' => isset($dealData['Client_Name_Only']) ? $dealData['Client_Name_Only'] : null,
                'stage' => "Potential",
                'contactId' => isset($contact->id) ? $contact->id : null,
                'contact_name' => isset($contact_name) ? $contact_name->first_name." ".$contact_name->last_name : null,
                'contact_name_id' => isset($contact_name) ? $contact_name->zoho_contact_id : null,
            ]);
            Log::info("Retrieved Deal Contact From Database", ['deal' => $deal]);
            return $deal;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }


    public function createNonTmData(User $user, $accessToken, $dealData ,$zohoDealArray)
    {
        try {
            Log::info("zohoDealArray Deatils" . json_encode($zohoDealArray));

            $nontm = NonTm::create([
                'name' =>$dealData['Name'],
                'userId' => $user->id,
                'dealId' => $dealData['Related_Transaction']['id'],
                // 'zoho_nontm_id' => $zohoDealArray['data'][0]['details']['id'],
                'isNonTmCompleted' =>false,
            ]);
            Log::info("Retrieved Deal nontm From Database", ['nontm' => $nontm]);
            return $nontm;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal nontm: " . $e->getMessage());
            throw $e;
        }
    }

    public function createContact(User $user, $accessToken, $zohoContactId)
    {
        try {
            Log::info("User Deatils" . $user);
            $contact = Contact::create([
                // 'last_name' => "CHR",
                'isContactCompleted' => false,
                'contact_owner' => $user->root_user_id,
                'isInZoho' => false,
                'zoho_contact_id' => $zohoContactId,
            ]);
            Log::info("Retrieved Deal Contact From Database", ['contact' => $contact]);
            return $contact;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateDeal(User $user, $accessToken, $zohoDeal, $DBdeal)
    {
        try {
            $helper = new Helper();
            Log::info("User Details", $zohoDeal);
            if ($zohoDeal['Client_Name_Only']) {
                $clientId = explode("||", $zohoDeal['Client_Name_Only']);
                Log::info("clientId: " . implode(", ", $clientId));

                $contact = Contact::where('zoho_contact_id', trim($clientId[1]))->first();
            }
             $updatedDealData = [
                'personal_transaction' => isset($zohoDeal['Personal_Transaction']) ? ($zohoDeal['Personal_Transaction'] == true ? 1 : 0) : null,
                'double_ended' => isset($zohoDeal['Double_Ended']) ? ($zohoDeal['Double_Ended'] == true ? 1 : 0) : null,
                'address' => isset($zohoDeal['Address']) ? $zohoDeal['Address'] : null,
                'representing' => isset($zohoDeal['Representing']) ? $zohoDeal['Representing'] : null,
                'client_name_only' => isset($zohoDeal['Client_Name_Only']) ? $zohoDeal['Client_Name_Only'] : null,
                'commission' => isset($zohoDeal['Commission']) ? $zohoDeal['Commission'] : null,
                'commission_flat_free' => isset($zohoDeal['Commission_Flat_Fee']) ? $zohoDeal['Commission_Flat_Fee'] : null,
                'zip' => isset($zohoDeal['Zip']) ? $zohoDeal['Zip'] : null,
                'client_name_primary' => isset($zohoDeal['Client_Name_Primary']) ? $zohoDeal['Client_Name_Primary'] : null,
                'closing_date' => isset($zohoDeal['Closing_Date']) ? $helper->convertToUTC($zohoDeal['Closing_Date']) : null,
                'stage' => isset($zohoDeal['Stage']) ? $zohoDeal['Stage'] : null,
                'sale_price' => isset($zohoDeal['Sale_Price']) ? $zohoDeal['Sale_Price'] : null,
                'city' => isset($zohoDeal['City']) ? $zohoDeal['City'] : null,
                'state' => isset($zohoDeal['State']) ? $zohoDeal['State'] : null,
                'pipeline1' => isset($zohoDeal['Pipeline1']) ? $zohoDeal['Pipeline1'] : null,
                'ownership_type' => isset($zohoDeal['Ownership_Type']) ? $zohoDeal['Ownership_Type'] : null,
                'deal_name' => isset($zohoDeal['Deal_Name']) ? $zohoDeal['Deal_Name'] : null,
                'pipeline_probability' => isset($zohoDeal['Pipeline_Probability']) ? $zohoDeal['Pipeline_Probability'] : null,
                'property_type' => isset($zohoDeal['Property_Type']) ? $zohoDeal['Property_Type'] : null,
                'potential_gci' => isset($zohoDeal['Potential_GCI']) ? $zohoDeal['Potential_GCI'] : null,
                'primary_contact'=>isset($zohoDeal['Primary_Contact']) ? $zohoDeal['Primary_Contact'] : null,
                'review_gen_opt_out' => isset($zohoDeal['Review_Gen_Opt_Out']) ? $zohoDeal['Review_Gen_Opt_Out'] : false,
                'deadline_em_opt_out' => isset($zohoDeal['Deadline_Emails']) ? $zohoDeal['Deadline_Emails'] : false,
                'status_rpt_opt_out' => isset($zohoDeal['Status_Reports']) ? $zohoDeal['Status_Reports'] : false,
                'tm_preference' => isset($zohoDeal['TM_Preference']) ? $zohoDeal['TM_Preference'] : null,
                'tm_name' => isset($zohoDeal['TM_Name']['name']) ? $zohoDeal['TM_Name']['name'] : null,
                'tm_name_id' => isset($zohoDeal['TM_Name']['id']) ? $zohoDeal['TM_Name']['id'] : null,
                'lead_agent' => isset($zohoDeal['Lead_Agent']['name']) ? $zohoDeal['Lead_Agent']['name'] : null,
                'lead_agent_id' => isset($zohoDeal['Lead_Agent']['id']) ? $zohoDeal['Lead_Agent']['id'] : null,
                'isDealCompleted' => true,
                'contactId' => isset($contact) ? $contact->id : null,
                'financing' => isset($zohoDeal['Financing']) ? $zohoDeal['Financing'] : null,
                'lender_company' => isset($zohoDeal['Lender_Company']) ? $zohoDeal['Lender_Company'] : null,
                'modern_mortgage_lender' => isset($zohoDeal['Modern_Mortgage_Lender']) ? $zohoDeal['Modern_Mortgage_Lender'] : null,
             ];
            if($DBdeal->zoho_deal_id === null){
                $updatedDealData['zoho_deal_id'] = $zohoDeal['id'];
            }

            $zohoDeal = Deal::updateOrCreate(['id' => $DBdeal->id], $updatedDealData);

            Log::info("Retrieved Deal Contact From Database", ['deal' => $zohoDeal]);
            return $zohoDeal;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function storeGroupsIntoDB($allGroups, $user)
    {
        $helper = new Helper();
        $zoho = new ZohoCRM();
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;

        Log::info("Storing Groups Into Database only group");

        foreach ($allGroups as $groupData) {
            $group = Groups::updateOrCreate(
                ['zoho_group_id' => $groupData['id']],
                [
                    'ownerId' => $groupData['Owner']['id'],
                    'name' => $groupData['Name'] ?? null,
                    'isPublic' => $groupData['Is_Public'] ?? false,
                    'isABCD' => $groupData['isABC'] ?? false,
                ]
            );
        }

        Log::info("Groups stored into database successfully.");
    }

    public function storeContactGroupsIntoDB($allContactGroups, $user)
    {
        $helper = new Helper();
        $zoho = new ZohoCRM();
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;

        Log::info("Storing Groups Into Database");

        foreach ($allContactGroups as $allContactGroup) {
            Log::info("allContactGroup", $allContactGroup);
            if ($allContactGroup['Contacts']) {
                $contact = Contact::where('zoho_contact_id', $allContactGroup['Contacts']['id'])->first();
            }
            if ($allContactGroup['Groups']) {
                $group = Groups::where('zoho_group_id', $allContactGroup['Groups']['id'])->first();
            }
            $contactGroup = ContactGroups::updateOrCreate(
                ['zoho_contact_group_id' => $allContactGroup['id']],
                [
                    'ownerId' => $user->id,
                    "contactId" => $contact['id'] ?? null,
                    "groupId" => $group['id'] ?? null,
                    "zoho_contact_group_id" => $allContactGroup['id'] ?? null,
                ]
            );
        }

        Log::info("Groups stored into database successfully.");
    }

    public function retrieveContactGroups(User $user, $accessToken, $filter = null, $sort = 'asc')
    {
        try {
            Log::info("Retrieve Contacts From Database");
            $condition = [['contacts.contact_owner', $user->root_user_id], ['contacts.zoho_contact_id', '!=', null]];
            $contacts = Contact::where($condition)
                // Left join with contact table to get Secondary contact
                ->leftJoin('contacts as c', function ($join) {
                    $join->on('contacts.zoho_contact_id', '=', DB::raw('COALESCE(JSON_UNQUOTE(JSON_EXTRACT(c.spouse_partner, "$.id")), c.spouse_partner)'));
                })
                ->select(
                    'contacts.id',
                    'contacts.contact_owner',
                    'contacts.zoho_contact_id',
                    'contacts.first_name',
                    'contacts.last_name',
                    'contacts.relationship_type',
                    'contacts.spouse_partner',
                    'contacts.has_email',
                    'contacts.has_address',
                    DB::raw('COALESCE(JSON_UNQUOTE(JSON_EXTRACT(contacts.spouse_partner, "$.id")), contacts.spouse_partner) as partner_id')
                )
                ->with([
                    'groups' => function ($query) use ($filter) {
                        // $query->where('groupId', $filter);
                    },
                ])
                ->when($filter, function ($query) use ($filter) {
                    if ($filter === "has_email") {
                        $query->where('contacts.has_email', 1);
                    } else if($filter==="has_address"){
                        $query->where('contacts.has_address', 1);
                    } else{
                        $query->whereHas('groups', function ($query) use ($filter) {
                            $query->where('groupId', $filter);
                        });
                    }
                })
                ->orderByRaw('COALESCE(JSON_UNQUOTE(JSON_EXTRACT(contacts.spouse_partner, "$.id")), contacts.spouse_partner)')
                ->orderByRaw('CASE WHEN contacts.spouse_partner IS NOT NULL THEN 1 ELSE 0 END')
                ->orderByRaw("CONCAT_WS(' ', contacts.first_name, contacts.last_name) $sort")
                ->orderBy('contacts.updated_at','desc')
                ->paginate();

            return $contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveContactGroupsData(User $user, $accessToken, $contactId, $filter = null, $sortType = null, $sortValue = null, $sort = null)
    {
        try {
            Log::info("Retrieve Tasks From Database");
            $tasks = ContactGroups::where('ownerId', $user->id)
                ->with([
                    'groups' => function ($query) use ($contactId, $sortValue, $sortType) {
                        // Sort the groups only if sort value and type are provided
                        if ($sortValue && $sortType) {
                            $query->join('groups', 'contact_groups.groupId', '=', 'groups.id')
                                ->orderBy('groups.' . $sortValue, $sortType);
                        }
                    },
                ])
                ->when($sort, function ($query, $sort) {
                    $query->orderBy('created_at', $sort);
                })
                ->when($filter, function ($query, $filter) {
                    $query->whereHas('group', function ($query) use ($filter) {
                        $query->where('groupId', $filter);
                    });
                })
                ->when($contactId, function ($query, $contactId) {
                    $query->where('id', $contactId);
                })
                ->get();

            // Filter out contacts with empty groups arrays (optional)
            $tasks = $tasks->filter(function ($contact) {
                return $contact->groups->isNotEmpty();
            });

            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateGroups(User $user, $accessToken, $data)
    {
        try {
            Log::info("Updating Groups");

            // Decode JSON data to array

            if (!$data) {
                throw new \Exception("Error decoding JSON data");
            }

            // Count the number of data items
            $dataCount = count($data);

            // Loop through each data item
            for ($i = 0; $i < $dataCount; $i++) {
                $currData = $data[$i];
                // Update the group record
                $group = Groups::find($currData['id']);
                if ($group) {
                    $group->isShow = $currData['isChecked'];
                    $group->save();
                }
            }

            Log::info("Groups updated successfully");

            // Return updated groups or any other necessary response
            return $data; // Return the updated data for now, you may adjust it according to your requirements
        } catch (\Exception $e) {
            Log::error("Error updating groups: " . $e->getMessage());
            throw $e;
        }
    }

    public function getOwnerGroups(User $user, $accessToken)
    {
        try {
            Log::info("Retrieve Groups From Database");

            // Retrieve all groups owned by the user
            $groups = Groups::where('ownerId', $user->id)->get();

            return $groups;
        } catch (\Exception $e) {
            Log::error("Error retrieving groups: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveGroups(User $user, $accessToken, $isShown = null)
    {
        try {
            Log::info("Retrieve Groups From Database");

            $query = Groups::query();

            if ($isShown) {
                $query->where('isShow', true);
            }

            // Apply ownership filter
            $query->where(function ($query) use ($user) {
                $query->where('ownerId', $user->id)
                    ->orWhere('isPublic', true);
            });

            // Get all groups
            $groups = $query->with(['contacts' => function ($query) use ($user) {
                $query->where('ownerId', $user->id);
            }])->get();

            // Separate the groups into different categories
            $abcdGroups = $groups->filter(function ($group) {
                return $group->isABCD && !$group->isD;
            })->sortBy('name');

            $dGroups = $groups->filter(function ($group) {
                return $group->isD && !$group->isABCD;
            })->sortBy('name');

            $publicGroups = $groups->filter(function ($group) {
                return $group->isPublic && !$group->isABCD && !$group->isD;
            })->sortBy('name');

            $userGroups = $groups->filter(function ($group) {
                return !$group->isPublic && !$group->isABCD && !$group->isD;
            })->sortBy('name');

            // Merge the groups back together in the desired order
            $sortedGroups = $abcdGroups->merge($dGroups)->merge($publicGroups)->merge($userGroups);

            return $sortedGroups->values(); // Reindex the sorted collection
        } catch (\Exception $e) {
            Log::error("Error retrieving groups: " . $e->getMessage());
            throw $e;
        }
    }

    public function storeAttachmentIntoDB($attachments, $userInstance, $dealId)
    {
        $helper = new Helper();
        Log::info("Storing Attachments Into Database" . $userInstance['root_user_id']);
        $filteredAttachments = $attachments->filter(function ($value, $key) use ($userInstance) {
            return $value['Owner']['id'] == $userInstance['root_user_id'];
        });
        Log::info("filteredAttachments" . $filteredAttachments);
        foreach ($filteredAttachments as $attachment) {
            $user = User::where('root_user_id', $attachment['Owner']['id'])->first();
            // if(isset($attachment['Parent_Id']['id'])){
            //     $deal = Deal::where('zoho_deal_id', $attachment['Parent_Id']['id'])->first();
            //     if((isset($deal))){
            Attachment::updateOrCreate(['dealId' => $dealId], [
                "file_name" => isset($attachment['File_Name']) ? $attachment['File_Name'] : null,
                "modified_time" => isset($attachment['Modified_Time']) ? $helper->convertToUTC($attachment['Modified_Time']) : null,
                "size" => isset($attachment['Size']) ? $attachment['Size'] : null,
                "userId" => isset($user['id']) ? $user['id'] : null,
                "dealId" => isset($dealId) ? $dealId : null,
            ]);
            //     }
            // }
        }

        Log::info("Attachment stored into database successfully.");
    }

    public function retreiveAttachment($dealId)
    {
        try {

            Log::info("Retrieve attachments From Database");

            $attachments = Attachment::where('dealId', $dealId)->get();
            Log::info("Retrieved attachments From Database", ['attachments' => $attachments->toArray()]);
            return $attachments;
        } catch (\Exception $e) {
            Log::error("Error retrieving attachments: " . $e->getMessage());
            throw $e;
        }
    }

    public function storeNonTmIntoDB($nontms, $userInstance, $dealId)
    {
        $helper = new Helper();
        Log::info("Storing NonTm Into Database" . $userInstance['root_user_id']);
        $filterednontms = $nontms->filter(function ($value, $key) use ($userInstance) {
            return $value['Owner']['id'] == $userInstance['root_user_id'];
        });
        Log::info("filterednontms" . $filterednontms);
        foreach ($filterednontms as $nontm) {
            $user = User::where('root_user_id', $nontm['Owner']['id'])->first();
            NonTm::updateOrCreate(['zoho_nontm_id' => $nontm['id']], [
                "name" => isset($nontm['Name']) ? $nontm['Name'] : null,
                "closed_date" => isset($nontm['Close_Date']) ? $helper->convertToUTC($nontm['Close_Date']) : null,
                "dealId" => isset($dealId) ? $dealId : null,
                "zoho_nontm_id" => isset($nontm['id']) ? $nontm['id'] : null,
                "userId" => isset($user) ? $user->id : null,
            ]);

        }

        Log::info("NonTm stored into database successfully.");
    }

    public function retreiveNonTm($dealId)
    {
        try {

            Log::info("Retrieve NonTm From Database");

            $NonTm = NonTm::where('dealId', $dealId)->get();
            Log::info("Retrieved NonTm From Database", ['NonTm' => $NonTm->toArray()]);
            return $NonTm;
        } catch (\Exception $e) {
            Log::error("Error retrieving NonTm: " . $e->getMessage());
            throw $e;
        }
    }

    public function storeSubmittalsIntoDB($allSubmittals, $userInstance)
    {
        $helper = new Helper();
        Log::info("Storing Submittals Into Database" . $userInstance['root_user_id']);
        $filteredsubmittals = $allSubmittals->filter(function ($value, $key) use ($userInstance) {
            return $value['Owner']['id'] == $userInstance['root_user_id'];
        });
        Log::info("filteredsubmittals" . $filteredsubmittals);
        foreach ($filteredsubmittals as $submittals) {
            $user = User::where('root_user_id', $submittals['Owner']['id'])->first();
            Submittals::updateOrCreate(['zoho_submittal_id' => $submittals['id']], [
                "submittalName" => isset($submittals['Name']) ? $submittals['Name'] : null,
                "closed_date" => isset($submittals['Close_Date']) ? $helper->convertToUTC($submittals['Close_Date']) : null,
                "dealId" => isset($submittals['Transaction_Name']['id']) ? $submittals['Transaction_Name']['id'] : null,
                "zoho_submittal_id" => isset($submittals['id']) ? $submittals['id'] : null,
                "userId" => isset($user) ? $user->id : null,
            ]);

        }

        Log::info("Submittals stored into database successfully.");
    }

    public function retreiveSubmittals($dealId="")
    {
        try {

            $submittalData = Submittals::where('dealId', $dealId)->with('userData','dealData')->orderBy('updated_at','desc')->paginate(5);
            return $submittalData;
        } catch (\Exception $e) {
            Log::error("Error retrieving Submittals: " . $e->getMessage());
            throw $e;
        }
    }

    public function saveBulkJobInDB($fileId, $userId, $jobId)
    {
        try {
            $bulkJob = BulkJob::create([
                'userId' => $userId,
                'jobId' => $jobId,
                'fileId' => $fileId,
            ]);
            return $bulkJob;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function getBulkJob($jobId)
    {
        try {
            // Retrieve bulk job with user data eagerly loaded
            $bulkJob = BulkJob::where('jobId', $jobId)->with('userData')->firstOrFail();

            // Log retrieved bulk job
            Log::info("Bulk job retrieved from the database", ['bulkJob' => $bulkJob->toArray()]);

            // Return user data associated with the bulk job
            return $bulkJob;
        } catch (\Exception $e) {
            // Log error if any exception occurs
            Log::error("Error retrieving bulk job: " . $e->getMessage());

            // Rethrow the exception to handle it elsewhere
            throw $e;
        }
    }

    public function removeContactGroupFromDB($ids)
    {
        try {
            $bulkJob = ContactGroups::whereIn('zoho_contact_group_id', $ids)->delete();
            return $bulkJob;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }
    public function retriveModules($request, $user, $accessToken)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->access_token;

        // Retrieve query parameters
        $searchQuery = $request->input('q', '');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 5);
        $offset = ($page - 1) * $limit;

        // Fetch the modules and prepare data structure
        $filteredModules = Module::whereIn('api_name', ['Deals', 'Contacts'])->get();
        $moduleIds = $filteredModules->pluck('zoho_module_id', 'api_name');

        $data = [];
        $totalItems = 0;

        foreach ($filteredModules as $module) {
            $query = null;
            if ($module->api_name === 'Deals') {
                $query = Deal::where('userID', $user->id);
            } elseif ($module->api_name === 'Contacts') {
                $query = Contact::where('contact_owner', $user->root_user_id);
            }

            if ($query && $searchQuery) {
                $searchTerms = explode(' ', $searchQuery);
                $query->where(function ($q) use ($searchTerms, $module) {
                    foreach ($searchTerms as $term) {
                        $q->where(function ($subQuery) use ($term, $module) {
                            if ($module->api_name === 'Deals') {
                                $subQuery->where('deal_name', 'like', "%$term%")
                                        ->orWhere('address', 'like', "%$term%");
                            } elseif ($module->api_name === 'Contacts') {
                                $subQuery->where('first_name', 'like', "%$term%")
                                        ->orWhere('last_name', 'like', "%$term%")
                                        ->orWhere('email', 'like', "%$term%")
                                        ->orWhere('phone', 'like', "%$term%")
                                        ->orWhere('mobile', 'like', "%$term%")
                                        ->orWhere('mailing_address', 'like', "%$term%");
                            }
                        });
                    }
                });
            }

            if ($query) {
                $totalItems += $query->count();
                $items = $query->orderBy('updated_at', 'desc')->offset($offset)->limit($limit)->get();
                if ($items->isNotEmpty()) {
                    $data[$module->api_name] = $items->map(function ($item) use ($moduleIds, $module) {
                        $item['zoho_module_id'] = $moduleIds[$module->api_name];
                        return $item;
                    });
                }
            }
        }

        // Prepare response data
        $responseData = [];
        foreach ($data as $moduleName => $moduleData) {
            if (!empty($moduleData)) {
                $responseData[] = [
                    'text' => $moduleName,
                    'children' => $moduleData,
                ];
            }
        }

        // Return response with total count for pagination
        return response()->json([
            'items' => $responseData,
            'total_count' => $totalItems,
        ]);
    }


    public function storeRolesIntoDB($contactRoles, $user)
    {
        Log::info("Storing Contact Roles Into Database");
        foreach ($contactRoles as $contactRole) {
            Log::info("contactRoles", $contactRole);

            ContactRole::updateOrCreate([
                'zoho_role_id' => $contactRole['id'],
            ], [
                'zoho_role_id' => $contactRole['id'],
                'name' => $contactRole['name'],
                'userId' => $user ? $user->id : null,
                'sequence_no' => $contactRole['sequence_number'],
            ]);
        }

        Log::info("Contact Role stored into database successfully.");
    }

    public function retrieveRoles($user)
    {
        Log::info("Get Contact Roles Into Database");
        $roles = ContactRole::where([
            'userId' => $user['id'],
        ])->get();
        return $roles;
        Log::info("Contact Role retrived from database successfully.");
    }

    public function importDataFromCSV($csvFilePath, $module)
    {
        $reader = Reader::createFromPath($csvFilePath, 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $batchSize = 10; // Adjust the batch size based on your memory and performance needs
        $dataBatch = [];

        DB::beginTransaction();
        try {
            foreach ($records as $record) {
                try {
                    // Dynamically call the mapping method based on the module
                    $mappedData = $this->mapDataByModule($module, $record);

                    $dataBatch[] = $mappedData;
                } catch (\Exception $e) {
                    Log::error("Error mapping record for module {$module}: " . $e->getMessage());
                    // Optionally log the failed record or handle it as needed
                    continue; // Skip the failed record and continue with the next one
                }

                if (count($dataBatch) >= $batchSize) {
                    try {
                        $this->upsertDataBatch($dataBatch, $module);
                    } catch (\Exception $e) {
                        Log::error("Error upserting data batch for module {$module}: " . $e->getMessage());
                        // Optionally log specific records causing issues here
                    }
                    $dataBatch = [];
                }
            }

            // Insert any remaining records
            if (count($dataBatch) > 0) {
                try {
                    $this->upsertDataBatch($dataBatch, $module);
                } catch (\Exception $e) {
                    Log::error("Error upserting remaining data batch for module {$module}: " . $e->getMessage());
                    // Optionally log specific records causing issues here
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error importing data from CSV for module {$module}: " . $e->getMessage());
        }
    }

    protected function mapDataByModule($module, $record)
    {
        switch ($module) {
            case 'Contacts':
                return \App\Models\Contact::mapZohoData($record, 'csv');
            case 'Deals':
                return \App\Models\Deal::mapZohoData($record, 'csv');
            case 'Groups':
                return \App\Models\Groups::mapZohoData($record, 'csv');
            case 'Contacts_X_Groups':
                return \App\Models\ContactGroups::mapZohoData($record, 'csv');
            case 'Tasks':
                return \App\Models\Task::mapZohoData($record, 'csv');
            // Add other cases as needed
            default:
                throw new \Exception("Mapping not defined for module {$module}");
        }
    }

    protected function upsertDataBatch(array $dataBatch, $module)
    {
        try {
            switch ($module) {
                case 'Contacts':
                    \App\Models\Contact::upsert($dataBatch, ['zoho_contact_id']);
                    break;
                case 'Deals':
                    \App\Models\Deal::upsert($dataBatch, ['zoho_deal_id']);
                    break;
                case 'Contacts_X_Groups':
                    \App\Models\ContactGroups::upsert($dataBatch, ['zoho_contact_group_id']);
                    break;
                case 'Agent_Commission_Incomes':
                    \App\Models\ACI::upsert($dataBatch, ['zoho_aci_id']);
                    break;
                case 'Groups':
                    \App\Models\Groups::upsert($dataBatch, ['zoho_group_id']);
                    break;
                case 'Tasks':
                    \App\Models\Task::upsert($dataBatch, ['zoho_task_id']);
                    break;

                    // Add other cases as needed
            }
        } catch (\Exception $e) {
            Log::error("Error upserting data batch for module {$module}: " . $e->getMessage());
        }
    }

    public function createSpouseContact(User $user, $accessToken, $spouseId, $zohoSpouseContact)
    {
        try {
            Log::info("Spouse Contact Details", ['contact' => $zohoSpouseContact]);
            $contact = Contact::create([
                'last_name' => $zohoSpouseContact['Last_Name'],
                'relationship_type' => $zohoSpouseContact['Relationship_Type'],
                'missing_abcd' => $zohoSpouseContact['Missing_ABCD'],
                'first_name' => $zohoSpouseContact['First_Name'],
                'email' => $zohoSpouseContact['Email'],
                'phone' => $zohoSpouseContact['Phone'],
                'mobile' => $zohoSpouseContact['Mobile'],
                'isContactCompleted' => false,
                'contact_owner' => $user->root_user_id,
                'isInZoho' => true,
                'zoho_contact_id' => $spouseId,
                /* "Layout"=>[
            "name"=> "Standard",
            "id"=> "5141697000000091033"
            ],  */
            ]);
            Log::info("Spouse Contact Create In Database", ['contact' => $contact]);
            return $contact;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function removeDealContactfromDB($data)
    {

        try {
            $dealContacts = DealContact::where('zoho_deal_id', $data['dealId'])
                ->where('contactId', $data['contact_id'])
                ->firstOrFail();
            $dealContacts->delete();
            return $dealContacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function createListingSubmittal($user, $accessToken, $zohoSubmittal, $submittalData,$dealId,$submittalType)
    {
    try {

        $submittal = Submittals::create([
            'isSubmittalComplete' => "false",
            'userId' => $user->id,
            'isInZoho' => true,
            // 'zoho_submittal_id' => $zohoSubmittal['id'],
            'dealId'=>$dealId,
            'submittalType'=>$submittalType,
            'submittalName'=>$submittalData['Name'],
            'formType'=>$submittalData['formType']
        ]);
        Log::info("Retrieved Submittal Contact From Database", ['submittal' => $submittal]);
        return $submittal;
    } catch (\Exception $e) {
        Log::error("Error retrieving submittal contacts: " . $e->getMessage());
        throw $e;
    }
    }

    public function retrieveSubmittal(User $user, $accessToken, $submittalId)
    {
        try {
            Log::info("submittalId", ['submittal' => $submittalId]);
            $submittal = Submittals::where('id', $submittalId)->orWhere('zoho_submittal_id', $submittalId)->with('dealData')->first();
            Log::info("Retrieved Submittal Contact From Database", ['submittal' => $submittal]);
            return $submittal;
        } catch (\Exception $e) {
            Log::error("Error retrieving submittal contacts: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function updateListingSubmittal($user, $accessToken, $zohoSubmittal, $submittalData,$isNew)
    {
    try {

        $submittal = Submittals::where('id', $submittalData['id'])->orWhere('zoho_submittal_id', $submittalData['id'])->first();
        if (!$submittal) {
            throw new \Exception("Submittal not found for zoho_submittal_id: {$submittalData['id']}");
        }
        $submittal->dealId = isset($submittalData["Transaction_Name"]["id"]) ? $submittalData["Transaction_Name"]["id"] : null;
        $submittal->bedsBathsTotal = isset($submittalData["Beds_Baths_Total_Sq_Ft"]) ? $submittalData["Beds_Baths_Total_Sq_Ft"] : null;
        $submittal->referralDetails = isset($submittalData["Referral_Details"]) ? $submittalData["Referral_Details"] : null;
        $submittal->mlsPublicRemarks = isset($submittalData["MLS_Public_Remarks"]) ? $submittalData["MLS_Public_Remarks"] : null;
        $submittal->navica = isset($submittalData["Navica"]) ? $submittalData["Navica"] : null;
        $submittal->hoaPhone = isset($submittalData["HOA_Phone"]) ? $submittalData["HOA_Phone"] : null;
        $submittal->hasHOA = isset($submittalData["Has_HOA"]) ? $submittalData["Has_HOA"] : null;
        $submittal->ppar = isset($submittalData["PPAR"]) ? $submittalData["PPAR"] : null;
        $submittal->signInstallDate = isset($submittalData["Sign_Install_Date"]) ? $submittalData["Sign_Install_Date"] : null;
        $submittal->brochurePickupDate = isset($submittalData["Pick_Up_Date"]) ? $submittalData["Pick_Up_Date"] : null;
        $submittal->hoaName = isset($submittalData["HOA_Name"]) ? $submittalData["HOA_Name"] : null;
        $submittal->usingCHR = isset($submittalData["Using_CHR_TM"]) ? $submittalData["Using_CHR_TM"] : null;
        $submittal->emailBlastSphere = isset($submittalData["Email_Blast_to_Sphere"]) ? $submittalData["Email_Blast_to_Sphere"] : null;
        $submittal->qrCodeSheet = isset($submittalData["Print_QR_Code_Sheet"]) ? $submittalData["Print_QR_Code_Sheet"] : null;
        $submittal->mlsPrivateRemarks = isset($submittalData["MLS_Private_Remarks"]) ? $submittalData["MLS_Private_Remarks"] : null;
        $submittal->featureCards = isset($submittalData["Feature_Cards_or_Sheets"]) ? $submittalData["Feature_Cards_or_Sheets"] : null;
        $submittal->stickyDots = isset($submittalData["Sticky_Dots"]) ? $submittalData["Sticky_Dots"] : null;
        $submittal->brochureLine = isset($submittalData["Brochure_Line"]) ? $submittalData["Brochure_Line"] : null;
        $submittal->brochurePrint = isset($submittalData["Select_your_prints"]) ? $submittalData["Select_your_prints"] : null;
        $submittal->miscNotes = isset($submittalData["TM_Notes"]) ? $submittalData["TM_Notes"] : null;
        $submittal->conciergeListing = isset($submittalData["Concierge_Listing_Optional"]) ? $submittalData["Concierge_Listing_Optional"] : null;
        $submittal->draftShowingInstructions = isset($submittalData["Draft_Showing_Instructions1"]) ? $submittalData["Draft_Showing_Instructions1"] : null;
        $submittal->floorPlans = isset($submittalData["Floor_Plans"]) ? $submittalData["Floor_Plans"] : null;
        $submittal->onsiteVideo = isset($submittalData["Onsite_Video"]) ? $submittalData["Onsite_Video"] : null;
        $submittal->customDomainName = isset($submittalData["Custom_Domain_Name"]) ? $submittalData["Custom_Domain_Name"] : null;
        $submittal->bullets = isset($submittalData["bullets_4_words_per_bullet"]) ? $submittalData["bullets_4_words_per_bullet"] : null;
        $submittal->headlineForBrochure = isset($submittalData["Word_Headline_If_Opting_for_A_Line_Brochure"]) ? $submittalData["Word_Headline_If_Opting_for_A_Line_Brochure"] : null;
        $submittal->printedItemsPickupDate = isset($submittalData["In_House_Printed_Brochure_Pick_Up_Date"]) ? $submittalData["In_House_Printed_Brochure_Pick_Up_Date"] : null;
        $submittal->hoaWebsite = isset($submittalData["HOA_Website"]) ? $submittalData["HOA_Website"] : null;
        $submittal->photoURL = isset($submittalData["Photo_URL"]) ? $submittalData["Photo_URL"] : null;
        $submittal->tourURL = isset($submittalData["3D_Tour_URL"]) ? $submittalData["3D_Tour_URL"] : null;
        $submittal->closerNamePhone = isset($submittalData["Closer_Name_Phone"]) ? $submittalData["Closer_Name_Phone"] : null;
        $submittal->agreementExecuted = isset($submittalData["Listing_Agreement_Executed"]) ? $submittalData["Listing_Agreement_Executed"] : null;
        $submittal->signInstallVendorOther = isset($submittalData["Sign_Install_Vendor_if_Other"]) ? $submittalData["Sign_Install_Vendor_if_Other"] : null;
        $submittal->threeDZillowTour = isset($submittalData["D_Zillow_Tour"]) ? $submittalData["D_Zillow_Tour"] : null;
        $submittal->emailBlastReverseProspect = isset($submittalData["Email_Blast_to_Reverse_Prospect_List"]) ? $submittalData["Email_Blast_to_Reverse_Prospect_List"] : null;
        $submittal->socialMediaAds = isset($submittalData["Social_Media_Ads"]) ? $submittalData["Social_Media_Ads"] : null;
        $submittal->qrCodeSignRider = isset($submittalData["QR_Code_Sign_Rider"]) ? $submittalData["QR_Code_Sign_Rider"] : null;
        $submittal->grandCounty = isset($submittalData["Grand_County"]) ? $submittalData["Grand_County"] : null;
        $submittal->agentName = isset($submittalData["Agent_Name"]) ? $submittalData["Agent_Name"] : null;
        $submittal->mailoutNeeded = isset($submittalData["Mailout_Needed1"]) ? $submittalData["Mailout_Needed1"] : null;
        $submittal->photoDate = isset($submittalData["Photo_Date"]) ? $submittalData["Photo_Date"] : null;
        $submittal->socialMediaImages = isset($submittalData["Social_Media_Images"]) ? $submittalData["Social_Media_Images"] : null;
        $submittal->featureCardCopy = isset($submittalData["Add_Feature_Card_or_Sheet_Copy"]) ? $submittalData["Add_Feature_Card_or_Sheet_Copy"] : null;
        $submittal->titleCompany = isset($submittalData["Title_Company"]) ? $submittalData["Title_Company"] : null;
        $submittal->referralToPay = isset($submittalData["Referral_to_Pay"]) ? $submittalData["Referral_to_Pay"] : null;
        $submittal->marketingNotes = isset($submittalData["Property_Promotion_Notes"]) ? $submittalData["Property_Promotion_Notes"] : null;
        $submittal->ires = isset($submittalData["IRES"]) ? $submittalData["IRES"] : null;
        $submittal->price = isset($submittalData["Price"]) ? $submittalData["Price"] : null;
        $submittal->commingSoon = isset($submittalData["Coming_Soon"]) ? $submittalData["Coming_Soon"] : null;
        $submittal->titleToOrderHOA = isset($submittalData["Title_to_Order_HOA_docs"]) ? $submittalData["Title_to_Order_HOA_docs"] : null;
        $submittal->includeInsights = isset($submittalData["Include_Insights_in_Intro1"]) ? $submittalData["Include_Insights_in_Intro1"] : null;
        $submittal->featuresNeededForVideo = isset($submittalData["Features_Needed_for_Video"]) ? $submittalData["Features_Needed_for_Video"] : null;
        $submittal->matterport = isset($submittalData["Matterport"]) ? $submittalData["Matterport"] : null;
        $submittal->scheduleSignInstall = isset($submittalData["Schedule_Sign_Install"]) ? $submittalData["Schedule_Sign_Install"] : null;
        $submittal->brochureDeliveryDate = isset($submittalData["Pick_Up_Delivery_Date"]) ? $submittalData["Pick_Up_Delivery_Date"] : null;
        $submittal->propertyWebsite = isset($submittalData["Property_Website_QR_Code"]) ? $submittalData["Property_Website_QR_Code"] : null;
        $submittal->powerOfAttnyNeeded = isset($submittalData["Power_of_Attny_Needed1"]) ? $submittalData["Power_of_Attny_Needed1"] : null;
        $submittal->additionalEmail = isset($submittalData["Additional_Email_for_Confirmation"]) ? $submittalData["Additional_Email_for_Confirmation"] : null;
        $submittal->tmName = isset($submittalData["TM_Name"]) ? $submittalData["TM_Name"] : null;
        $submittal->propertyHighlightVideo = isset($submittalData["Property_Highlight_Video"]) ? $submittalData["Property_Highlight_Video"] : null;
        $submittal->comingSoonDate = isset($submittalData["Coming_Soon_MLS_Date"]) ? $submittalData["Coming_Soon_MLS_Date"] : null;
        $submittal->amountToCHR = isset($submittalData["Amount_to_CHR_Gives"]) ? $submittalData["Amount_to_CHR_Gives"] : null;
        $submittal->reColorado = isset($submittalData["REColorado"]) ? $submittalData["REColorado"] : null;
        $submittal->activeDate = isset($submittalData["Active_Date"]) ? $submittalData["Active_Date"] : null;
        $submittal->needOE = isset($submittalData["Need_O_E1"]) ? $submittalData["Need_O_E1"] : null;
        $submittal->signInstallVendor = isset($submittalData["Sign_Install_Vendor_Info"]) ? $submittalData["Sign_Install_Vendor_Info"] : null;
        $submittal->deliveryAddress = isset($submittalData["Delivery_Only_Shipping_Address_Name"]) ? $submittalData["Delivery_Only_Shipping_Address_Name"] : null;
        $submittal->feesCharged = isset($submittalData["Fees_Charged_to_Seller_at_Closing"]) ? $submittalData["Fees_Charged_to_Seller_at_Closing"] : null;
        $submittal->showPromotion = isset($submittalData["showPromotion"]) ? $submittalData["showPromotion"] : false;
        if ($isNew) {
           $submittal->isSubmittalComplete = $isNew;
        }
        if ( $submittal->zoho_submittal_id === null) {
           $submittal->zoho_submittal_id = isset($zohoSubmittal['id']) ? $zohoSubmittal['id'] : null;;
        }

        $submittal->save();
        Log::info("Retrieved Submittal Contact From Database", ['submittal' => $submittal]);
        return $submittal;
    } catch (\Exception $e) {
        Log::error("Error retrieving submittal contacts: " . $e->getMessage());
        throw $e;
    }
    }

    public function updateBuyerSubmittal($user, $accessToken, $zohoSubmittal, $submittalData,$isNew)
    {
        try {

            $submittal = Submittals::where('zoho_submittal_id', $submittalData['id'])->orWhere('id', $submittalData['id'])->first();
            if (!$submittal) {
                throw new \Exception("Submittal not found for zoho_submittal_id: {$submittalData['id']}");
            }
            $submittal->dealId = isset($submittalData["Related_Transaction"]["id"]) ? $submittalData["Related_Transaction"]["id"] : null;
            $submittal->referralDetails = isset($submittalData["Referral_Details"]) ? $submittalData["Referral_Details"] : null;
            $submittal->buyerAgency = isset($submittalData["Buyer_Agency_Executed"]) ? $submittalData["Buyer_Agency_Executed"] : null;
            $submittal->mailoutNeeded = isset($submittalData["Mailout_Needed"]) ? $submittalData["Mailout_Needed"] : null;
            $submittal->titleCompany = isset($submittalData["Title_Company_Closer_Info"]) ? $submittalData["Title_Company_Closer_Info"] : null;
            $submittal->referralToPay = isset($submittalData["Referral_to_Pay"]) ? $submittalData["Referral_to_Pay"] : null;
            $submittal->marketingNotes = isset($submittalData["Other_Important_Notes"]) ? $submittalData["Other_Important_Notes"] : null;
            $submittal->includeInsights = isset($submittalData["Include_Insights_in_Intro"]) ? $submittalData["Include_Insights_in_Intro"] : null;
            $submittal->powerOfAttnyNeeded = isset($submittalData["Power_of_Attny_Needed"]) ? $submittalData["Power_of_Attny_Needed"] : null;
            $submittal->additionalEmail = isset($submittalData["Additional_Email_for_Confirmation"]) ? $submittalData["Additional_Email_for_Confirmation"] : null;
            $submittal->tmName = isset($submittalData["TM_Name"]) ? $submittalData["TM_Name"] : null;
            $submittal->amountToCHR = isset($submittalData["Amount_to_CHR_Gives"]) ? $submittalData["Amount_to_CHR_Gives"] : null;

            $submittal->buyerPackage = isset($submittalData["Buyer_Package"]) ? $submittalData["Buyer_Package"] : null;
            $submittal->buyerClosingDate = isset($submittalData["Closing_Date"]) ? $submittalData["Closing_Date"] : null;
            $submittal->buyerLenderEmail = isset($submittalData["Lender_Email"]) ? $submittalData["Lender_Email"] : null;
            $submittal->buyerLenderPhone = isset($submittalData["Lender_Phone"]) ? $submittalData["Lender_Phone"] : null;
            $submittal->buyerFeesCharged = isset($submittalData["Fees_Charged_to_Buyer_at_Closing"]) ? $submittalData["Fees_Charged_to_Buyer_at_Closing"] : null;
            $submittal->buyerBuilderrepresent = isset($submittalData["Builder_Representative"]) ? $submittalData["Builder_Representative"] : null;
            $submittal->builderCommisionPercent = isset($submittalData["Builder_Commission_and_or_flat_fee"]) ? $submittalData["Builder_Commission_and_or_flat_fee"] : null;
            $submittal->builderCommision = isset($submittalData["Builder_Commission_Based_On"]) ? $submittalData["Builder_Commission_Based_On"] : null;
            $submittal->contractExecuted = isset($submittalData["Contract_Fully_Executed"]) ? $submittalData["Contract_Fully_Executed"] : null;
            if ($isNew) {
            $submittal->isSubmittalComplete = $isNew;
            }
            if ($submittal['zoho_submittal_id']===null) {
            $submittal->zoho_submittal_id = isset($zohoSubmittal["id"]) ? $zohoSubmittal["id"] : null;;
            }

            $submittal->save();
            Log::info("Retrieved Submittal Contact From Database", ['submittal' => $submittal]);
            return $submittal;
        } catch (\Exception $e) {
            Log::error("Error retrieving submittal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function removeContactFromDB($id)
    {
        try {
            $bulkJob = Contact::where('zoho_contact_id', $id)->delete();
            return $bulkJob;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function removeDealFromDB($id)
    {
        try {
            $bulkJob = Deal::where('zoho_deal_id', $id)->delete();
            return $bulkJob;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

}
