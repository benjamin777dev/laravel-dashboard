<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Services\ZohoCRM;
use App\Services\DB;

class SaveRolesInDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-roles-in-d-b';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save contact roles in db';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Get user access token
            $accessToken = $user->getAccessToken();
            $allContactRoles = collect();
            $page = 1;
            $hasMorePages = true;
            $error = '';
            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            try {
                 // Retrieve submittals in pages
                while ($hasMorePages) {
                    $response = $zoho->getContactRoles($user, $accessToken);;
                    
                    if (!$response->successful()) {
                        Log::error("Error retrieving tasks: " . $response->body());
                        // Handle unsuccessful response
                        $hasMorePages = false;
                    break;
                    } 
                    // Process successful response
                    $responseData = $response->json();
                    $submittals = collect($responseData['contact_roles'] ?? []);
                    $allContactRoles = $allContactRoles->concat($submittals);

                    // Check for more pages
                    $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                    $page++;
                
                }
            } catch (\Exception $e) {
                // Log any errors
                Log::error("Error retrieving tasks for user {$user->id}: " . $e->getMessage());
            }
            // Log the number of submittals retrieved for the user
            Log::info("Retrieved tasks for user {$user->id}: " . $allContactRoles);

            // Store submittals in the database
            $saveInDB = new DB();
            $saveInDB->storeRolesIntoDB($allContactRoles,$user);

        }
    }
}
