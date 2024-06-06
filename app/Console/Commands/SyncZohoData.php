<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DatabaseService;
use App\Services\ZohoBulkRead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class SyncZohoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:sync-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data from Zoho CRM using Bulk Read API and REST API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', 'phillip@coloradohomerealty.com')->first();
        Log::info("Syncing data for user: {$user->email}");
        if (!$user) {
            Log::error("User not found.");
            $this->error("User not found.");
            return;
        }

        $zoho = new ZohoBulkRead($user);
        $db = new DatabaseService();

        $modules = ['Contacts']; // Add other modules as needed
        Log::info("Syncing data for modules: " . implode(', ', $modules));

        // Sync users separately using the REST API
        $this->syncUsers($user);

        foreach ($modules as $module) {
            $jobResponse = $zoho->createBulkReadJob($module);

            if ($jobResponse) {
                $jobId = $jobResponse['data'][0]['details']['id'];
                $this->info("Bulk read job created for module: {$module} with job ID: {$jobId}");
            } else {
                $this->error("Failed to create bulk read job for module: {$module}");
            }
        }
    }

    /**
     * Sync users from Zoho CRM and update/insert them into the database.
     */
    private function syncUsers($user)
    {
        $accessToken = $user->getAccessToken(); 

        try {
            $response = Http::withHeaders([
                'Authorization' => "Zoho-oauthtoken {$accessToken}",
            ])->get("https://www.zohoapis.com/crm/v2/users", [
                'type' => 'AllUsers',
            ]);

            if ($response->successful()) {
                $users = $response->json()['users'];

                foreach ($users as $zohoUser) {
                    $password = Hash::make($zohoUser['id'] . 'zportalINACTIVEUSER'. $zohoUser['email']);
                    User::updateOrCreate(
                        ['root_user_id' => $zohoUser['id']],
                        [
                            'name' => $zohoUser['full_name'] ?? null,
                            'email' => $zohoUser['email'] ?? null,
                            'country' => $zohoUser['country'] ?? null,
                            'city' => $zohoUser['city'] ?? null,
                            'state' => $zohoUser['state'] ?? null,
                            'zip' => $zohoUser['zip'] ?? null,
                            'street' => $zohoUser['street'] ?? null,
                            'language' => $zohoUser['language'] ?? null,
                            'locale' => $zohoUser['locale'] ?? null,
                            'is_online' => $zohoUser['Isonline'] ?? false,
                            'currency' => $zohoUser['Currency'] ?? null,
                            'time_format' => $zohoUser['time_format'] ?? null,
                            'profile_name' => $zohoUser['profile']['name'] ?? null,
                            'profile_id' => $zohoUser['profile']['id'] ?? null,
                            'mobile' => $zohoUser['mobile'] ?? null,
                            'time_zone' => $zohoUser['time_zone'] ?? null,
                            'created_time' => $zohoUser['created_time'] ?? null,
                            'modified_time' => $zohoUser['Modified_Time'] ?? null,
                            'confirmed' => $zohoUser['confirm'] ?? false,
                            'full_name' => $zohoUser['full_name'] ?? null,
                            'date_format' => $zohoUser['date_format'] ?? null,
                            'status' => $zohoUser['status'] ?? null,
                            'website' => $zohoUser['website'] ?? null,
                            'email_blast_opt_in' => $zohoUser['Email_Blast_Opt_In'] ?? null,
                            'strategy_group' => $zohoUser['Strategy_Group'] ?? null,
                            'notepad_mailer_opt_in' => $zohoUser['Notepad_Mailer_Opt_In'] ?? null,
                            'market_mailer_opt_in' => $zohoUser['Market_Mailer_Opt_In'] ?? null,
                            'role_name' => $zohoUser['role']['name'] ?? null,
                            'role_id' => $zohoUser['role']['id'] ?? null,
                            'modified_by_name' => $zohoUser['Modified_By']['name'] ?? null,
                            'modified_by_id' => $zohoUser['Modified_By']['id'] ?? null,
                            'created_by_name' => $zohoUser['created_by']['name'] ?? null,
                            'created_by_id' => $zohoUser['created_by']['id'] ?? null,
                            'alias' => $zohoUser['alias'] ?? null,
                            'fax' => $zohoUser['fax'] ?? null,
                            'country_locale' => $zohoUser['country_locale'] ?? null,
                            'sandbox_developer' => $zohoUser['sandboxDeveloper'] ?? false,
                            'microsoft' => $zohoUser['microsoft'] ?? false,
                            'reporting_to' => $zohoUser['Reporting_To'] ?? null,
                            'offset' => $zohoUser['offset'] ?? null,
                            'next_shift' => $zohoUser['Next_Shift'] ?? null,
                            'shift_effective_from' => $zohoUser['Shift_Effective_From'] ?? null,
                            'transaction_status_reports' => $zohoUser['Transaction_Status_Reports'] ?? false,
                            'joined_date' => $zohoUser['Joined_Date'] ?? null,
                            'territories' => json_encode($zohoUser['territories'] ?? []),
                            'password' => $password,
                        ]
                    );
                    Log::info("User {$zohoUser['id']} synchronized successfully.");
                }

                $this->info("Users synchronized successfully.");
            } else {
                Log::error("Failed to fetch users from Zoho CRM: " . $response->body());
                $this->error("Failed to fetch users from Zoho CRM.");
            }
        } catch (\Exception $e) {
            Log::error("Error syncing users: " . $e->getMessage());
            $this->error("Error syncing users: " . $e->getMessage());
        }
    }
}
