<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Services\Helper;
use App\Services\ZohoCRM;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $contacts = $this->retrieveContactsFromZoho($user->root_user_id, $accessToken);

        return view('contacts.index', compact('contacts'));
    }

    public function createContact(Request $request){
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $relatedToObject = json_decode($request->contactOwner);
        $validatedData1 = validator()->make((array) $relatedToObject, [
            'id' => 'required|string|max:255',
            'Full_Name' => 'required|string|max:255',
        ])->validate();

        $validatedData2 = $request->validate([
            'last_name' => 'required|string|max:255',
        ]);
          $last_name =  $validatedData2['last_name'];
        $responseData = [
            "data" => [
              [
                "Relationship_Type"=> "Primary",
                "Missing_ABCD"=> true,
                "Owner" => [
                  "id"=> $validatedData1['id'],
                  "full_name"=> $validatedData1['Full_Name'],
                ],
                "Unsubscribe_From_Reviews"=> false,
                "Currency"=> "USD",
                "Market_Area"=> "-None-",
                "Lead_Source"=> "-None-",
                "ABCD"=> "-None-",
                "Last_Name"=> $last_name,
                // "Layout"=> [
                //   "id"=> "5141697000000091033"
                // ],
                // "$zia_owner_assignment"=> "owner_recommendation_unavailable",
                "zia_suggested_users"=> []
              ]
            ],
            "skip_mandatory"=> true
        ];
        $helper = new Helper();
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        
        try {
            $response = $zoho->createContactData($responseData);

            if (!$response->successful()) {
                Log::error("Error creating contacts:");
                return "error somthing".$response;
            }
            $data = json_decode($response, true);
            $contact = new Contact();
            if (isset($response['data'][0])) {
                $data = $response['data'][0];
                $id = $data['details']['id'];
                $createdByName = $data['details']['Created_By']['name'];
                $createdById = $data['details']['Created_By']['id'];
                $contact->created_time = isset($data['details']['Created_Time']) ? $helper->convertToUTC($data['details']['Created_Time']) : null;
                $contact->contact_owner = $user->id;
                $contact->zoho_contact_id = $id;
                $contact->last_name = $last_name;
                $contact->save();
            }
            // Redirect back with a success message
            return redirect()->back()->with('success', 'Contact saved successfully!');
         } catch (\Exception $e) {
             Log::error("Error creating notes:new " . $e->getMessage());
             return redirect()->back()->with('error', 'Contact Not saved successfully!'.$e->getMessage());
                return "somthing went wrong".$e->getMessage();
            }
       
        }

    public function databaseGroup(){
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        return view('contacts.group');
    }

    private function retrieveContactsFromZoho($rootUserId, $accessToken)
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

    public function show($contactId)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken(); // Method to get the access token.
        $contactDetails = $this->retrieveContactDetailsFromZoho($contactId, $accessToken);

        return view('contacts.detail', compact('contactDetails'));
    }

    public function showCreateContactForm()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken(); // Method to get the access token.
        $contactDetails = $this->retrieveContactDetailsFromZoho("5141697000013347001", $accessToken);

        return view('contacts.create', compact('contactDetails'));
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

}
