<?php

namespace App\Http\Controllers;

use App\Models\Aci;
use App\Models\Contact;
use App\Models\ContactGroups;
use App\Models\Deal;
use App\Models\Note;
use App\Models\Task;
use App\Models\User;
use App\Services\DatabaseService;
use App\Services\ZohoBulkRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateFromZohoCRMController extends Controller
{

    public function handleUpdateDeleteFromZoho(Request $request, $module)
    {
        $data = $request->all();
        $zohoId = $data['id'];

        Log::info("Received $module update from Zoho CRM", ['data' => $data]);

        // Map the appropriate model based on the module
        $modelMapping = [
            'contact' => Contact::class,
            'deal' => Deal::class,
            'task' => Task::class,
            'contact_group' => ContactGroups::class,
            'aci' => Aci::class,
            'note' => Note::class,
        ];

        // Ensure the module exists in the mapping
        if (!isset($modelMapping[$module])) {
            Log::error("Invalid module received: $module");
            return response()->json(['error' => 'Invalid module'], 400);
        }

        $model = $modelMapping[$module];

        if (isset($data['webhook_type']) && $data['webhook_type'] == 'delete') {
            Log::info("Webhook delete triggered for Zoho ID: $zohoId in module $module");
            $model::where("zoho_{$module}_id", $zohoId)->delete();
            return response()->json(['message' => 'Deleted'], 200);
        }

        // Map the Zoho data using the model's mapping method
        $mappedData = $model::mapZohoData($data, 'webhook');

        try {
            $model::updateOrCreate(
                ["zoho_{$module}_id" => $zohoId],
                $mappedData
            );
        } catch (\Exception $e) {
            Log::error("Error updating $module", ['zoho_id' => $zohoId, 'exception' => $e->getMessage()]);
            return response()->json(['error' => 'Error updating module'], 500);
        }

        Log::info("$module updated/inserted successfully", ["zoho_{$module}_id" => $zohoId]);

        return response()->json(['message' => "$module updated successfully"], 200);
    }

    /* depreciated by single method
    public function handleContactUpdate(Request $request)
    {
    $data = $request->all();

    Log::info('Received contact update from Zoho CRM', ['data' => $data]);

    $zohoContactId = $data['id'];
    if (isset($data['webhook_type']) && $data['webhook_type'] == 'delete') {
    Log::info("Webhook delete triggered for Zoho Contact ID: " . $zohoContactId);
    Contact::where('zoho_contact_id', $zohoContactId)->delete();
    return response()->json(['message' => 'Deleted'], 200);
    }
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

    public function handleDealUpdate(Request $request)
    {
    $data = $request->all();

    Log::info('Received deal update from Zoho CRM', ['data' => $data]);

    $zohoDealId = $data['id'];
    if (isset($data['webhook_type']) && $data['webhook_type'] == 'delete') {
    Log::info("Webhook delete triggered for Zoho Deal ID: " . $zohoDealId);
    Deal::where('zoho_deal_id', $zohoDealId)->delete();
    return response()->json(['message' => 'Deleted'], 200);
    }
    // Map the data using the Contact model's mapping method
    $mappedData = Deal::mapZohoData($data, 'webhook');

    // Update or create the contact record in the database
    try {
    Deal::updateOrCreate(
    ['zoho_deal_id' => $zohoDealId],
    $mappedData
    );
    } catch (\Exception $e) {
    Log::error('Error updating deal', ['zoho_deal_id' => $zohoDealId, 'exception' => $e->getMessage()]);
    return response()->json(['error' => 'Error updating contact'], 500);
    }

    Log::info('Deal updated/inserted successfully', ['zoho_deal_id' => $zohoDealId]);

    return response()->json(['message' => 'Deal updated successfully'], 200);
    }

    public function handleAciUpdate(Request $request)
    {
    $data = $request->all();

    Log::info('Received aci update from Zoho CRM', ['data' => $data]);

    $zohoAciId = $data['id'];
    if (isset($data['webhook_type']) && $data['webhook_type'] == 'delete') {
    Log::info("Webhook delete triggered for Zoho Aci ID: " . $zohoAciId);
    Aci::where('zoho_aci_id', $zohoAciId)->delete();
    return response()->json(['message' => 'Deleted'], 200);
    }
    // Map the data using the Contact model's mapping method
    $mappedData = Aci::mapZohoData($data, 'webhook');

    // Update or create the contact record in the database
    try {
    Aci::updateOrCreate(
    ['zoho_deal_id' => $zohoAciId],
    $mappedData
    );
    } catch (\Exception $e) {
    Log::error('Error updating aci', ['zoho_aci_id' => $zohoAciId, 'exception' => $e->getMessage()]);
    return response()->json(['error' => 'Error updating contact'], 500);
    }

    Log::info('Aci updated/inserted successfully', ['zoho_aci_id' => $zohoAciId]);

    return response()->json(['message' => 'Aci updated successfully'], 200);
    }

    public function handleContactXGroupUpdate(Request $request)
    {
    $data = $request->all();

    Log::info('Received Contact X Group update from Zoho CRM', ['data' => $data]);

    $zohoContactXGroupId = $data['id'];
    if (isset($data['webhook_type']) && $data['webhook_type'] == 'delete') {
    Log::info("Webhook delete triggered for Zoho ContactXGroup ID: " . $zohoContactXGroupId);
    ContactGroups::where('zoho_contact_group_id', $zohoContactXGroupId)->delete();
    return response()->json(['message' => 'Deleted'], 200);
    }
    // Map the data using the Contact model's mapping method
    $mappedData = ContactGroups::mapZohoData($data, 'webhook');

    // Update or create the contact record in the database
    try {
    ContactGroups::updateOrCreate(
    ['zoho_contact_group_id' => $zohoContactXGroupId],
    $mappedData
    );
    } catch (\Exception $e) {
    Log::error('Error updating ContactXGroup', ['zoho_contact_group_id' => $zohoContactXGroupId, 'exception' => $e->getMessage()]);
    return response()->json(['error' => 'Error updating contact'], 500);
    }

    Log::info('ContactXGroup updated/inserted successfully', ['zoho_contact_group_id' => $zohoContactXGroupId]);

    return response()->json(['message' => 'ContactXGroup updated successfully'], 200);
    }

    public function handleTaskUpdate(Request $request)
    {
    $data = $request->all();

    Log::info('Received Task update from Zoho CRM', ['data' => $data]);

    $zohoTaskId = $data['id'];
    if (isset($data['webhook_type']) && $data['webhook_type'] == 'delete') {
    Log::info("Webhook delete triggered for Zoho Task ID: " . $zohoTaskId);
    Task::where('zoho_task_id', $zohoTaskId)->delete();
    return response()->json(['message' => 'Deleted'], 200);
    }
    // Map the data using the Contact model's mapping method
    $mappedData = Task::mapZohoData($data, 'webhook');

    // Update or create the contact record in the database
    try {
    Task::updateOrCreate(
    ['zoho_task_id' => $zohoTaskId],
    $mappedData
    );
    } catch (\Exception $e) {
    Log::error('Error updating Task', ['zoho_task_id' => $zohoTaskId, 'exception' => $e->getMessage()]);
    return response()->json(['error' => 'Error updating contact'], 500);
    }

    Log::info('Task updated/inserted successfully', ['zoho_task_id' => $zohoTaskId]);

    return response()->json(['message' => 'Task updated successfully'], 200);
    }
     */
    public function handleCSVCallback(Request $request)
    {
        $data = $request->all();
        Log::info('Received CSV callback', ['data' => $data]);

        $user = User::where('email', 'phillip@coloradohomerealty.com')->first();
        if (!$user) {
            Log::error("User not found.");
            return;
        }

        if ($data["state"] == "COMPLETED") {
            Log::info('CSV callback completed', ['data' => $data]);
            $jobId = $data["job_id"];

            $zoho = new ZohoBulkRead($user);
            $db = new DatabaseService();

            // Download the result
            $result = $zoho->downloadResult($jobId);

            if ($result) {
                $module = $data["query"]["module"]["api_name"];
                $fileName = "{$module}_bulk_read.zip";
                Storage::put($fileName, $result);
                Log::info("Downloaded result for module: {$module} to {$fileName}");

                // Extract the CSV and import data to the database
                $zip = new \ZipArchive();
                if ($zip->open(storage_path('app/' . $fileName)) === true) {
                    $zip->extractTo(storage_path('app/zoho_bulk_read/' . $module . '/' . $jobId . '/'));
                    $zip->close();

                    $extractedFiles = Storage::files('zoho_bulk_read/' . $module . '/' . $jobId . '/');

                    foreach ($extractedFiles as $csvFilePath) {
                        if (pathinfo($csvFilePath, PATHINFO_EXTENSION) === 'csv') {
                            // Process CSV and import data to the database in chunks
                            $db->importDataFromCSV(storage_path('app/' . $csvFilePath), $module);
                            Log::info("Data imported for module: {$module} from {$csvFilePath}");
                        }
                    }
                } else {
                    Log::error("Failed to extract {$fileName}");
                }
            } else {
                Log::error("Failed to download result for job ID: {$jobId}");
            }

        } elseif ($data["state"] == "FAILURE") {
            Log::error('CSV callback failed', ['data' => $data]);

        } elseif ($data["state"] == "success" && $data['code'] == "ADDED_SUCCESSFULLY") {
            Log::info('CSV callback success', ['data' => $data]);
        }
    }

}
