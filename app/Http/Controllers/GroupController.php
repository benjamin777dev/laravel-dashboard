<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Models\Groups;
use App\Models\ContactGroups;
use App\Services\Helper;
use App\Services\ZohoCRM;
use Carbon\Carbon;
use App\Services\DB;
use League\Csv\Writer;

use League\Csv\CannotInsertRecord;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $db = new DB();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        
        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $contacts = $db->retrieveContactGroups($user, $accessToken);
        $groups = $db->retrieveGroups($user, $accessToken);
        $shownGroups = $db->retrieveGroups($user, $accessToken,"shownGroups");
        
        return view('groups.index', compact('contacts','groups','shownGroups'));
    }

    public function filterGroups(Request $request)
    {
        $db = new DB();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $columnShow = $request->query('columnShow');
        $columnShowArray = json_decode($columnShow, true);
        $filter = $request->query('filter');
        $sort = $request->query('sort');
        if($columnShowArray!=[]){
            $db->updateGroups($user, $accessToken,$columnShowArray);
        }
        $shownGroups = $db->retrieveGroups($user, $accessToken,"shownGroups");
        $contacts = $db->retrieveContactGroups($user, $accessToken,$filter,$sort);
        // return response()->json(['shownGroups' => $shownGroups, 'contacts' => $contacts]);
        return view('groups.group', compact('shownGroups','contacts'))->render();
    }

    public function updateContactGroup(Request $request){
        try {
            $db = new DB();
            $zoho = new ZohoCRM();
            $user = auth()->user();
            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $jsonInput = $request->json()->all();
            $jsonData = $jsonInput['data'][0];
            $response = $zoho->updateContactGroup($jsonInput);
            if (!$response['data'][0]['code']==="SUCCESS") {
                return "error something".$response;
            }
            $responseArray = json_decode($response, true);
            $data = $responseArray['data'][0]['details']; 
            Log::info('Update GRoup RESPONSE ' . json_encode($data));
            $contact = Contact::where('zoho_contact_id',$jsonData['Contacts']['id'])->first();
            
            $group = Groups::where('zoho_group_id',$jsonData['Groups']['id'])->first();
            $contactGroup = ContactGroups::create(
                [
                    'ownerId' => $user->id,
                    "contactId" => $contact['id'] ?? null,
                    "groupId" => $group['id'] ?? null,
                    "zoho_contact_group_id" => $data['id'] ?? null
                ]
            );
            return response()->json($contactGroup);
        } catch (\Throwable $e) {
            Log::error("Error" . $e->getMessage());
            throw $e;
        }
    }

    public function deleteContactGroup(Request $request,$id){
        try {
            $db = new DB();
            $zoho = new ZohoCRM();
            $user = auth()->user();
            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $zohoGroupId = $request->route('contactGroupId');
            
            $response = $zoho->deleteContactGroup($zohoGroupId);
            Log::info('Update GRoup RESPONSE ' . json_encode($response));
            // if (!$response['data'][0]['code']==="SUCCESS") {
            //     return "error something".$response;
            // }
            $item = ContactGroups::where('zoho_contact_group_id', $zohoGroupId)->first();
            if ($item) {
                $item->delete();
            }
            return response()->json($response);
        } catch (\Throwable $e) {
            Log::error("Error" . $e->getMessage());
            throw $e;
        }
    }

    public function createCsv(Request $request)
    {
        try {
            $zoho = new ZohoCRM();
            $db = new DB();
            $user = auth()->user();
            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;

            // Create CSV data
            $csvData = [];
            $jsonInput = $request->input('laravelData');
            $keyValueArray = json_decode($jsonInput, true);
            // Define CSV headers
            $csvHeaders = ["Contacts", "Groups"];
            foreach ($keyValueArray as $record) {
                $csvData[] = [
                    "Contacts" =>$record['contactId'],
                    "Groups" =>$record['groupId']
                ];
            }

            // Generate a unique filename for the CSV
            $csvFilename = 'example_' . uniqid() . '.csv';

            // Write CSV data to file
            $csvFilepath = storage_path('app/' . $csvFilename);
            $csv = Writer::createFromPath($csvFilepath, 'w+');
            $csv->insertOne($csvHeaders);
            foreach ($csvData as $row) {
                $csv->insertOne([$row['Contacts'], $row['Groups']]);
            }

            // Create a zip archive
            $zip = new ZipArchive;
            $zipFilename = 'example_' . uniqid() . '.zip';
            $zipFilepath = storage_path('app/' . $zipFilename);
            if ($zip->open($zipFilepath, ZipArchive::CREATE) === true) {
                $zip->addFile($csvFilepath, $csvFilename);
                $zip->close();
            } else {
                throw new \Exception('Failed to create zip archive');
            }

            // Upload zip file to Zoho
            $response = $zoho->uploadZipFile($zipFilepath);
            $fileId = $response['details']['file_id'];
            //Bulk Write
            $bulkJob = $zoho->bulkWriteJob($fileId);
            $jobID = $bulkJob['details']['id'];
            $saveBulkJobInDB = $db->saveBulkJobInDB($fileId,$user->id,$jobID);
            // Return download response for the zip file
            return response()->json($saveBulkJobInDB);
        } catch (\Exception $e) {
            Log::error("Error creating CSV and uploading to Zoho: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create CSV or upload to Zoho'], 500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        // Process the webhook payload here
        $payload = $request->all();
        $db = new DB();
        $getBulkJob = $db->getBulkJob($payload['id']);
        $getUser = $getBulkJob->userData;
        $zoho = new ZohoCRM();
        $helper = new Helper();
        $user = $getUser;
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $zoho->access_token = $accessToken;

        // Example: Log the payload
        Log::info('Webhook received:', $payload);
        $jobID = $payload['id'];
        $getJobDetail = $zoho->getJobDetail($jobID);
        Log::info('getJobDetail:', ['response' => $getJobDetail->json()]);
        $zipFile = $getJobDetail['result']['download_url'];
        Log::info('ZIPFILE:', ['ZIPFILE' => $zipFile]);
        $getBulkJob->file = $zipFile;
        $extractZipFile = $helper->extractZipFile($zipFile,$zoho);
        $extractedFilesJSON = $helper->csvToJson($extractZipFile);

        $statusToFind = 'ADDED';
        $recordByStatus = $helper->array_find($extractedFilesJSON, function($item) use ($statusToFind) {
            return $item['STATUS'] !== $statusToFind;
        });
        if ($recordByStatus) {
            $getBulkJob->jobStatus = 'cancelled';
        }else{
            $getBulkJob->jobStatus = 'completed';
        }
        for ($i=0; $i < count($extractedFilesJSON); $i++) { 
            $curr = $extractedFilesJSON[$i];
            if ($curr['STATUS']=='ADDED') {
                $contact = Contact::where('zoho_contact_id', $curr['Contacts'])->first();
                $group = Groups::where('zoho_group_id', $curr['Groups'])->first();
                $contactGroup = ContactGroups::create(
                    [
                        'ownerId' => $user->id,
                        "contactId" => $contact['id'] ?? null,
                        "groupId" => $group['id'] ?? null,
                        "zoho_contact_group_id" => $curr['RECORD_ID'] ?? null
                    ]
                );
                
            }
            
        }
       $getBulkJob->save();
        return response()->json(['status' => 'success', 'extracted_files' => $extractedFilesJSON], 200);
    }

    public function createRemoveCsv(Request $request)
    {
        try {
            $zoho = new ZohoCRM();
            $db = new DB();
            $user = auth()->user();
            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;

            // Create CSV data
            $csvData = [];
            $jsonInput = $request->getContent();
            $records = json_decode($jsonInput, true);
            /* // Define CSV headers
            $csvHeaders = ["Contacts_X_Groups"];
            foreach ($keyValueArray as $record) {
                $csvData[] = [
                    "Contacts_X_Groups" =>$record['contactGroupId'],
                ];
            }

            // Generate a unique filename for the CSV
            $csvFilename = 'example_' . uniqid() . '.csv';

            // Write CSV data to file
            $csvFilepath = storage_path('app/' . $csvFilename);
            $csv = Writer::createFromPath($csvFilepath, 'w+');
            $csv->insertOne($csvHeaders);
            foreach ($csvData as $row) {
                $csv->insertOne([$row['Contacts_X_Groups']]);
            }

            // Create a zip archive
            $zip = new ZipArchive;
            $zipFilename = 'example_' . uniqid() . '.zip';
            $zipFilepath = storage_path('app/' . $zipFilename);
            if ($zip->open($zipFilepath, ZipArchive::CREATE) === true) {
                $zip->addFile($csvFilepath, $csvFilename);
                $zip->close();
            } else {
                throw new \Exception('Failed to create zip archive');
            }

            // Upload zip file to Zoho
            $response = $zoho->uploadZipFile($zipFilepath);
            $fileId = $response['details']['file_id']; */
            //Bulk Write
            $bulkJob = $zoho->bulkWriteJobToRemove($records);
            $removeContactGroupFromDB = $db->removeContactGroupFromDB($records);
            // Return download response for the zip file
            return response()->json($removeContactGroupFromDB);
        } catch (\Exception $e) {
            Log::error("Error creating CSV and uploading to Zoho: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create CSV or upload to Zoho'], 500);
        }
    }
}
