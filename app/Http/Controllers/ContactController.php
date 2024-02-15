<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
}
