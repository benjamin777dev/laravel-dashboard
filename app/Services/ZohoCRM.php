<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class ZohoCRM
{
    private $apiUrl = 'https://www.zohoapis.com/crm/v6/';
    private $contentURL = 'https://content.zohoapis.com/crm/v3/';
    private $apiNoteUrl = 'https://www.zohoapis.com/crm/v5/';
    private $authUrl = 'https://accounts.zoho.com/oauth/v2/';
    private $bulkUrl = 'https://crm.zoho.com/crm/v6/';
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
        $this->serverUrl = env('APP_URL');

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
            'scope' => 'ZohoProjects.projects.ALL,ZohoCRM.composite_requests.CUSTOM,ZohoCRM.modules.ALL,ZohoCRM.bulk.backup.ALL,ZohoCRM.users.ALL,ZohoCRM.settings.ALL,ZohoCRM.org.ALL,ZohoCRM.bulk.READ,ZohoCRM.notifications.READ,ZohoCRM.notifications.CREATE,ZohoCRM.notifications.UPDATE,ZohoCRM.notifications.DELETE,ZohoCRM.modules.notes.ALL,ZohoCRM.modules.Leads.ALL,ZohoCRM.coql.READ,ZohoFiles.files.ALL,ZohoCRM.bulk.ALL,ZohoCRM.mass_delete.custom.DELETE',
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
        //Log::info('Zoho callback response: ' . print_r($response, true));

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

        try {
            $response = Http::asForm()->post($this->authUrl . 'token', $headers);

            // Log the full Zoho refresh token response
            Log::info('Zoho refresh token response:', [
                'response_body' => $response->body(),
                'successful' => $response->successful()
            ]);

            // Check if the response is successful
            if (!$response->successful() || isset($response->json()['error'])) {
                // Log the error with detailed information
                Log::error('Failed to refresh Zoho access token', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);

                // Handle specific response codes if necessary
                if ($response->status() === 401 || $response->json()['error'] === 'invalid_code') {
                    Log::error('Unauthorized: Invalid refresh token or client credentials.');
                } elseif ($response->status() === 400) {
                    Log::error('Bad Request: Possibly incorrect request parameters.');
                } else {
                    Log::error('An unexpected error occurred.');
                }

                // Redirect to Zoho for authentication if the refresh token fails
                return $this->redirectToZoho();
            }

            return $response;
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Exception occurred while refreshing Zoho access token', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Redirect to Zoho for authentication if an exception occurs
            return $this->redirectToZoho();
        }
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
            Log::info('Access token is not empty' . $this->access_token);
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
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->get($this->apiUrl . 'users?type=CurrentUser');

        //Log::info('Zoho user data response: ' . print_r($response, true));

        return $response;
    }

    // get contact data from search
    public function getContactData($search, $fields = 'Contact Owner,Email,First Name,Last Name,Phone', $page = 1, $per_page = 1)
    {
        Log::info('Getting Zoho contact data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->get($this->apiUrl . 'Contacts/search', [
                    'page' => $page,
                    'per_page' => $per_page,
                    'criteria' => $search,
                ]);

        //Log::info('Zoho contact data response: ' . print_r($response, true));
        return $response;
    }

    //create contacts to zoho 
    public function createContactData($inputJson, $id)
    {
        try {
            Log::info('Creating Zoho contacts',[$inputJson]);

            // Trigger workflows
            $inputJson['trigger'] = 'workflow';
            // Adjust the URL and HTTP method based on your Zoho API requirements
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->patch($this->bulkUrl . "Contacts/$id?affected_data=true", $inputJson);

            $responseData = $response->json();

            // Check if the request was successful
            if (!$response->successful()) {
                Log::error('Zoho contacts creation failed: ' . print_r($responseData, true));
                throw new \Exception('Failed to create Zoho contacts');
            }

            Log::info('Zoho contacts creation response: ' . print_r($responseData, true));

            return $response;
        } catch (\Throwable $th) {
            Log::error('Error creating Zoho contacts: ' . $th->getMessage());
            throw new \Exception('Failed to create Zoho contacts');
        }
    }


    public function createNewContactData($inputJson)
    {
        try {
            Log::info('Creating Zoho contacts');

            // Trigger workflows
            $inputJson['trigger'] = 'workflow';

            // Adjust the URL and HTTP method based on your Zoho API requirements
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->post($this->bulkUrl . "Contacts", $inputJson);

            $responseData = $response->json();

            // Check if the request was successful
            if (!$response->successful()) {
                Log::error('Zoho contacts creation failed: ' . print_r($responseData, true));
                throw new \Exception('Failed to create Zoho contacts');
            }

            Log::info('Zoho contacts creation response: ' . print_r($responseData, true));

            return $response;
        } catch (\Throwable $th) {
            Log::error('Error creating Zoho contacts: ' . $th->getMessage());
            throw new \Exception('Failed to create Zoho contacts');
        }
    }



    // get deals data from search
    public function getDealsData($search, $fields = 'Deal Name,Deal Owner,Amount,Stage', $page = 1, $per_page = 200)
    {
        Log::info('Getting Zoho deals data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->get($this->apiUrl . 'Deals/search', [
                    'page' => $page,
                    'per_page' => $per_page,
                    'criteria' => $search,
                ]);

        Log::info('Zoho deals data response: ' . print_r($response, true));

        return $response;
    }

    public function getModuleData()
    {

        Log::info('Getting Zoho Module data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->get($this->apiUrl . 'settings/modules');

        Log::info('Zoho Module data response: ' . print_r($response, true));
        return $response;
    }

    // get tasks data from search
    public function getTasksData($search, $fields = 'Subject,Task Owner,Status,Due Date', $page = 1, $per_page = 200)
    {
        Log::info('Getting Zoho tasks data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->get($this->apiUrl . 'Tasks/search', [
                    'page' => $page,
                    'per_page' => $per_page,
                    'criteria' => $search,
                ]);

        //Log::info('Zoho tasks data response: ' . print_r($response, true));

        return $response;
    }

    // get Agent_Commission_Incomes data from Search
    public function getACIData($search, $fields = 'Deal Name,Deal Owner,Amount,Stage', $page = 1, $per_page = 200)
    {
        Log::info('Getting Zoho Agent_Commission_Incomes data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->get($this->apiUrl . 'Agent_Commission_Incomes/search', [
                    'page' => $page,
                    'per_page' => $per_page,
                    'criteria' => $search,
                ]);

        //Log::info('Zoho Agent_Commission_Incomes data response: ' . print_r($response, true));

        return $response;
    }

    public function getNotesData($search, $fields, $page = 1, $per_page = 200)
    {
        Log::info('Getting Zoho notes data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->get($this->apiUrl . 'Notes/search', [
                    'page' => $page,
                    'per_page' => $per_page,
                    'criteria' => $search,
                    'fields' => $fields,
                ]);

        //Log::info('Zoho notes data response: ' . print_r($response, true));
        return $response;
    }

    public function createNoteData($inputJson, $id, $apiName)
    {
        Log::info('Creating Zoho Notes');
        // trigger workflows
        $inputJson['trigger'] = 'workflow';
        // Adjust the URL and HTTP method based on your Zoho API requirements
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->post($this->apiNoteUrl . "Notes", $inputJson);

        Log::info('Zoho Notes creation response: ' . print_r($response->json(), true));
        return $response;
    }

    public function updateNoteData($inputJson, $id)
    {
        Log::info('Creating Zoho Task');
        // trigger workflows
        $inputJson['trigger'] = 'workflow';
        // return $inputJson;
        try {        // Adjust the URL and HTTP method based on your Zoho API requirements
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->put($this->apiNoteUrl . "Notes/" . $id, $inputJson);
        } catch (\Exception $e) {
            return "somthing went wrong" . $e->getMessage();
        }
        //Log::info('Zoho Task creation response: ' . print_r($response->json(), true));
        return $response;
    }

    public function getDealTransactionData($page = 1, $per_page = 200)
    {
        Log::info('Getting Zoho TransactionData data');
        $fields = "approval,Contact_Name,Deal_Name,Address,Owner,TM_Name,Closing_Date";
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->post($this->bulkUrl . "Deals/bulk?fields=$fields");
        //Log::info('Zoho getDealTransactionData data response: ' . print_r($response, true));
        return $response;
    }

    public function createTask($inputJson)
    {
        try {
            Log::info('Creating Zoho Task');

            // Trigger workflows
            $inputJson['trigger'] = 'workflow';

            // Adjust the URL and HTTP method based on your Zoho API requirements
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . "Tasks", $inputJson);

            $responseData = $response->json();

            // Check if the request was successful
            if (!$response->successful()) {
                Log::error('Zoho Task creation failed: ' . print_r($responseData, true));
                throw new \Exception($response['data'][0]['message']);
            }

            Log::info('Zoho Task creation response: ' . print_r($responseData, true));

            return $response;
        } catch (\Throwable $th) {
            Log::error('Error creating Zoho Task: ' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }

    public function updateTask($inputJson, $id)
    {
        // trigger workflows
        $inputJson['trigger'] = 'workflow';
        // return $inputJson;
        try {        // Adjust the URL and HTTP method based on your Zoho API requirements
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->put($this->apiUrl . "Tasks/" . $id, $inputJson);
            Log::info('Zoho Task updation response: ' . print_r($response->json(), true));
            return $response;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
        
    }

    public function deleteTask($inputJson, $id)
    {
        Log::info('Creating Zoho Task');
        // trigger workflows
        $inputJson['trigger'] = 'workflow';
        // Adjust the URL and HTTP method based on your Zoho API requirements
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->delete($this->apiUrl . "Tasks/" . $id);

        //Log::info('Zoho Task creation response: ' . print_r($response->json(), true));
        return $response;
    }

    public function deleteNote($id)
    {
        Log::info('Creating Zoho Task');
        // trigger workflows
        $inputJson['trigger'] = 'workflow';
        // Adjust the URL and HTTP method based on your Zoho API requirements
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->delete($this->apiUrl . "Notes/" . $id);

        //Log::info('Zoho Task creation response: ' . print_r($response->json(), true));
        return $response;
    }

    public function deleteTaskSelected($inputJson, $ids)
    {
        Log::info('Creating Zoho Task');
        // trigger workflows
        $inputJson['trigger'] = 'workflow';
        // Adjust the URL and HTTP method based on your Zoho API requirements
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->delete($this->apiUrl . "Tasks?ids=" . $ids);

        //Log::info('Zoho Task creation response: ' . print_r($response->json(), true));
        return $response;
    }


    public function getDealContact($dealId)
    {
        Log::info('Getting Zoho Deal contact data');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
        ])->get($this->apiUrl . "Deals/$dealId/Contact_Roles", [
                    'fields' => 'Email,Department,First_Name,Last_Name'
                ]);

        Log::info('Zoho Deal contact data response: ' . print_r($response, true));
        return $response;
    }

    public function createZohoDeal($inputJson)
    {
        Log::info('Creating Zoho Deal');
        // trigger workflows
        $inputJson['trigger'] = 'workflow';
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . 'Deals', $inputJson);

        //Log::info('Zoho deals data response: ' . print_r($response, true));

        return $response;
    }

    public function updateZohoDeal($inputJson, $id)
    {
        try {
            Log::info('Creating Zoho Deal', $inputJson);

            // trigger workflows
            $inputJson['trigger'] = 'workflow';
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->put($this->apiUrl . 'Deals/' . $id, $inputJson);

            Log::info('Zoho deals data response: ' . print_r($response->body(), true));

            return $response;
        } catch (RequestException $exception) {
            // Log the exception
            Log::error('Error updating Zoho Deal: ' . $exception->getMessage());

            // Return a default error response or rethrow the exception
            throw $exception;
        }
    }

    public function getZohoDeal($id)
    {
        Log::info('Creating Zoho Deal'.$id);

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->get($this->apiUrl . 'Deals/' . $id);

        Log::info('Zoho deals data response: ' . print_r($response->body(), true));

        return $response;
    }

    public function getContactGroupData($criteria, $fields, $page = 1, $per_page = 1)
    {
        Log::info('Creating Contact Zoho Deal');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->get($this->apiUrl . 'Contacts_X_Groups/search', [
                    'page' => $page,
                    'per_page' => $per_page,
                    'criteria' => $criteria,
                ]);

        Log::info('Response Zoho Contact Group');
        return $response;
    }

    public function getGroupsData($criteria, $fields, $page = 1, $per_page = 1)
    {
        Log::info('Creating Group Zoho ');

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->get($this->apiUrl . 'Groups', [
                    'page' => $page,
                    'per_page' => $per_page,
                    'criteria' => $criteria,
                    'fields'=>$fields
                ]);

        Log::info('Response Zoho Group');
        return $response;
    }

    public function compositeApi($user, $page)
    {
        try {
            Log::info('Creating Composite API Zoho ');

            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            ])->post($this->apiUrl . '__composite_requests', [
                        "rollback_on_fail" => true,
                        "parallel_execution" => false,
                        "__composite_requests" => [
                            [
                                "sub_request_id" => "Contacts",
                                "method" => "GET",
                                "params" => [
                                    'page' => $page,
                                    'per_page' => 200,
                                    "criteria" => "(Owner:equals:$user->root_user_id)"
                                ],
                                "uri" => "/crm/v6/Contacts/search",
                            ],
                            [
                                "sub_request_id" => "ContactGroups",
                                "method" => "GET",
                                "params" => [
                                    'page' => $page,
                                    'per_page' => 200,
                                    "criteria" => "(Owner:equals:$user->root_user_id)"
                                ],
                                "uri" => "/crm/v6/Contacts_X_Groups/search",
                            ],
                            [
                                "sub_request_id" => "Deals",
                                "method" => "GET",
                                "params" => [
                                    'page' => $page,
                                    'per_page' => 200,
                                    "criteria" => "(Contact_Name:equals:$user->zoho_id)"
                                ],
                                "uri" => "/crm/v6/Deals/search",
                            ],
                            [
                                "sub_request_id" => "Tasks",
                                "method" => "GET",
                                "params" => [
                                    'page' => $page,
                                    'per_page' => 200,
                                    "criteria" => "(Owner:equals:$user->root_user_id)"
                                ],
                                "uri" => "/crm/v6/Tasks/search",
                            ],
                            [
                                "sub_request_id" => "Notes",
                                "method" => "GET",
                                "params" => [
                                    'page' => $page,
                                    'per_page' => 200,
                                    "criteria" => "(Owner:equals:$user->root_user_id)",
                                    'fields' => "Note_Content,Created_Time,Owner,Parent_Id",
                                ],
                                "uri" => "/crm/v6/Notes/search",
                            ],
                        ]
                    ]);

            Log::info('Response Zoho Group');
            Log::info(response()->json($response->json())); // Log the response data for debugging

            return $response;
        } catch (\Throwable $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
        }

    }

    public function getAttachmentData($dealId)
    {
        try {
            Log::info('Creating Attachment Zoho ');

            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            ])->post($this->apiUrl . "Deals/bulk?relatedId=$dealId&relationId=5141697000000003819&fields=Owner,Modified_Time,Size,File_Name,Parent_Id,Record_Status__s");

            Log::info('Response Zoho Attachments');
            Log::info(response()->json($response->json())); // Log the response data for debugging

            return $response;
        } catch (\Throwable $e) {
            Log::error("Error retrieving Attachments: " . $e->getMessage());
        }

    }

    public function getNonTmData($dealId)
    {
        try {
            Log::info('Creating nonTm Zoho ');

            $nonTm = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            ])->post($this->apiUrl . "Deals/bulk?relatedId=$dealId&relationId=5141697000005296186&fields=Name,Close_Date,Owner");

            Log::info('Response Zoho nonTm');
            Log::info(response()->json($nonTm->json())); // Log the response data for debugging

            return $nonTm;
        } catch (\Throwable $e) {
            Log::error("Error retrieving nonTm: " . $e->getMessage());
        }

    }

    public function getSubmittalsData($criteria, $fields, $page = 1, $per_page = 200)
    {
        try {
            Log::info('Creating Submittals Zoho ');

            $submittals = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            ])->post($this->apiUrl . "Listing_Submittals/bulk?page=$page&fields=$fields&per_page=$per_page&criteria=$criteria", [
                        'page' => $page,
                        'per_page' => $per_page,
                        'criteria' => $criteria,
                        'fields' => $fields
                    ]);

            Log::info('Response Zoho submittals');
            Log::info(response()->json($submittals->json())); // Log the response data for debugging

            return $submittals;
        } catch (\Throwable $e) {
            Log::error("Error retrieving submittals: " . $e->getMessage());
        }

    }

    public function getAllStages($criteria, $fields, $page = 1, $per_page = 200)
    {
        try {
            Log::info('Creating stages Zoho ');

            $stages = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
            ])->get($this->apiUrl . "Stages?page=$page&fields=$fields&per_page=$per_page&criteria=$criteria", [
                        'page' => $page,
                        'per_page' => $per_page,
                        'criteria' => $criteria,
                    ]);

            Log::info('Response Zoho stages');
            Log::info(response()->json($stages->json())); // Log the response data for debugging

            return $stages;
        } catch (\Throwable $e) {
            Log::error("Error retrieving stages: " . $e->getMessage());
        }

    }
    public function updateContactGroup($inputJSON)
    {
        try {
            Log::info('Creating Contact Zoho Deal: ' . json_encode($inputJSON));
            // trigger workflows
            $inputJson['trigger'] = 'workflow';
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . 'Contacts_X_Groups', $inputJSON);
            return $response;
        } catch (\Throwable $e) {
            Log::error("Error retrieving Update Group: " . $e->getMessage());
        }

    }

    public function deleteContactGroup($id)
    {
        try {
            Log::info('Deleting Contact Zoho Deal: ' . json_encode($id));
            // trigger workflows
            $inputJson['trigger'] = 'workflow';
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->delete($this->apiUrl . 'Contacts_X_Groups' . $id);
            return $response;
        } catch (\Throwable $e) {
            Log::error("Error retrieving Update Group: " . $e->getMessage());
        }

    }

    public function uploadZipFile($zipFilepath)
    {
        try {
            Log::info('Upload Zip file to ZOHO');
            $getOrgIdResponse = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->get($this->apiUrl . 'org');

            $orgId = $getOrgIdResponse->json()['org'][0]['zgid'];
            
            $headers = [
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'X-CRM-ORG' => $orgId,
                'feature' => 'bulk-write',
            ];
            
            // Upload zip file
            $response = Http::withHeaders($headers)
                ->attach('file', file_get_contents($zipFilepath), basename($zipFilepath))
                ->post($this->contentURL . 'upload');
            
            // Log response
            Log::info('Response after uploading ZIP file to Zoho', ['response' => $response->json()]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error("Error uploading ZIP file to Zoho: " . $e->getMessage());
            throw $e;
        }
    }

    public function bulkWriteJob($fileId)
    {
        try {
            Log::info('Bulk Write Job In ZOHO');
            // Define the JSON input for the bulk write job
            $inputJSON = [
                "operation" => "insert",
                "ignore_empty" => true,
                "callback"=> [
                    "url"=> $this->serverUrl."/bulkJob/update",
                    "method"=> "post"
                ],
                "resource" => [
                    [
                        "type" => "data",
                        "module" => [ 
                            "api_name" => "Contacts_X_Groups" 
                        ],   
                        "file_id" => $fileId,
                        "field_mappings" => [
                            [
                                "api_name" => "Contacts",
                                "find_by" => "id",
                                "parent_column_index"=>0,
                                "index" => 0
                            ],
                            [
                                "api_name" => "Groups",
                                "find_by" => "id",
                                "parent_column_index"=>0,
                                "index" => 1
                            ]
                        ]
                    ]
                ]
            ];

            // Send a POST request to Zoho API
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->post('https://www.zohoapis.com/crm/bulk/v3/write', $inputJSON);
            
            // Log response
            Log::info('Response after Bulk Write Job In ZOHO', ['response' => $response->json()]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error("Error executing Bulk Write Job in Zoho: " . $e->getMessage());
            throw $e;
        }
    }

    public function getJobDetail($id)
    {
        try {
            Log::info('Get Job Details' . json_encode($id));
            // trigger workflows
            $inputJson['trigger'] = 'workflow';
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->get('https://www.zohoapis.com/crm/bulk/v3/write/' . $id);
            return $response;
        } catch (\Throwable $e) {
            Log::error("Error retrieving Job Details: " . $e->getMessage());
        }

    }

    public function bulkWriteJobToRemove($fileId)
    {
        try {
            Log::info('Bulk Write Job In ZOHO');
            // Define the JSON input for the bulk write job
            $inputJSON = [
                "ids" => $fileId
            ];
            Log::info('IDS', ['inputJSON' => $inputJSON]);
            // Send a POST request to Zoho API
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->post('https://www.zohoapis.com/crm/v2/Contacts_X_Groups/actions/mass_delete', $inputJSON);
            
            // Log response
            Log::info('Response after Bulk Write Job In ZOHO', ['response' => $response->json()]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error("Error executing Bulk Write Job in Zoho: " . $e->getMessage());
            throw $e;
        }
    }

    
    public function storeDealContactIntoZOHO($dealContacts, $dealId)
    {
        try {
            Log::info('Store Deal Contact In ZOHO');
            // Define the JSON input for the bulk write job
            $inputJSON = [
                "ids" => $fileId
            ];
            Log::info('IDS', ['inputJSON' => $inputJSON]);
            // Send a POST request to Zoho API
           $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                ])->put($this->apiUrl . "Deals/$dealId/Contact_Roles", [
                'fields' => 'Email,Department,First_Name,Last_Name'
            ]);
            
            // Log response
            Log::info('Response after Bulk Write Job In ZOHO', ['response' => $response->json()]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error("Error executing Bulk Write Job in Zoho: " . $e->getMessage());
            throw $e;
        }
    }

    public function getContactRoles()
    {
        try {
           $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                ])->get($this->apiUrl . "Contacts/roles", [
            ]);
            
            // Log response
            Log::info('Contact Roles', ['response' => $response->json()]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error("Error executing Bulk Write Job in Zoho: " . $e->getMessage());
            throw $e;
        }
    }

    public function addContactRoleForDeal($dealId, $inputData)
    {
        Log::info('Getting Zoho Deal contact data' . print_r($inputData, true));

        $arrayResponse = [];
        foreach ($inputData['data'] as $input) {
            $contactId = $input['contactId'];
            $role = $input['role'];
            $url = $this->apiUrl . "Deals/$dealId/Contact_Roles/$contactId";
            $formData = [
                "data" => [
                    [
                        "Contact_Role" => [
                            "name"=>$role
                        ]
                    ]
                ],
            ];
            $jsonObject = json_encode($formData);
            Log::info('Making Zoho API request', [
                'url' => $url,
                'formData' => json_decode($jsonObject)
            ]);
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->put($url, json_decode($jsonObject));
            $responseData = $response->json();
            $arrayResponse[] = $responseData['data']['0']['details'];

            // Check if the response is successful
            if (!$response->successful()) {
                Log::error('Zoho API error: ' . $response->body());
                // Optionally, you can throw an exception here to stop the execution
                throw new \Exception('Zoho API error: ' . $response->body());
            }
        }

        Log::info('Zoho Deal contact data responses: ' . json_encode($arrayResponse, true));
        return $arrayResponse;
    }

    public function removeContactRoleForDeal($inputData)
    {
        Log::info('Getting Zoho Deal contact data' . print_r($inputData, true));

        $arrayResponse = [];
            $contactId = $inputData['zohocontactId'];
            $dealId = $inputData['dealId'];
            $url = $this->apiUrl . "Deals/$dealId/Contact_Roles/$contactId";
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->delete($url);
            $responseData = $response->json();
            $arrayResponse[] = $responseData['data']['0']['details'];

            // Check if the response is successful
            if (!$response->successful()) {
                Log::error('Zoho API error: ' . $response->body());
                // Optionally, you can throw an exception here to stop the execution
                throw new \Exception('Zoho API error: ' . $response->body());
            }

        Log::info('Zoho Deal contact data responses: ' . json_encode($arrayResponse, true));
        return $arrayResponse;
    }

    public function createAciData($inputJson)
    {
        try {
            Log::info('Creating Zoho contacts',[$inputJson]);

            // Trigger workflows
            $inputJson['trigger'] = 'workflow';
            // Adjust the URL and HTTP method based on your Zoho API requirements
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
                'Content-Type' => 'application/json',
            ])->patch($this->apiUrl . "Agent_Commission_Incomes/?affected_data=true", $inputJson);

            $responseData = $response->json();

            // Check if the request was successful
            if (!$response->successful()) {
                Log::error('Zoho contacts creation failed: ' . print_r($responseData, true));
                throw new \Exception('Failed to create Zoho contacts');
            }

            Log::info('Zoho contacts creation response: ' . print_r($responseData, true));

            return $response;
        } catch (\Throwable $th) {
            Log::error('Error creating Zoho contacts: ' . $th->getMessage());
            throw new \Exception('Failed to create Zoho contacts');
        }
    }

    public function createListingSubmittal($inputJson)
    {
    try {
    Log::info('Creating Zoho contacts',[$inputJson]);
    
    // Trigger workflows
    $inputJson['trigger'] = 'workflow';
    // Adjust the URL and HTTP method based on your Zoho API requirements
    $response = Http::withHeaders([
    'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
    'Content-Type' => 'application/json',
    ])->post($this->apiUrl . "Listing_Submittals", $inputJson);
    
    $responseData = $response->json();
    
    // Check if the request was successful
    if (!$response->successful()) {
    Log::error('Zoho contacts creation failed: ' . print_r($responseData, true));
    throw new \Exception('Failed to create Zoho contacts');
    }
    
    Log::info('Zoho contacts creation response: ' . print_r($responseData, true));
    
    return $response;
    } catch (\Throwable $th) {
    Log::error('Error creating Zoho contacts: ' . $th->getMessage());
    throw new \Exception('Failed to create Zoho contacts');
    }
    }

    public function updateListingSubmittal($submittalId,$inputJson)
    {
    try {
    Log::info('Creating Zoho contacts',[$inputJson]);
    
    // Trigger workflows
    $inputJson['trigger'] = 'workflow';
    // Adjust the URL and HTTP method based on your Zoho API requirements
    $response = Http::withHeaders([
    'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
    'Content-Type' => 'application/json',
    ])->PATCH($this->apiUrl . "Listing_Submittals/" . $submittalId . "?affected_data=true", $inputJson);

    
    $responseData = $response->json();
    
    // Check if the request was successful
    if (!$response->successful()) {
    Log::error('Zoho contacts creation failed: ' . print_r($responseData, true));
    throw new \Exception('Failed to create Zoho contacts');
    }
    
    Log::info('Zoho contacts creation response: ' . print_r($responseData, true));
    
    return $response;
    } catch (\Throwable $th) {
    Log::error('Error creating Zoho contacts: ' . $th->getMessage());
    throw new \Exception('Failed to create Zoho contacts');
    }
    }




}