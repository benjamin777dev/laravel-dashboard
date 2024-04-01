<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\ZohoCRM;
use App\Models\Note;
use App\Models\Deal;


class DashboardController extends Controller
{
    private function masterFilter($deal) {
        $closingDate = Carbon::parse($deal['Closing_Date']);
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
        $accessToken = $user->getAccessToken(); // Ensure we have a valid access token
        Log::info("Got Access Token: $accessToken");

        //Get Date Range
        $startDate = Carbon::now()->subDays(7)->format('d.m.Y'); // 7 days ago
        $endDate = Carbon::now()->format('d.m.Y'); // Current date

        // Set default goal or use user-defined goal
        $goal = $user->goal ?? 250000;
        Log::info("Goal: $goal");

        // Retrieve deals from Zoho CRM
        $deals = $this->retrieveDealsFromZoho($user, $accessToken);
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
                return $deal['Stage'] === $stage && $this->masterFilter($deal);
            });
            $stageProgress = $this->calculateStageProgress($filteredDeals, $goal);
            $stageProgressClass = $stageProgress <= 15 ? "bg-danger" : ($stageProgress <= 45 ? "bg-warning" : "bg-success");
            $stageProgressIcon = $stageProgress <= 15 ? "mdi mdi-arrow-bottom-right" : ($stageProgress <= 45 ? "mdi mdi-arrow-top-right" : "mdi mdi-arrow-top-right");
            $stageProgressExpr = $stageProgress <= 15 ? "-" : ($stageProgress <= 45 ? "-" : "+");
            Log::info("stageProgress: $stageProgress");
            return [
                $stage => [
                    'count' => $this->formatNumber($filteredDeals->count()),
                    'sum' => $this->formatNumber($filteredDeals->sum('Pipeline1')),
                    'asum' => $filteredDeals->sum('Pipeline1'),
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

        $beyond12Months = $deals->filter(function ($deal) {
            $closingDate = Carbon::parse($deal['Closing_Date']);
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
        $needsNewDate = $deals->filter(function ($deal) {
            return Carbon::parse($deal['Closing_Date'])->lt(now())
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

        $monthlyGCI = $filteredDeals->groupBy(function ($deal) {
            return Carbon::parse($deal['Closing_Date'])->format('Y-m');
        })->map(function ($dealsGroup) {
            return $dealsGroup->sum('Pipeline1');
        });

        $averagePipelineProbability = $deals->filter(function ($deal) {
            return $this->masterFilter($deal)
                   && !Str::startsWith($deal['Stage'], 'Dead')
                   && $deal['Stage'] !== 'Sold';
        })->avg('Pipeline_Probability');

        $newDealsLast30Days = $deals->filter(function ($deal) {
            return now()->diffInDays(Carbon::parse($deal['Created_Time'])) <= 30
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
        $tasks = $this->retreiveAndCheckTasks($user, $accessToken,$tab);
        Log::info("Task Details: ". print_r($tasks, true));

        // get cap information
        // initial cap comes from agent profile
        // residual cap comes from agent profile
        // cap paid ytd, comes from aci table
        // -- pull all aci records for current year and current agent
        // -- that are sold
        // -- and sum up the TOTAL column
        // total checks, like above, this comes from aci for the current year
        // -- pull all aci records for current year and current agent
        // -- that are sold
        // -- then sum up the agent check amount column
        // 1099 like above, this comes from aci for current year
        // -- pull all aci records for current year and current agent
        // -- that are sold
        // -- then sum up the IRS reported 1099 Income For This Transaction

        // so game plan is as follows
        // single API request for aci
        // single api request for contact information
        // then query the aci data for the necessary totals
        // then add necessary contact information to the array
        // include the array information in the frontend

        $aciInfo = $this->retrieveACIFromZoho($user, $accessToken);
         $notesInfo = $this->retrieveNOTESFromZoho($user,$accessToken);
         $getdealsTransaction = $this->retrieveDealTransactionData($user,$accessToken);
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
                'averagePipelineProbability', 'tasks', 'aciData','tab','getdealsTransaction','notes'));
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

    private function retreiveAndCheckTasks(User $user, $accessToken,$tab) {
        $allTasks = collect();
        $page = 1;
        $hasMorePages = true;
        $error = '';

        $criteria = "(Owner:equals:$user->root_user_id)and(Status:equals:$tab)";
        Log::info("Retrieving tasks for criteria: $criteria");

        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;

        try {
            $response = $zoho->getTasksData($criteria, 'Subject,Task Owner,Status,Due_Date,id,Who_Id', $page, 200);
            if (!$response->successful()) {
                Log::error("Error retrieving tasks: " . $response->body());
                // Handle unsuccessful response
                $hasMorePages = false;
                $error = $response->json();
            } else {
                Log::info("Successful task fetch... Page: " . $page);
                $responseData = $response->json();
                //Log::info("Response data: ". print_r($responseData, true));
                $tasks = collect($responseData['data'] ?? []);
                $allTasks = $allTasks->concat($tasks);
            }

            $currentPage = $page;
            $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
            if ($hasMorePages) {
                $page++;
            }

            return [
                'error' => $error?? '',
                'tasks' => $allTasks,
                'pagination' => [
                    'hasMorePages' => $hasMorePages,
                    'nextPage' => $page,
                    'currentPage' => $currentPage,
                ]
            ];
        } catch (\Exception $e) {
            Log::error("Error retrieving tasks: " . $e->getMessage());
            return [
                'error' => $e->getMessage(),
                'tasks' => $allTasks,
                'pagination' => [
                    'hasMorePages' => false,
                    'nextPage' => 1,
                    'currentPage' => 1,
                ]
            ];
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

        $criteria = "(CHR_Agent:equals:$user->zoho_id)";
        $fields = "Note_Title,Created_Time,Owner";
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

    private function retrieveDealsFromZoho(User $user, $accessToken)
    {
        $allDeals = collect();
        $page = 1;
        $hasMorePages = true;

        //((Created_By:equals:5141697000052483001)and(Stage:in:Potential,Pre-Active,Active,Under%20Contract,Sold))
        //and ((Stage:in:Potential,Pre-Active,Active,Under%20Contract,Sold)
        $criteria = "(Contact_Name:equals:$user->zoho_id)";
        $fields = "Address,Amount,City,Primary_Contact,Client_Name_Primary,Client_Name_Only,Closing_Date,Created_By,Created_Time,Commission,Contact_Name,Contract,Create_Date,Created_By,Double_Ended,Lender_Company,Lender_Company_Name,Lender_Name,Loan_Amount,Loan_Type,MLS_No,Needs_New_Date,Needs_New_Date1,Needs_New_Date2,Ownership_Type,Personal_Transaction,Pipeline_Probability,Potential_GCI,Primary_Contact_Email,Probability,Pipeline1,Probable_Volume,Property_Type,Representing,Sale_Price,Stage,State,TM_Name,TM_Preference,Deal_Name,Owner,Transaction_Type,Type,Under_Contract,Using_TM,Z_Project_Id,Zip";
        Log::info("Retrieving deals for criteria: $criteria");

        $zoho = new ZohoCRM();
        $zoho->access_token = $accessToken;

        try {
            while ($hasMorePages) {
                $response = $zoho->getDealsData($criteria, $fields, $page, 200);

                if (!$response->successful()) {
                    Log::error("Error retrieving deals: " . $response->body());
                    // Handle unsuccessful response
                    $hasMorePages = false;
                    break;
                }

                Log::info("Successful deal fetch... Page: " . $page);
                $responseData = $response->json();
                //Log::info("Response data: ". print_r($responseData, true));
                $deals = collect($responseData['data'] ?? []);
                $allDeals = $allDeals->concat($deals);

                $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
                $page++;
            }
        } catch (\Exception $e) {
            Log::error("Error retrieving deals: " . $e->getMessage());
            return $allDeals;
        }
        $this->storeDealsIntoDB($allDeals);
        return $allDeals;
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

    

    private function storeDealsIntoDB($dealsData) {
        Log::info("Deal Data For DB " . $dealsData);
        foreach ($dealsData as $deal) {
            // Check if the deal already exists
            // $existingDeal = Deal::where('zoho_deal_id', $deal['id'])->first();
            
            // if (!$existingDeal) {
                $user = User::where('zoho_id', $deal['Contact_Name']['id'])->first();
                // Deal doesn't exist, so insert it
                Deal::updateOrCreate(['zoho_deal_id' => $deal['id']],[
                    'personal_transaction'=> $deal['Personal_Transaction'],
                    'double_ended'=> $deal['Double_Ended'],
                    'userID'=> $user['id'],//In zoho Owner Details
                    'address'=> $deal['Address'],
                    'representing'=> $deal['Representing'],
                    'client_name_only'=> $deal['Client_Name_Only'],
                    'commission'=> $deal['Commission'],
                    'probable_volume'=> $deal['Probable_Volume'],
                    'lender_company'=> $deal['Lender_Company'],
                    'closing_date'=> $deal['Closing_Date'],
                    'ownership_type'=> $deal['Ownership_Type'],
                    'needs_new_date2'=> $deal['Needs_New_Date2'],
                    'deal_name'=> $deal['Deal_Name'],
                    'tm_preference'=> $deal['TM_Preference'],
                    'stage'=> $deal['Stage'],
                    'sale_price'=> $deal['Sale_Price'],
                    'zoho_deal_id'=> $deal['id'],
                    'pipeline1'=> $deal['Pipeline1'],
                    'pipeline_probability'=> $deal['Pipeline_Probability'],
                    'zoho_deal_createdTime'=> $deal['Created_Time'],
                    'property_type'=> $deal['Property_Type'],
                    'city'=> $deal['City'],
                    'state'=> $deal['State'],
                    'lender_company_name'=> $deal['Lender_Company_Name'],
                    'client_name_primary'=> $deal['Client_Name_Primary'],
                    'lender_name'=> $deal['Lender_Name'],
                    'potential_gci'=> $deal['Potential_GCI'],
                    'contractId'=> null,
                    'contactId'=>null
                ]);
            // } else {
            //     // Deal already exists, handle accordingly (log, skip, update, etc.)
            //     Log::info("Deal with ID {$deal['deal_id']} already exists in the database.");
            // }
        }
    }

    private function calculateStageProgress($deals, $goal)
    {
        // Sum the 'Pipeline1' values of the filtered deals.
        $totalGCI = $deals->sum('Pipeline1');
        Log::info("Total GCI from open stages: $totalGCI");

        // Calculate the progress as a percentage of the goal.
        $progress = ($totalGCI / $goal) * 100;
        Log::info("Progress towards goal: $progress");

        // Ensure progress does not exceed 100%.
        return round(min($progress, 100));
    }

    private function retrieveAndCheckContacts($rootUserId, $accessToken)
    {
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

        $contactsLast30Days = $allContacts->filter(function ($contact) {
            return now()->diffInDays($contact['Created_Time']) < 30;
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

    private function retrieveDealTransactionData(User $user, $accessToken)
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

        Log::info("Total notes records: ". $allDeals->count());
        Log::info("notes Records: ", $allDeals->toArray());
        return $allDeals;
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
    }

    public function fetchNotes()
    {
        // Fetch notes from the database
        $notes = Note::all(); // Or you can use any query based on your requirement
        
        // Pass notes data to the Blade file
        return $notes;
    }
    
    public function deleteNotes(Request $request)
    {
        $noteIds = $request->input('noteIds');
    
            // Perform deletion operation based on $noteIds
            Note::whereIn('id', $noteIds)->delete();
            
            return response()->json(['success' => true]);
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
