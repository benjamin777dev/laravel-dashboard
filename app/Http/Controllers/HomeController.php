<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;


class HomeController extends Controller
{ 
    private function masterFilter($deal) {
        $closingDate = Carbon::parse($deal['Closing_Date']);
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
    
        return $closingDate->gte($startOfYear) && $closingDate->lte($endOfYear);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
        $accessToken = $user->getAccessToken(); // Ensure we have a valid access token
        Log::info("Got Access Token: $accessToken"); 

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
        $stageData = collect($stages)->mapWithKeys(function ($stage) use ($deals) {
            $filteredDeals = $deals->filter(function ($deal) use ($stage) {
                return $deal['Stage'] === $stage && $this->masterFilter($deal);
            });
            return [
                $stage => [
                    'count' => $this->formatNumber($filteredDeals->count()),
                    'sum' => $this->formatNumber($filteredDeals->sum('Pipeline1')),
                    'asum' => $filteredDeals->sum('Pipeline1')
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
            'sum' => $this->formatNumber($needsNewDate->sum('Pipeline1')), 
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

        $tasks = $this->retreiveAndCheckTasks($user, $accessToken);
        Log::info("Task Details: " . print_r($tasks, true));

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

        Log::Info("ACI Data: " . print_r($aciData, true));

        // Pass data to the view
        return view(
            'home.index',
            compact(
                'deals',
                'progress',
                'goal',
                'progressClass',
                'progressTextColor',
                'stageData',
                'currentPipelineValue',
                'projectedIncome',
                'beyond12MonthsData',
                'needsNewDateData',
                'allMonths',
                'contactData',
                'newContactsLast30Days',
                'newDealsLast30Days',
                'averagePipelineProbability',
                'tasks',
                'aciData'
            )
        );
    }

    public function root()
    {
        return view('index');
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar = '/images/' . $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return response()->json([
                'isSuccess' => true,
                'Message' => "User Details Updated successfully!"
            ], 200); // Status code here
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return response()->json([
                'isSuccess' => true,
                'Message' => "Something went wrong!"
            ], 200); // Status code here
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }

    private function formatNumber($number)
    {
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

    private function retreiveAndCheckTasks(User $user, $accessToken)
    {
        $allTasks = collect();
        $page = 1;
        $hasMorePages = true;
        $error = '';

        $criteria = "(Owner:equals:$user->root_user_id)and(Status:not_equal:Completed)";
        Log::info("Retrieving tasks for criteria: $criteria");

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
        ])->get('https://www.zohoapis.com/crm/v6/Tasks/search', [
            'page' => $page,
            'per_page' => 200,
            'criteria' => $criteria,
        ]);

        if (!$response->successful()) {
            Log::error("Error retrieving deals: " . $response->body());
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
    }

    private function retrieveACIFromZoho(User $user, $accessToken)
    {
        $allACI = collect();
        $page = 1;
        $hasMorePages = true;

        $criteria = "(CHR_Agent:equals:$user->zoho_id)";
        $fields = "Closing_Date,Current_Year,Agent_Check_Amount,CHR_Agent,IRS_Reported_1099_Income_For_This_Transaction,Stage,Total";
        Log::info("Retrieving aci for criteria: $criteria");

        while ($hasMorePages) {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get('https://www.zohoapis.com/crm/v6/Agent_Commission_Incomes/search', [
                'page' => $page,
                'per_page' => 200,
                'criteria' => $criteria,
                'fields' => $fields,
            ]);

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

        Log::info("Total aci records: " . $allACI->count());
        Log::info("Aci Records: ", $allACI->toArray());
        return $allACI;
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

        while ($hasMorePages) {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get('https://www.zohoapis.com/crm/v6/Deals/search', [
                'page' => $page,
                'per_page' => 200,
                'criteria' => $criteria,
                'fields' => $fields,
            ]);

            if (!$response->successful()) {
                Log::error("Error retrieving deals: " . $response->body());
                // Handle unsuccessful response
                $hasMorePages = false;
                break;
            }

            Log::info("Response: " . $response->body());

            Log::info("Successful deal fetch... Page: " . $page);
            $responseData = $response->json();
            //Log::info("Response data: ". print_r($responseData, true));
            $deals = collect($responseData['data'] ?? []);
            $allDeals = $allDeals->concat($deals);

            $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'] >= 1;
            $page++;

        }
        
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
            'abcContacts' => $abcContacts, 
            'needsEmail' => $needsEmail, 
            'needsAddress' => $needsAddress, 
            'needsPhone' => $needsPhone, 
            'missingAbcd' => $missingAbcd, 
            'contactsLast30Days' => $contactsLast30Days
        ];
    }

    private function retrieveContactsFromZoho($rootUserId, $accessToken)
    {
        $allContacts = collect();
        $page = 1;
        $hasMorePages = true;

        $criteria = "(Owner:equals:$rootUserId)";
        Log::info("Retrieving contacts for criteria: $criteria");
        while ($hasMorePages) {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get('https://www.zohoapis.com/crm/v2/Contacts/search', [
                'page' => $page,
                'per_page' => 200,
                'criteria' => $criteria,
            ]);

            if (!$response->successful()) {
                Log::error("Error retrieving contacts: " . $response->body());
                $hasMorePages = false;
                break;
            } else {
                Log::info("Successful contact fetch... Page: " . $page);
            }

            $responseData = $response->json();
            $contacts = collect($responseData['data'] ?? []);
            $allContacts = $allContacts->concat($contacts);

            $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'];
            $page++;
        }
        Log::info("Retrieved contacts: " . $allContacts->count());

        return $allContacts;
    }
}
