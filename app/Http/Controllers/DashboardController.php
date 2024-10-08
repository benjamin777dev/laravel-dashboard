<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Note;
use App\Models\Task;
use App\Models\User;
use App\Models\Deal;
use App\Services\DatabaseService;
use App\Services\Helper;
use App\Services\ZohoCRM;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use DataTables;

class DashboardController extends Controller
{
    protected $db;
    protected $helper;

    public function __construct(DatabaseService $db, Helper $helper)
    {
        $this->db = $db;
        $this->helper = $helper;
    }


    private function masterFilter($deal)
    {
        $closingDate = Carbon::parse($this->helper->convertToMST($deal['closing_date'] ?? null));
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addYear();

        return $closingDate->between($startDate, $endDate);
    }

    public function index()
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
 
        $accessToken = $user->getAccessToken();
        $cRecord = Contact::where('zoho_contact_id', $user->zoho_id)->first();
        $goal = $cRecord->income_goal ?? 250000;
        $deals = $this->db->retrieveDeals($user, null, null, null, null, null, true);

        $progress = $this->calculateProgress($deals, $goal);
        $totalGciForDah = $this->totalGCIForDash($deals);
        Log::info("Progress: $progress");

        $stageData = $this->getStageData($deals, $goal);
        $needsNewDate = $this->getNeedsNewDateData($deals);
        $allMonths = $this->getMonthlyGCI($deals);

        $tab = request()->query('tab') ?? 'In Progress';
        $retrieveModuleData = $this->db->retrieveModuleDataDB($user, $accessToken);
        $tasks = $this->db->retreiveTasks($user, $accessToken, $tab);
        $upcomingTasks = $this->db->retreiveTasks($user, $accessToken, 'Upcoming');
         
        $notesInfo = $this->db->retrieveNotes($user, $accessToken,5);
        $notes = $this->fetchNotes();
        $userContact = $this->db->retrieveContactDetailsByZohoId($user, $accessToken, $user->zoho_id);


        if (request()->ajax()) {
            return view('common.tasks', compact('tasks', 'retrieveModuleData', 'tab'))->render();
        }

