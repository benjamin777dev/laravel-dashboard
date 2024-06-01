<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Services\ZohoCRM;
use App\Services\DatabaseService;

class SaveNotesToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-notes-to-d-b';

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
            $allNotes = collect();
            $page = 1;
            $hasMorePages = true;
            $criteria = "(Owner:equals:$user->root_user_id)";
            $fields = "Note_Content,Created_Time,Owner,Parent_Id";
            Log::info("Retrieving notes for criteria: $criteria");
            // Get user's access token
            $accessToken = $user->getAccessToken(); // Ensure we have a valid access token

            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            try {
                while ($hasMorePages) {
                    $response = $zoho->getNotesData($criteria,$fields, $page, 200);
                    if (!$response->successful()) {
                        Log::error("Error retrieving notes: " . $response->body());
                        // Handle unsuccessful response
                        $hasMorePages = false;
                        break;
                    }

                    Log::info("Successful notes fetch... Page: " . $page);
                    $responseData = $response->json();
                    //Log::info("Response data: ". print_r($responseData, true));
                    $allNotes = collect($responseData['data'] ?? []);
                    $allNotes = $allNotes->concat($allNotes);

                    $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                    $page++;
            }
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
        }

        Log::info("Total notes records: ". $allNotes->count());
        Log::info("notes Records: ", $allNotes->toArray());
        // Store contacts in the database
            $saveInDB = new DatabaseService();
            $saveInDB->storeNotesIntoDB($allNotes);
    }
    }
}
