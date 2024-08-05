<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Contact;
use App\Models\Deal;
use App\Services\DatabaseService;
use DataTables;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\ZohoCRM;

class TaskController extends Controller
{
    public function index(Request $request)
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
        $taskcal = $this->taskCalculation();
        $status = $request->input('status');
        if(!empty($status)){
        if (request()->ajax()) {
            if(!empty($status) && $status==="Overdue"){
                $pageUrl = $overdueTasks->nextPageUrl();
                $view = view('common.tasks.taskcard', [
                    'overdueTasks' => $overdueTasks,
                    'getdealsTransaction' => $getdealsTransaction,
                    'retrieveModuleData' => $retrieveModuleData,
                ])->render();
            
                return response()->json([
                    'html' => $view,
                    'nextPageUrl' => $pageUrl // Also return the next page URL if needed
                ]);
        }
        if(!empty($status) && $status==="Completed"){
            $pageUrl = $completedTasks->nextPageUrl();
            $view = view('common.tasks.completecard', [
                'completedTasks' => $completedTasks,
                'getdealsTransaction' => $getdealsTransaction,
                'retrieveModuleData' => $retrieveModuleData,
            ])->render();
        
            return response()->json([
                'html' => $view,
                'nextPageUrl' => $pageUrl // Also return the next page URL if needed
            ]);
    }
    if(!empty($status) && $status==="Overdue_today"){
        $pageUrl = $overdueTasks->nextPageUrl();
        $view = view('common.tasks.overduecard', [
            'inProgressTasks' => $overdueTasks,
            'getdealsTransaction' => $getdealsTransaction,
            'retrieveModuleData' => $retrieveModuleData,
        ])->render();
    
        return response()->json([
            'html' => $view,
            'nextPageUrl' => $pageUrl // Also return the next page URL if needed
        ]);
}
if(!empty($status) && $status==="Upcomming"){
    $pageUrl = $overdueTasks->nextPageUrl();
    $view = view('common.tasks.upcommingcard', [
        'upcomingTasks' => $overdueTasks,
        'getdealsTransaction' => $getdealsTransaction,
        'retrieveModuleData' => $retrieveModuleData,
    ])->render();

    return response()->json([
        'html' => $view,
        'nextPageUrl' => $pageUrl // Also return the next page URL if needed
    ]);
}
    }
}

        return view('task.index', compact('upcomingTasks', 'inProgressTasks', 
            'completedTasks', 'getdealsTransaction', 'retrieveModuleData', 'overdueTasks','taskcal'));
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

    public function taskForPipeJson()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DatabaseService();
        $tab = request()->query('search') ?? 'In Progress';
        $accessToken = $user->getAccessToken();
        $dealId = request()->route('dealId');
        $deal = Deal::findOrFail($dealId);
       
        if (!$deal) {
            return redirect('/deal');
        } 
        $tasks = $db->retreiveTasksFordeal($user, $accessToken, $tab, $deal->id);
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
            $module = $request->input('module');
            $rules = [
                'id' => 'required|exists:tasks,id',
                'field' => 'required|in:subject,related_to,due_date,done_task',
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
            if($dbfield==="done_task"){
                $field = "Status";
            }
            if($dbfield==="due_date"){
                $field = "Due_Date";
            }
            if ($dbfield !== 'related_to') {
                if($field ==="Status"){
                    $jsonData = [
                        'data' => [
                            [
                              $field =>"Completed",
                            ],
                        ],
                        'skip_mandatory' => true,
                    ];
                }else{
            $jsonData = [
                'data' => [
                    [
                      $field => $value,
                    ],
                ],
                'skip_mandatory' => true,
            ];
        }

        }
       
        $deal;
        $contact;
        if ($dbfield === 'related_to') {
            $contact = Contact::find($value); // Using findOrFail to handle the case where the record is no
            if (!empty($contact->zoho_contact_id)) {
                $jsonData = [
                    'data' => [
                        [
                            'Who_Id' => [
                                'id' => $contact->zoho_contact_id,
                            ],
                            '$se_module' => 'Contacts' // Corrected the syntax for '$se_module'
                        ],
                    ],
                    'skip_mandatory' => true,
                ];
            } else {
                $deal = Deal::find($value); // Using findOrFail to handle the case where the record is not found
                $jsonData = [
                    'data' => [
                        [
                            'What_Id' => [ // Corrected the key to 'What_Id'
                                'id' => $deal->zoho_deal_id,
                            ],
                            '$se_module' => 'Deals' // Corrected the module type to 'Deals'
                        ],
                    ],
                    'skip_mandatory' => true,
                ];
            }
        }         
             
            $zohotask = $zoho->updateTask($jsonData, $task->zoho_task_id);

            if (!$zohotask->successful()) {
                return response()->json(['error' => 'Zoho task update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            if(!empty($field) && $field === "Status"){
                $dbfield = "status";
                $value = "Completed";
            }
            $task->$dbfield = $value;
            if(!empty($jsonData['data'][0]['$se_module'])){
                $task->related_to =  $jsonData['data'][0]['$se_module'];

            }

            if(!empty($jsonData['data'][0]['Who_Id']['id'])){
                $task->who_id =  $contact->id;
            }
           
            if(!empty($jsonData['data'][0]['What_Id']['id'])){
                $task->who_id =  $deal->id;
            }
            
            $task->save();
            
            return response()->json(['data'=>$task,'message'=>"Successfully Updated"]);
        } catch (\Throwable $th) {
            // Handle the exception here
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function upcommingTaskForDashboard()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $db = new DatabaseService();
        $upcomingTasks = $db->retreiveTasks($user, $accessToken, 'Upcoming');

        return view('common.tasks.taskdash', compact('upcomingTasks'))->render();
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

    public function taskCalculation()
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $db = new DatabaseService();
        $getTaskCalculatedCounts = $db->getTaskCounts($user);

         return $getTaskCalculatedCounts;
    }

}
