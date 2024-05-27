<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\ZohoCRM;
use App\Services\DB;
use App\Models\Note;
use App\Models\Deal;
use App\Models\Contact;
use App\Models\Task;
use App\Models\Module;
use App\Services\Helper;



class DashboardController extends Controller
{
    private function masterFilter($deal) {
        $helper = new Helper();
        $closingDate = Carbon::parse($helper->convertToMST(isset($deal['closing_date'])?$deal['closing_date']:null));
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        return $closingDate->gte($startOfYear) && $closingDate->lte($endOfYear);
    }

    public function index()
    {

        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $db = new DB();
        $helper = new Helper();
        $accessToken = $user->getAccessToken(); // Ensure we have a valid access token
        Log::info("Got Access Token: $accessToken");

        //Get Date Range
        $startDate = Carbon::now()->subYear()->format('d.m.Y'); // 1 year
        $endDate = Carbon::now()->format('d.m.Y'); // Current date

        // Set default goal or use user-defined goal
        $goal = $user->goal ?? 250000;
        Log::info("Goal: $goal");

        // Retrieve deals from Zoho CRM
        $deals = $db->retrieveDeals($user, $accessToken);
        $closedDeals = $db->retrieveDeals($user, $accessToken, $search = null, $sortField=null, $sortType=null,"closedDeals");
        Log::info("Deals: " . print_r($deals, true));
        // Calculate the progress towards the goal
        $progress = $this->calculateProgress($deals, $goal);
        $totalGciForDah = $this->totalGCIForDash($deals, $goal);
        $progressClass = $progress <= 15 ? "#FE5243" : ($progress <= 45 ? "#FADA05" : "#21AC25");
        $progressTextColor = $progress <= 15 ? "#fff" : ($progress <= 45 ? "#333" : "#fff");
        Log::info("Progress: $progress");

        // master filter will exclude anything that is beyond 12 months
        // anything that is a bad date (less than today)
        $stages = ['Potential', 'Pre-Active', 'Active', 'Under Contract'];
        $stageData = collect($stages)->mapWithKeys(function ($stage) use ($deals,$goal,$startDate,$endDate) {
            $filteredDeals = $deals->filter(function ($deal) use ($stage,$startDate,$endDate) {
               $closingDate = Carbon::parse($deal['closing_date']);
            return $deal['stage'] === $stage && $this->masterFilter($deal) && $closingDate->gte($startDate) && $closingDate->lte($endDate);
            });
            $stageProgress = $this->calculateStageProgress($filteredDeals, $goal);
            $stageProgressClass = $stageProgress <= 15 ? "bg-danger" : ($stageProgress <= 45 ? "bg-warning" : "bg-success");
            $stageProgressIcon = $stageProgress <= 15 ? "mdi mdi-arrow-bottom-right" : ($stageProgress <= 45 ? "mdi mdi-arrow-top-right" : "mdi mdi-arrow-top-right");
            $stageProgressExpr = $stageProgress <= 15 ? "-" : ($stageProgress <= 45 ? "-" : "+");
            Log::info("stageProgress: $stageProgress");
            return [
                $stage => [
                    'count' => $this->formatNumber($filteredDeals->count()),
                    'sum' => $this->formatNumber($filteredDeals->sum('pipeline1')),
                    'asum' => $filteredDeals->sum('pipeline1'),
                    'stageProgress' => $stageProgress,
                    "stageProgressClass"=>$stageProgressClass,
                    'stageProgressIcon'=>$stageProgressIcon,
                    'stageProgressExpr'=>$stageProgressExpr
                ],
            ];
        });
   
        $cpv = $stageData->sum(function ($stage) {
            return $stage['asum'];
        });
        // Calculate Current Pipeline Value
        $currentPipelineValue = $this->formatNumber($cpv);

        // Calculate Projected Income
        $projectedIncome = $cpv * 2;

        // Beyond 12 Months

        $beyond12Months = $deals->filter(function ($deal) use ($helper) {
            $closingDate = Carbon::parse($helper->convertToMST($deal['closing_date']));
            $endOfYear = Carbon::now()->endOfYear();
            return $closingDate->gt($endOfYear)
                && !Str::startsWith($deal['stage'], 'Dead')
                && $deal['stage'] !== 'Sold';
        });

        $beyond12MonthsData = [
            'sum' => $this->formatNumber($beyond12Months->sum('pipeline1')),
            'count' => $beyond12Months->count(),
            'asum' => $beyond12Months->sum('pipeline1')
        ];

        // Needs New Date
        $needsNewDate = $deals->filter(function ($deal) use ($helper) {
            return Carbon::parse($helper->convertToMST($deal['closing_date']))->lt(now())
                   && !Str::startsWith($deal['stage'], 'Dead')
                   && $deal['stage'] !== 'Sold';
        });

        $needsNewDateData = [
            'sum' =>$this->formatNumber($needsNewDate->sum('pipeline1')),
            'asum' => $needsNewDate->sum('pipeline1'),
            'count' => $needsNewDate->count(),
        ];

        $filteredDeals = $deals->filter(function ($deal) {
            return $this->masterFilter($deal)
                   && !Str::startsWith($deal['stage'], 'Dead')
                   && $deal['stage'] !== 'Sold';
        });
        
        $monthlyGCI = $filteredDeals->groupBy(function ($deal) use ($helper) {
            return Carbon::parse($helper->convertToMST($deal['closing_date']))->format('Y-m');
        })->map(function ($dealsGroup) {
            return $dealsGroup->sum('pipeline1');
        });

        $averagePipelineProbability = $deals->filter(function ($deal) {
            return $this->masterFilter($deal)
                   && !Str::startsWith($deal['stage'], 'Dead')
                   && $deal['stage'] !== 'Sold';
        })->avg('Pipeline_Probability');

        $newDealsLast30Days = $deals->filter(function ($deal) use ($helper) {
            return now()->diffInDays(Carbon::parse($helper->convertToMST($deal['zoho_deal_createdTime']))) <= 30
                   && $this->masterFilter($deal)
                   && !Str::startsWith($deal['stage'], 'Dead')
                   && $deal['stage'] !== 'Sold';
        })->count();
        // Ensure all months of the year are represented, fill missing months with 0
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $allMonths = [];
        while ($startOfYear->lessThanOrEqualTo($endOfYear)) {
            $month = $startOfYear->format('Y-m');
            $gci = $monthlyGCI->get($month, 0);
            
            // Get the count of deals for this month
            $dealCount = $filteredDeals->filter(function ($deal) use ($month, $helper) {
                return Carbon::parse($helper->convertToMST($deal['closing_date']))->format('Y-m') === $month;
            })->count();
        
            $allMonths[$month] = [
                'gci' => $gci,
                'deal_count' => $dealCount
            ];
            
            $startOfYear->addMonth();
        }
        
        $rootUserId = $user->root_user_id; // Assuming root_user_id is a field in your User model
        $contactData = $this->retrieveAndCheckContacts($rootUserId, $accessToken);

        $newContactsLast30Days = $contactData['contactsLast30Days'];
        $tab = request()->query('tab') ?? 'In Progress';
        $tasks = $db->retreiveTasks($user, $accessToken,$tab);
        Log::info("Task Details: ". print_r($tasks, true));

        $aciInfo = $this->retrieveACIFromZoho($user, $accessToken);
         $notesInfo = $db->retrieveNotes($user,$accessToken);
         $getdealsTransaction = $db->retrieveDeals($user,$accessToken);
         $retrieveModuleData =  $db->retrieveModuleDataDB($user,$accessToken);
         $dealFordash = $this->getDealsForDash();
         $contactInfo = Contact::getZohoContactInfo();
        //  fetch notes
        //   print("<pre/>");
        //   print_r(json_encode($dealFordash));
        //   die;
         $notes = $this->fetchNotes();
        $totalaci = $aciInfo->filter(function ($aci) {
            return isset($aci['Total'], $aci['Closing_Date'])
                   && $aci['Total'] > 0
                   && (!isset($aci['Stage']) || $aci['Stage'] == 'Sold')
                   && $this->masterFilter(['Closing_Date' => $aci['Closing_Date']]);
        })->sum('Total');

        $totalAgentCheck = $aciInfo->filter(function ($aci) {
            return isset($aci['Agent_Check_Amount'], $aci['Closing_Date'])
                && $aci['Agent_Check_Amount'] > 0
                && (!isset($aci['Stage']) || $aci['Stage'] == 'Sold')
                && $this->masterFilter(['Closing_Date' => $aci['Closing_Date']]);
        })->sum('Agent_Check_Amount');

        $totalIRS1099 = $aciInfo->filter(function ($aci) {
            return isset($aci['IRS_Reported_1099_Income_For_This_Transaction'], $aci['Closing_Date'])
                && $aci['IRS_Reported_1099_Income_For_This_Transaction'] > 0
                && (!isset($aci['Stage']) || $aci['Stage'] == 'Sold')
                && $this->masterFilter(['Closing_Date' => $aci['Closing_Date']]);
        })->sum('IRS_Reported_1099_Income_For_This_Transaction');

        $aciData = [
            'totalaci' => $this->formatNumber($totalaci ?? 0),
            'totalAgentCheck' => $this->formatNumber($totalAgentCheck ?? 0),
            'totalIRS1099' => $this->formatNumber($totalIRS1099 ?? 0),
        ];

        Log::Info("ACI Data: ". print_r($aciData, true));
      
        // Pass data to the view
        return view('dashboard.index',
            compact('deals', 'progress', 'goal',
                'progressClass', 'progressTextColor',
                'stageData', 'currentPipelineValue',
                'projectedIncome', 'beyond12MonthsData',
                'needsNewDateData', 'allMonths', 'contactData',
                'newContactsLast30Days', 'newDealsLast30Days',
                'averagePipelineProbability', 'tasks', 'aciData','tab','dealFordash','getdealsTransaction','notes','startDate','endDate','user','notesInfo','closedDeals','retrieveModuleData','accessToken','contactInfo','totalGciForDah'));
    }

