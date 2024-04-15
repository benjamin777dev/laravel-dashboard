<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Services\ZohoCRM;
use App\Services\DB;

class SaveACIInDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-a-c-i-in-d-b';

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
            $accessToken = $user->getAccessToken(); // Ensure we have a valid access token
            Log::info("Got Access Token: $accessToken");
            $allACI = collect();
            $page = 1;
            $hasMorePages = true;

            $criteria = "(CHR_Agent:equals:$user->zoho_id)";
            $fields = "Agent_Name,Less_Split_to_CHR,Transaction,Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
            Log::info("Retrieving aci for criteria: $criteria");

            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            try {
                while ($hasMorePages) {
                    $response = $zoho->getACIData($criteria, $fields, $page, 200);
                    if (!$response->successful()) {
                        Log::error("Error retrieving aci: " . $response->body());
                        // Handle unsuccessful response
                        $hasMorePages = false;
                        break;
                    }

                    Log::info("Successful aci fetch... Page: " . $page);
                    $responseData = $response->json();
                    //Log::info("Response data: ". print_r($responseData, true));
                    $aciData = collect($responseData['data'] ?? []);
                    $allACI = $allACI->concat($aciData);

                    $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                    $page++;
                }
            } catch (\Exception $e) {
                Log::error("Error retrieving aci: " . $e->getMessage());
                return $allACI;
            }

            Log::info("Total aci records: ". $allACI->count());
            Log::info("Aci Records: ", $allACI->toArray());
            // Store contacts in the database
            $saveInDB = new DB();
            $saveInDB->storeACIIntoDB($allACI);
        }
    }
}