        return view('dashboard.index', compact(
            'progress', 'goal', 'stageData', 'needsNewDate', 'allMonths', 
            'tasks', 'tab', 'notes', 'notesInfo', 'retrieveModuleData', 
            'totalGciForDah', 'userContact', 'upcomingTasks'
        ));
    }

    public function getStageForDashboard()
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
 
        $accessToken = $user->getAccessToken();
        $cRecord = Contact::where('zoho_contact_id', $user->zoho_id)->first();
        $goal = $cRecord->income_goal ?? 250000;
        $deals = $this->db->retrieveDeals($user, null, null, null, null, null, true);
        $stageData = $this->getStageData($deals, $goal);
        return view('components.dash-cards', compact('stageData'))->render();
    }

    private function getStageData($deals, $goal)
    {
        // Define the stages to process
        $stages = ['Potential', 'Pre-Active', 'Active', 'Under Contract'];
        // Define the date range for filtering deals (current 12-month period)
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addYear();

        // Process each stage and calculate metrics
        return collect($stages)->mapWithKeys(function ($stage) use ($deals, $goal, $startDate, $endDate) {
            // Filter deals for the current stage and date range
            $filteredDeals = $deals->filter(function ($deal) use ($stage, $startDate, $endDate) {
                $closingDate = Carbon::parse($deal['closing_date']);
                return $deal['stage'] === $stage 
                    && $closingDate->between($startDate, $endDate)
                    && !Str::startsWith($deal['stage'], 'Dead') 
                    && $deal['stage'] !== 'Sold';
            });

            // Calculate stage progress and related metrics
            $stageProgress = $this->calculateStageProgress($filteredDeals, $goal);
            $stageProgressClass = $this->getProgressClass($stageProgress);
            $stageProgressIcon = $this->getProgressIcon($stageProgress);
            $stageProgressExpr = $this->getProgressExpr($stageProgress);

            // Return metrics for the current stage
            return [
                $stage => [
                    'count' => $this->formatNumber($filteredDeals->count()),
                    'sum' => $this->formatNumber($filteredDeals->sum('pipeline1')),
                    'asum' => $filteredDeals->sum('pipeline1'),
                    'stageProgress' => $stageProgress,
                    'stageProgressClass' => $stageProgressClass,
                    'stageProgressIcon' => $stageProgressIcon,
                    'stageProgressExpr' => $stageProgressExpr,
                ],
            ];
        });
    }

    private function getProgressClass($stageProgress)
    {
        return $stageProgress <= 15 ? "bg-danger" : ($stageProgress <= 45 ? "bg-warning" : "bg-success");
    }

    private function getProgressIcon($stageProgress)
    {
        return $stageProgress <= 15 ? "mdi mdi-arrow-bottom-right" : "mdi mdi-arrow-top-right";
    }

    private function getProgressExpr($stageProgress)
    {
        return $stageProgress <= 15 ? "-" : "+";
    }

    private function getNeedsNewDateData($deals)
    {
        $endDate30Days = Carbon::now()->addMonth();

        $needsNewDate = $deals->filter(function ($deal) use ($endDate30Days) {
            $closingDate = Carbon::parse($this->helper->convertToMST($deal['closing_date']));
            $now = now();

            return ($closingDate->lt($now) || $closingDate->between($now, $endDate30Days))
                && !Str::startsWith($deal['stage'], 'Dead')
                && $deal['stage'] !== 'Sold'
                && $deal['stage'] !== "Under Contract";
        });

        return [
            'sum' => $this->formatNumber($needsNewDate->sum('pipeline1')),
            'asum' => $needsNewDate->sum('pipeline1'),
            'count' => $needsNewDate->count(),
            'deals' => $needsNewDate
        ];
    }

    private function getMonthlyGCI($deals)
    {
        // Filter deals to exclude 'Dead' and 'Sold' stages and ensure they fall within the next 12-month period
        $filteredDeals = $deals;
        // Group deals by month and calculate the total GCI for each month
        $monthlyGCI = $filteredDeals->groupBy(function ($deal) {
            return Carbon::parse($this->helper->convertToMST($deal['closing_date']))->format('Y-m');
        })->map(function ($dealsGroup) {
            return $dealsGroup->sum('pipeline1');
        });

        // Initialize the start and end of the rolling 12 months period
        $currentMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->addYear()->endOfMonth();
        $allMonths = [];

        // Iterate through each month of the rolling 12-month period
        while ($currentMonth->lessThanOrEqualTo($endMonth)) {
            $month = $currentMonth->format('Y-m');
            $gci = $monthlyGCI->get($month, 0);

            // Get the count of deals for the current month
            $dealCount = $filteredDeals->filter(function ($deal) use ($month) {
                return Carbon::parse($this->helper->convertToMST($deal['closing_date']))->format('Y-m') === $month;
            })->count();

            // Store the GCI and deal count for the current month
            $allMonths[$month] = [
                'gci' => $gci,
                'deal_count' => $dealCount,
            ];

            // Move to the next month
            $currentMonth->addMonth();
        }

        return $allMonths;
    }

    private function formatNumber($number)
    {
        if ($number < 1000) {
            return (string) $number; // Less than 1,000
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

        Log::info("Total aci records: " . $allACI->count());
        Log::info("Aci Records: ", $allACI->toArray());
        return $allACI;
    }

    public function needNewDateMethod()
    {
        $db = new DatabaseService();
        $helper = new Helper();
        $user = $this->user();
        $accessToken = $user->getAccessToken(); // Ensure we have a valid access token
        if (!$user) {
            return redirect('/login');
        }
        //Get Date Range
        $endDate30Days = Carbon::now()->addMonth()->format('d.m.Y'); // 30 days
        $deals = $db->retrieveDeals($user, null, null, null, null, null, true);
        // Needs New Date
        $needsNewDate = $deals->filter(function ($deal) use ($helper, $endDate30Days) {
            $closingDate = Carbon::parse($helper->convertToMST($deal['closing_date']));
            $now = now();
        
            // Debugging: Print the deal details and dates
            Log::info('Checking deal:', [
                'deal_id' => $deal['id'],
                'closing_date' => $deal['closing_date'],
                'converted_closing_date' => $closingDate,
                'now' => $now,
                'end_date_30_days' => $endDate30Days,
                'stage' => $deal['stage']
            ]);
        
            $needsNewDate = (
                ($closingDate->lt($now) || $closingDate->between($now, $endDate30Days))
                && !Str::startsWith($deal['stage'], 'Dead')
                && $deal['stage'] !== 'Sold'
                && $deal['stage'] !== "Under Contract"
            );
        
            // Debugging: Print if the deal needs a new date
            if ($needsNewDate) {
                Log::info('Deal needs new date:', ['deal_id' => $deal['id']]);
            }
        
            return $needsNewDate;
        });
        return Datatables::of($needsNewDate)->make(true);
        
    }

    public function retriveTaskforDatatable()
    {
        $db = new DatabaseService();
        $helper = new Helper();
        $user = $this->user();
        $accessToken = $user->getAccessToken(); // Ensure we have a valid access token
        if (!$user) {
            return redirect('/login');
        }
        $tab = request()->query('tab') ?? 'In Progress';
        $tasks = $db->retreiveTasksJson($user, $accessToken, $tab);
        return Datatables::of($tasks)->make(true);
        
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

        try {
            while ($hasMorePages) {
                $response = $zoho->getNotesData($criteria, $fields, $page, 200);
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

        Log::info("Total notes records: " . $allNotes->count());
        Log::info("notes Records: ", $allNotes->toArray());
        return $allNotes;
    }

    private function calculateProgress($deals, $goal)
    {
        $filteredDeals = $deals->filter(function ($deal) {
            return !Str::startsWith($deal['stage'], 'Dead')
                && $deal['stage'] !== 'Sold'
                && $this->masterFilter($deal);
        });

        $totalGCI = $filteredDeals->sum('pipeline1');
        Log::info("Total GCI from open stages: $totalGCI");

        $progress = ($totalGCI / $goal) * 100;
        Log::info("Progress towards goal: $progress");

        return min($progress, 100);
    }

    private function totalGCIForDash($deals)
    {
        $filteredDeals = $deals;
        $totalProbableGCI = 0;
        foreach ($filteredDeals as $deal) {
            $salePrice = $deal->sale_price ?? 0;
            $commission = $deal->commission ?? 0;
            $pipelineProbability = $deal->pipeline_probability ?? 0;
            $totalProbableGCI += ($salePrice * ($commission / 100)) * ($pipelineProbability / 100);
        }

        return $totalProbableGCI;
    }

    private function calculateStageProgress($deals, $goal)
    {
        $totalGCI = $deals->sum('pipeline1');
        Log::info("Total GCI from open stages: $totalGCI");

        $progress = ($totalGCI / $goal) * 100;
        Log::info("Progress towards goal: $progress");

        return round(min($progress, 100));
    }

    public function createTaskaction(Request $request, User $user)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $zoho = new ZohoCRM();
        $jsonData = $request->json()->all();
        $data = $jsonData['data'][0];
         
        $subject = null;
        // $whoid = null;
        $status = null;
        $Due_Date = null;
        // $What_Id = null;
        // $priority = null;
        $contact = null;
        $seModule = null;
        // Access the 'Subject' field
        if (!empty($data['Subject'])) {
            $subject = $data['Subject'] ?? null;
        }
        if (!empty($data['Detail'])) {
            $detail = $data['Detail'] ?? null;

        }
        if (!empty($data['Who_Id']['id'])) {
            $whoid = $data['Who_Id']['id'] ?? null;
            $contact = Contact::where('zoho_contact_id', $data['Who_Id']['id'])->firstOrFail();
            $seModule = "Contacts";
        }
        if (!empty($data['Status'])) {
            $status = $data['Status'] ?? null;
        }
        if (!empty($data['Due_Date'])) {
            $Due_Date = $data['Due_Date'] ?? null;

        }
        if (!empty($data['What_Id']['id'])) {
            $What_Id = $data['What_Id']['id'] ?? null;
            $deal = Deal::where('zoho_deal_id', $data['What_Id']['id'])->firstOrFail();
            $seModule = "Deals";

        }
        if (!empty($data['Priority'])) {
            $What_Id = $data['What_Id']['id'] ?? null;
            $priority = $data['Priority'] ?? null;

        }

        $created_time = Carbon::now();
        // $closed_time = $data['Closed_Time'] ?? null;
        // $related_to = $data['$se_module'] ?? null;

        $criteria = "(CHR_Agent:equals:$user->zoho_id)";
        // $fields = "Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
        Log::info("Retrieving notes for criteria: $criteria");
        // $response;

        try {
            $response = $zoho->createTask($jsonData);

            if (!$response->successful()) {
                response()->json(['message' => "failed, not found"], 404);
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
                'detail' => $detail ?? null,
                'zoho_task_id' => $zoho_id,
                'owner' => "1",
                'status' => $status ?? null,
                'who_id' => $contact['id'] ?? null,
                'due_date' => $Due_Date ?? null,
                'what_id' => $deal['id'] ?? null,
                // 'closed_time' => $closed_time ?? null,
                'created_by' => $user->id,
                // 'priority' => $priority ?? null,
                'created_time' => $created_time ?? null,
                'related_to' => $seModule ?? null,
            ]);
            Log::info("Successful notes create... " . $task);
            return response()->json($responseArray, 201);

            // $task->modified_by_name = $modifiedByName;
            // $task->modified_by_id = $modifiedById;
            // return $data;

        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function deleteTaskaction(Request $request, User $user, $id)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $jsonData = $request->json()->all();
        $zoho = new ZohoCRM();
        try {
            if (strpos($id, ',') === false) {
                $response = $zoho->deleteTask($jsonData, $id);
                if (!$response->successful()) {
                    return response()->json(['error' => "Not found in Zoho crm"], 401);
                }
                $task = Task::where('zoho_task_id', $id)->first();
                if (!$task) {
                    return "Task not found";
                }
                $task->delete();
            } else {
                // Multiple IDs provided
                $response = $zoho->deleteTaskSelected($jsonData, $id);
                if (!$response->successful()) {
                    return response()->json(['error' => "Not found in Zoho crm"], 401);
                }
                $idArray = explode(',', $id);
                $tasks = Task::whereIn('zoho_task_id', $idArray)->delete();
                if (!$tasks) {
                    return "Task not found";
                }
            }
            Log::info("Successful notes delete... " . $response);
            return $response;

        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            return "somthing went wrong" . $e->getMessage();
        }
    }
    public function updateTaskaction(Request $request, User $user, $id)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $jsonData = $request->json()->all();

        Log::info("JSON TASK INPUT: " . json_encode($jsonData));
        $zoho = new ZohoCRM();
        $task = Task::where('zoho_task_id', $id)->first() ?? Task::where('id', $id)->first();

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        try {
            $response = $zoho->updateTask($jsonData, $task['zoho_task_id']);
            if (!$response->successful()) {
                $error = $response->json();
                Log::error('zoho_task_update_error', $error);
                throw new \Exception($error['message']);
            }

            $data = $jsonData['data'][0];
            $task->due_date = $data['Due_Date'] ?? $task->due_date;
            $task->subject = $data['Subject'] ?? $task->subject;
            $task->what_id = $data['What_Id']['id'] ?? $task->what_id;
            $task->related_to = $data['$se_module'] ?? $task->related_to;
            $task->who_id = $data['Who_Id']['id'] ?? $task->who_id;
            $task->status = $data['Status'] ?? $task->status;
            $task->save();

            Log::info("Successful task update... ", ['response' => $response->json()]);
            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error("Error updating task: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
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

        Log::info("Total deals records: " . $allDeals->count());
        Log::info("deals Records: ", $allDeals->toArray());
        return $allDeals;
    }

    public function retriveModulesDB(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        // Search query
        $searchQuery = request()->query('search');
        $accessToken = $user->getAccessToken();
        $tasks = $db->retriveModules($request, $user, $accessToken);
        return $tasks;
    }

    public function getTasks(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $search = "";
        $dealId = request()->query('dealId');
        $contactId = request()->query('contactId');
        // Pass the search parameters to the retrieveTasks method
        $tasks = $db->retreiveTasksJson($user, $accessToken, $dealId, $contactId);
        return response()->json($tasks);
        // return view('pipeline.index', compact('deals'));
    }

    public function getDeals(Request $request)
    {
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $dealId = request()->query('dealId');
        $contactId = request()->query('contactId');
        // Pass the search parameters to the retrieveTasks method
        $deals = $db->retreiveDealsJson($user, $accessToken, $dealId, $contactId);

        return response()->json($deals);
        // return view('pipeline.index', compact('deals'));
    }

    public function getDealsForDash()
    {
        $db = new DatabaseService();
        $user = $this->user();
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
        $db = new DatabaseService();
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        // Pass the search parameters to the retrieveTasks method
        $dealId = request()->query('dealId');
        $contactId = request()->query('contactId');
        if ($dealId) {
            $contacts = $db->retrieveDealContactFordeal($user, $accessToken, $dealId);
        } else if ($contactId) {
            $contacts = $db->retrieveDealContactForContact($user, $accessToken, $contactId);
        } else {
            $contacts = $db->retreiveContactsJson($user, $accessToken);
        }

        return response()->json($contacts);
        // return view('pipeline.index', compact('deals'));
    }

    public function getStagesData()
    {
        $start_date = request()->query('start_date') ?? ''; // Start date of the range
        $end_date = request()->query('end_date') ?? ''; // End date of the range

        $user = $this->user();
        $db = new DatabaseService();
        $accessToken = $user->getAccessToken();
        if (!$user) {
            return redirect('/login');
        }
        // Set default goal or use user-defined goal
        $goal = $user->goal ?? 250000;
        Log::info("Goal: $goal");

        // Retrieve deals from Zoho CRM
        $deals = $db->retrieveDeals($user);
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
                    'stageProgressCal' => $stageProgressCal,
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

    public function deleteNote(Request $request, $id)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        // $jsonData = $request->json()->all();
        $zoho = new ZohoCRM();
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
                return "error somthing" . $response;
            }
            // $idArray = explode(',', $id);
            $tasks = Note::where('zoho_note_id', $id)->delete();
            if (!$tasks) {
                return "Note not found";
            }
            // }
            Log::info("Successful notes delete... " . $response);
            return $response;

        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            return "somthing went wrong" . $e->getMessage();
        }

    }

    public function saveNote(Request $request)
    {
        Log::info("CHECK NOTES", $request->all());
        $contactId = $request->query('conID');
        $related_to_ = json_decode($request->input('related_to'), true);

        $mergedData = json_decode($request->input('merged_data'), true);
        $note_text = $request->input('note_text');
        $related_to = "";
        $related_to_parent = "";
        if (empty($mergedData)) {
            $related_to = $related_to_['api_name'] ?? null;
            $related_to_parent = $request->input('related_to_parent');
            $moduleId = $related_to_['zoho_module_id'] ?? null;
        } else {
            $related_to = $mergedData['groupLabel'] ?? null;
            $related_to_parent = $mergedData['relatedTo'] ?? null;
            $moduleId = $mergedData['moduleId'] ?? null;
        }
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $db = new DatabaseService();

        $jsonData = [
            "data" => [
                [
                    "Parent_Id" => [
                        "module" => [
                            "api_name" => $related_to,
                            "id" => $moduleId,
                        ],
                        "id" => $related_to_parent,
                    ],
                    "Note_Content" => $note_text,
                    '$se_module' => $related_to,
                ],
            ],
        ];
        $recordId = $related_to_parent;
        $apiName = $related_to;
        Log::info("CHECK NOTES", $jsonData, $recordId, $apiName);
        try {
            $response = $zoho->createNoteData($jsonData, $recordId, $apiName);
            if (!$response->successful()) {
                Log::error("Error creating notes:");
                return "error something" . $response;
            }
            $data = json_decode($response, true);
            $zoho_node_id = $data['data'][0]['details']['id'];
            $deal = $db->retrieveDealByZohoId($user, $accessToken, $recordId);
            $contact = $db->retrieveContactByZohoId($user, $accessToken, $recordId);
            $note = new Note();
            $note->related_to_module_id = $moduleId;
            $note->zoho_note_id = $zoho_node_id;
            $note->owner = $user->id;
            $note->related_to = $deal->id ?? $contact->id ?? null;
            $note->created_time = Carbon::now();
            $note->related_to_type = $apiName;
            $note->related_to_parent_record_id = $recordId;
            $note->note_content = $note_text;
            $note->save();
            return response()->json(['success' => true, 'note' => $note], 200);
        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function markAsDone(Request $request)
    {
        $user = $this->user();
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
        $notes = Note::latest()->take(10)->get();

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
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        try {
            $jsonData = [
                "data" => [
                    [
                        // "Note_Title" => "Contacted",
                        "Note_Content" => $validatedData['note_text'],
                    ],
                ],
            ];
            $response = $zoho->updateNoteData($jsonData, $id);
            if (!$response->successful()) {
                Log::error("Error creating notes:");
                return "error somthing" . $response;
            }
            // Find the Note instance by its ID
            $note = Note::where('zoho_note_id', $id)->firstOrFail();
            // Update the Note attributes
            // $note->related_to = $validatedData['related_to'];
            $note->note_content = $validatedData['note_text'];
            // Save the updated Note to the database
            $note->save();
            return redirect()->back()->with('success', 'Note Updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Note Not updated successfully!');
            Log::error("Error creating notes: " . $e->getMessage());
            return "somthing went wrong" . $e->getMessage();
        }
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Note updated successfully!');
    }

    public function getTasksForDashboard(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $db = new DatabaseService();
        try {
            $tab = request()->query('tab') ?? 'In Progress';
            $tasks = $db->retreiveTasks($user, $accessToken, $tab);
            return view('common.tasks', compact('tasks', 'tab'))->render();
        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            throw $e;
        }
    }

    public function getContactRole(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $db = new DatabaseService();
        try {
            $contactRoles = $zoho->getContactRoles($user, $accessToken);
            $saveInDB = $db->storeRolesIntoDB($contactRoles, $this->user());
            Log::info("contactRoles " . print_r($saveInDB, true));
            return response()->json($saveInDB, 201);
        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            throw $e;
        }
    }

    public function showNotes(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken();
        $zoho = new ZohoCRM();
        $db = new DatabaseService();
        try {
            $notesInfo = $this->db->retrieveNotes($user, $accessToken, '');
            $notes = $this->fetchNotes();
            Log::info("notesInfo " . print_r($notesInfo, true));
            return view('common.notes.show-more-notes', compact('notesInfo', 'notes'));
        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            throw $e;
        }
    }

}
