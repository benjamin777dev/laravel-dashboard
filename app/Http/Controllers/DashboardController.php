<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
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

        // Group deals by stage and calculate counts and sums
        $stages = ['Potential', 'Pre-Active', 'Active', 'Sold'];
        $stageData = collect($stages)->mapWithKeys(function ($stage) use ($deals) {
            $filteredDeals = $deals->where('Stage', $stage);
            return [
                $stage => [
                    'count' => $filteredDeals->count(),
                    'sum' => $filteredDeals->sum('Pipeline1'),
                ],
            ];
        });

        // Calculate Current Pipeline Value
        $currentPipelineValue = $stageData->sum(function ($stage) {
            return $stage['sum'];
        });

        // Calculate Projected Income
        $projectedIncome = $currentPipelineValue * 2;

        // Beyond 12 Months
        $beyond12Months = $deals->filter(function ($deal) {
            return now()->diffInMonths($deal['Closing_Date']) > 12;
        });
        $beyond12MonthsData = [
            'sum' => $beyond12Months->sum('Pipeline1'),
            'count' => $beyond12Months->count(),
        ];

        // Needs New Date
        $needsNewDate = $deals->filter(function ($deal) {
            return $deal['Closing_Date'] < now();
        });
        $needsNewDateData = [
            'sum' => $needsNewDate->sum('Pipeline1'),
            'count' => $needsNewDate->count(),
        ];

        // Prepare monthly GCI data
        $monthlyGCI = $deals->groupBy(function ($deal) {
            return Carbon::parse($deal['Closing_Date'])->format('Y-m');
        })->map(function ($deals) {
            return $deals->sum('Pipeline1');
        });

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

        // Pass data to the view
        return view('dashboard.index',
            compact('deals', 'progress', 'goal',
                'progressClass', 'progressTextColor',
                'stageData', 'currentPipelineValue',
                'projectedIncome', 'beyond12MonthsData',
                'needsNewDateData', 'allMonths'));

    }

    private function retrieveDealsFromZoho(User $user, $accessToken)
    {
        $allDeals = collect();
        $page = 1;
        $hasMorePages = true;

        //((Created_By:equals:5141697000052483001)and(Stage:in:Potential,Pre-Active,Active,Under%20Contract,Sold))
        //and ((Stage:in:Potential,Pre-Active,Active,Under%20Contract,Sold)
        $criteria = "(Contact_Name:equals:$user->zoho_id)";
        $fields = "Address,Amount,City,Primary_Contact,Client_Name_Primary,Client_Name_Only,Closing_Date,Commission,Contact_Name,Contract,Create_Date,Created_By,Double_Ended,Lender_Company,Lender_Company_Name,Lender_Name,Loan_Amount,Loan_Type,MLS_No,Needs_New_Date,Needs_New_Date1,Needs_New_Date2,Ownership_Type,Personal_Transaction,Pipeline_Probability,Potential_GCI,Primary_Contact_Email,Probability,Pipeline1,Probable_Volume,Property_Type,Representing,Sale_Price,Stage,State,TM_Name,TM_Preference,Deal_Name,Owner,Transaction_Type,Type,Under_Contract,Using_TM,Z_Project_Id,Zip";
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
        $totalGCI = $deals->sum('Pipeline1');
        Log::info("Total GCI: $totalGCI");
        $progress = ($totalGCI / $goal) * 100;
        Log::info("Progress: $progress");
        return min($progress, 100);
    }

    private function retrieveAndCheckContacts($rootUserId, $accessToken)
    {
        $allContacts = $this->retrieveContactsFromZoho($rootUserId, $accessToken);

        $abcContacts = $allContacts->filter(function ($contact) {
            return !empty($contact['ABCD']);
        })->count();

        $needsEmail = $allContacts->filter(function ($contact) {
            return empty($contact['Email']);
        })->count();

        $needsAddress = $allContacts->filter(function ($contact) {
            return empty($contact['Mailing_Address']) || empty($contact['Mailing_City']) || empty($contact['Mailing_State']) || empty($contact['Mailing_Zip']);
        })->count();

        $needsPhone = $allContacts->filter(function ($contact) {
            return empty($contact['Phone']);
        })->count();

        $missingAbcd = $allContacts->filter(function ($contact) {
            return empty($contact['ABCD']);
        })->count();

        return compact('abcContacts', 'needsEmail', 'needsAddress', 'needsPhone', 'missingAbcd');
    }

    private function retrieveContactsFromZoho($rootUserId, $accessToken)
    {
        $allContacts = collect();
        $page = 1;
        $hasMorePages = true;

        $criteria = "(Owner:equals:$rootUserId)";

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
            }

            $responseData = $response->json();
            $contacts = collect($responseData['data'] ?? []);
            $allContacts = $allContacts->concat($contacts);

            $hasMorePages = isset($responseData['info'], $responseData['info']['more_records']) && $responseData['info']['more_records'];
            $page++;
        }

        return $allContacts;
    }

}
