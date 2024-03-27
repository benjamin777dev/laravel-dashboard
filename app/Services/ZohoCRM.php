<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ZohoCRM
{
    private $apiUrl = 'https://www.zohoapis.com/crm/v6/';
    private $authUrl = 'https://accounts.zoho.com/oauth/v2/';
    private $client_id;
    private $client_secret;
    public $redirect_uri;
    public $access_token;
    public $refresh_token;
    public function __construct()
    {
        Log::info('Initializing Zoho CRM');

        $this->client_id = config('services.zoho.client_id');
        $this->client_secret = config('services.zoho.client_secret');
        $this->redirect_uri = route('auth.callback');

        Log::info('Zoho CRM initialized');
    }

    // rediect to Zoho for authentication
    public function redirectToZoho()
    {
        Log::info('Redirecting to Zoho for authentication');

        $query = http_build_query([
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
            'scope' => 'ZohoProjects.projects.ALL,ZohoCRM.modules.ALL,ZohoCRM.users.ALL,ZohoCRM.settings.ALL,ZohoCRM.org.ALL,ZohoCRM.bulk.READ,ZohoCRM.notifications.READ,ZohoCRM.notifications.CREATE,ZohoCRM.notifications.UPDATE,ZohoCRM.notifications.DELETE,ZohoCRM.coql.READ',
            'prompt' => 'consent',
            'access_type' => 'offline',
        ]);
        Log::info(print_r($query, true));
        Log::info('Zoho authentication URL: ' . $this->authUrl . 'auth?' . $query);

        return redirect($this->authUrl . 'auth?' . $query);
    }

    // handle the callback from Zoho
    public function handleZohoCallback($request)
    {
        Log::info('Handling Zoho callback');
        $headers = [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.zoho.client_id'),
            'client_secret' => config('services.zoho.client_secret'),
            'redirect_uri' => route('auth.callback'),
            'code' => $request->code,
        ];

        Log::info('Zoho callback headers: ' . print_r($headers, true));

        $response = Http::asForm()->post($this->authUrl . 'token', $headers);
        Log::info('Zoho callback response: ' . print_r($response, true));

        return $response;
    }

    // refresh the access token
    public function refreshAccessToken()
    {
        Log::info('Refreshing Zoho access token');

        $headers = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'refresh_token' => $this->refresh_token,
        ];

        Log::info('Zoho refresh token headers: ' . print_r($headers, true));

        $response = Http::asForm()->post($this->authUrl . 'token', $headers);
        Log::info('Zoho refresh token response: ' . print_r($response, true));

        // if refresh token fails, redirect to Zoho for authentication
        if (!$response->successful()) {
            Log::error('Failed to refresh Zoho access token', ['response' => $response->body()]);
            return $this->redirectToZoho();
        }

        return $response;
    }

    // get the access token
    public function getAccessToken()
    {
        Log::info('Getting Zoho access token');

        if (empty($this->access_token)) {
            Log::info('Access token is empty, refreshing');
            $response = $this->refreshAccessToken();
            $tokenData = $response->json();
            Log::info('Token data: ' . print_r($tokenData, true));
            $this->access_token = $tokenData['access_token'];
            $this->refresh_token = $tokenData['refresh_token'];
        } else {
            Log::info('Access token is not empty');
            // check if the token is expired
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            ])->get($this->apiUrl . 'settings/modules');
            if ($response->status() == 401) {
                Log::info('Access token is expired, refreshing');
                $response = $this->refreshAccessToken();
                $tokenData = $response->json();
                Log::info('Token data: ' . print_r($tokenData, true));
                $this->access_token = $tokenData['access_token'];
                $this->refresh_token = $tokenData['refresh_token'];
            }
        }

        return $this->access_token;
    }

    // get the user data
    public function getUserData()
    {
        Log::info('Getting Zoho user data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken(),
        ])->get($this->apiUrl . 'users?type=CurrentUser');

        Log::info('Zoho user data response: ' . print_r($response, true));

        return $response;
    }

    // get contact data from search
    public function getContactData($search, $fields = 'Contact Owner,Email,First Name,Last Name,Phone', $page = 1, $per_page = 1)
    {
        Log::info('Getting Zoho contact data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken(),
        ])->get($this->apiUrl . 'Contacts/search', [
            'page' => $page,
            'per_page' => $per_page,
            'criteria' => $search,
            'fields' => $fields,
        ]);

        Log::info('Zoho contact data response: ' . print_r($response, true));

        return $response;
    }

    // get deals data from search
    public function getDealsData($search, $fields = 'Deal Name,Deal Owner,Amount,Stage', $page = 1, $per_page = 200)
    {
        Log::info('Getting Zoho deals data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken(),
        ])->get($this->apiUrl . 'Deals/search', [
            'page' => $page,
            'per_page' => $per_page,
            'criteria' => $search,
            'fields' => $fields,
        ]);

        Log::info('Zoho deals data response: ' . print_r($response, true));

        return $response;
    }

    // get tasks data from search
    public function getTasksData($search, $fields = 'Subject,Task Owner,Status,Due Date', $page = 1, $per_page = 200)
    {
        Log::info('Getting Zoho tasks data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken(),
        ])->get($this->apiUrl . 'Tasks/search', [
            'page' => $page,
            'per_page' => $per_page,
            'criteria' => $search,
            'fields' => $fields,
        ]);

        Log::info('Zoho tasks data response: ' . print_r($response, true));

        return $response;
    }

    // get Agent_Commission_Incomes data from Search
    public function getACIData($search, $fields = 'Deal Name,Deal Owner,Amount,Stage', $page = 1, $per_page = 200)
    {
        Log::info('Getting Zoho Agent_Commission_Incomes data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken(),
        ])->get($this->apiUrl . 'Agent_Commission_Incomes/search', [
            'page' => $page,
            'per_page' => $per_page,
            'criteria' => $search,
            'fields' => $fields,
        ]);

        Log::info('Zoho Agent_Commission_Incomes data response: ' . print_r($response, true));

        return $response;
    }

}
