<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\ZohoCRM;
use App\Services\DatabaseService;
use App\Models\User; // Add this line to import the User model

class CompositeApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:composite-api';

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
        $zoho = new ZohoCRM();
        $db = new DatabaseService();
        foreach ($users as $user) {
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;

            $allData = collect();
            $page = 1;
            $hasMorePages = true;
            $error = '';

            
            $criteria = "(Contact_Name:equals:$user->zoho_id)";
            try {
                while ($hasMorePages) {
                    $response = $zoho->compositeApi($user,$page);
                    $responseCount = count($response['__composite_requests']);
                    foreach ($response['__composite_requests'] as $index => $currResponse) {
                        $body = isset($currResponse['details']['response']['body']);
                        if($body){
                            // Extract the data from the current response
                            $data = collect($currResponse['details']['response']['body']['data']??[]);
                            $info = $currResponse['details']['response']['body']['info'];

                            switch ($index) {
                                case '0':
                                    $db->storeContactsIntoDB($data);
                                    break;
                                case '1':
                                    $db->storeContactGroupsIntoDB($data,$user);
                                    break;
                                case '2':
                                    $db->storeDealsIntoDB($data,$user);
                                    break;
                                case '3':
                                    $db->storeTasksIntoDB($data);
                                    break;
                                case '4':
                                   $db->storeNotesIntoDB($data);
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                                                    
                            // Log the extracted data
                            Log::info("Data for index {$index}: " . json_encode($data));
                                // Check if there are more pages to fetch
                            if (isset($info['more_records'])) {
                                $hasMorePages = $info['more_records'];
                                $page++;
                                // $response = $zoho->compositeApi($user,$page);
                            }
                        }

                         
                    }
                    
                }
            } catch (\Exception $e) {
                Log::error("Error retrieving notes: " . $e->getMessage());
            }
        }
    }
}
