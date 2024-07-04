<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Contact;
use App\Services\DatabaseService;
use DataTables;

class TaskController extends Controller
{
    public function index()
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $db = new DatabaseService();
        $accessToken = $user->getAccessToken();

        // Fetch tasks for each category
        $upcomingTasks = $db->retreiveTasks($user, $accessToken, 'Upcoming');
        $inProgressTasks = $db->retreiveTasks($user, $accessToken, 'Due Today');
        $completedTasks = $db->retreiveTasks($user, $accessToken, 'Completed');
        $overdueTasks = $db->retreiveTasks($user, $accessToken, 'Overdue');

        $getdealsTransaction = $db->retrieveDeals($user, $accessToken);
        $retrieveModuleData = $db->retrieveModuleDataDB($user, $accessToken);

        return view('task.index', compact('upcomingTasks', 'inProgressTasks', 
            'completedTasks', 'getdealsTransaction', 'retrieveModuleData', 'overdueTasks'));
    }

    public function taskForContact()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $tab = request()->query('tab') ?? 'In Progress';
        $accessToken = $user->getAccessToken();
        $contactId = request()->route('contactId');
        $contact = $db->retrieveContactById($user, $accessToken, $contactId);
        if (!$contact) {
            return redirect('/contacts');
        } 
        $tasks = $db->retreiveTasksForContact($user, $accessToken, $tab, $contact->zoho_contact_id);
        $retrieveModuleData =  $db->retrieveModuleDataDB($user,$accessToken);

       return view('common.tasks',
            compact('tasks','contact','retrieveModuleData','tab'));
    }

    public function taskForContactJson()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $tab = request()->query('search') ?? 'In Progress';
        $accessToken = $user->getAccessToken();
        $contactId = request()->route('contactId');
        $contact = Contact::findOrFail($contactId);
       
        if (!$contact) {
            return redirect('/contacts');
        } 
        $tasks = $db->retreiveTasksForContact($user, $accessToken, $tab, $contact->id);
        $retrieveModuleData =  $db->retrieveModuleDataDB($user,$accessToken);
        return Datatables::of($tasks)->make(true);
    
    }

    public function updateTask(Request $request){
        try {
            $user = $this->user();
            $accessToken = $user->getAccessToken();
             $db = new DatabaseService();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
            $id = $request->input('id');
            $dbfield = $request->input('field');
            $value = $request->input('value');
            $rules = [
                'id' => 'required|exists:deals,id',
                'field' => 'required|in:subject,related_to,due_date',
                'value' => 'nullable', // Allow the value to be nullable (empty)
            ];
    
            $messages = [
                'id.required' => 'Contact ID is required.',
                'id.exists' => 'Invalid contact ID.',
                'field.required' => 'Field type is required.',
                'field.in' => 'Invalid field type.',
            ];
         
    
            // Validate request inputs
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }
           
            $task = Task::findorFail($id);
            if(!$task) return response()->json(['error' => 'Deal Id Not Found'], Response::HTTP_INTERNAL_SERVER_ERROR);
            
            $zoho = new ZohoCRM();
 
            $accessToken = $user->getAccessToken();
            $zoho->access_token = $accessToken;
            $field;
            if($dbfield==="subject"){
                $field = "Subject";
            }
            if($dbfield==="related_to"){
                $field = "Who_Id";
            }
            if($dbfield==="due_date"){
                $field = "Due_Date";
            }
            
            $jsonData = [
                'data' => [
                    [
                      $field => $value,
                    ],
                ],
                'skip_mandatory' => true,
            ];
            
            $zohotask = $zoho->updateTask($jsonData, $task->zoho_task_id);

            if (!$zohotask->successful()) {
                return response()->json(['error' => 'Zoho Deal update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $zohotaskArray = json_decode($zohotask, true);
            $zohoDealData = $zohoDealArray['data'][0]['details'];
            $resp = $zoho->getZohoDeal($zohoDealData['id']);

            if (!$resp->successful()) {
                return response()->json(['error' => 'Zoho Deal retrieval failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $zohoDeal_Array = json_decode($resp, true);
            $zohoDealValues = $zohoDeal_Array['data'][0];
            $data = $jsonData['data'];
            $dealDatas =  $db->updateDeal($user, $accessToken, $zohoDealValues, $deal);
            return response()->json(['data'=>$dealDatas,'message'=>"Successfully Updated"]);
        } catch (\Throwable $th) {
            // Handle the exception here
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function taskForPipeline()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $tab = request()->query('tab') ?? 'In Progress';
        $accessToken = $user->getAccessToken();
        $dealId = request()->route('dealId');
        $deal = $db->retrieveDealById($user, $accessToken, $dealId);
        if (!$deal) {
            return redirect('/pipeline');
        } 
        $tasks = $db->retreiveTasksForDeal($user, $accessToken, $tab, $deal->zoho_deal_id);
        $retrieveModuleData =  $db->retrieveModuleDataDB($user,$accessToken);

       return view('common.tasks',
            compact('tasks','deal','retrieveModuleData','tab'));
    }

}
