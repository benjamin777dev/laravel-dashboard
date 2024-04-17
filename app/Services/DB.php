<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Models\Deal; // Import the Deal model
use App\Models\DealContact; // Import the Deal model
use App\Models\Contact; // Import the Deal model
use App\Models\Task; // Import the Deal model
use App\Models\Note; // Import the Deal model
use App\Models\Module; // Import the Module model
use App\Models\Aci;
use App\Services\Helper;
use App\Services\ZohoCRM;
use Carbon\Carbon;


class DB
{
    public function storeDealsIntoDB($dealsData, $user)
    {
        $helper = new Helper();
        $zoho = new ZohoCRM();
        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;
        Log::info("Storing Deals Into Database");
        $dealCount = count($dealsData);
        for ($i = 0; $i < $dealCount; $i++) {
            $deal = $dealsData[$i];
            $userInstance = User::where('zoho_id', $deal['Contact_Name']['id'])->first();
            if ($deal['Client_Name_Only']) {
                $clientId = explode("||", $deal['Client_Name_Only']);
                Log::info("clientId: " . implode(", ", $clientId));

                $contact = Contact::where('zoho_contact_id', trim($clientId[1]))->first();
            }
            if (!$userInstance) {
                // Log an error if the user is not found
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

            // Update or create the deal
            Deal::updateOrCreate(['zoho_deal_id' => $deal['id']], [
                'zip' => $deal['Zip'],
                'personal_transaction' => $deal['Personal_Transaction'],
                'double_ended' => $deal['Double_Ended'],
                'userID' => $userInstance->id,
                'address' => $deal['Address'],
                'representing' => $deal['Representing'],
                'client_name_only' => $deal['Client_Name_Only'],
                'commission' => $deal['Commission'],
                'probable_volume' => $deal['Probable_Volume'],
                'lender_company' => $deal['Lender_Company'],
                'closing_date' => $helper->convertToUTC($deal['Closing_Date']),
                'ownership_type' => $deal['Ownership_Type'],
                'needs_new_date2' => $deal['Needs_New_Date2'],
                'deal_name' => $deal['Deal_Name'],
                'tm_preference' => $deal['TM_Preference'],
                'stage' => $deal['Stage'],
                'sale_price' => $deal['Sale_Price'],
                'zoho_deal_id' => $deal['id'],
                'pipeline1' => $deal['Pipeline1'],
                'pipeline_probability' => $deal['Pipeline_Probability'],
                'zoho_deal_createdTime' => $helper->convertToUTC($deal['Created_Time']),
                'property_type' => $deal['Property_Type'],
                'city' => $deal['City'],
                'state' => $deal['State'],
                'lender_company_name' => $deal['Lender_Company_Name'],
                'client_name_primary' => $deal['Client_Name_Primary'],
                'lender_name' => $deal['Lender_Name'],
                'potential_gci' => $deal['Potential_GCI'],
                'contractId' => null,
                'contactId' => isset($contact) ? $contact->id : null,
                'isDealCompleted'=>true,
                'isInZoho'=>true
            ]);
        }

        Log::info("Deals stored into database successfully.");
    }

    public function storeDealContactIntoDB($dealContacts, $dealId)
    {
        Log::info("Storing Deal Contacts Into Database");
        $dealContactsCount = count($dealContacts);
        for ($i = 0; $i < $dealContactsCount; $i++) {
            $dealContact = $dealContacts[$i];
            $contact = Contact::where('zoho_contact_id', $dealContact['id'])->first();
            $user = User::where('zoho_id', $dealContact['id'])->first();

            DealContact::updateOrCreate([
                'zoho_deal_id' => $dealId,
                'contactId' => $contact ? $contact->id : null,
                'userId' => $user ? $user->id : null,
                'contactRole' => $dealContact['Contact_Role']['name']
            ],[
                'zoho_deal_id' => $dealId,
                'contactId' => $contact ? $contact->id : null,
                'userId' => $user ? $user->id : null,
                'contactRole' => $dealContact['Contact_Role']['name']
            ]);
        }

        Log::info("Deal Contacts stored into database successfully.");
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

            // if (!$user) {
            //     // Log an error if the user is not found
            //     Log::error("User with Zoho ID {$deal['Contact_Name']['id']} not found.");
            //     continue; // Skip to the next deal
            // }

            // Update or create the deal
            Contact::updateOrCreate(['zoho_contact_id' => $contact['id']], [
                "contact_owner" => isset($user['id']) ? $user['id'] : null,
                "email" => isset($contact['Email']) ? $contact['Email'] : null,
                "first_name" => isset($contact['First_Name']) ? $contact['First_Name'] : null,
                "last_name" => isset($contact['Last_Name']) ? $contact['Last_Name'] : null,
                "phone" => isset($contact['Phone']) ? $contact['Phone'] : null,
                "created_time" => isset($contact['Created_Time']) ? $helper->convertToUTC($contact['Created_Time']) : null,
                "abcd" => isset($contact['ABCD']) ? $contact['ABCD'] : null,
                "mailing_address" => isset($contact['Mailing_Address']) ? $contact['Mailing_Address'] : null,
                "mailing_city" => isset($contact['Mailing_City']) ? $contact['Mailing_City'] : null,
                "mailing_state" => isset($contact['Mailing_State']) ? $contact['Mailing_State'] : null,
                "mailing_zip" => isset($contact['Mailing_Zip']) ? $contact['Mailing_Zip'] : null,
                "zoho_contact_id" => isset($contact['id']) ? $contact['id'] : null
            ]);
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
             if ($task['What_Id']) {
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
                "who_id" => isset($contact['id']) ? $contact['id'] : null,
                "created_by" => isset($contact['id']) ? $contact['id'] : null,
                "description" => isset($task['Description']) ? $task['Description'] : null,
                "due_date" => isset($task['Due_Date']) ? $helper->convertToUTC($task['Due_Date']) : null,
                "priority" => isset($task['Priority']) ? $task['Priority'] : null,
                "what_id" => isset($task['What_Id']) ? $task['What_Id']['id'] : null,
                "status" => isset($task['Status']) ? $task['Status'] : null,
                "subject" => isset($task['Subject']) ? $task['Subject'] : null,
                "owner" => isset($user['id']) ? $user['id'] : null,
                "created_time" => isset($task['Created_Time']) ? $helper->convertToUTC($task['Created_Time']) : null,
                "zoho_task_id" => isset($task['id']) ? $task['id'] : null
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
                 "api_name"=> isset($module['api_name']) ? $module['api_name'] : null,
                "modified_time"=>isset($module['modified_time']) ? $module['modified_time'] : null,
                "zoho_module_id" => isset($module['id']) ? $module['id'] : null
            ]);
        }

