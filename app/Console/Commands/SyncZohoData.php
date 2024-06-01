<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DB;
use App\Services\ZohoBulkRead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
    protected $description = 'Sync data from Zoho CRM using Bulk Read API';

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
        $db = new DB();

        $modules = ['Contacts', 'Deals', 'Contacts_X_Groups', 'Agent_Commission_Incomes']; // Add other modules as needed
        Log::info("Syncing data for modules: " . implode(', ', $modules));

        foreach ($modules as $module) {
            $jobResponse = $zoho->createBulkReadJob($module);

            if ($jobResponse) {
                $jobId = $jobResponse['data'][0]['details']['id'];
                $this->info("Bulk read job created for module: {$module} with job ID: {$jobId}");

                // Check job status until it's completed
                do {
                    $statusResponse = $zoho->checkJobStatus($jobId);
                    $state = $statusResponse['data'][0]['state'] ?? 'IN_PROGRESS';
                    sleep(30); // Wait for 30 seconds before checking the status again
                } while ($state !== 'COMPLETED');

                $this->info("Bulk read job completed for module: {$module}");

                // Download the result
                $result = $zoho->downloadResult($jobId);

                if ($result) {
                    $fileName = "{$module}_bulk_read.zip";
                    Storage::put($fileName, $result);
                    $this->info("Downloaded result for module: {$module} to {$fileName}");

                    // Extract the CSV and import data to the database
                    $zip = new \ZipArchive();
                    if ($zip->open(storage_path('app/' . $fileName)) === true) {
                        $zip->extractTo(storage_path('app/zoho_bulk_read/'));
                        $zip->close();

                        $extractedFiles = Storage::files('zoho_bulk_read');

                        foreach ($extractedFiles as $csvFilePath) {
                            if (pathinfo($csvFilePath, PATHINFO_EXTENSION) === 'csv') {
                                // Process CSV and import data to the database in chunks
                                $db->importDataFromCSV(storage_path('app/' . $csvFilePath), $module);
                                $this->info("Data imported for module: {$module} from {$csvFilePath}");
                            }
                        }
                    } else {
                        $this->error("Failed to extract {$fileName}");
                    }
                } else {
                    $this->error("Failed to download result for job ID: {$jobId}");
                }
            } else {
                $this->error("Failed to create bulk read job for module: {$module}");
            }
        }
    }

}
