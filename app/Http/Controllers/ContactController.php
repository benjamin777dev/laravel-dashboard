<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

            // Calculate if a contact is perfect
            $contacts->transform(function ($contact) {
                $hasEmail = !empty($contact['Email']);
                $hasPhone = !empty($contact['Phone']) || !empty($contact['Mobile']);
                $hasAddress = !empty($contact['Mailing_Street']) && !empty($contact['Mailing_City']) && !empty($contact['Mailing_State']) && !empty($contact['Mailing_Zip']);
                $hasImpDate = isset($contact['HasMissingImportantDate']) && !$contact['HasMissingImportantDate']; // Assuming 'HasMissingImportantDate' is true when an important date is missing

                // Mark as perfect if all conditions are met
                $contact['perfect'] = $hasEmail && $hasPhone && $hasAddress && $hasImpDate;

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
}