        Log::info("Module stored into database successfully.");
    }

    public function retrieveModuleDataDB(User $user, $accessToken){
        try {
            // Validate user token (pseudo-code, replace it with your actual validation logic)
            if (!$accessToken) {
                throw new \Exception("Invalid user token");
            }
            
            // Retrieve module data from MySQL
            $allModules = Module::all(); // Assuming you want to retrieve all modules

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

    public function retrieveDeals(User $user, $accessToken, $search = null, $sortValue = null, $sortType = null,$dateFilter=null)
    {

        try {
            Log::info("Retrieve Deals From Database");
            
            $conditions = [['userID', $user->id]];

            // Adjust query to include contactName table using join
            $deals = Deal::with('userData', 'contactName')
                ->join('contacts', 'deals.contactId', '=', 'contacts.id') // Adjust 'contactName' to the actual table name if different
                ->select('deals.*'); // Select only fields from the deals table
            if ($search !== "") {
                $searchTerms = urldecode($search);
                $deals->where(function ($query) use ($searchTerms) {
                    $query->where('deal_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('contacts.first_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('contacts.last_name', 'like', '%' . $searchTerms . '%')
                       ->orWhere(\Illuminate\Support\Facades\DB::raw("CONCAT(contacts.first_name, ' ', contacts.last_name)"), 'like', '%' . $searchTerms . '%');
                    // Add more OR conditions as needed
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
                        // Handle default sorting logic if needed
                        break;
                }
            }

            if($dateFilter&&$dateFilter!=''){
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $deals->whereBetween('closing_date', [$startOfWeek, $endOfWeek]);
            }
            Log::info("Deal Conditions", ['deals' => $conditions]);

            // Retrieve deals based on the conditions
            $deals = $deals->where($conditions)->get();
            Log::info("Retrieved Deals From Database", ['deals' => $deals->toArray()]);
            return $deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }


    }

    public function retrieveDealById(User $user, $accessToken,$dealId)
    {

        try {
            Log::info("Retrieve Deals From Database");
            
            $conditions = [['userID', $user->id],['id', $dealId]];

            // Adjust query to include contactName table using join
            $deals = Deal::with('userData', 'contactName');

            
            Log::info("Deal Conditions", ['deals' => $conditions]);

            // Retrieve deals based on the conditions
            $deals = $deals->where($conditions)->first();
            Log::info("Retrieved Deals From Database", ['deals' => $deals]);
            return $deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }



    }

    public function retrieveContactById(User $user, $accessToken,$contactId)
    {

        try {
            Log::info("Retrieve Deals From Database");
            
            $conditions = [['contact_owner', $user->id],['id', $contactId]];

            // Adjust query to include contactName table using join
            $contacts = Contact::with('userData', 'contactName');

            
            Log::info("Contacts Conditions", ['contacts' => $conditions]);

            // Retrieve deals based on the conditions
            $contacts = $contacts->where($conditions)->first();
            Log::info("Retrieved Deals From Database", ['contacts' => $contacts]);
            return $contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving Contacts: " . $e->getMessage());
            throw $e;
        }

    }

     public function retrieveDealByZohoId(User $user, $accessToken,$dealId)
    {

        try {
            Log::info("Retrieve Deals From Database");
            
            $conditions = [['userID', $user->id],['zoho_deal_id', $dealId]];

            // Adjust query to include contactName table using join
            $deals = Deal::with('userData', 'contactName');

            
            Log::info("Deal Conditions", ['deals' => $conditions]);

            // Retrieve deals based on the conditions
            $deals = $deals->where($conditions)->first();
            Log::info("Retrieved Deals From Database", ['deals' => $deals]);
            return $deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }


    }

    public function retreiveTasks(User $user, $accessToken, $tab = '')
    {
        try {

            Log::info("Retrieve Tasks From Database");
            $tasks = Task::where('owner', $user->id)->where('status', $tab)->paginate(3);
            Log::info("Retrieved Tasks From Database", ['tasks' => $tasks->toArray()]);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveTasksJson(User $user, $accessToken,$dealId=null)
    {
        try {

            Log::info("Retrieve Tasks From Database");
            $condition =[
                ['owner', $user->id]
            ];
            if($dealId){
               $condition[] = ['what_id', $dealId];
            }
            $tasks = Task::where($condition)->get();
            Log::info("Retrieved Tasks From Database", ['tasks' => $tasks]);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveDealsJson(User $user, $accessToken,$dealId=null)
    {
        try {

            Log::info("Retrieve Deals From Database");
            $condition =[
                ['userID', $user->id]
            ];
            if($dealId){
               $condition[] = ['zoho_deal_id', $dealId];
            }
            $Deals = Deal::where($condition)->get();
            Log::info("Retrieved deals From Database", ['Deals' => $Deals]);
            return $Deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveContactsJson(User $user,$accessToken){
        try {

            Log::info("Retrieve contacts From Database");
            $Contacts = Contact::where('contact_owner', $user->id)->get();
            Log::info("Retrieved contacts From Database", ['Contacts' => $Contacts]);
            return $Contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;

        }
    }
    public function retreiveTasksFordeal(User $user, $accessToken,$tab = '',$dealId='')
    {
        try {

            Log::info("DealIDS".$dealId);
            $tasks = Task::with('dealData')->where([['owner', $user->id],['status', $tab]])->whereNotNull('what_id')
        ->where('what_id', $dealId)->paginate(10);
            Log::info("Retrieved Tasks From Database", ['tasks' => $tasks]);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveTasksForContact(User $user, $accessToken,$tab = '',$dealId='')
    {
        try {

            Log::info("DealIDS".$dealId);
            $contactTask = Task::with('dealData')->where([['owner', $user->id],['status', $tab]])->whereNotNull('what_id')
        ->where('what_id', $dealId)->paginate(10);
            Log::info("Retrieved Tasks From Database", ['contactTask' => $contactTask]);
            return $contactTask;
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
                    'related_to_module_id' => isset($note['Parent_Id']['module']['id']) ?$note['Parent_Id']['module']['id'] : null,
                    'related_to_parent_record_id' => isset($note['Parent_Id']['id']) ?$note['Parent_Id']['id'] : null,
                    'note_content' => isset($note['Note_Content']) ? $note['Note_Content'] : null,
                    'created_time' => isset($note['Created_Time']) ? $helper->convertToUTC($note['Created_Time']) : null,
                    'zoho_note_id' => isset($note['id']) ? $note['id'] : null,
                    '$related_to_type' => isset($related_to_type) ? $related_to_type : null,
                ]);
            }

            Log::info("Notes stored into database successfully.");
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveNotes(User $user, $accessToken)
    {

        try {
            Log::info("Retrieve Notes From Database");
            $tasks = Note::with('userData')->with('dealData')->where('owner', $user->id)->get();
            Log::info("Retrieved Notes From Database", ['notes' => $tasks->toArray()]);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveNotesFordeal(User $user, $accessToken,$dealId)
    {

        try {
            Log::info("Retrieve Notes From Database");
            $notes = Note::with('userData')->with('dealData')->where([['owner', $user->id],['related_to_type','Deals'],['related_to',$dealId]])->get();
            Log::info("Retrieved Notes From Database", ['notes' => $notes->toArray()]);
            return $notes;
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveDealContactFordeal(User $user, $accessToken,$dealId)
    {

        try {
            Log::info("Retrieve Deal Contact From Database".$dealId);
            $dealContacts = DealContact::with('userData')->with('contactData')->where('zoho_deal_id', $dealId)->get();
            Log::info("Retrieved Deal Contact From Database", ['notes' => $dealContacts->toArray()]);
            return $dealContacts;
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
            $user = User::where('zoho_id', $aci['CHR_Agent']['id'])->first();
            $deal = Deal::where('zoho_deal_id', $aci['Transaction']['id'])->first();

            // if (!$user) {
            //     // Log an error if the user is not found
            //     Log::error("User with Zoho ID {$deal['Contact_Name']['id']} not found.");
            //     continue; // Skip to the next deal
            // }

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
                'less_split_to_chr'=> isset($aci['Less_Split_to_CHR']) ? $aci['Less_Split_to_CHR'] : null,
            ]);
        }

        Log::info("ACI stored into database successfully.");
    }

    public function retrieveAciFordeal(User $user, $accessToken,$dealId)
    {

        try {
            Log::info("Retrieve Deal Contact From Database".$dealId);
            $dealContacts = Aci::with('userData')->with('dealData')->where('dealId', $dealId)->get();
            Log::info("Retrieved Deal Contact From Database", ['notes' => $dealContacts->toArray()]);
            return $dealContacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function getIncompleteDeal(User $user, $accessToken)
    {
        try {
            Log::info("Retrieve Deal Contact From Database");
            $deal = Deal::where('isDealCompleted', false)->first();
            Log::info("Retrieved Deal Contact From Database", ['deal' => $deal]);
            return $deal;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function getIncompleteContact(User $user, $accessToken)
    {
        try {
            Log::info("Retrieve Deal Contact From Database");
            $contact = Contact::where('isContactCompleted', false)->first();
            Log::info("Retrieved Deal Contact From Database", ['contact' => $contact]);
            return $contact;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function createDeal(User $user, $accessToken,$zohoDealId)
    {
        try {
            Log::info("User Deatils".$user);
            $deal = Deal::create([
                'deal_name' => config('variables.dealName'),
                'isDealCompleted'=>false,
                'userID'=>$user->id,
                'isInZoho'=>true,
                'zoho_deal_id'=>$zohoDealId
            ]);
            Log::info("Retrieved Deal Contact From Database", ['deal' => $deal]);
            return $deal;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

     public function updateDeal(User $user, $accessToken,$deal,$id)
    {
        try {
            $helper = new Helper();
            Log::info("User Deatils".$user);
            $deal =  Deal::updateOrCreate(['zoho_deal_id' => $id], [
                'zip' => $deal['Zip'],
                'address' => $deal['Address'],
                'representing' => $deal['Representing'],
                'client_name_only' => $deal['Client_Name_Only'],
                'closing_date' => $helper->convertToUTC($deal['Closing_Date']),
                'deal_name' => $deal['Deal_Name'],
                'stage' => $deal['Stage'],
                'sale_price' => $deal['Sale_Price'],
                'city' => $deal['City'],
                'state' => $deal['State'],
                'pipeline1'=>$deal['Pipeline1'],
                'personal_transaction' => $deal['Personal_Transaction']==true?1:0,
                'double_ended' => $deal['Double_Ended']==true?1:0,
                'commission' => $deal['Commission'],
                'ownership_type' => $deal['Ownership_Type'],
                'deal_name' => $deal['Deal_Name'],
                'pipeline_probability' => $deal['Pipeline_Probability'],
                'property_type' => $deal['Property_Type'],
                'potential_gci' => $deal['Potential_GCI'],
            ]);
            Log::info("Retrieved Deal Contact From Database", ['deal' => $deal]);
            return $deal;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }
}