    private function formatNumber($number) {
        if ($number < 1000) {
            return (string)$number; // Less than 1,000
        } elseif ($number < 1000000) {
            return round($number / 1000, 2) . 'k'; // Less than 1 million
        } elseif ($number < 1000000000) {
            return round($number / 1000000, 2) . 'm'; // Less than 1 billion
        } else {
            return round($number / 1000000000, 2) . 'b'; // 1 billion or more
        }
    }



    private function retrieveACIFromZoho(User $user, $accessToken)
    {
        $allACI = collect();
        $page = 1;
        $hasMorePages = true;

        $criteria = "(CHR_Agent:equals:$user->zoho_id)";
        $fields = "Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
        Log::info("Retrieving aci for criteria: $criteria");

        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;

        try {
            while ($hasMorePages) {
                $response = $zoho->getACIData($criteria, $fields, $page, 200);
                if (!$response->successful()) {
                    Log::error("Error retrieving aci: " . $response->body());
                    // Handle unsuccessful response
                    $hasMorePages = false;
                    break;
                }

                Log::info("Successful aci fetch... Page: " . $page);
                $responseData = $response->json();
                $aciData = collect($responseData['data'] ?? []);
                $allACI = $allACI->concat($aciData);

                $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                $page++;
            }
        } catch (\Exception $e) {
            Log::error("Error retrieving aci: " . $e->getMessage());
            return $allACI;
        }

        Log::info("Total aci records: ". $allACI->count());
        Log::info("Aci Records: ", $allACI->toArray());
        return $allACI;
    }

