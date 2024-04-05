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
use App\Models\Task;
use App\Services\Helper;


class DashboardController extends Controller
{
    private function masterFilter($deal) {
        $helper = new Helper();
        $closingDate = Carbon::parse($helper->convertToMST($deal['closing_date']));
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
        $startDate = Carbon::now()->subDays(7)->format('d.m.Y'); // 7 days ago
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
        $progressClass = $progress <= 15 ? "bg-danger" : ($progress <= 45 ? "bg-warning" : "bg-success");
        $progressTextColor = $progress <= 15 ? "#fff" : ($progress <= 45 ? "#333" : "#fff");
        Log::info("Progress: $progress");

        // master filter will exclude anything that is beyond 12 months
        // anything that is a bad date (less than today)
        $stages = ['Potential', 'Pre-Active', 'Active', 'Under Contract'];
        $stageData = collect($stages)->mapWithKeys(function ($stage) use ($deals,$goal) {
            $filteredDeals = $deals->filter(function ($deal) use ($stage) {
                return $deal['stage'] === $stage && $this->masterFilter($deal);
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
            $closingDate = Carbon::parse($helper->convertToMST($deal['Closing_Date']));
            $endOfYear = Carbon::now()->endOfYear();
            return $closingDate->gt($endOfYear)
                && !Str::startsWith($deal['Stage'], 'Dead')
                && $deal['Stage'] !== 'Sold';
        });

        $beyond12MonthsData = [
            'sum' => $this->formatNumber($beyond12Months->sum('Pipeline1')),
            'count' => $beyond12Months->count(),
            'asum' => $beyond12Months->sum('Pipeline1')
        ];

        // Needs New Date
        $needsNewDate = $deals->filter(function ($deal) use ($helper) {
            return Carbon::parse($helper->convertToMST($deal['Closing_Date']))->lt(now())
                   && !Str::startsWith($deal['Stage'], 'Dead')
                   && $deal['Stage'] !== 'Sold';
        });

        $needsNewDateData = [
            'sum' =>$this->formatNumber($needsNewDate->sum('Pipeline1')),
            'asum' => $needsNewDate->sum('Pipeline1'),
            'count' => $needsNewDate->count(),
        ];

        $filteredDeals = $deals->filter(function ($deal) {
            return $this->masterFilter($deal)
                   && !Str::startsWith($deal['Stage'], 'Dead')
                   && $deal['Stage'] !== 'Sold';
        });

        $monthlyGCI = $filteredDeals->groupBy(function ($deal) use ($helper) {
            return Carbon::parse($helper->convertToMST($deal['Closing_Date']))->format('Y-m');
        })->map(function ($dealsGroup) {
            return $dealsGroup->sum('Pipeline1');
        });

        $averagePipelineProbability = $deals->filter(function ($deal) {
            return $this->masterFilter($deal)
                   && !Str::startsWith($deal['Stage'], 'Dead')
                   && $deal['Stage'] !== 'Sold';
        })->avg('Pipeline_Probability');

        $newDealsLast30Days = $deals->filter(function ($deal) use ($helper) {
            return now()->diffInDays(Carbon::parse($helper->convertToMST($deal['Created_Time']))) <= 30
                   && $this->masterFilter($deal)
                   && !Str::startsWith($deal['Stage'], 'Dead')
                   && $deal['Stage'] !== 'Sold';
        })->count();

        // Ensure all months of the year are represented, fill missing months with 0
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $allMonths = collect();
        while ($startOfYear->lessThanOrEqualTo($endOfYear)) {
            $month = $startOfYear->format('Y-m');
            $allMonths->put($month, $monthlyGCI->get($month, 0));
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
         $getdealsTransaction = $this->retrieveDealTransactionData($user,$accessToken);
         $retrieveModuleDataZoho = $this->retrieveModuleDataZoho($user,$accessToken);
         //fetch notes
         $notes = $this->fetchNotes();
        //  print("<pre/>");
        //  print($notes);
        //  die;
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
        // print("<pre>");
        // print_r($tasks);
        // die;
        // Pass data to the view
        return view('dashboard.index',
            compact('deals', 'progress', 'goal',
                'progressClass', 'progressTextColor',
                'stageData', 'currentPipelineValue',
                'projectedIncome', 'beyond12MonthsData',
                'needsNewDateData', 'allMonths', 'contactData',
                'newContactsLast30Days', 'newDealsLast30Days',
                'averagePipelineProbability', 'tasks', 'aciData','tab','getdealsTransaction','notes','startDate','endDate','user','notesInfo','closedDeals'));
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
                //Log::info("Response data: ". print_r($responseData, true));
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
                //Log::info("Response data: ". print_r($responseData, true));
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
            return !Str::startsWith($deal['Stage'], 'Dead')
                   && $deal['Stage'] !== 'Sold'
                   && $this->masterFilter($deal); // Correct usage within the method
        });

        // Sum the 'Pipeline1' values of the filtered deals.
        $totalGCI = $filteredDeals->sum('Pipeline1');
        Log::info("Total GCI from open stages: $totalGCI");

        // Calculate the progress as a percentage of the goal.
        $progress = ($totalGCI / $goal) * 100;
        Log::info("Progress towards goal: $progress");

        // Ensure progress does not exceed 100%.
        return min($progress, 100);
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
                //Log::info("Response data: ". print_r($responseData, true));
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
        $jsonData = $request->json()->all();
        $data = $jsonData['data'][0];

        // Access the 'Subject' field
        $subject = $data['Subject'];
        $whoid = $data['Who_Id']['id'];
        $status = $data['Status'];
        $Due_Date = $data['Due_Date'];
        


        $criteria = "(CHR_Agent:equals:$user->zoho_id)";
        // $fields = "Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
        Log::info("Retrieving notes for criteria: $criteria");
        // $response;
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;

        try {
                $response = $zoho->createTask($jsonData);


                if (!$response->successful()) {
                     return "error somthing".$response;
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
                ]);
        
                return response()->json($responseArray, 201);

                // $task->modified_by_name = $modifiedByName;
                // $task->modified_by_id = $modifiedById;
                return $data;
                Log::info("Successful notes create... ".$response);


        } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            return "somthing went wrong". $e->getMessage();
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
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        try {
                $response = $zoho->updateTask($jsonData,$id);
                if (!$response->successful()) {
                    return "error somthing".$response;
                }
                $task = Task::where('zoho_task_id', $id)->first();
                $requestData = json_decode($request->getContent(), true);
                $subject = $requestData['data'][0]['Subject'];
                if($task){
                    $task->subject = $subject;
                    $task->save();
                }

                Log::info("Successful notes update... ".$response);
                return $response;
            } catch (\Exception $e) {
                Log::error("Error creating notes: " . $e->getMessage());
                return "somthing went wrong".$e->getMessage();
            }

    }

