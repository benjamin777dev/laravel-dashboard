<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\DB;

class ZohoController extends Controller
{
    public function handleZohoCallback(Request $request)
    {
        Log::info('Received Zoho callback', ['request' => $request->all()]);

        // Verify the callback contains the expected data
        if (!$request->has('data') || !isset($request->data[0]['result']['download_url'])) {
            Log::error('Invalid callback data', ['request' => $request->all()]);
            return response()->json(['error' => 'Invalid callback data'], 400);
        }

        $downloadUrl = $request->data[0]['result']['download_url'];
        $jobId = $request->data[0]['id'];
        $module = $request->data[0]['query']['module']['api_name'];

        // Download the CSV file
        try {
            $csvContent = file_get_contents("https://www.zohoapis.com{$downloadUrl}");
            $filePath = "zoho_bulk_read/{$jobId}.zip";
            Storage::put($filePath, $csvContent);

            // Unzip the file and process the CSV
            $this->processCsv($filePath, $module);
        } catch (\Exception $e) {
            Log::error('Error downloading or processing CSV', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Error downloading or processing CSV'], 500);
        }

        return response()->json(['status' => 'success'], 200);
    }

    protected function processCsv($filePath, $module)
    {
        // Unzip the file
        $zip = new \ZipArchive();
        if ($zip->open(Storage::path($filePath)) === TRUE) {
            $zip->extractTo(Storage::path('zoho_bulk_read'));
            $zip->close();
        } else {
            Log::error('Failed to unzip file', ['filePath' => $filePath]);
            return;
        }

        // Get the extracted CSV file path
        $csvFilePath = Storage::path('zoho_bulk_read/' . basename($filePath, '.zip') . '.csv');

        // Process the CSV file and update the database
        $dbService = new DB();
        $dbService->importDataFromCSV($csvFilePath, $module);
    }
}