    //get notes data function
    private function retrieveNOTESFromZoho(User $user, $accessToken)
    {
        $allNotes = collect();
        $page = 1;
        $hasMorePages = true;

        $startDateTime = now()->subDays(7)->toIso8601String(); // Get start date/time (7 days ago) in ISO 8601 format
        $endDateTime = now()->toIso8601String(); // Get current date/time in ISO 8601 format
        $criteria = "(Owner:equals:$user->root_user_id)";
        $fields = "Note_Content,Created_Time,Owner,Parent_Id";
        Log::info("Retrieving notes for criteria: $criteria");

        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;

        try {
            while ($hasMorePages) {
                $response = $zoho->getNotesData($criteria,$fields, $page, 200);
                if (!$response->successful()) {
                    Log::error("Error retrieving notes: " . $response->body());
                    // Handle unsuccessful response
                    $hasMorePages = false;
                    break;
                }

                Log::info("Successful notes fetch... Page: " . $page);
                $responseData = $response->json();
                $allNotes = collect($responseData['data'] ?? []);
                $allNotes = $allNotes->concat($allNotes);

                $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                $page++;
            }
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            return $allNotes;
        }

        Log::info("Total notes records: ". $allNotes->count());
        Log::info("notes Records: ", $allNotes->toArray());
        return $allNotes;
    }

    private function calculateProgress($deals, $goal)
    {
        // Filter out deals that are in any stage that starts with 'Dead' or are in 'Sold' stage.
        // don't count anything beyond 12 months
        // exclude bad dates as well
        $filteredDeals = $deals->filter(function ($deal) {
            return !Str::startsWith($deal['stage'], 'Dead')
                   && $deal['stage'] !== 'Sold'
                   && $this->masterFilter($deal); // Correct usage within the method
        });

        // Sum the 'Pipeline1' values of the filtered deals.
        $totalGCI = $filteredDeals->sum('pipeline1');
        Log::info("Total GCI from open stages: $totalGCI");

        // Calculate the progress as a percentage of the goal.
        $progress = ($totalGCI / $goal) * 100;
        Log::info("Progress towards goal: $progress");

        // Ensure progress does not exceed 100%.
        return min($progress, 100);
    }

