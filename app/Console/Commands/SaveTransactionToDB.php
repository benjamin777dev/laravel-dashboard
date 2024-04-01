<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SaveTransactionToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-transaction-to-d-b';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve transactions from Zoho CRM and save them to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $allDeals = collect();
            $page = 1;
            $hasMorePages = true;

            $criteria = "(CHR_Agent:equals:$user->zoho_id)";
            // $fields = "Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
            Log::info("Retrieving notes for criteria: $criteria");

            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;

            try {
                while ($hasMorePages) {
                    $response = $zoho->getDealTransactionData($page, 200);

            
                    if (!$response->successful()) {
                        Log::error("Error retrieving notes: " . $response->body());
                        // Handle unsuccessful response
                        $hasMorePages = false;
                        break;
                    }
                    
                    Log::info("Successful notes fetch... Page: " . $page);
                    $responseData = $response->json();

                    //Log::info("Response data: ". print_r($responseData, true));
                    $allDealsdata = collect($responseData['data'] ?? []);
                    $allDeals = $allDeals->concat($allDealsdata);

                    $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                    $page++;
                }
            } catch (\Exception $e) {
                Log::error("Error retrieving notes: " . $e->getMessage());
            }
             // Log the number of contacts retrieved for the user
            Log::info("Retrieved transactions for user {$user->id}: " . $allDeals);

            // Store contacts in the database
            $saveInDB = new DB();
            $saveInDB->storeTransactionsIntoDB($allDeals);
        }
    }
}
