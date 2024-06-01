<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ZohoBulkRead;
use App\Services\DB;
use App\Models\User;
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

        if (!$user) {
            $this->error("User not found.");
            return;
        }

        $zoho = new ZohoBulkRead($user);
        $db = new DB();

        $modules = ['Contacts', 'Deals', 'ContactGroups', 'AgentCommission']; // Add other modules as needed

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
                    if ($zip->open(storage_path('app/' . $fileName)) === TRUE) {
                        $zip->extractTo(storage_path('app/'));
                        $zip->close();

                        $csvFileName = str_replace('.zip', '.csv', $fileName);
                        $csvFilePath = storage_path('app/' . $csvFileName);
                        
                        // Process CSV and import data to the database
                        $db->importDataFromCSV($csvFilePath, $module);

                        $this->info("Data imported for module: {$module} from {$csvFileName}");
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
