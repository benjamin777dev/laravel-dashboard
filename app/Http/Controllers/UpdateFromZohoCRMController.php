<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use App\Services\ZohoBulkRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\DatabaseService;


class UpdateFromZohoCRMController extends Controller
{
    public function handleContactUpdate(Request $request)
    {
        $data = $request->all();

        Log::info('Received contact update from Zoho CRM', ['data' => $data]);

        $zohoContactId = $data['id'];

        // Map the data using the Contact model's mapping method
        $mappedData = Contact::mapZohoData($data, 'webhook');

        // Update or create the contact record in the database
        try {
            Contact::updateOrCreate(
                ['zoho_contact_id' => $zohoContactId],
                $mappedData
            );
        } catch (\Exception $e) {
            Log::error('Error updating contact', ['zoho_contact_id' => $zohoContactId, 'exception' => $e->getMessage()]);
            return response()->json(['error' => 'Error updating contact'], 500);
        }

        Log::info('Contact updated/inserted successfully', ['zoho_contact_id' => $zohoContactId]);

        return response()->json(['message' => 'Contact updated successfully'], 200);
    }

    public function handleCSVCallback(Request $request)
    {
        $data = $request->all();
        Log::info('Received CSV callback', ['data' => $data]);

        $user = User::where('email', 'phillip@coloradohomerealty.com')->first();
        if (!$user) {
            Log::error("User not found.");
            $this->error("User not found.");
            return;
        }

        if ($data["status"] == "COMPLETED") {
            Log::info('CSV callback completed', ['data' => $data]);
            $jobId = $data["id"];

            $zoho = new ZohoBulkRead($user);
            $db = new DatabaseService();

            // Download the result
            $result = $zoho->downloadResult($data["download_url"]);

            if ($result) {
                $module = $data["query"]["module"]["api_name"];
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

        } elseif ($data["status"] == "FAILURE") {
            Log::error('CSV callback failed', ['data' => $data]);

        } elseif ($data["status"] == "success" && $data['code'] == "ADDED_SUCCESSFULLY") {
            Log::info('CSV callback success', ['data' => $data]);
        }
    }
}
