<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Services\ZohoCRM;
use App\Services\DB;

class SaveTasksToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-tasks-to-d-b';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve tasks from Zoho CRM and save them to the database';

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
            $allTasks = collect();
            $page = 1;
            $hasMorePages = true;
            $error = '';

            $criteria = "(Owner:equals:$user->root_user_id)";
            Log::info("Retrieving tasks for criteria: $criteria");

            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            try {
                 // Retrieve contacts in pages
                while ($hasMorePages) {
                    $response = $zoho->getTasksData($criteria, 'Subject,Owner,Status,Due_Date,id,Who_Id,Closed_Time,Created_By,Description,Due_Date,Priority,Contact_Name,Created_Time', $page, 200);
                    
                    if (!$response->successful()) {
                        Log::error("Error retrieving tasks: " . $response->body());
                        // Handle unsuccessful response
                        $hasMorePages = false;
                    break;
                    } 
                    // Process successful response
                    $responseData = $response->json();
                    $contacts = collect($responseData['data'] ?? []);
                    $allTasks = $allTasks->concat($contacts);

                    // Check for more pages
                    $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                    $page++;
                
                }
            } catch (\Exception $e) {
                // Log any errors
                Log::error("Error retrieving tasks for user {$user->id}: " . $e->getMessage());
            }
            // Log the number of contacts retrieved for the user
            Log::info("Retrieved tasks for user {$user->id}: " . $allTasks);

            // Store contacts in the database
            $saveInDB = new DB();
            $saveInDB->storeTasksIntoDB($allTasks);
        }
    }
}
