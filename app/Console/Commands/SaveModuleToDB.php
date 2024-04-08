<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Services\ZohoCRM;
use App\Services\DB;


class SaveModuleToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-module-to-d-b';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve Modules from Zoho CRM and save them to the database';

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
            $allModule = collect();
            $page = 1;
            // $hasMorePages = true;
            $error = '';

            $criteria = "(Owner:equals:$user->root_user_id)";
            Log::info("Retrieving tasks for criteria: $criteria");

            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            try {
                 // Retrieve contacts in pages
                // while ($hasMorePages) {
                    $response = $zoho->getModuleData();
                    if (!$response->successful()) {
                        Log::error("Error retrieving module: " . $response->body());
                        return "something went wrong in module". $response->body();
                    } 
                    // Process successful response
                    $responseData = $response->json();
                    $contacts = collect($responseData['modules'] ?? []);
                    $allModule = $allModule->concat($contacts);

                    // Check for more pages
                    // $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                    // $page++;
                
                // }
            } catch (\Exception $e) {
                // Log any errors
                Log::error("Error retrieving module for user {$user->id}: " . $e->getMessage());
            }
            // Log the number of contacts retrieved for the user
            Log::info("Retrieved module for user {$user->id}: " . $allModule);

            // Store contacts in the database
            $saveInDB = new DB();
            $saveInDB->storeModuleIntoDB($allModule);
        }
    }
}