    private function totalGCIForDash($deals, $goal)
    {
        // Filter out deals that are in any stage that starts with 'Dead' or are in 'Sold' stage.
        // don't count anything beyond 12 months
        // exclude bad dates as well
        $filteredDeals = $deals->filter(function ($deal) {
            return !Str::startsWith($deal['stage'], 'Dead')
                   && $deal['stage'] !== 'Sold'
                   && $this->masterFilter($deal); // Correct usage within the method
        });

        // Sum the 'Pipeline1' values of the filtered deals.
        $totalGCI = $filteredDeals->sum('pipeline1');
       
        return $totalGCI;
    }

    private function calculateStageProgress($deals, $goal)
    {
        // Sum the 'Pipeline1' values of the filtered deals.
        $totalGCI = $deals->sum('pipeline1');
        Log::info("Total GCI from open stages: $totalGCI");

        // Calculate the progress as a percentage of the goal.
        $progress = ($totalGCI / $goal) * 100;
        Log::info("Progress towards goal: $progress");

        // Ensure progress does not exceed 100%.
        return round(min($progress, 100));
    }

    private function retrieveAndCheckContacts($rootUserId, $accessToken)
    {
        $helper = new Helper();
        $allContacts = $this->retrieveContactsFromZoho($rootUserId, $accessToken);

        $abcContacts = $allContacts->filter(function ($contact) {
            return (!empty($contact['ABCD']) && $contact['ABCD'] !== 'D');
        })->count();
        Log::info("ABC Contacts: $abcContacts");

        $needsEmail = $allContacts->filter(function ($contact) {
            return empty($contact['Email']);
        })->count();
        Log::info("Needs Email: $needsEmail");

        $needsAddress = $allContacts->filter(function ($contact) {
            return empty($contact['Mailing_Address']) || empty($contact['Mailing_City']) || empty($contact['Mailing_State']) || empty($contact['Mailing_Zip']);
        })->count();
        Log::info("Needs Address: $needsAddress");

        $needsPhone = $allContacts->filter(function ($contact) {
            return empty($contact['Phone']);
        })->count();
        Log::info("Needs Phone: $needsPhone");

        $missingAbcd = $allContacts->filter(function ($contact) {
            return empty($contact['ABCD']);
        })->count();
        Log::info("Missing ABCD: $missingAbcd");

        $contactsLast30Days = $allContacts->filter(function ($contact) use ($helper) {
            return now()->diffInDays($helper->convertToMST($contact['Created_Time'])) < 30;
        })->count();

        return [
            'abcContacts'=>$abcContacts,
            'needsEmail'=>$needsEmail,
            'needsAddress'=>$needsAddress,
            'needsPhone'=>$needsPhone,
            'missingAbcd'=>$missingAbcd,
            'contactsLast30Days'=>$contactsLast30Days
        ];
    }

    private function retrieveContactsFromZoho($rootUserId, $accessToken)
    {
        $allContacts = collect();
        $page = 1;
        $hasMorePages = true;

        $criteria = "(Owner:equals:$rootUserId)";
        $fields = 'Contact Owner,Email,First Name,Last Name,Phone,Created_Time,ABCD,Mailing_Address,Mailing_City,Mailing_State,Mailing_ZipContact Owner,Email,First Name,Last Name,Phone,Created_Time,ABCD,Mailing_Address,Mailing_City,Mailing_State,Mailing_Zip';
        Log::info("Retrieving contacts for criteria: $criteria");

        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;

        try {
            while ($hasMorePages) {
                $response = $zoho->getContactData($criteria, $fields, $page, 200);

                if (!$response->successful()) {
                    Log::error("Error retrieving contacts: " . $response->body());
                    // Handle unsuccessful response
                    $hasMorePages = false;
                    break;
                }

                Log::info("Successful contact fetch... Page: " . $page);
                $responseData = $response->json();
                $contacts = collect($responseData['data'] ?? []);
                $allContacts = $allContacts->concat($contacts);

                $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                $page++;
            }

        } catch (\Exception $e) {
            Log::error("Error retrieving contacts: " . $e->getMessage());
            return $allContacts;
        }
        Log::info("Retrieved contacts: ". $allContacts->count());

        return $allContacts;
    }


