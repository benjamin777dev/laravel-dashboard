<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Services\ZohoCRM;
use App\Services\DatabaseService;

class SaveSubmittalsInDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-submittals-in-d-b';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
             // Retrieve the root user ID for the current user
            $rootUserId = $user->root_user_id;

            // Get user access token
            $accessToken = $user->getAccessToken();
            $allSubmittals = collect();
            $page = 1;
            $hasMorePages = true;
            $error = '';

            $criteria = "(Owner:equals:$user->root_user_id)";
            Log::info("Retrieving tasks for criteria: $criteria");

            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            try {
                 // Retrieve submittals in pages
                while ($hasMorePages) {
                    $response = $zoho->getSubmittalsData($criteria, 'Name,Owner,Transaction_Name,', $page, 200);
                    
                    if (!$response->successful()) {
                        Log::error("Error retrieving tasks: " . $response->body());
                        // Handle unsuccessful response
                        $hasMorePages = false;
                    break;
                    } 
                    // Process successful response
                    $responseData = $response->json();
                    $submittals = collect($responseData['data'] ?? []);
                    $allSubmittals = $allSubmittals->concat($submittals);

                    // Check for more pages
                    $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                    $page++;
                
                }
            } catch (\Exception $e) {
                // Log any errors
                Log::error("Error retrieving tasks for user {$user->id}: " . $e->getMessage());
            }
            // Log the number of submittals retrieved for the user
            Log::info("Retrieved tasks for user {$user->id}: " . $allSubmittals);

            // Store submittals in the database
            $saveInDB = new DatabaseService();
            $saveInDB->storeSubmittalsIntoDB($allSubmittals,$user);
        }
    }
}
