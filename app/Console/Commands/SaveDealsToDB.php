<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\ZohoCRM;
use App\Services\DatabaseService;
use App\Models\User; // Add this line to import the User model

class SaveDealsToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-deals-to-d-b';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve deals from Zoho CRM and save them to the database';

    /**
     * Execute the console command.
     */
    public function handle()
        {
            $users = User::all();

            foreach ($users as $user) {
                // Check if user exists
                $rootUserId = $user->root_user_id;

                // Get user's access token
                $accessToken = $user->getAccessToken(); // Ensure we have a valid access token
                $allDeals = collect();
                $page = 1;
                $hasMorePages = true;
                $error = '';

                $criteria = "(Contact_Name:equals:$user->zoho_id)";
                $fields = "Address,Amount,City,Primary_Contact,Client_Name_Primary,Client_Name_Only,Closing_Date,Created_By,Created_Time,Commission,Contact_Name,Contract,Create_Date,Created_By,Double_Ended,Lender_Company,Lender_Company_Name,Lender_Name,Loan_Amount,Loan_Type,MLS_No,Needs_New_Date,Needs_New_Date1,Needs_New_Date2,Ownership_Type,Personal_Transaction,Pipeline_Probability,Potential_GCI,Primary_Contact_Email,Probability,Pipeline1,Probable_Volume,Property_Type,Representing,Sale_Price,Stage,State,TM_Name,TM_Preference,Deal_Name,Owner,Transaction_Type,Type,Under_Contract,Using_TM,Z_Project_Id,Zip";

                // Log criteria
                Log::info("Retrieving deals for criteria: $criteria");

                // Initialize Zoho CRM service
                $zoho = new ZohoCRM();
                $zoho->access_token = $accessToken;

                try {
                    // Retrieve deals from Zoho CRM
                    while ($hasMorePages) {
                        $response = $zoho->getDealsData($criteria, $fields, $page, 200);

                        if (!$response->successful()) {
                            Log::error("Error retrieving deals: " . $response->body());
                            // Handle unsuccessful response
                            $hasMorePages = false;
                            break;
                        }

                        // Log successful fetch
                        Log::info("Successful deal fetch... Page: " . $page);

                        // Process response data
                        $responseData = $response->json();
                        $deals = collect($responseData['data'] ?? []);
                        $allDeals = $allDeals->concat($deals);

                        // Check if there are more pages
                        $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                        $page++;
                }
                } catch (\Exception $e) {
                    // Log error
                    Log::error("Error retrieving deals: " . $e->getMessage());
                    // return;
                }
               
                // Store retrieved deals into the database
                $saveInDB = new DatabaseService();
                $saveInDB->storeDealsIntoDB($allDeals,$user);

                // Log success
                Log::info("Deals saved to database successfully.".$allDeals);
            }
        }
}
