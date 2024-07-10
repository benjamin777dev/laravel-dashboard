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
use App\Services\DatabaseService;
use League\Csv\Writer;

use League\Csv\CannotInsertRecord;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken(); // Placeholder method to get the access token.
        $contacts = $db->retrieveContactGroups($user, $accessToken);    
        $groups = $db->retrieveGroups($user, $accessToken);
        $shownGroups = $db->retrieveGroups($user, $accessToken,"shownGroups");
        $ownerGroups = $db->getOwnerGroups($user, $accessToken);
        if (request()->ajax()) {
            // If it's an AJAX request, return the pagination HTML
            return view('groups.load', compact('contacts','groups','shownGroups'))->render();
        }
        return view('groups.index', compact('contacts','groups','shownGroups', 'ownerGroups'));
    }

    public function createGroup(Request $request)
    {
        try {
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = auth()->user();

            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $jsonInput = $request->all();
            $jsonData = [];
            $group_name = $jsonInput['group_name'] ?? null;

            // Check if group_name is provided
            if (!$group_name) {
                return redirect()->back()->withErrors(['group_name' => 'Group name is required']);
            }

            // Add the group_name to the data array to send to Zoho
            $jsonData['data'] = [
                [
                    'Name' => $group_name,
                    // Add other required fields here
                ]
            ];

            $responseArray = $zoho->createGroup($jsonData);

            if (!$responseArray || !isset($responseArray['data'][0]['code']) || $responseArray['data'][0]['code'] !== 'SUCCESS') {
                Log::error('Error creating group in Zoho', ['response' => $responseArray]);
                return redirect()->back()->withErrors(['zoho_error' => 'Failed to create group in Zoho']);
            }

            $data = $responseArray['data'][0]['details'];
            Log::info('Create Group RESPONSE', ['data' => $data]);

            $group = Groups::create([
                'ownerId' => $user->id,
                'name' => $group_name,
                'zoho_group_id' => $data['id']
            ]);

            return redirect('/group')->with('message', 'Group added successfully');
        } catch (\Throwable $e) {
            Log::error("Error creating group: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating the group.']);
        }
    }

    public function updateGroup(Request $request, $id)
    {
        try {
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            $res = ["status" => "success", "message" => "Group updated successfully"];
            if (!$user) {
                return redirect('/login');
            }

            // find the group
            $groupId = $request->route('groupId');
            $group = Groups::where('id', $groupId)->first();
            if (!$group) {
                return response()->json(['status' => 'error', 'message' => 'Group not found']);
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $jsonInput = $request->all();
            $jsonData = [];
            $jsonData['data'] = [
                [
                    'Name' => $jsonInput['group_name']
                ]
            ];
            $response = $zoho->updateGroup($jsonData, $group->zoho_group_id);
            if (!$response['data'][0]['code']==="SUCCESS") {
                return response()->json(['status' => 'error', 'message' => 'Failed to update group']);
            }
            Log::info('Update GRoup RESPONSE ' . json_encode($response));
            $group->name = $jsonInput['group_name'];
            $group->save();
            return response()->json($res);
        } catch (\Throwable $e) {
            Log::error("Error" . $e->getMessage());
            throw $e;
        }
    }

    public function deleteGroup(Request $request, $id)
    {
        try {
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
            $res = ["status" => "success", "message" => "Group deleted successfully"];
            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $groupId = $request->route('groupId');

            // if group is present in contact group table then return error
            $contactGroup = ContactGroups::where('groupId', $groupId)->first();
            if ($contactGroup) {
                return response()->json(['status' => 'error', 'message' => 'Group is associated with contacts']);
            }
            $group = Groups::where('id', $groupId)->first();

            if (!$group) {
                return response()->json(['status' => 'error', 'message' => 'Group not found']);
            }

            $response = $zoho->deleteGroup($group->zoho_group_id);
            Log::info('Update Group RESPONSE ' . json_encode($response));
            if (!$response['data'][0]['code']==="SUCCESS") {
                return response()->json(['status' => 'error', 'message' => 'Failed to delete group']);
            }
            if ($group) {
                $group->delete();
            }
            return response()->json($res);
        } catch (\Throwable $e) {
            Log::error("Error" . $e->getMessage());
            throw $e;
        }
    }

    public function filterGroups(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
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
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
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
            // Check if the record already exists
            $existingContactGroup = ContactGroups::where('zoho_contact_group_id', $data['id'])->first();
            if ($existingContactGroup) {
               $existingContactGroup->ownerId = $user->id;
               $existingContactGroup->contactId = $contact['id'] ?? null;
               $existingContactGroup->groupId = $group['id'] ?? null;
               $existingContactGroup->save();
                return response()->json($existingContactGroup);
            }else{
                $contactGroup = ContactGroups::create(
                    [
                        'ownerId' => $user->id,
                        "contactId" => $contact['id'] ?? null,
                        "groupId" => $group['id'] ?? null,
                        "zoho_contact_group_id" => $data['id'] ?? null
                    ]
                );
                return response()->json($contactGroup);
            }

            
        } catch (\Throwable $e) {
            Log::error("Error" . $e->getMessage());
            throw $e;
        }
    }

    public function deleteContactGroup(Request $request,$id){
        try {
            $db = new DatabaseService();
            $zoho = new ZohoCRM();
            $user = $this->user();
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
            $db = new DatabaseService();
            $user = $this->user();
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
        $db = new DatabaseService();
        $getBulkJob = $db->getBulkJob($payload['id']);
        $getUser = $getBulkJob->userData;
        $zoho = new ZohoCRM();
        $helper = new Helper();
        $user = $getUser;
        if (!$user) {
            Log::error("User Not Found");
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

    public function bulkRemove(Request $request)
    {
        try {
            $zoho = new ZohoCRM();
            $db = new DatabaseService();
            $user = $this->user();
            if (!$user) {
                return redirect('/login');
            }

            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;

            // Create CSV data
            $csvData = [];
            $jsonInput = $request->getContent();
            $records = json_decode($jsonInput, true);

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
