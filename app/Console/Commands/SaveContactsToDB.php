<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Services\ZohoCRM;
use App\Services\DB;

class SaveContactsToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-contacts-to-d-b';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve contacts from Zoho CRM and save them to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    // Retrieve all users
        $users = User::all();

        // Iterate over each user
        foreach ($users as $user) {
            // Retrieve the root user ID for the current user
            $rootUserId = $user->root_user_id;

            // Get user access token
            $accessToken = $user->getAccessToken();

            // Initialize variables
            $allContacts = collect();
            $page = 1;
            $hasMorePages = true;

            // Define criteria and fields
            $criteria = "(Owner:equals:$rootUserId)";
            $fields = 'Contact Owner,Email,First Name,Last Name,Phone,Created_Time,ABCD,Mailing_Address,Mailing_City,Mailing_State,Mailing_Zip';

            Log::info("Retrieving contacts for user {$user->id}");

            // Create ZohoCRM instance and set access token
            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            try {
                // Retrieve contacts in pages
                while ($hasMorePages) {
                    $response = $zoho->getContactData($criteria, $fields, $page, 200);

                    if (!$response->successful()) {
                        Log::error("Error retrieving contacts for user {$user->id}: " . $response->body());
                        // Handle unsuccessful response
                        $hasMorePages = false;
                        break;
                    }

                    // Process successful response
                    $responseData = $response->json();
                    $contacts = collect($responseData['data'] ?? []);
                    $allContacts = $allContacts->concat($contacts);

                    // Check for more pages
                    $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                    $page++;
                }
            } catch (\Exception $e) {
                // Log any errors
                Log::error("Error retrieving contacts for user {$user->id}: " . $e->getMessage());
            }

            // Log the number of contacts retrieved for the user
            Log::info("Retrieved contacts for user {$user->id}: " . $allContacts->count());

            // Store contacts in the database
            $saveInDB = new DB();
            $saveInDB->storeContactsIntoDB($allContacts);
        }
    }
}
