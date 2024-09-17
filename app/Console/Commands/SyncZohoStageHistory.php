<?php

namespace App\Console\Commands;

use App\Models\Deal;
use App\Models\DealStageHistory;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class SyncZohoStageHistory extends Command
{
    protected $signature = 'zoho:fetch-stage-history';
    protected $description = 'Fetch stage history from Zoho Analytics and store it in the database';

    private $zohoAnalyticsUrl = 'https://analyticsapi.zoho.com/restapi/v2/workspaces/{workspace_id}/views/{view_id}/data';
    private $zohoDealsUrl = 'https://www.zohoapis.com/crm/v2/Deals';
    private $accessToken = '';
    private $workspaceId = '2487682000001378004';
    private $viewId = '2487682000013843217';
    private $orgId = '764575620';
    private $missingDeals = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Retrieve the access token (assuming it's stored in a user model)
        $user = User::where("email", "phillip@coloradohomerealty.com")->first();
        if (!$user) {
            $this->error("User not found.");
            return 1;
        }

        $this->accessToken = $user->getAccessToken();

        if (!$this->accessToken) {
            $this->error("Access token is missing or invalid.");
            return 1;
        }

        // Construct the API URL with the workspace and view ID for Stage History
        $apiUrl = str_replace(
            ['{workspace_id}', '{view_id}'],
            [$this->workspaceId, $this->viewId],
            $this->zohoAnalyticsUrl
        );

        $config = [
            'responseFormat' => 'csv',
        ];

        // Fetch stage history data from Zoho Analytics with the Organization ID in headers
        $response = Http::withToken($this->accessToken)
            ->withHeaders([
                'ZANALYTICS-ORGID' => $this->orgId, // Include the organization ID in the header
            ])
            ->get($apiUrl, ['CONFIG' => json_encode($config)]);

        if ($response->ok()) {
            // Fetch CSV data
            $csvData = $response->body();
            $csvData = preg_replace('/^\xEF\xBB\xBF/', '', $csvData); // Remove BOM if present

            // Create CSV Reader from CSV string data
            $reader = Reader::createFromString($csvData);
            $reader->setHeaderOffset(0); // The first row will be the header

            // Fetch all records
            $records = $reader->getRecords();

            $batchSize = 100; // Adjust the batch size based on your memory and performance needs
            $dataBatch = [];

            //DB::beginTransaction();
            try {

                foreach ($records as $record) {
                    // Look up the corresponding deal based on 'Transaction Name' (zoho_deal_id)
                    $deal = Deal::whereNotNull('zoho_deal_id')
                    ->where(DB::raw('TRIM(zoho_deal_id)'), trim((string) $record['Transaction Name']))
                    ->first();

        
                    if ($deal) {
                        $dataBatch[] = [
                            'zoho_id' => $record['Id'],
                            'zoho_deal_id' => $deal->zoho_deal_id,
                            'stage' => $record['Stage'] ?? null,
                            'modified_time' => !empty($record['Modified Time']) ? $this->convertToDateTime($record['Modified Time']) : null,
                            'stage_duration' => !empty($record['Stage Duration (Calendar Days)']) ? intval($record['Stage Duration (Calendar Days)']) : null,
                            'amount' => !empty($record['Amount']) ? floatval($record['Amount']) : null,
                            'closing_date' => !empty($record['Closing Date']) ? $this->convertToDate($record['Closing Date']) : null,
                            'currency' => $record['Currency'] ?? 'USD',
                            'exchange_rate' => $record['Exchange Rate'] ?? 1.00,
                            'expected_revenue' => !empty($record['Expected Revenue']) ? floatval($record['Expected Revenue']) : null,
                            'last_activity_time' => !empty($record['Last Activity Time']) ? $this->convertToDateTime($record['Last Activity Time']) : null,
                            'moved_to' => $record['Moved To'] ?? null,
                            'probability' => $record['Probability (%)'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        // Process data in batches
                        if (count($dataBatch) >= $batchSize) {
                            Log::info("Upsering new batch!...");
                            DealStageHistory::upsert(
                                $dataBatch,
                                ['zoho_id'],
                                ['stage', 'modified_time', 'stage_duration', 'amount', 'closing_date', 'currency', 'exchange_rate', 'expected_revenue', 'last_activity_time', 'moved_to', 'probability', 'updated_at']
                            );
                            $dataBatch = []; // Clear the batch after insert
                        }
                    } else {
                        $this->missingDeals[] = $record['Transaction Name'];
                        $this->warn("Deal with Zoho ID {$record['Transaction Name']} not found.");
                    }
                }

                // Insert the remaining batch
                if (count($dataBatch) > 0) {
                    DealStageHistory::upsert(
                        $dataBatch,
                        ['zoho_id'],
                        ['stage', 'modified_time', 'stage_duration', 'amount', 'closing_date', 'currency', 'exchange_rate', 'expected_revenue', 'last_activity_time', 'moved_to', 'probability', 'updated_at']
                    );
                }

                DB::commit();
                $this->info('Stage history data fetched and stored successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error('Failed to process the CSV records.');
                $this->error($e->getMessage());
            }
        } else {
            $this->error('Failed to fetch data from Zoho Analytics.');
            $this->error('Status Code: ' . $response->status());
            $this->error('Response Body: ' . $response->body());
        }

        if (!empty($this->missingDeals)) {
            // Fetch missing deals using Zoho Bulk API
            Log::info("missing deals: ", ['deals' => $this->missingDeals]);
            $this->fetchMissingDeals($this->missingDeals);
        }

        return 0;
    }

    /**
     * Convert the Zoho date format to a standard DateTime format for the database.
     */
    private function convertToDateTime($dateString)
    {
        try {
            return \Carbon\Carbon::createFromFormat('M d, Y h:i A', $dateString);
        } catch (\Exception $e) {
            return null; // Return null if the date format is invalid
        }
    }

    /**
     * Convert a string to a standard Date format for the database.
     */
    private function convertToDate($dateString)
    {
        try {
            return \Carbon\Carbon::createFromFormat('Y-m-d', $dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Return null if the date format is invalid
        }
    }

    private function fetchMissingDeals($missingDeals)
    {
        // Prepare the Bulk API request payload
        $batchSize = 100; // Adjust according to Zoho Bulk API limits
        $batches = array_chunk($missingDeals, $batchSize);

        foreach ($batches as $batch) {
            $response = Http::withToken($this->accessToken)
                ->get($this->zohoDealsUrl, [
                    'criteria' => 'id in (' . implode(',', $batch) . ')',
                ]);

            if ($response->ok()) {
                $deals = $response->json()['data'];
                Log::info("deals: ", ['deals'=>$deals]);

                // Insert or update the fetched deals using `mapZohoData`
                foreach ($deals as $dealData) {
                    $mappedData = Deal::mapZohoData($dealData, 'webhook');
                    Log::info("--mapped data: ", ['mappedData' => $mappedData]);
                    if (!empty($mappedData)) {
                        Deal::updateOrCreate(
                            ['zoho_deal_id' => $mappedData['zoho_deal_id']],
                            $mappedData
                        );
                    }
                }

                $this->info("Successfully fetched and inserted missing deals.");
            } else {
                $this->error("Failed to fetch missing deals: " . $response->status());
            }
        }
    }

}
