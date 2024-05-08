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
        if($columnShowArray!=[]){
            $db->updateGroups($user, $accessToken,$columnShowArray);
        }
        $shownGroups = $db->retrieveGroups($user, $accessToken,"shownGroups");
        $contacts = $db->retrieveContactGroups($user, $accessToken,$filter);
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

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        
        $jsonInput = $request->input('laravelData');
        $keyValueArray = json_decode($jsonInput, true);
        
        $csv->insertOne(['Group', 'Contact']);
        
        foreach ($keyValueArray as $record) {
            $groupId = $record['groupId'];
            $contactId = $record['contactId'];
            try {
                $csv->insertOne([$groupId, $contactId]);
            } catch (CannotInsertRecord $e) {
                return response()->json(['error' => 'Failed to insert record into CSV.']);
            }
        }
        $filename = 'example_' . uniqid() . '.csv';
        $cleanFilename = '';
        foreach (str_split($filename) as $char) {
            if (ctype_alnum($char) || $char === '_' || $char === '.') {
                $cleanFilename .= $char;
            }
        }


        $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'csv' . DIRECTORY_SEPARATOR . $cleanFilename);

        $csv->output($filePath);

        return response()->json(['success' => true, 'filePath' => $filePath]);

    }



}