    public function retrieveDealTransactionData(User $user, $accessToken)
    {
        $allDeals = collect();
        $page = 1;
        $hasMorePages = true;

        $criteria = "(CHR_Agent:equals:$user->zoho_id)";
        // $fields = "Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
        Log::info("Retrieving notes for criteria: $criteria");

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

                Log::info("Successful notes fetch... Page: " . $page);
                $responseData = $response->json();

                //Log::info("Response data: ". print_r($responseData, true));
                $allDealsdata = collect($responseData['data'] ?? []);
                $allDeals = $allDeals->concat($allDealsdata);

                $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                $page++;
            }
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            return $allDeals;
        }
        return $allDeals;
        echo "<pre>";
        // print_r($responseData);
        print_r($allDeals);
        die;

        Log::info("Total deals records: ". $allDeals->count());
        Log::info("deals Records: ", $allDeals->toArray());
        return $allDeals;
    }

    public function retrieveModuleDataZoho(User $user, $accessToken){
        
        $allModules = collect();

        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;
        try {
            while ($hasMorePages) {
                $response = $zoho->getModuleData();
                if (!$response->successful()) {
                    Log::error("Error retrieving notes: " . $response->body());
                    // Handle unsuccessful response
                    break;
                }

                Log::info("Successful module fetch... Page: " . $page);
                $responseData = $response->json();

                //Log::info("Response data: ". print_r($responseData, true));
                $allModulesdata = collect($responseData['data'] ?? []);
                $allModules = $allModules->concat($allModulesdata);
                print_r($allModules);
                die;
            }
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            return $allModules;
        }
        return $allModules;
        Log::info("Total deals records: ". $allModules->count());
        Log::info("deals Records: ", $allModules->toArray());
    }

    public function saveNote(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'related_to' => 'required|string|max:255',
            'note_text' => 'required|string',
        ], [
            'related_to.required' => 'The Related to field is required.',
            'note_text.required' => 'The Note field is required.',
        ]);
       
        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;

        $jsonData = [
            "data" => [
                [
                    "Parent_Id" => [
                        "module" => [
                            "api_name" => "Deals",
                            "id" => "2423488000000000125"
                        ],
                        "id" => "2423488000000771297"
                    ],
                    "Note_Content" => "note content after edit"
                ]
            ]
        ];

        try {
         $response = $zoho->createNoteData($jsonData);
        if (!$response->successful()) {
            return "error somthing".$response;
        }

        // Create a new Note instance
        $note = new Note();
        // You may want to change 'deal_id' to 'id' or add a new column if you want to associate notes directly with deals.
        $note->deal_id = $validatedData['deal_id'] ?? Str::random(10);
        $note->related_to = $validatedData['related_to'];
        $note->note_text = $validatedData['note_text'];

        // Save the Note to the database
        $note->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Note saved successfully!');
     } catch (\Exception $e) {
            Log::error("Error creating notes: " . $e->getMessage());
            return "somthing went wrong".$e->getMessage();
        }
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

    public function deleteNote($id)
    {
        echo "Hi Note Delete";
        // $note = Note::findOrFail($id);

        // // Delete the note
        // $note->delete();

        // // Redirect or respond with a success message
        // return redirect()->back()->with('success', 'Note deleted successfully');
    }

    public function updateNote(Request $request, $id)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'related_to' => 'required|string|max:255',
        'note_text' => 'required|string',
    ], [
        'related_to.required' => 'The Related to field is required.',
        'note_text.required' => 'The Note field is required.',
    ]);

    // Find the Note instance by its ID
    $note = Note::findOrFail($id);

    // Update the Note attributes
    $note->related_to = $validatedData['related_to'];
    $note->note_text = $validatedData['note_text'];

    // Save the updated Note to the database
    $note->save();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Note updated successfully!');
}

}
