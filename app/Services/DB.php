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
use App\Models\Groups; 
use App\Models\ContactGroups; 
use App\Models\Attachment; 
use App\Models\NonTm; 
use App\Models\Submittals; 
use App\Models\BulkJob; 

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

            // Fetching deal attachments
            $attachmentResponse = $zoho->getAttachmentData($deal['id']);
            if (!$attachmentResponse->successful()) {
                Log::error("Error retrieving deal contacts: " . $attachmentResponse->body());
                continue; // Skip to the next deal
            }
            $attachments = collect($attachmentResponse->json()['data'] ?? []);
            Log::error("USERSARA" . $user);
            $this->storeAttachmentIntoDB($attachments, $userInstance, $deal['id']);

            // Fetching deal nonTM
            $nonTmResponse = $zoho->getNonTmData($deal['id']);
            if (!$nonTmResponse->successful()) {
                Log::error("Error retrieving deal contacts: " . $nonTmResponse->body());
                continue; // Skip to the next deal
            }
            $nonTm = collect($nonTmResponse->json()['data'] ?? []);
            Log::error("USERSARA" . $user);
            $this->storeNonTmIntoDB($nonTm, $userInstance, $deal['id']);

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
                'isDealCompleted' => true,
                'isInZoho' => true
            ]);
        }

        Log::info("Deals stored into database successfully.");
    }

    public function storeDealContactIntoDB($dealContacts, $dealId)
    {
        Log::info("Storing Deal Contacts Into Database");
        foreach ($dealContacts as $dealContact) {
            Log::info("dealContact", $dealContact);
            $contact = Contact::where('zoho_contact_id', $dealContact['id'])->first();
            $user = User::where('zoho_id', $dealContact['id'])->first();

            DealContact::updateOrCreate([
                'zoho_deal_id' => $dealId,
                'contactRole' => $dealContact['Contact_Role']['name']
            ], [
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
                "phone" => isset($contact['Phone']) ? $contact['Phone'] : null,
                "mobile" => isset($contact['Mobile']) ? $contact['Mobile'] : null,
                "mailing_state" => isset($contact['Mailing_State']) ? $contact['Mailing_State'] : null,
                "market_area" => isset($contact['Market_Area']) ? $contact['Market_Area'] : null,
                "relationship_type" => isset($contact['Relationship_Type']) ? $contact['Relationship_Type'] : null,
                "envelope_salutation" => isset($contact['Salutation']) ? $contact['Salutation'] : null,
                "envelope_salutation" => isset($contact['Salutation']) ? $contact['Salutation'] : null,
                "referred_id" => isset($contact['Referred_By']) ? $contact['Referred_By']['id'] : null,
                "Lead_Source" => isset($contact['Lead_Source']) ? $contact['Lead_Source'] : null,
                "lead_source_detail" => isset($contact['Lead_Source_Detail']) ? $contact['Lead_Source_Detail'] : null,
                "spouse_partner" => isset($contact['Spouse_Partner']) ? $contact['Spouse_Partner']['id'] : null,
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
                "related_to" => isset($task['$se_module']) ? $task['$se_module'] : null
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
                "zoho_module_id" => isset($module['id']) ? $module['id'] : null
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

    public function retrieveDeals(User $user, $accessToken, $search = null, $sortValue = null, $sortType = null, $dateFilter = null, $filter = null)
    {

        try {
            Log::info("Retrieve Deals From Database");

            $conditions = [['userID', $user->id], ['stage', '!=', 'Dead-Lost To Competition']];

            // Adjust query to include contactName table using join
            $deals = Deal::where($conditions); // Select only fields from the deals table

            if ($search !== "") {
                $searchTerms = urldecode($search);
                $deals->where(function ($query) use ($searchTerms) {
                    $query->where('deal_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('client_name_primary', 'like', '%' . $searchTerms . '%');
                    //     ->orWhere('contacts.last_name', 'like', '%' . $searchTerms . '%')
                    //    ->orWhere(\Illuminate\Support\Facades\DB::raw("CONCAT(contacts.first_name, ' ', contacts.last_name)"), 'like', '%' . $searchTerms . '%');
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

            if ($dateFilter && $dateFilter != '') {
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $startOfNext30Days  = Carbon::now()->startOfDay();
                $endOfNext30Days  = Carbon::now()->addDays(30)->endOfDay();
                $deals->whereBetween('closing_date', [$startOfWeek, $endOfWeek])->orWhere(function($query) use ($startOfNext30Days, $endOfNext30Days) {
                    $query->whereBetween('closing_date', [$startOfNext30Days, $endOfNext30Days])->where('stage','!=','Under Contract');
                });
            }
            if ($filter) {
                $conditions[] = ['stage', $filter];
            }
            Log::info("Deal Conditions", ['deals' => $conditions]);

            // Retrieve deals based on the conditions
            $deals = $deals->where($conditions)->paginate(10);
            Log::info("Retrieved Deals From Database", ['deals' => $deals->toArray()]);
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

    public function retrieveContactById(User $user, $accessToken, $contactId)
    {

        try {
            Log::info("Retrieve contact From Database");

            $conditions = [['contact_owner', $user->id], ['id', $contactId]];

            // Adjust query to include contactName table using join
            $contacts = Contact::with('userData', 'contactName');


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
            Log::info("Retrieved Deals From Database", ['deals' => $deals]);
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

            $conditions = [['contact_owner', $user->id], ['zoho_contact_id', $contactId]];

            // Adjust query to include contactName table using join
            $contacts = Contact::with('userData', 'contactName');


            Log::info("Deal Conditions", ['contacts' => $conditions]);

            // Retrieve contacts based on the conditions
            $contacts = $contacts->where($conditions)->first();
            Log::info("Retrieved contacts From Database", ['contacts' => $contacts]);
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
            $condition = [];
            $tasks = Task::where('owner', $user->id)->with(['dealData', 'contactData']);
            if ($tab == 'Completed') {
                $tasks
                    ->where('due_date', '<', now());
            } elseif ($tab == 'Not Started') {
                $tasks
                    ->where('due_date', '>=', now());
            } else {
                $tasks->where('status', $tab);
            }
            $tasks = $tasks->orderBy('updated_at', 'desc')->paginate(10);
            Log::info("Retrieved Tasks From Database", ['tasks' => $tasks->toArray()]);
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
                ['owner', $user->id]
            ];
            if ($dealId) {
                $condition[] = ['what_id', $dealId];
            }
            if ($contactId) {
                $condition[] = ['who_id', $contactId];
            }
            $tasks = Task::where($condition)->orderBy('updated_at', 'desc')->get();
            Log::info("Retrieved Tasks From Database", ['tasks' => $tasks]);
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
                ['userID', $user->id]
            ];
            if ($dealId) {
                $condition[] = ['zoho_deal_id', $dealId];
            }
            if ($contactId) {
                $condition[] = ['zoho_deal_id', $contactId];
            }
            $Deals = Deal::where($condition)->orderBy('updated_at','desc')->get();
            Log::info("Retrieved deals From Database", ['Deals' => $Deals]);
            return $Deals;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveContacts(User $user, $accessToken, $search = null, $sortValue = null, $sortType = null, $dateFilter = null, $filter = null)
    {
        try {
            Log::info("Retrieve Contact From Database");

            $conditions = [['contact_owner', $user->id]];

            // Adjust query to include contactName table using join
            $contacts = Contact::where($conditions); // Select only fields from the contacts table

            if ($search !== "" || $filter) {
                $searchTerms = urldecode($search);
                $contacts->where(function ($query) use ($searchTerms) {
                    $query->where('first_name', 'like', '%' . $searchTerms . '%')
                        ->orWhere('email', 'like', '%' . $searchTerms . '%');
                    //     ->orWhere('contacts.last_name', 'like', '%' . $searchTerms . '%')
                    //    ->orWhere(\Illuminate\Support\Facades\DB::raw("CONCAT(contacts.first_name, ' ', contacts.last_name)"), 'like', '%' . $searchTerms . '%');
                    // Add more OR conditions as needed
                });
            }

            if ($filter) {
                $conditions[] = ['abcd', $filter];
            }
            // Retrieve deals based on the conditions
            $contacts = $contacts->where($conditions)->orderBy('updated_at', 'desc')->paginate(10);
            Log::info("Retrieved contacts From Database", ['contacts' => $contacts->toArray()]);
            return $contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            throw $e;
        }

    }

    public function retreiveContactsJson(User $user, $accessToken)
    {
        try {

            Log::info("Retrieve contacts From Database");
            $Contacts = Contact::where('contact_owner', $user->id)->orderBy('updated_at', 'desc')->get();
            Log::info("Retrieved contacts From Database", ['Contacts' => $Contacts]);
            return $Contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;

        }
    }
    public function retreiveTasksFordeal(User $user, $accessToken, $tab = '', $dealId = '')
    {
        try {

            Log::info("DealIDS" . $dealId);
            $tasks = Task::with('dealData')->where([['owner', $user->id], ['status', $tab]])->whereNotNull('what_id')
                ->where('what_id', $dealId)->orderBy('updated_at', 'desc')->paginate(10);
            Log::info("Retrieved Tasks From Database", ['tasks' => $tasks]);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            throw $e;
        }
    }

    public function retreiveTasksForContact(User $user, $accessToken, $tab = '', $dealId = '')
    {
        try {

            Log::info("DealIDS" . $dealId);
            $contactTask = Task::with('dealData')->where([['owner', $user->id], ['status', $tab]])->whereNotNull('who_id')
                ->where('who_id', $dealId)->orderBy('updated_at', 'desc')->paginate(10);
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

    public function retrieveNotes(User $user, $accessToken)
    {

        try {
            Log::info("Retrieve Notes From Database");
            $tasks = Note::with('userData')->with('dealData')->where('owner', $user->id)->orderBy('updated_at', 'desc')->get();
            Log::info("Retrieved Notes From Database", ['notes' => $tasks->toArray()]);
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
            Log::info("Retrieved Notes From Database", ['notes' => $notes->toArray()]);
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
            Log::info("Retrieved Notes From Database", ['notes' => $notes->toArray()]);
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
            $dealContacts = DealContact::with('userData')->with('contactData')->where('zoho_deal_id', $dealId)->get();
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
            $dealContact = Contact::with('userData')->with('contactName')->where('zoho_contact_id', $contactId)->get();
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

    public function createDeal(User $user, $accessToken, $zohoDeal,$dealData)
    {
        try {
            Log::info("User Deatils" . json_encode($zohoDeal));
             if (isset($dealData['Client_Name_Only'])) {
                $clientId = explode("||", $dealData['Client_Name_Only']);
                Log::info("clientId: " . implode(", ", $clientId));

                $contact = Contact::where('zoho_contact_id', trim($clientId[1]))->first();
            }
            $deal = Deal::create([
                'deal_name' => config('variables.dealName'),
                'isDealCompleted' => false,
                'userID' => $user->id,
                'isInZoho' => true,
                'zoho_deal_id' => $zohoDeal['id'],
                'client_name_primary'=>isset($dealData['Client_Name_Primary'])?$dealData['Client_Name_Primary']:null,
                'client_name_only'=>isset($dealData['Client_Name_Only'])?$dealData['Client_Name_Only']:null,
                'stage' => "Potential",
                'contactId'=>isset($contact->id)?$contact->id:null
            ]);
            Log::info("Retrieved Deal Contact From Database", ['deal' => $deal]);
            return $deal;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function createContact(User $user, $accessToken, $zohoContactId)
    {
        try {
            Log::info("User Deatils" . $user);
            $contact = Contact::create([
                'last_name' => "CHR",
                'isContactCompleted' => false,
                'contact_owner' => $user->id,
                'isInZoho' => true,
                'zoho_contact_id' => $zohoContactId
            ]);
            Log::info("Retrieved Deal Contact From Database", ['contact' => $contact]);
            return $contact;
        } catch (\Exception $e) {
            Log::error("Error retrieving deal contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateDeal(User $user, $accessToken, $deal, $id)
    {
        try {
            $helper = new Helper();
            Log::info("User Details" ,$deal);
            if ($deal['Client_Name_Only']) {
                $clientId = explode("||", $deal['Client_Name_Only']);
                Log::info("clientId: " . implode(", ", $clientId));

                $contact = Contact::where('zoho_contact_id', trim($clientId[1]))->first();
            }
            $deal = Deal::updateOrCreate(['zoho_deal_id' => $id], [
                'zip' => isset($deal['Zip']) ? $deal['Zip'] : null,
                'address' => isset($deal['Address']) ? $deal['Address'] : null,
                'representing' => isset($deal['Representing']) ? $deal['Representing'] : null,
                'client_name_primary' => isset($deal['Client_Name_Primary']) ? $deal['Client_Name_Primary'] : null,
                'closing_date' => isset($deal['Closing_Date']) ? $helper->convertToUTC($deal['Closing_Date']) : null,
                'stage' => isset($deal['Stage']) ? $deal['Stage'] : null,
                'sale_price' => isset($deal['Sale_Price']) ? $deal['Sale_Price'] : null,
                'city' => isset($deal['City']) ? $deal['City'] : null,
                'state' => isset($deal['State']) ? $deal['State'] : null,
                'pipeline1' => isset($deal['Pipeline1']) ? $deal['Pipeline1'] : null,
                'personal_transaction' => isset($deal['Personal_Transaction']) ? ($deal['Personal_Transaction'] == true ? 1 : 0) : null,
                'double_ended' => isset($deal['Double_Ended']) ? ($deal['Double_Ended'] == true ? 1 : 0) : null,
                'commission' => isset($deal['Commission']) ? $deal['Commission'] : null,
                'ownership_type' => isset($deal['Ownership_Type']) ? $deal['Ownership_Type'] : null,
                'deal_name' => isset($deal['Deal_Name']) ? $deal['Deal_Name'] : null,
                'pipeline_probability' => isset($deal['Pipeline_Probability']) ? $deal['Pipeline_Probability'] : null,
                'property_type' => isset($deal['Property_Type']) ? $deal['Property_Type'] : null,
                'potential_gci' => isset($deal['Potential_GCI']) ? $deal['Potential_GCI'] : null,
                'commission_flat_free'=>isset($deal['Commission_Flat_Free']) ? $deal['Commission_Flat_Free'] : null,
                'review_gen_opt_out'=>isset($deal['Review_Gen_Opt_Out']) ? $deal['Review_Gen_Opt_Out'] : null,
                'isDealCompleted' => true,
                'contactId' => isset($contact) ? $contact->id : null,
            ]);

            Log::info("Retrieved Deal Contact From Database", ['deal' => $deal]);
            return $deal;
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

        Log::info("Storing Groups Into Database");

        foreach ($allGroups as $groupData) {
            $group = Groups::updateOrCreate(
                ['zoho_group_id' => $groupData['id']],
                [
                    'ownerId' => $user->id,
                    "name" => $groupData['Name'] ?? null,
                    "isPublic" => $groupData['Is_Public'] ?? false,
                    "isABCD" => $groupData['isABC'] ?? false,
                    "zoho_group_id" => $groupData['id'] ?? null
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
                    "zoho_contact_group_id" => $allContactGroup['id'] ?? null
                ]
            );
        }

        Log::info("Groups stored into database successfully.");
    }

    public function retrieveContactGroups(User $user, $accessToken, $filter = null, $sort = null)
    {
        try {
            Log::info("Retrieve Contacts From Database");

            $contacts = Contact::where('contact_owner', $user->id)
                ->with([
                    'groups' => function ($query) use ($filter) {
                        if ($filter) {
                            $query->where('groupId', $filter);
                        }
                    }
                ])
                ->when($filter, function ($query) use ($filter) {
                    $query->whereHas('groups', function ($query) use ($filter) {
                        $query->where('groupId', $filter);
                    });
                })
                ->when($sort, function ($query, $sort) {
                    $query->orderBy('first_name', $sort);
                })
                ->get();


            Log::info("Retrieved Contacts From Database", ['contacts_count' => $contacts->count()]);

            return $contacts;
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveContactGroupsData(User $user, $accessToken, $filter = null, $sortType = null, $contactId, $sortValue = null, $sort = null)
    {
        try {
            Log::info("Retrieve Tasks From Database");
            $tasks = ContactGroups::where('ownerId', $user->id)
                ->with([
                    'groups' => function ($query) use ($contactId, $sortValue, $sortType) {
                        // Sort the groups only if contact ID is provided
                        if ($sortValue && $sortType) {
                            $query->join('groups', 'contact_groups.groupId', '=', 'groups.id')
                                ->orderBy('groups.' . $sortValue, $sortType);
                        }
                    }
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

            Log::info("Retrieved Tasks From Database", ['tasks_count' => $tasks->count()]);

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

    public function retrieveGroups(User $user, $accessToken, $isShown = null)
    {
        try {

            Log::info("Retrieve Tasks From Database");
            $condition = [['ownerId', $user->id]];
            if ($isShown) {
                $condition[] = ['isShow', true];
            }
            $tasks = Groups::where($condition)->with('contacts')->orderBy('name', 'asc')->get();
            Log::info("Retrieved Tasks From Database", ['tasks' => $tasks->toArray()]);
            return $tasks;
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
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
        Log::info("Storing NonTm Into Database" . $userInstance['root_user_id']);
        $filteredsubmittals = $allSubmittals->filter(function ($value, $key) use ($userInstance) {
            return $value['Owner']['id'] == $userInstance['root_user_id'];
        });
        Log::info("filteredsubmittals" . $filteredsubmittals);
        foreach ($filteredsubmittals as $submittals) {
            $user = User::where('root_user_id', $submittals['Owner']['id'])->first();
            Submittals::updateOrCreate(['zoho_submittal_id' => $submittals['id']], [
                "name" => isset($submittals['Name']) ? $submittals['Name'] : null,
                "closed_date" => isset($submittals['Close_Date']) ? $helper->convertToUTC($submittals['Close_Date']) : null,
                "dealId" => isset($submittals['Transaction_Name']['id']) ? $submittals['Transaction_Name']['id'] : null,
                "zoho_submittal_id" => isset($submittals['id']) ? $submittals['id'] : null,
                "userId" => isset($user) ? $user->id : null,
            ]);

        }

        Log::info("NonTm stored into database successfully.");
    }

    public function retreiveSubmittals($dealId)
    {
        try {

            Log::info("Retrieve Submittals From Database");

            $Submittals = Submittals::where('dealId', $dealId)->with('userData')->get();
            Log::info("Retrieved Submittals From Database", ['Submittals' => $Submittals->toArray()]);
            return $Submittals;
        } catch (\Exception $e) {
            Log::error("Error retrieving Submittals: " . $e->getMessage());
            throw $e;
        }
    }

    public function saveBulkJobInDB($fileId,$userId,$jobId)
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
    public function retriveModules($request,$user, $accessToken)
    { 
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
    
        $accessToken = $user->getAccessToken();
        // if (!$accessToken) {
        //     throw new \Exception("Invalid user token");
        // }
    
        // Retrieve query parameters
        $searchQuery = $request->input('q', '');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 5);
        $offset = ($page - 1) * $limit;
    
        $filteredModules = Module::whereIn('api_name', ['Deals', 'Contacts'])->get();
        $data = [];
        $moduleIds = [];
    
        foreach ($filteredModules as $module) {
            $moduleIds[$module->api_name] = $module->zoho_module_id;
            $data[$module->api_name] = [];
        }
    
        foreach ($filteredModules as $module) {
            if ($module->api_name === 'Deals') {
                // Retrieve Deals data based on search query if provided
                $dealsQuery = Deal::query();
                if ($searchQuery) {
                    $dealsQuery->where('deal_name', 'like', "%$searchQuery%");
                }
                $totalDeals = $dealsQuery->count();
                $dealsData = $dealsQuery->offset($offset)->limit($limit)->get();
                if ($searchQuery || $dealsData->isNotEmpty()) {
                    $data['Deals'] = $dealsData->map(function ($deal) use ($moduleIds) {
                        $deal['zoho_module_id'] = $moduleIds['Deals'];
                        return $deal;
                    });
                }
            } elseif ($module->api_name === 'Contacts') {
                // Retrieve Contacts data based on search query if provided
                $contactsQuery = Contact::query();
                if ($searchQuery) {
                    $contactsQuery->where(function($query) use ($searchQuery) {
                        $query->where('first_name', 'like', "%$searchQuery%")
                              ->orWhere('last_name', 'like', "%$searchQuery%");
                    });
                }
                $totalContacts = $contactsQuery->count();
                $contactsData = $contactsQuery->offset($offset)->limit($limit)->get();
                if ($searchQuery || $contactsData->isNotEmpty()) {
                    $data['Contacts'] = $contactsData->map(function ($contact) use ($moduleIds) {
                        $contact['zoho_module_id'] = $moduleIds['Contacts'];
                        return $contact;
                    });
                }
            }
        }
    
        // Add objects for Contacts and Deals with their respective data arrays
        $responseData = [];
        foreach ($data as $moduleName => $moduleData) {
            if (!empty($moduleData)) {
                $responseData[] = [
                    'text' => $moduleName,
                    'children' => $moduleData
                ];
            }
        }
    
        // Return response with total count for pagination
        return response()->json([
            'items' => $responseData,
            'total_count' => isset($totalDeals) ? $totalDeals : (isset($totalContacts) ? $totalContacts : 0)
        ]);
    }
    
    

}