    public function createTaskaction(Request $request,User $user)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        Log::info("Access Token,$accessToken");
        $jsonData = $request->json()->all();
        $data = $jsonData['data'][0];

        // Access the 'Subject' field
        if(!empty($data['Subject'])){
            $subject = $data['Subject'] ?? null;

        }
        if(!empty($data['Who_Id']['id'])){
            $whoid = $data['Who_Id']['id'] ?? null;

        }
        if(!empty($data['Status'])){
            $status = $data['Status'] ?? null;

        }
        if(!empty($data['Due_Date'])){
            $Due_Date = $data['Due_Date'] ?? null;

        }
        if(!empty($data['What_Id']['id'])){
            $What_Id = $data['What_Id']['id'] ?? null;

        }
        if(!empty($data['Priority'])){
            $What_Id = $data['What_Id']['id'] ?? null;
            $priority = $data['Priority']??null;

        }

        $created_time = Carbon::now();
        $closed_time = $data['Closed_Time']??null;
        $related_to = $data['$se_module']??null;
        


        $criteria = "(CHR_Agent:equals:$user->zoho_id)";
        // $fields = "Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
        Log::info("Retrieving notes for criteria: $criteria");
        // $response;
       

        try {
                $response = $zoho->createTask($jsonData);


                if (!$response->successful()) {
                     return "error something".$response;
                }
                $responseArray = json_decode($response, true);
                $data = $responseArray['data'][0]['details']; 
                $zoho_id = $data['id'];
                $modifiedTime = $data['Modified_Time'];
                $Modified_By = $data['Modified_By']['name'];
                $Modified_Id = $data['Modified_By']['id'];
                // Create a new Task record using the Task model
                $task = Task::create([
                    'subject' => $subject,
                    'zoho_task_id'=>$zoho_id,
                    'owner' => "1",
                    'status'=>$status ?? "Not Started",
                    'who_id'=>$whoid ?? null,
                    'due_date'=>$Due_Date ?? null,
                    'what_id'=>$What_Id ?? null,
                    'closed_time'=>$closed_time??null,
                    'created_by'=>$user->id,
                    'priority'=>$priority??null,
                    'created_time'=>$created_time??null,
                    'related_to'=>$related_to
                ]);
                return response()->json($responseArray, 201);

                // $task->modified_by_name = $modifiedByName;
                // $task->modified_by_id = $modifiedById;
                return $data;
                Log::info("Successful notes create... ".$response);


        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            return "somthing went wrong". $e;
        }
    }

    public function deleteTaskaction(Request $request,User $user,$id)  {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $jsonData = $request->json()->all();
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        try {
            if (strpos($id, ',') === false) {
                $response = $zoho->deleteTask($jsonData,$id);
                if (!$response->successful()) {
                     return "error somthing".$response;
                }
                $task = Task::where('zoho_task_id', $id)->first();
                if (!$task) {
                    return "Task not found";
                }
                $task->delete();
            } else {
                // Multiple IDs provided
                $response = $zoho->deleteTaskSelected($jsonData,$id);
                if (!$response->successful()) {
                     return "error somthing".$response;
                }
                $idArray = explode(',', $id);
               $tasks = Task::whereIn('zoho_task_id', $idArray)->delete();
                if (!$tasks) {
                    return "Task not found";
                }
            }
            Log::info("Successful notes delete... ".$response);

            
        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            return "somthing went wrong". $e->getMessage();
        }
        return $response;
    }
    public function updateTaskaction(Request $request,User $user,$id){
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $jsonData = $request->json()->all();
        
        Log::info("JSON TASK INPUT".json_encode($jsonData));
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        $task = Task::where('zoho_task_id', $id)->first();
        if(empty($task)){
        $task = Task::where('id', $id)->first();
        }
        try {
                $response = $zoho->updateTask($jsonData,$task['zoho_task_id']);
                if (!$response->successful()) {
                      throw $response;
                }
                $requestData = $request->json()->all(); // Get JSON data from request
                $data = $requestData['data'][0]; // Access the 'data' array
                $subject = $data['Subject'] ?? null; // Get 'Subject' from data
                $dueDate = $data['Due_Date'] ?? null; // Get 'Due_Date' from data
                $whatId = $data['What_Id']['id'] ?? null; // Get 'What_Id' from data
                $whoId = $data['Who_Id']['id'] ?? null; // Get 'What_Id' from data
                $seModule = $data['$se_module'] ?? null;
                if($task){
                    if($dueDate !== null){
                        $task->due_date = $dueDate ?? $task->due_date;
                    }
                    if($subject !== null){
                        $task->subject = $subject;
                    }
                    if($whatId!==null){
                        $task->what_id = $whatId;
                    }
                    if($seModule!==null){
                        $task->related_to = $seModule;
                    }
                    if($whoId!==null){
                        $task->who_id = $whoId;
                    }
                    $task->status=$status ?? $task->status;
                    $task->save();
                }

                Log::info("Successful task update... ".$response);
                return $response;
            } catch (\Exception $e) {
                Log::error("Error creating task: " . $e->getMessage());
                return  $e->getMessage();
            }

    }

    public function retrieveDealTransactionData(User $user, $accessToken)
    {
        $allDeals = collect();
        $page = 1;
        $hasMorePages = true;

        $criteria = "(CHR_Agent:equals:$user->zoho_id)";
        // $fields = "Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
        Log::info("Retrieving deal for criteria: $criteria");

        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;

        try {
            while ($hasMorePages) {
                $response = $zoho->getDealTransactionData($page, 200);


                if (!$response->successful()) {
                    Log::error("Error retrieving notes: " . $response->body());
                    // Handle unsuccessful response
                    $hasMorePages = false;
                    break;
                }

                Log::info("Successful deal fetch... Page: " . $page);
                $responseData = $response->json();

                $allDealsdata = collect($responseData['data'] ?? []);
                $allDeals = $allDeals->concat($allDealsdata);

                $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                $page++;
            }
        } catch (\Exception $e) {
            Log::error("Error retrieving deal: " . $e->getMessage());
            return $allDeals;
        }
        return $allDeals;
        

        Log::info("Total deals records: ". $allDeals->count());
        Log::info("deals Records: ", $allDeals->toArray());
        return $allDeals;
    }

   public function retriveModulesDB(Request $request){
    $db = new DB();
    $user = auth()->user();
    if (!$user) {
        return redirect('/login');
    }
     // Search query
     $searchQuery = request()->query('search');
    $accessToken = $user->getAccessToken();
    $tasks = $db->retriveModules($request,$user, $accessToken);
     return $tasks;
   }

    public function getTasks(Request $request)
    { 
        $db = new DB();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $search = "";
        $dealId = request()->query('dealId');
        $contactId = request()->query('contactId');
            // Pass the search parameters to the retrieveTasks method
        $tasks = $db->retreiveTasksJson($user, $accessToken,$dealId,$contactId);
        return response()->json($tasks);
        // return view('pipeline.index', compact('deals'));
    }

    public function getDeals(Request $request)
    { 
        $db = new DB();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
         $dealId = request()->query('dealId');
         $contactId = request()->query('contactId');
        // Pass the search parameters to the retrieveTasks method
        $deals = $db->retreiveDealsJson($user, $accessToken,$dealId,$contactId);
        
        return response()->json($deals);
        // return view('pipeline.index', compact('deals'));
    }
    



    public function getDealsForDash()
    { 
        $db = new DB();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        // Pass the search parameters to the retrieveTasks method
        $deals = $db->retreiveDealsJson($user, $accessToken);
        
        return $deals;
        // return view('pipeline.index', compact('deals'));
    }

    public function getContacts(Request $request)
    { 
        $db = new DB();
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        // Pass the search parameters to the retrieveTasks method
        $dealId = request()->query('dealId');
        $contactId = request()->query('contactId');
        if($dealId){
            $contacts = $db->retrieveDealContactFordeal($user, $accessToken,$dealId);
        }else if($contactId){
            $contacts = $db->retrieveDealContactForContact($user, $accessToken,$contactId);
        }
        else{
        $contacts = $db->retreiveContactsJson($user, $accessToken);
        }
        
        return response()->json($contacts);
        // return view('pipeline.index', compact('deals'));
    }

    public function getStagesData(){
        $start_date =  request()->query('start_date') ?? ''; // Start date of the range
        $end_date = request()->query('end_date') ?? '';   // End date of the range
       
        $user = auth()->user();
        $db = new DB();
        $accessToken = $user->getAccessToken();
        if (!$user) {
            return redirect('/login');
        }
           // Set default goal or use user-defined goal
           $goal = $user->goal ?? 250000;
           Log::info("Goal: $goal");
   
           // Retrieve deals from Zoho CRM
           $deals = $db->retrieveDeals($user, $accessToken);
       $stages = ['Potential', 'Pre-Active', 'Active', 'Under Contract'];

        $stageData = collect($stages)->mapWithKeys(function ($stage) use ($deals, $goal, $start_date, $end_date) {
        $filteredDeals = $deals->filter(function ($deal) use ($stage, $start_date, $end_date) {
            $closingDate = Carbon::parse($deal['closing_date']);
            return $deal['stage'] === $stage && $this->masterFilter($deal) && $closingDate->gte($start_date) && $closingDate->lte($end_date);
        });
        $stageProgress = $this->calculateStageProgress($filteredDeals, $goal);
        $stageProgressCal = $this->calculateProgress($filteredDeals, $goal);
        $stageProgressClass = $stageProgress <= 15 ? "bg-danger" : ($stageProgress <= 45 ? "bg-warning" : "bg-success");
        $stageProgressIcon = $stageProgress <= 15 ? "mdi mdi-arrow-bottom-right" : ($stageProgress <= 45 ? "mdi mdi-arrow-top-right" : "mdi mdi-arrow-top-right");
        $stageProgressExpr = $stageProgress <= 15 ? "-" : ($stageProgress <= 45 ? "-" : "+");
        return [
            $stage => [
                'count' => $this->formatNumber($filteredDeals->count()),
                'sum' => $this->formatNumber($filteredDeals->sum('pipeline1')),
                'asum' => $filteredDeals->sum('pipeline1'),
                'stageProgress' => $stageProgress,
                "stageProgressClass" => $stageProgressClass,
                'stageProgressIcon' => $stageProgressIcon,
                'stageProgressExpr' => $stageProgressExpr,
                'stageProgressCal'  => $stageProgressCal,
            ],
        ];
        });
        Log::info("STAGE DATA: $stageData");
        $sums = [];
        foreach ($stageData as $segment => $data) {
            $sums[$segment] = $data['stageProgressCal'];
        }
        $totalSum = array_sum($sums);
        $stageData['calculateProgress'] = $totalSum;
        return response()->json($stageData);

    }

    public function deleteNote(Request $request,$id)
    {
            $user = auth()->user();
            if (!$user) {
                return redirect('/login');
            }
            $accessToken = $user->getAccessToken();
            // $jsonData = $request->json()->all();
            $zoho = new ZohoCRM();
            $zoho->access_token = $accessToken;
            try {
                // if (strpos($id, ',') === false) {
                //     $response = $zoho->deleteTask($jsonData,$id);
                //     if (!$response->successful()) {
                //          return "error somthing".$response;
                //     }
                //     $task = Task::where('zoho_task_id', $id)->first();
                //     if (!$task) {
                //         return "Task not found";
                //     }
                //     $task->delete();
                // } else {
                    // Multiple IDs provided
                    $response = $zoho->deleteNote($id);
                    if (!$response->successful()) {
                         return "error somthing".$response;
                    }
                    // $idArray = explode(',', $id);
                   $tasks = Note::where('zoho_note_id', $id)->delete();
                    if (!$tasks) {
                        return "Note not found";
                    }
                // }
                Log::info("Successful notes delete... ".$response);
                return $response;
    
                
            } catch (\Exception $e) {
                Log::error("Error creating notes: " . $e->getMessage());
                return "somthing went wrong". $e->getMessage();
            }

       
    }
    
    public function saveNote(Request $request)
    {
      Log::info("CHECK NOTES", $request->all());
        $contactId = $request->query('conID');
        $related_to_ = json_decode($request->input('related_to'), true);
        // Validate the incoming request data
        // $request->validate([
        //     'note_text' => 'required|string',
        //     'merged_data' => 'required|string'
        // ]);
        $mergedData = json_decode($request->input('merged_data'), true);
        $note_text = $request->input('note_text');
        $related_to;
        $related_to_parent;
        if(empty($mergedData)){
            $related_to = $related_to_['api_name'] ?? null;
            $related_to_parent = $request->input('related_to_parent');
            $moduleId = $related_to_['zoho_module_id'] ?? null;
        }else{

            $related_to = $mergedData['groupLabel'] ?? null;
            $whoid = $mergedData['whoid'] ?? null;
            $related_to_parent = $mergedData['relatedTo'] ?? null;
            $moduleId = $mergedData['moduleId'] ?? null;
            if($groupLabel==="Contacts"){
                $contactId = $relatedTo;
            }
            if($groupLabel==="Deals"){
                $dealId = $relatedTo;
            }

        }
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $db = new DB();
        $zoho->access_token = $accessToken;

        $jsonData = [
            "data" => [
                [
                    "Parent_Id" => [
                        "module" => [
                            "api_name" =>$related_to,
                            "id" => $moduleId,
                        ],
                        "id" => $related_to_parent,
                    ],
                    "Note_Content" => $note_text,
                ]
            ]
        ];
        $recordId = $related_to_parent;
        $apiName = $related_to;
        Log::info("CHECK nOTES",$jsonData,$recordId,$apiName);
        try {
        $response = $zoho->createNoteData($jsonData,$recordId,$apiName);

        if (!$response->successful()) {
            Log::error("Error creating notes:");
            return "error somthing".$response;
        }
        $data = json_decode($response, true);
        $zoho_node_id = $data['data'][0]['details']['id'];
        $deal = $db->retrieveDealByZohoId($user,$accessToken,$recordId);
        // dd($deal);
        $contact = $db->retrieveContactByZohoId($user,$accessToken,$recordId);
        // Create a new Note instance
        $note = new Note();
        // You may want to change 'deal_id' to 'id' or add a new column if you want to associate notes directly with deals.
        $note->related_to_module_id = $moduleId;
        $note->zoho_note_id = $zoho_node_id;
        $note->owner = $user->id;
        $note->related_to = $deal->id ?? $contact->id ?? null;
        $note->created_time = Carbon::now();
        $note->related_to_type = $apiName;
        $note->related_to_parent_record_id = $recordId;
        $note->note_content = $note_text;
        // Save the Note to the database
        $note->save();
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Note saved successfully!');
     } catch (\Exception $e) {
         Log::error("Error creating notes:new " . $e->getMessage());
         return redirect()->back()->with('error', 'Note Not saved successfully!');
            return "somthing went wrong".$e->getMessage();
        }
    }
    public function markAsDone(Request $request){
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
          // Retrieve the note ID from the request
            $noteId = $request->input('note_id');

            // Assuming you have a Note model
            $note = Note::find($noteId);

            // Update the note as done in the database
            $note->mark_as_done = 1;
            $note->save();
            return $note;
    }
    public function fetchNotes()
    {
        // Fetch notes from the database
        $notes = Note::all(); // Or you can use any query based on your requirement

        // Pass notes data to the Blade file
        return $notes;
    }
  //fetch specific note
    public function fetchNote(Request $request, $id)
    {

    $note = Note::find($id);

    // Check if the note exists
    if (!$note) {
        // If the note is not found, return a 404 response
        return response()->json(['error' => 'Note not found'], 404);
    }

    // Return the note details as a JSON response
    return response()->json($note);
    }


    public function updateNote(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'note_text' => 'required|string',
        ], [
            'note_text.required' => 'The Note field is required.',
        ]);
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        try {
        $jsonData =   [
                "data"=>[
                [
                        // "Note_Title" => "Contacted",
                        "Note_Content"=> $validatedData['note_text']
                ]
                ]
                ];
            $response = $zoho->updateNoteData($jsonData,$id);
            if (!$response->successful()) {
                Log::error("Error creating notes:");
                return "error somthing".$response;
            }
        // Find the Note instance by its ID
        $note = Note::where('zoho_note_id', $id)->firstOrFail();
        // Update the Note attributes
        // $note->related_to = $validatedData['related_to'];
        $note->note_content = $validatedData['note_text'];
        // Save the updated Note to the database
        $note->save();
        return redirect()->back()->with('success', 'Note Updated successfully!');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Note Not updated successfully!');
                Log::error("Error creating notes: " . $e->getMessage());
                return "somthing went wrong".$e->getMessage();
            }
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Note updated successfully!');
    }

     public function getTasksForDashboard(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $db = new DB();
        $zoho->access_token = $accessToken;
        try {
            $tab = request()->query('tab') ?? 'In Progress';
            $tasks = $db->retreiveTasks($user, $accessToken, $tab);
            Log::info("Task Details: " . print_r($tasks, true));
            return view('common.tasks', compact('tasks','tab'))->render();
        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            throw $e;
        }
    }

    public function getContactRole(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $db = new DB();
        $zoho->access_token = $accessToken;
        try {
            $contactRoles = $zoho->getContactRoles($user, $accessToken);
            $saveInDB = $db->storeRolesInDB($contactRoles->contact_roles);
            Log::info("contactRoles " . print_r($saveInDB, true));
            return response()->json($saveInDB, 201);
        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            throw $e;
        }
    }

}
