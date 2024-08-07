<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactGroups;
use App\Models\Groups;
use App\Models\User;
use App\Services\DatabaseService;
use App\Services\Helper;
use App\Services\ZohoCRM;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DataTables;
use Illuminate\Support\Facades\Validator;
use App\Rules\ValidMobile;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $search = request()->query('search');
        $sortField = $request->input('sort');
        $sortType = $request->input('sortType');
        $filter = $request->input('filter');
        $missingFeild = $request->input('missingField');
        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $contacts = $db->retreiveContacts($user, $accessToken, $search, $sortField, $sortType, null, $filter, $missingFeild);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        $userContact = $db->retrieveContactDetailsByZohoId($user, $accessToken, $user->zoho_id);
        $apend = false;
        if ($request->ajax()) {
            $apend = true;
            $view = view('contacts.load', compact('contacts', 'userContact', 'retrieveModuleData', 'apend'))->render();
            return Response::json(['view' => $view, 'nextPageUrl' => $contacts->nextPageUrl()]);
        }

        return view('contacts.index', compact('contacts', 'userContact', 'retrieveModuleData', 'apend'));
    }

    public function contactList(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $search = request()->query('q');
        $sortField = $request->input('sort');
        $sortType = $request->input('sortType');
        $filter = $request->input('filter');
        $missingFeild = $request->input('missingField');
        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $contacts = $db->retreiveContacts($user, $accessToken, $search, $sortField, $sortType, null, $filter, $missingFeild);
        
        return response()->json(['contacts'=>$contacts]);
    }

    public function showCreateContactForm()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $user_id = $user->root_user_id;
        $name = $user->name;
        $db = new DatabaseService();
        $accessToken = $user->getAccessToken(); // Method to get the access token.
        $contactId = request()->route('contactId');
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
         if (!$contact) {
            return response()->json(["redirect" => "/contacts"]);
        }
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        return view('contacts.create', compact('contactId','retrieveModuleData','contact'));

    }
    public function contactCreateForm(Request $request)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        if (session()->has('spouseContact')) {
            session()->forget('spouseContact');
        }
        $spouseContact = session('spouseContact');
        $user_id = $user->root_user_id;
        $name = $user->name;
        $db = new DatabaseService();
        $accessToken = $user->getAccessToken(); // Method to get the access token.
        $contactId = request()->route('contactId');
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
        if (!$contact) {
            return response()->json(["redirect" => "/contacts"]);
        }
        $users = $user;
        $contacts = $db->retreiveContactsJson($user, $accessToken);
        $contactsGroups = $db->retrieveContactGroupsData($user, $accessToken, $contactId, $filter = null, $sortType = null, $sortField = null);
        $groups = $db->retrieveGroups($user, $accessToken);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        if ($spouseContact) {
            // Ensure $spouseContact is an array or an object, not a string
            if (is_string($spouseContact)) {
                $spouseContact = json_decode($spouseContact, true);
            }
        }

        return view('contacts.createForm', compact('contact','retrieveModuleData','contacts',  'users', 'spouseContact',  'contactId', 'groups', 'contactsGroups'))->render();

    }

    public function show($contactId)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $spouseContact = session('spouseContact');
        $user_id = $user->root_user_id;
        $name = $user->name;
        $db = new DatabaseService();
        // $contactInfo = Contact::getZohoContactInfo();
        $accessToken = $user->getAccessToken(); // Method to get the access token.
        $contactId = request()->route('contactId');
        Log::info('CONTACTIDDATA' . $contactId);
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
        if (!$contact) {
           return response()->json(["redirect" => "/contacts"]);
        }
        $groups = $db->retrieveGroups($user, $accessToken);
        $contactsGroups = $db->retrieveContactGroupsData($user, $accessToken, $contactId, $filter = null, $sortType = null, $sortField = null);
        $tab = request()->query('tab') ?? 'In Progress';
        $users = $user;
        $tasks = $db->retreiveTasksForContact($user, $accessToken, $tab, $contact->zoho_contact_id);
        $notes = $db->retrieveNotesForContact($user, $accessToken, $contactId);
        $dealContacts = $db->retrieveDealContactFordeal($user, $accessToken, $contact->zoho_contact_id);
        $getdealsTransaction = $db->retrieveDeals($user, $accessToken, $search = null, $sortField = null, $sortType = null, "");
        $contacts = $db->retreiveContacts($user, $accessToken);
        $userContact = $db->retrieveContactDetailsByZohoId($user, $accessToken, $user->zoho_id);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        if (request()->ajax()) {
            // If it's an AJAX request, return the pagination HTML
            return view('common.tasks', compact('contact', 'tasks', 'retrieveModuleData', 'tab'))->render();
        }
        if ($spouseContact) {
            // Ensure $spouseContact is an array or an object, not a string
            if (is_string($spouseContact)) {
                $spouseContact = json_decode($spouseContact, true);
            }
        }
        return view('contacts.detail', compact('contact', 'userContact', 'user_id', 'tab', 'name', 'contacts', 'tasks', 'notes', 'getdealsTransaction', 'retrieveModuleData', 'dealContacts', 'contactId', 'users', 'groups', 'contactsGroups','spouseContact'));
    }

    public function getContactJson(){
        {
            try{
            $user = $this->user();
    
            if (!$user) {
                return redirect('/login');
            }
            $db = new DatabaseService();
            $search = request()->query('search');
            $stage = request()->query('stage');
            $filterobj = request()->query('filterobj');
            // $contactInfo = Contact::getZohoContactInfo();
            $accessToken = $user->getAccessToken(); // Method to get the access token.
            if(!$accessToken){
                return "invalid token.";
            }
            $contacts = $db->retreiveContactsJson($user, $accessToken,$search,$stage,$filterobj);
            if (!$contacts) {
               return response()->json(["redirect" => "/contacts"]);
            }
            return Datatables::of($contacts)->make(true);
        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            throw $e;
        }
        }
    }

    public function updateContactField(Request $request){
        try {
            $user = $this->user();
    
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
            $id = $request->input('id');
            $dbfield = $request->input('field');
            $value = $request->input('value');
               // Define validation rules and messages
           // Define validation rules and messages
           $rules = [
            'id' => 'required|exists:contacts,id',
            'field' => 'required|in:email,mobile,phone,envelope_salutation',
            'value' => 'nullable', // Allow the value to be nullable (empty)
        ];

        $messages = [
            'id.required' => 'Contact ID is required.',
            'id.exists' => 'Invalid contact ID.',
            'field.required' => 'Field type is required.',
            'field.in' => 'Invalid field type.',
            'value.email' => 'Invalid email format.',
            'value.regex' => 'Invalid mobile phone format.',
            'value.numeric' => $dbfield .' number must be numeric.',
        ];

        // Add custom validation for email format and phone number format if value is not empty
        if (!empty($request->input('value'))) {
            if ($request->input('field') === 'email') {
                $rules['value'] .= '|email';
            } elseif ($request->input('field') === 'mobile' || $request->input('field') === 'phone') {
                $rules['value'] .= '|numeric'; // Regex for international phone numbers
            }
        }

        // Validate request inputs
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
        }
        
            $zoho = new ZohoCRM();
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
    
            $field = "";
            switch ($dbfield) {
                case "email":
                    $field = "Email";
                    break;
                case "mobile":
                    $field = "Mobile";
                    break;
                case "phone":
                    $field = "Phone";
                    break;
                case "envelope_salutation":
                    $field = "Mailing_Address"; // Adjust field name as needed
                    break;
                default:
                    return response()->json(['error' => 'Invalid field '], Response::HTTP_BAD_REQUEST);
            }
    
            $contact = Contact::findOrFail($id);
    
            $formData = [
                'data' => [
                    [
                        $field => $value,
                    ],
                ],
                'skip_mandatory' => true,
            ];
    
            $zohoContact = $zoho->createContactData($formData, $contact->zoho_contact_id);
    
            if (!$zohoContact->successful()) {
                return response()->json(['error' => 'Zoho Contact update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    
            // Update local contact record
            $contact->$dbfield = $value;
            $contact->save();
    
            return response()->json(['data' => $zohoContact, 'message' => "Successfully Updated"]);
    
        } catch (\Throwable $th) {
            // Handle the exception here
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    

    public function showDetailForm($contactId)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $spouseContact = session('spouseContact');
        $user_id = $user->root_user_id;
        $name = $user->name;
        $db = new DatabaseService();
        // $contactInfo = Contact::getZohoContactInfo();
        $accessToken = $user->getAccessToken(); // Method to get the access token.
        $contactId = request()->route('contactId');
        Log::info('CONTACTIDDATA' . $contactId);
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
        if (!$contact) {
           return response()->json(["redirect" => "/contacts"]);
        }
        $groups = $db->retrieveGroups($user, $accessToken);
        $contactsGroups = $db->retrieveContactGroupsData($user, $accessToken, $contactId, $filter = null, $sortType = null, $sortField = null);
        $tab = request()->query('tab') ?? 'In Progress';
        $users = $user;
        $tasks = $db->retreiveTasksForContact($user, $accessToken, $tab, $contact->zoho_contact_id);
        $notes = $db->retrieveNotesForContact($user, $accessToken, $contactId);
        $dealContacts = $db->retrieveDealContactFordeal($user, $accessToken, $contact->zoho_contact_id);
        $deals = $db->retrieveDeals($user, $accessToken, $contact->zoho_contact_id, null, null, null, null, false);
        $allstages = config('variables.dealStages');
        $getdealsTransaction = $db->retrieveDeals($user, $accessToken, $search = null, $sortField = null, $sortType = null, "");
        $contacts = $db->retreiveContacts($user, $accessToken);
        $userContact = $db->retrieveContactDetailsByZohoId($user, $accessToken, $user->zoho_id);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        if ($spouseContact) {
            // Ensure $spouseContact is an array or an object, not a string
            if (is_string($spouseContact)) {
                $spouseContact = json_decode($spouseContact, true);
            }
        }
        return view('contacts.detailForm', compact('contact', 'userContact','deals','allstages', 'user_id', 'tab', 'name', 'contacts', 'tasks', 'notes', 'getdealsTransaction', 'retrieveModuleData', 'dealContacts', 'contactId', 'users', 'groups', 'contactsGroups','spouseContact'));
    }


    public function getContact(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $search = request()->query('search');
        $sortField = $request->input('sort');
        $sortType = $request->input('sortType');
        $filter = $request->input('filter');
        $missingFeild = $request->input('missingField');
        $contacts = $db->retreiveContacts($user, $accessToken, $search, $sortField, $sortType, null, $filter, $missingFeild);
        $getdealsTransaction = $db->retrieveDeals($user, $accessToken, $search = null, $sortField = null, $sortType = null, "");
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        $groups = $db->retrieveGroups($user, $accessToken);
        $apend = false;
        return view('contacts.contact', compact('contacts', 'getdealsTransaction', 'retrieveModuleData', 'groups', 'apend'))->render();
        // return view('pipeline.index', compact('deals'));
    }

    public function updateContact(Request $request, $id)
    {

        try {
            
            $user = $this->user();

            if (!$user) {
                return redirect('/login');
            }
            $rules = [];
            $helper = new Helper();
            $accessToken = $user->getAccessToken();
            $zoho = new ZohoCRM();
            $db = new DatabaseService();
            $zoho->access_token = $accessToken;
            $frontData = $request->all();
            if (!empty($frontData['data'])) {
                $first_name = "";
                $last_name = "";
                $contactInstanceforJson = Contact::where('zoho_contact_id', $id)->first();
                if (!$contactInstanceforJson) {
                    return redirect('/contacts');
                }
                if (isset($frontData['data'][0]['First_Name'])) {
                    if (strpos($frontData['data'][0]['First_Name'], ' ') !== false) {
                        $parts = explode(' ', $frontData['data'][0]['First_Name']);
                        if (count($parts) == 5) {
                            $first_name = $parts[0];
                            // The last part will be the last name
                            $last_name = end($parts);
                            $frontData['data'][0]['First_Name'] = $first_name;
                            $frontData['data'][0]['Last_Name'] = $last_name;
                        } else {
                            return response()->json(['error' => 'Use Only One Space', 'status' => 401], 500);
                        }
                    }
                }
                $mobile = "";
                if (isset($frontData['data'][0]['Mobile'])) {
                    $mobile = $frontData['data'][0]['Mobile'];
                    if (!ctype_digit($mobile)) {
                        // If mobile contains non-numeric characters, return an error
                        return response()->json(['error' => 'Mobile must contain only numbers', 'status' => 401], 500);
                    }
                }

                $salutation = "";
                if (isset($frontData['data'][0]['Salutation'])) {
                    $salutation = $frontData['data'][0]['Salutation'];  
                } 

                $abcd = "";
                if (isset($frontData['data'][0]['ABCD'])) {
                    $abcd = $frontData['data'][0]['ABCD'];  
                } 
                $validEmail = "";
                if (isset($frontData['data'][0]['Email'])) {

                    $email = $frontData['data'][0]['Email'];

                    // Validate email address
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Email is valid, assign it to the contact instance
                        $validEmail = $email;
                    } else {
                        // Email is not valid, handle the error (return an error response, log it, etc.)
                        return response()->json(['error' => 'Invalid email address', 'status' => 401], 500);
                    }
                }
                $responseFromZoho = $zoho->createContactData($frontData, $id);
                if (!$responseFromZoho->successful()) {
                    Log::error("Error creating contacts:");
                    return "error somthing" . $responseFromZoho;
                }

                $dataJson = json_decode($responseFromZoho, true);
                if (isset($responseFromZoho['data'][0])) {
                    $data = $responseFromZoho['data'][0];
                    $id = $data['details']['id'];
                    $createdByName = $data['details']['Created_By']['name'];
                    $createdById = $data['details']['Created_By']['id'];
                    $contactInstanceforJson->created_time = isset($data['details']['Created_Time']) ? $helper->convertToUTC($data['details']['Created_Time']) : null;
                    if (!empty($first_name)) {
                        $contactInstanceforJson->first_name = $first_name ?? null;
                    }
                    if (!empty($last_name)) {
                        $contactInstanceforJson->last_name = $last_name ?? null;
                    }
                    if (!empty($mobile)) {
                        $contactInstanceforJson->mobile = $mobile ?? null;

                    }
                    if (!empty($salutation)) {
                        $contactInstanceforJson->salutation_s = $salutation ?? null;

                    }
                    if (!empty($abcd)) {
                        $contactInstanceforJson->abcd = $abcd ?? null;

                    }
                    if (!empty($validEmai)) {
                        $contactInstanceforJson->email = $validEmail ?? null;
                        $contactInstanceforJson->has_email = true;
                    }
                    if (!empty($frontData['data'][0]['Email'])) {
                        $contactInstanceforJson->email = $frontData['data'][0]['Email'] ?? null;

                    }
                    $contactInstanceforJson->zoho_contact_id = $id ?? null;
                    // $contactInstanceforJson->abcd = $validatedData['abcd_class'] ?? null;
                    $contactInstanceforJson->save();
                    return $responseFromZoho;
                }
            }

            $contactOwnerArray = json_decode($request->contactOwner, true);
            // Validate the array
            $contactOwnerArray = json_decode($request->contactOwner, true);
            if (!is_null($contactOwnerArray['id'])) {
                $validatedData1 = validator()->make($contactOwnerArray, [
                    'id' => 'required|numeric',
                    'Full_Name' => 'required|string|max:255',
                ])->validate();
                Log::info('Validated contact owner', ['validatedData1' => $validatedData1]);
            } else {
                Log::warning('contactOwner id is null, skipping validation', ['contactOwnerArray' => $contactOwnerArray]);
                $validatedData1 = $contactOwnerArray;
            }

            if (isset($request->reffered_by) && $request->reffered_by !== '') {
                Log::info('Processing reffered_by', ['reffered_by' => $request->reffered_by]);
                $refferedData = json_decode($request->reffered_by, true);

                // Check if the 'id' is null
                if (!is_null($refferedData['id'])) {
                    $validatedRefferedData = validator()->make($refferedData, [
                        'id' => 'required|numeric',
                        'Full_Name' => 'required|string|max:255',
                    ])->validate();
                    Log::info('Validated reffered_by', ['validatedRefferedData' => $validatedRefferedData]);
                } else {
                    Log::warning('reffered_by id is null, skipping validation', ['refferedData' => $refferedData]);
                    $validatedRefferedData = $refferedData;
                }
            }
        
            if (isset($request->spouse_partner) && $request->spouse_partner !== '' && is_object(json_decode($request->spouse_partner))) {
                $spouse_partner = json_decode($request->spouse_partner, true);
                // Check if the 'id' is null
                if (!is_null($spouse_partner['id'])) {
                    $validatedSpouse = validator()->make($spouse_partner, [
                        'id' => 'required|numeric',
                        'Full_Name' => 'required|string|max:255',
                    ])->validate();
                    Log::info('Validated spouse_partner', ['validatedSpouse' => $validatedSpouse]);
                } else {
                    Log::warning('spouse_partner id is null, skipping validation', ['spouse_partner' => $spouse_partner]);
                    $validatedSpouse = $spouse_partner;
                }
            }

            $input = $request->all();
            if (isset($input['last_called']) && $input['last_called'] !== '') {
                $rules['last_called'] = 'date';
            }
            if (isset($input['first_name']) && $input['first_name'] !== '') {
                $rules['first_name'] = 'required|string|max:255';
            }
            if (isset($input['last_emailed']) && $input['last_emailed'] !== '') {
                $rules['last_emailed'] = 'date';
            }
            if (isset($input['mobile']) && $input['mobile'] !== '') {
                $rules['mobile'] = ['required', 'string', new ValidMobile];
            }

            if (isset($input['phone']) && $input['phone'] !== '') {
                $rules['phone'] = ['required', 'string', new ValidMobile];
            }
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            }
            if (isset($input['email']) && $input['email'] !== '') {
                $rules['email'] = 'required|string|email';
            }
            if (isset($input['market_area']) && $input['market_area'] !== '') {
                $rules['market_area'] = 'required|string|max:255';
            }
            if (isset($input['relationship_type']) && $input['relationship_type'] !== '') {
                $rules['relationship_type'] = 'required|string|max:255';
            }
            if (isset($input['lead_source']) && $input['lead_source'] !== '') {
                $rules['lead_source'] = 'required|string|max:255';
            }
            if (isset($input['lead_source_detail']) && $input['lead_source_detail'] !== '') {
                $rules['lead_source_detail'] = 'required|string|max:255';
            }
            if (isset($input['envelope_salutation']) && $input['envelope_salutation'] !== '') {
                $rules['envelope_salutation'] = 'required|string|max:255';
            }
            if (isset($input['spouse_partner']) && $input['spouse_partner'] !== '') {
                $rules['spouse_partner'] = 'required|string|max:255';
            }
            if (isset($input['business_name']) && $input['business_name'] !== '') {
                $rules['business_name'] = 'required|string|max:255';
            }
            if (isset($input['abcd_class']) && $input['abcd_class'] !== '') {
                $rules['abcd_class'] = 'required|string|max:255';
            }
            if (isset($input['business_information']) && $input['business_information'] !== '') {
                $rules['business_information'] = 'required|string|max:255';
            }
            if (isset($input['address_line1']) && $input['address_line1'] !== '') {
                $rules['address_line1'] = 'required|string|max:255';
            }
            if (isset($input['address_line2']) && $input['address_line2'] !== '') {
                $rules['address_line2'] = 'required|string|max:255';
            }
            if (isset($input['city']) && $input['city'] !== '') {
                $rules['city'] = 'required|string|max:255';
            }
            if (isset($input['state']) && $input['state'] !== '') {
                $rules['state'] = 'required|string|max:255';
            }
            if (isset($input['zip_code']) && $input['zip_code'] !== '') {
                $rules['zip_code'] = 'required|string|max:255';
            }
            if (isset($input['email_primary']) && $input['email_primary'] !== '') {
                $rules['email_primary'] = 'required|string|max:255';
            }
            if (isset($input['primary_address']) && $input['primary_address'] !== '') {
                $rules['primary_address'] = 'required|string|max:255';
            }
            if (isset($input['secondry_address']) && $input['secondry_address'] !== '') {
                $rules['secondry_address'] = 'required|string|max:255';
            }
            
            // Validate the request data using the defined rules
            $validatedData = $request->validate($rules);

            $validatedData2 = $request->validate([
                'last_name' => 'required|string|max:255',
            ]);
            $last_name = $validatedData2['last_name'];
            $responseData = [
                "data" => [
                    [
                        "Relationship_Type" => $validatedData['relationship_type'] ?? "",
                        "Missing_ABCD" => true,
                        "Owner" => [
                            "id" => $validatedData1['id'],
                            "full_name" => $validatedData1['Full_Name'],
                        ],
                        "Unsubscribe_From_Reviews" => false,
                        "Currency" => "USD",
                        "Market_Area" => $validatedData['market_area'] ?? "-None-",
                        // "Salutation" => $validatedData['envelope_salutation'] ?? "-None-",
                        "First_Name" => $validatedData['first_name'] ?? "",
                        "Lead_Source" => $validatedData['lead_source'] ?? "-None-",
                        "Last_Name" => $last_name,
                        "Mobile" => $validatedData['mobile'] ?? "",
                        "Phone" => $validatedData['phone'] ?? "",
                        "ABCD" => $validatedData['abcd_class'] ?? "",
                        "Email" => $validatedData['email'] ?? "",
                        "Business_Name" => $validatedData['business_name'] ?? "",
                        "Business_Info" => $validatedData['business_information'] ?? "",
                        "Groups" => [],
                        "Referred_By" => [
                            "id" => $validatedRefferedData['id'] ?? "",
                            "name" => $validatedRefferedData['Full_Name'] ?? "",
                        ] ?? '-None-',
                        "Lead_Source_Detail" => $validatedData['lead_source_detail'] ?? "",
                        "Salutation_s"=> $validatedData['envelope_salutation'] ?? "-None-",
                        "Spouse_Partner" => [
                            "id" => $validatedSpouse['id'] ?? $request->spouse_partner ?? "",
                            "name" => $validatedSpouse['Full_Name'] ?? "",
                        ],
                        // "Random_Notes"=> $validatedData['phone'] ?? "",
                        "Mailing_Street" => $validatedData['address_line1'] ?? "",
                        "Mailing_City" => $validatedData['city'] ?? "",
                        "Mailing_State" => $validatedData['state'] ?? "",
                        "Mailing_Zip" => $validatedData['zip_code'] ?? "",
                        "Secondary_Email" => $validatedData['email_primary'] ?? "",
                        // "Groups_Tags"=> $validatedData['phone'] ?? "",
                        "Last_Called" => isset($validatedData['last_called']) ? $validatedData['last_called'] . 'T10:00:00' : "",
                        //"2024-04-24T10:00:00",
                        "Last_Emailed" => isset($validatedData['last_emailed']) ? $validatedData['last_emailed'] . 'T10:00:00' : "",
                        // "Other_Street"=> $validatedData['address_line2'] ?? "",
                        // "Other_City"=> $validatedData['phone'] ?? "",
                        // "Other_State"=> $validatedData['phone'] ?? "",
                        // "Other_Zip"=> $validatedData['phone'] ?? "",
                        // "Layout"=> [
                        //   "id"=> "5141697000000091033"
                        // ],
                        // "$zia_owner_assignment"=> "owner_recommendation_unavailable",
                        "zia_suggested_users" => [],
                    ],
                ],
                "skip_mandatory" => true,
            ];
            $selectedGroupsarr = [];
            $selectedGroupsarr = json_decode($request->selectedGroups);
            if (is_array($selectedGroupsarr) && count($selectedGroupsarr) !== 0) {
                foreach ($selectedGroupsarr as $group) {
                    $responseData["data"][0]["Groups"][] = [
                        "Groups" => [
                            "id" => $group,
                        ],
                    ];
                }
            }
             if($responseData['data'][0]['Email']!=''){
                $responseData['data'][0]['Has_Email']=true;
                $validatedData['has_email'] =true;
            }else{
                 $responseData['data'][0]['Has_Email']=false;
                $validatedData['has_email'] =false;
            }
            if($responseData['data'][0]['Mailing_Street']!=''||$responseData['data'][0]['Mailing_City']!=''||$responseData['data'][0]['Mailing_State']!=''||$responseData['data'][0]['Mailing_Zip']!=''){
                $responseData['data'][0]['Has_Address']=true;
                $validatedData['has_address'] =true;
            }else{
                 $responseData['data'][0]['Has_Address']=false;
                $validatedData['has_address'] =false;
            }
            if (empty($responseData["data"][0]["Groups"])) {
                unset($responseData["data"][0]["Groups"]);
            }

            if (empty($responseData['data'][0]['Relationship_Type'])) {
                unset($responseData['data'][0]['Relationship_Type']);
            }
            if (empty($responseData['data'][0]['Referred_By']['id'])) {
                unset($responseData['data'][0]['Referred_By']);
            }
            if (empty($responseData['data'][0]['First_Name'])) {
                unset($responseData['data'][0]['First_Name']);
            }
            if (empty($responseData['data'][0]['Lead_Source_Detail'])) {
                unset($responseData['data'][0]['Lead_Source_Detail']);
            }
            if (empty($responseData['data'][0]['Email'])) {
                unset($responseData['data'][0]['Email']);
            }
            if (empty($responseData['data'][0]['ABCD'])) {
                unset($responseData['data'][0]['ABCD']);
            }
            if (empty($responseData['data'][0]['Last_Emailed'])) {
                unset($responseData['data'][0]['Last_Emailed']);
            }
            if (empty($responseData['data'][0]['Last_Called'])) {
                unset($responseData['data'][0]['Last_Called']);
            }
            if (empty($responseData['data'][0]['Mailing_Zip'])) {
                unset($responseData['data'][0]['Mailing_Zip']);
            }
            if (empty($responseData['data'][0]['Mailing_State'])) {
                unset($responseData['data'][0]['Mailing_State']);
            }
            if (empty($responseData['data'][0]['Mailing_City'])) {
                unset($responseData['data'][0]['Mailing_City']);
            }
            if (empty($responseData['data'][0]['Mailing_Street'])) {
                unset($responseData['data'][0]['Mailing_Street']);
            }
            if (empty($responseData['data'][0]['Business_Info'])) {
                unset($responseData['data'][0]['Business_Info']);
            }
            if (empty($responseData['data'][0]['Business_Name'])) {
                unset($responseData['data'][0]['Business_Name']);
            }
            if (empty($responseData['data'][0]['Phone'])) {
                unset($responseData['data'][0]['Phone']);
            }
            if (empty($responseData['data'][0]['Mobile'])) {
                unset($responseData['data'][0]['Mobile']);
            }
            if (empty($responseData['data'][0]['Secondary_Email'])) {
                unset($responseData['data'][0]['Secondary_Email']);
            }
            if (empty($responseData['data'][0]['Salutation'])) {
                unset($responseData['data'][0]['Salutation']);
            }
            
            if (empty($responseData['data'][0]['Spouse_Partner']['id'])) {
                unset($responseData['data'][0]['Spouse_Partner']);
            }
            
            $contactInstance = Contact::where('id', $id)->first();
            $getContactFromZoho=null;
           
            if (!$contactInstance) {
                return redirect('/contacts');
            }
            if ($contactInstance->zoho_contact_id && $contactInstance->isContactCompleted) {
                $response = $zoho->createContactData($responseData, $contactInstance->zoho_contact_id);
            } else {
                $response = $zoho->createNewContactData($responseData);
                
            }
            if (!$response->successful()) {
               // Store the error message in the session
               return session()->flash('error', 'No permission to edit this record!'.$response);
            }
            $data = json_decode($response, true);
            if (isset($response['data']) && is_array($response['data'])) {
                if(!empty($contactInstance->zoho_contact_id)){
                    $getContactFromZoho = $zoho->getZohoContact($contactInstance->zoho_contact_id);
                }else{
                    $getContactFromZoho = $zoho->getZohoContact($response['data'][0]['details']['id']);
                }
                $zohoContact_Array = json_decode($getContactFromZoho, true);
                $zohoContactValues = $zohoContact_Array['data'][0];
               
                $db->storeContactIntoDB($id,$zohoContactValues);
                if (isset($zohoContactValues['Groups']) && is_array($zohoContactValues['Groups']) && count($zohoContactValues['Groups']) !== 0) {
                    foreach ($zohoContactValues['Groups'] as $group) {
                        
                        if(isset($group['Groups'])){
                            // Find the group instance in the database
                            $groupsInstance = Groups::where('zoho_group_id', $group['Groups']['id'])->first();

                            // Update or create a ContactGroup entry
                            ContactGroups::updateOrCreate(
                                ['zoho_contact_group_id' => $group['id']],
                                [
                                    'ownerId' => $user->id,
                                    'contactId' => $contactInstance['id'] ?? null,
                                    'groupId' => $groupsInstance['id'] ?? null,
                                    'zoho_contact_group_id' => $group['id'] ?? null,
                                ]
                            );
                        }
                    }
                }
            }
            // Redirect back with a success message
            return response()->json(['data' => $contactInstance], 200);
        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function databaseGroup()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        return view('contacts.group');
    }

    protected function retrieveContactsFromZoho($rootUserId, $accessToken)
    {
        $url = 'https://www.zohoapis.com/crm/v2/Contacts/search';
        $params = [
            'page' => 1,
            'per_page' => 200,
            'criteria' => "(Owner:equals:$rootUserId)",
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get($url, $params);

            if ($response->successful()) {
                $responseData = $response->json();
                $contacts = collect($responseData['data'] ?? []);

                // Adjust here for Bootstrap background colors
                $contacts->transform(function ($contact) {
                    $hasEmail = !empty($contact['Email']);
                    $hasPhone = !empty($contact['Phone']) || !empty($contact['Mobile']);
                    $hasAddress = !empty($contact['Mailing_Street']) && !empty($contact['Mailing_City']) && !empty($contact['Mailing_State']) && !empty($contact['Mailing_Zip']);
                    $hasImpDate = isset($contact['HasMissingImportantDate']) && !$contact['HasMissingImportantDate'];

                    $contact['perfect'] = $hasEmail && $hasPhone && $hasAddress && $hasImpDate;

                    // Update for background color
                    $abcdBackgroundClass = ''; // Default to nothing

                    if (isset($contact['ABCD'])) {
                        switch ($contact['ABCD']) {
                            case "A+":
                            case "A":
                                $abcdBackgroundClass = 'bg-success text-white';
                                break;
                            case "B":
                                $abcdBackgroundClass = 'bg-warning text-dark';
                                break;
                            case "C":
                                $abcdBackgroundClass = 'bg-danger text-white';
                                break;
                            case "D":
                                $abcdBackgroundClass = 'bg-secondary text-white';
                                break;
                            default:
                                $abcdBackgroundClass = '';
                        }
                    }

                    $contact['abcdBackgroundClass'] = $abcdBackgroundClass . ' text-center';

                    return $contact;
                });

                return $contacts;
            } else {
                Log::error("Error fetching contacts: {$response->body()}");
                return collect();
            }
        } catch (\Exception $e) {
            Log::error("Exception when fetching contacts: {$e->getMessage()}");
            return collect();
        }
    }

    public function getGroups(Request $request)
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $contactId = request()->route('contactId');
        $db = new DatabaseService();
        $sortField = $request->input('sort');
        $sortType = $request->input('sortType');
        // $contactInfo = Contact::getZohoContactInfo();
        $accessToken = $user->getAccessToken(); // Method to get the access token.
        $contactsGroups = $db->retrieveContactGroupsData($user, $accessToken, $contactId, $filter = null, $sortType, $sortField);
        return response()->json($contactsGroups);

    }

    public function retriveNotesForContact()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $contactId = request()->route('contactId');
        $accessToken = $user->getAccessToken();
        $notesInfo = $db->retrieveNotesForContact($user, $accessToken, $contactId);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
        return view('common.notes.listPopup', compact('notesInfo', 'retrieveModuleData', 'contact'))->render();
    }

    public function createNotesForContact()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $contactId = request()->route('contactId');
        $accessToken = $user->getAccessToken();
        $type = "Contacts";
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
        return view('common.notes.create', compact( 'retrieveModuleData', 'contact',"type"))->render();
    }

        public function createTasksForContact()
    {
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $contactId = request()->route('contactId');
        $accessToken = $user->getAccessToken();
        $type = "Contacts";
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
        return view('common.tasks.create', compact( 'retrieveModuleData', 'contact',"type"))->render();
    }

    

    public function createContactId(Request $request)
    {
        $db = new DatabaseService();
        // $zoho = new ZohoCRM();
        $user = $this->user();

        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $isIncompleteContact = $db->getIncompleteContact($user, $accessToken);
        if ($isIncompleteContact) {
            return response()->json($isIncompleteContact);
        } else {
            // $zoho->access_token = $accessToken;

            // $jsonData = $request->json()->all();
            // $zohoContact = $zoho->createNewContactData($jsonData);
            // if (!$zohoContact->successful()) {
            //     return "error something" . $zohoContact;
            // }
            // $zohoContactArray = json_decode($zohoContact, true);
            // $data = $zohoContactArray['data'][0]['details'];
            $contact = $db->createContact($user, $accessToken, null);
            return response()->json($contact);
        }
    }

    private function retrieveContactDetailsFromZoho($contactId, $accessToken)
    {
        $url = "https://www.zohoapis.com/crm/v2/Contacts/{$contactId}";
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get($url);

            if ($response->successful()) {
                $contactDetails = $response->json()['data'] ?? [];
                // Additional logic here to process and format the contact details as needed
                return $contactDetails[0]; // Assuming the response is an array with a single contact
            } else {
                Log::error("Error fetching contact details: {$response->body()}");
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Exception when fetching contact details: {$e->getMessage()}");
            return null;
        }
    }

    public function createSpouseContact(Request $request, $id)
    {
        $db = new DatabaseService();
        $zoho = new ZohoCRM();
        $user = $this->user();
           // Clear existing session value if it exists
        if (session()->has('spouseContact')) {
            session()->forget('spouseContact');
        }
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();

        $zoho->access_token = $accessToken;

        $jsonData = $request->all();
        $contactId = $id;

        $inputData =
            [
            "data" => [
                [
                    "Relationship_Type" => "Secondary",
                    "Missing_ABCD" => true,
                    "Owner" => [
                        "id" => $user->root_user_id,
                        "full_name" => $user->name,
                    ],
                    "Layout" => [
                        "name" => "Standard",
                        "id" => "5141697000000091033",
                    ],
                    "Last_Name" => $jsonData['last_name'],
                    "First_Name" => $jsonData['first_name'],
                    "Email" => $jsonData['email'],
                    "Phone" => $jsonData['phone'],
                    "Mobile" => $jsonData['mobile'],
                    "zia_suggested_users" => [],
                ],
            ],
        ];
        $zohoContact = $zoho->createNewContactData($inputData);
        if (!$zohoContact->successful()) {
            return "error something" . $zohoContact;
        }
        $zohoContactArray = json_decode($zohoContact, true);
        $data = $zohoContactArray['data'][0]['details'];
        $contact = $db->createSpouseContact($user, $accessToken, $data['id'], $inputData['data'][0]);
        session(['spouseContact' => $contact]);
        // Redirect to the route without passing the contact object
        return response()->json([
            'spouseContact'=>$contact
        ]);
    }

}
