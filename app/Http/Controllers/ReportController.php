<?php
namespace App\Http\Controllers;

use App\Models\Aci;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{

    public function productionProjections(Request $request)
    {
        //Log::info("At production projection report");

        $selectedYear = $request->input('year', Carbon::now()->year);
        $currentYear = Carbon::Now()->year;

        //Log::info("--Selected Year: $selectedYear & Current Year: $currentYear");

        // Get available years from both Deal and Aci models
        $availableYears = $this->getAvailableYears();

        // Fetch sold and UC (Under Contract) deals
        $deals = $this->getDealsByYearAndStage($selectedYear);

        // Filter out deals that don't have a valid contactName
        $dealsWithContact = $deals->filter(function ($deal) {
            return $deal->contactName !== null;
        });

        if ($deals->count() !== $dealsWithContact->count()) {
            //Log::warning($deals->count() - $dealsWithContact->count() . " deals are missing contactName and were skipped.");
        }

        // Group deals by agent and process them
        $reportData = $this->processAgentDeals($dealsWithContact);

        // Initialize totals
        $totalSoldTransactions = 0;
        $totalUCTransactions = 0;
        $totalSoldCheckAmount = 0;
        $totalUnderContractCheckAmount = 0;
        $totalSoldCHRSplit = 0;
        $totalUnderContractCHRSplit = 0;
        $totalUCInitialSplitCount = 0;
        $totalUCResidualSplitCount = 0;

        // Iterate over the processed reportData to calculate totals
        foreach ($reportData as $agentData) {
            // Sold Transactions
            $totalSoldTransactions += $agentData['Sold']['count'];
            $totalSoldCheckAmount += $agentData['Sold']['agent_check_amount'];
            $totalSoldCHRSplit += $agentData['Sold']['chr_split'];

            // Under Contract Transactions
            $totalUCTransactions += $agentData['Under Contract']['count'];
            $totalUnderContractCheckAmount += $agentData['Under Contract']['projected_agent_earnings'];
            $totalUnderContractCHRSplit += $agentData['Under Contract']['projected_chr_split'];

            // Split counts for Under Contract transactions
            foreach ($agentData['Under Contract']['info'] as $dealInfo) {
                if ($dealInfo['initialCHRSplit'] > 0) {
                    $totalUCInitialSplitCount++;
                }
                if ($dealInfo['residualCHRSplit'] > 0) {
                    $totalUCResidualSplitCount++;
                }
            }
        }

        // Calculate percentage splits for Under Contract transactions
        $totalUCSplitTransactions = $totalUCInitialSplitCount + $totalUCResidualSplitCount;
        $ucInitialTransactionPercentage = $totalUCSplitTransactions > 0 ? ($totalUCInitialSplitCount / $totalUCSplitTransactions) * 100 : 0;
        $ucResidualTransactionPercentage = $totalUCSplitTransactions > 0 ? ($totalUCResidualSplitCount / $totalUCSplitTransactions) * 100 : 0;

        // Calculate projected volume based on trend
        $projectionData = null;
        if ($selectedYear == $currentYear) {
            $cacheKey = "projections_{$selectedYear}_current_year";
            $result = Cache::remember($cacheKey, 60 * 15, function () use ($reportData) {
                return $this->calculateProjectedVolume($reportData);
            });
            $projectionData = $result['projections'];
            $reportData = $result['reportData'];
            //Log::info("-- Projection Data: " . json_encode($projectionData));
        } else {
            //Log::info("-- Skipping projections for past year: $selectedYear");
            $projectionData = [
                'status' => ['completed' => false],
                'sold' => [
                    'transactions' => 0,
                    'volume' => 0,
                    'chr_split' => 0,
                    'agent_earnings' => 0,
                ],
                'uc' => [
                    'transactions' => 0,
                    'volume' => 0,
                    'chr_split' => 0,
                    'agent_earnings' => 0,
                ],
            ];
        }

        // Return the view with all the data
        return view('reports.productionProjections', compact(
            'reportData',
            'projectionData',
            'currentYear',
            'availableYears',
            'totalSoldTransactions',
            'totalUCTransactions',
            'totalSoldCheckAmount',
            'totalUnderContractCheckAmount',
            'totalSoldCHRSplit',
            'totalUnderContractCHRSplit',
            'ucInitialTransactionPercentage',
            'ucResidualTransactionPercentage'
        ));
    }

    // Get available years where deals exist in both Deal and Aci models
    private function getAvailableYears()
    {

        // cache this for 15 days ;)
        return Cache::remember('available_years', 60 * 60 * 24 * 15, function () {
            $dealYears = Deal::selectRaw('YEAR(closing_date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();

            $aciYears = Aci::selectRaw('YEAR(closing_date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();

            return array_intersect($dealYears, $aciYears);
        });

    }

    // Get deals for a given year and stages
    private function getDealsByYearAndStage($year)
    {

        $cacheKey = "sold_deals_{$year}";

        // Cache sold deals but not Under Contract (UC) deals
        $soldDeals = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($year) {
            return Deal::with(['contactName', 'acis'])
                ->whereYear('closing_date', $year)
                ->where('stage', 'Sold')
                ->get();
        });

        // Fetch Under Contract deals without caching
        $underContractDeals = Deal::with(['contactName', 'acis'])
            ->whereYear('closing_date', $year)
            ->where('stage', 'Under Contract')
            ->get();

        // Merge Sold and Under Contract deals
        return $soldDeals->merge($underContractDeals);
    }

    // Process and group agent deals, handling both sold and under contract transactions
    private function processAgentDeals($deals)
    {
        $reportData = [];

        // Group deals by agent (e.g., John Doe)
        $dealsGroupedByAgent = $deals->groupBy(function ($deal) {
            return $deal->contactName->first_name . " " . $deal->contactName->last_name;
        });

        // Now sort each agent's deals by stage, prioritizing "Sold" first
        $dealsGroupedByAgent = $dealsGroupedByAgent->map(function ($agentDeals) {
            return $agentDeals->sortBy(function ($deal) {
                return $deal->stage === 'Sold' ? 0 : 1; // "Sold" deals get a priority of 0, "Under Contract" gets 1
            });
        });

        // Process each agent's deals
        foreach ($dealsGroupedByAgent as $agentName => $agentDeals) {
            //Log::info("Processing deals for agent: $agentName");

            $cacheKeySold = "agent_sold_deals_{$agentName}_{$agentDeals->first()->contactId}";
            $cacheKeyUC = "agent_uc_deals_{$agentName}_{$agentDeals->first()->contactId}";


            // Get the contact Id
            $contactId = $agentDeals->first()->contactId;
            $zohoContactId = $agentDeals->first()->contactName->id;

            // Initialize report data for the agent
            $initialCap = $agentDeals->first()->contactName->initial_cap ?: 27500;
            $residualCap = $agentDeals->first()->contactName->residual_cap ?: 37500;

            $initialSplit = $agentDeals->first()->contactName->initial_split ?: 20;
            $residualSplit = $agentDeals->first()->contactName->residual_split ?: 3;

            // Correctly initialize agent report, including UC structure
            $reportData[$agentName] = $this->initializeAgentReport($initialCap, $residualCap, $initialSplit, $residualSplit);
            $reportData[$agentName]['contact_id'] = $contactId;
            $reportData[$agentName]['zoho_contact_id'] = $zohoContactId;

            
            // Process each deal for the agent
            foreach ($agentDeals as $deal) {
                $this->processDealForAgent($deal, $agentName, $reportData);
            }
        }

        return $reportData;
    }

    // Initialize agent report structure
    private function initializeAgentReport($initialCap, $residualCap, $initialSplit, $residualSplit)
    {
        return [
            'Sold' => [
                'count' => 0,
                'agent_check_amount' => 0,
                'chr_split' => 0,
                'total_commission' => 0,
            ],
            'Under Contract' => [
                'count' => 0,
                'projected_agent_earnings' => 0,
                'projected_chr_split' => 0,
                'total_commission' => 0,
                'info' => [],
            ],
            'settings' => [
                'initial_cap' => $initialCap,
                'residual_cap' => $residualCap,
                'initial_split_percent' => $initialSplit,
                'residual_split_percent' => $residualSplit,
            ],
            'running' => [
                'initial_cap_remaining' => $initialCap,
                'residual_cap_remaining' => $residualCap - $initialCap,
                'projection_sold' => 0,
                'projection_uc' => 0,
            ],
        ];
    }

    // Process individual deal for an agent
    private function processDealForAgent($deal, $agentName, &$reportData)
    {
        $stage = $deal->stage;
        $acis = $deal->acis;

        // Sold Transactions
        if ($stage === 'Sold') {
            $this->processSoldTransaction($deal, $acis, $agentName, $reportData);
        }

        // **Fix UC handling here**
        if ($stage === 'Under Contract') {
            $this->processUnderContractTransaction($deal, $agentName, $reportData);
        }
    }

    // Process sold transactions
    private function processSoldTransaction($deal, $acis, $agentName, &$reportData)
    {
        $agentCheckAmount = $acis->sum(function ($aci) {
            return $aci->agent_check_amount ?: $aci->irs_reported_1099_income_for_this_transaction;
        }) ?? 0;

        $chrSplit = $acis->sum('less_split_to_chr') ?: 0;
        $totalCommission = $acis->sum('total_gross_commission') ?: 0;

        // Update Sold values
        $reportData[$agentName]['Sold']['count']++;
        $reportData[$agentName]['Sold']['agent_check_amount'] += $agentCheckAmount;
        $reportData[$agentName]['Sold']['chr_split'] += $chrSplit;
        $reportData[$agentName]['Sold']['total_commission'] += $totalCommission;

        // ** Update Running Totals for Initial and Residual Caps after Sold transactions **
        // Sold transactions reduce the agent's remaining caps
        $initialCapBefore = $reportData[$agentName]['running']['initial_cap_remaining'];
        $residualCapBefore = $reportData[$agentName]['running']['residual_cap_remaining'];

        // Apply the split to the initial cap first
        if ($initialCapBefore > 0) {
            $chrSplitForInitial = min($chrSplit, $initialCapBefore); // Take as much as needed from the initial cap
            $reportData[$agentName]['running']['initial_cap_remaining'] -= $chrSplitForInitial;
            $chrSplit -= $chrSplitForInitial; // Reduce the chrSplit by what was taken from the initial cap
        }

        // Now apply any remaining split to the residual cap
        if ($chrSplit > 0) {
            $reportData[$agentName]['running']['residual_cap_remaining'] -= $chrSplit;
        }

        $initialCapAfter = $reportData[$agentName]['running']['initial_cap_remaining'];
        $residualCapAfter = $reportData[$agentName]['running']['residual_cap_remaining'];

        //Log::info("-- Updated running totals after Sold transaction: Initial Cap Before: $initialCapBefore, Initial Cap After: $initialCapAfter, Residual Cap Before: $residualCapBefore, Residual Cap After: $residualCapAfter");

    }

    // Process under contract transactions
    private function processUnderContractTransaction($deal, $agentName, &$reportData)
    {
        $salePrice = $deal->sale_price ?: 0;
        $commissionPercent = $deal->commission ?: 0;

        // Calculate full agent earnings based on sale price and commission percentage
        $fullAgentEarnings = $salePrice * ($commissionPercent / 100);
        //Log::info("-- Under Contract transaction for $agentName: Sale Price: $salePrice, Commission Percent: $commissionPercent, Full Agent Earnings: $fullAgentEarnings");

        // Get the initial and residual split percentages
        $initialSplitPercent = $deal->contactName->initial_split ?: 20;
        $residualSplitPercent = $deal->contactName->residual_split ?: 3;

        //Log::info("-- Splits for $agentName: Initial Split Percent: $initialSplitPercent%, Residual Split Percent: $residualSplitPercent%");

        // Fetch remaining initial and residual caps from the running totals
        $remainingInitialCap = $reportData[$agentName]['running']['initial_cap_remaining'];
        $remainingResidualCap = $reportData[$agentName]['running']['residual_cap_remaining'];
        //Log::info("-- Caps for $agentName: Initial Cap Remaining: $remainingInitialCap, Residual Cap Remaining: $remainingResidualCap");

        // **Fix split calculations**
        list(
            $initialCHRSplit,
            $residualCHRSplit,
            $remainingInitialCap,
            $remainingResidualCap) = $this->calculateSplits($fullAgentEarnings, $initialSplitPercent, $residualSplitPercent, $remainingInitialCap, $remainingResidualCap);

        // Update caps
        $reportData[$agentName]['running']['initial_cap_remaining'] = max(0, $remainingInitialCap);
        $reportData[$agentName]['running']['residual_cap_remaining'] = max(0, $remainingResidualCap);

        // Store UC details
        $reportData[$agentName]['Under Contract']['info'][] = $this->getDealInfo($deal, $agentName, $initialCHRSplit, $residualCHRSplit, $fullAgentEarnings, $initialSplitPercent, $residualSplitPercent, $reportData, $remainingInitialCap, $remainingResidualCap);

        // Update UC counts and projections
        $reportData[$agentName]['Under Contract']['count'] += 1;
        $reportData[$agentName]['Under Contract']['projected_agent_earnings'] += $fullAgentEarnings - ($initialCHRSplit + $residualCHRSplit);
        $reportData[$agentName]['Under Contract']['projected_chr_split'] += $initialCHRSplit + $residualCHRSplit;
    }

    // Calculate initial and residual splits
    private function calculateSplits($fullAgentEarnings, $initialSplitPercent, $residualSplitPercent, $remainingInitialCap, $remainingResidualCap)
    {

        // Initialize splits
        $initialCHRSplit = 0;
        $residualCHRSplit = 0;

        // ** Handle Initial Cap: Only apply the initial split if the agent hasn't hit the initial cap **
        if ($remainingInitialCap > 0) {
            // Calculate initial split based on full agent earnings
            $initialSplitAmount = $fullAgentEarnings * ($initialSplitPercent / 100);

            // Apply the minimum of the initial split and the remaining initial cap
            $initialCHRSplit = min($initialSplitAmount, $remainingInitialCap);

            // Update the remaining initial cap
            $remainingInitialCap -= $initialCHRSplit;
            //Log::info("-- Initial CHR split: $initialCHRSplit, Remaining Initial Cap: $remainingInitialCap");
        }

        // ** Handle Residual Cap: Apply only if the initial cap is exhausted and the residual cap is not fully used **
        if ($remainingInitialCap <= 0 && $remainingResidualCap > 0) {
            // Calculate residual split based on full agent earnings
            $residualSplitAmount = $fullAgentEarnings * ($residualSplitPercent / 100);

            // Apply the minimum of the residual split and the remaining residual cap
            $residualCHRSplit = min($residualSplitAmount, $remainingResidualCap);

            // Update the remaining residual cap
            $remainingResidualCap -= $residualCHRSplit;
            //Log::info("-- Residual CHR split: $residualCHRSplit, Remaining Residual Cap: $remainingResidualCap");
        }

        return [$initialCHRSplit, $residualCHRSplit, $remainingInitialCap, $remainingResidualCap];
    }

    // Fetch deal information for report
    private function getDealInfo($deal, $agentName, $initialCHRSplit, $residualCHRSplit, $fullAgentEarnings, $initialSplitPercent, $residualSplitPercent, $reportData, $remainingInitialCap, $remainingResidualCap)
    {
        return [
            'deal_name' => $deal->deal_name,
            'deal_link' => route('pipeline.view', $deal->id),
            'closing_date' => Carbon::parse($deal->closing_date)->format('Y-m-d'),
            'sale_price' => $deal->sale_price,
            'commission_percent' => $deal->commission,
            'fullAgentEarnings' => $fullAgentEarnings,
            'initialCHRSplit' => $initialCHRSplit,
            'residualCHRSplit' => $residualCHRSplit,
            'initialCHRSplitPercent' => $initialSplitPercent,
            'residualCHRSplitPercent' => $residualSplitPercent,
            'projectedAgentEarnings' => $fullAgentEarnings - ($initialCHRSplit + $residualCHRSplit),
            'initialCapBefore' => $reportData[$agentName]['running']['initial_cap_remaining'],
            'initialCapAfter' => max(0, $remainingInitialCap),
            'residualCapBefore' => $reportData[$agentName]['running']['residual_cap_remaining'],
            'residualCapAfter' => max(0, $remainingResidualCap),
        ];
    }

    private function calculateProjectedVolume($reportData)
    {
        $totalProjectedSoldVolume = 0;
        $totalProjectedUCVolume = 0;
        $totalProjectedCHRSoldSplit = 0;
        $totalProjectedCHRUCsplit = 0;
        $totalProjectedAgentSoldEarnings = 0;
        $totalProjectedAgentUCEarnings = 0;

        $totalSoldTransactionCount = 0;
        $totalUCTransactionCount = 0;

        $remainingMonths = 12 - Carbon::now()->month; // Months left in the year
        $monthsPassed = Carbon::now()->month; // Months that have passed

        foreach ($reportData as $agentName => $agentData) {
            // Fetch transaction data for the agent
            $totalUCTransactions = $agentData['Under Contract']['count'];
            $totalSoldTransactions = $agentData['Sold']['count'];

            // Get historical cadence from last year
            $historicalCadence = $this->calculateHistoricalCadence($agentData);
            $historicalSoldTransactions = $historicalCadence['sold'];
            $historicalUCTransactions = $historicalCadence['uc'];

            // Calculate this year's cadence (UC and Sold transactions per month)
            $cadenceUCThisYear = $totalUCTransactions / $monthsPassed;
            $cadenceSoldThisYear = $totalSoldTransactions / $monthsPassed;

            // Blend current year's cadence with last year's cadence to balance the projection
            $blendedCadenceUC = ($cadenceUCThisYear + $historicalUCTransactions / 12) / 2;
            $blendedCadenceSold = ($cadenceSoldThisYear + $historicalSoldTransactions / 12) / 2;

            // Estimate new UC and Sold transactions for the rest of the year
            $projectedNewUCTransactions = $blendedCadenceUC * $remainingMonths;
            $projectedNewSoldTransactions = $blendedCadenceSold * $remainingMonths;

            // Get average sale price and commission for the agent
            $averageSalePrice = $this->calculateAverageSalePrice($agentData['contact_id'], $agentData['zoho_contact_id']);
            $averageCommission = $this->calculateAverageCommission($agentData['contact_id'], $agentData['zoho_contact_id']);

            // Estimate how many current UC deals will convert to sold (historical win/loss ratio)
            $winLossRatio = $this->estimateWinLossRatio($agentData);
            $estimatedSoldFromCurrentUCs = $totalUCTransactions * $winLossRatio;
            $estimatedSoldFromNewUCs = $projectedNewUCTransactions * $winLossRatio;

            // Add projected sold transactions (current and new UCs converting to Sold)
            $totalProjectedSoldTransactions = $estimatedSoldFromCurrentUCs + $estimatedSoldFromNewUCs + $projectedNewSoldTransactions;

            // Add remaining UC transactions (those that did not convert to sold)
            $totalProjectedUCTransactions = $projectedNewUCTransactions - $estimatedSoldFromNewUCs;

            // Store projections in the reportData object
            $reportData[$agentName]['settings']['projection_sold'] = [
                'count' => ceil($totalProjectedSoldTransactions),
            ];
            $reportData[$agentName]['settings']['projection_uc'] = [
                'count' => ceil($totalProjectedUCTransactions),
            ];

            $projectedVolume = $averageSalePrice * ($averageCommission / 100);

            // Get split percentages
            $initialSplitPercent = $agentData['settings']['initial_split_percent'] ?? 20;
            $residualSplitPercent = $agentData['settings']['residual_split_percent'] ?? 3;

            $remainingInitialCap = $agentData['running']['initial_cap_remaining'];
            $remainingResidualCap = $agentData['running']['residual_cap_remaining'];

            // Process projected sold transactions
            foreach (range(1, ceil($totalProjectedSoldTransactions)) as $i) {
                list(
                    $chrInitialSplit,
                    $chrResidualSplit,
                    $remainingInitialCap,
                    $remainingResidualCap
                ) = $this->calculateSplits($projectedVolume, $initialSplitPercent, $residualSplitPercent, $remainingInitialCap, $remainingResidualCap);

                $totalProjectedSoldVolume += $projectedVolume;
                $totalProjectedCHRSoldSplit += $chrInitialSplit + $chrResidualSplit;
                $totalProjectedAgentSoldEarnings += ($projectedVolume - $chrInitialSplit - $chrResidualSplit);
            }

            // Process projected UC transactions
            foreach (range(1, ceil($totalProjectedUCTransactions)) as $i) {
                list(
                    $chrInitialSplit,
                    $chrResidualSplit,
                    $remainingInitialCap,
                    $remainingResidualCap
                ) = $this->calculateSplits($projectedVolume, $initialSplitPercent, $residualSplitPercent, $remainingInitialCap, $remainingResidualCap);

                $totalProjectedUCVolume += $projectedVolume;
                $totalProjectedCHRUCsplit += $chrInitialSplit + $chrResidualSplit;
                $totalProjectedAgentUCEarnings += ($projectedVolume - $chrInitialSplit - $chrResidualSplit);
            }

            // Keep track of total transaction counts
            $totalSoldTransactionCount += ceil($totalProjectedSoldTransactions);
            $totalUCTransactionCount += ceil($totalProjectedUCTransactions);
        }

        // Return the projection data
        $retObj = [
            'projections' => [
                'status' => ['completed' => true],
                'sold' => [
                    'transactions' => $totalSoldTransactionCount,
                    'volume' => $totalProjectedSoldVolume,
                    'chr_split' => $totalProjectedCHRSoldSplit,
                    'agent_earnings' => $totalProjectedAgentSoldEarnings,
                ],
                'uc' => [
                    'transactions' => $totalUCTransactionCount,
                    'volume' => $totalProjectedUCVolume,
                    'chr_split' => $totalProjectedCHRUCsplit,
                    'agent_earnings' => $totalProjectedAgentUCEarnings,
                ],
            ],
            'reportData' => $reportData, // Returning the modified reportData with projections
        ];

        Log::info("Projections Done: ", ['retObj' => $retObj]);
        return $retObj;
    }

// Helper to calculate historical cadence (sold and UC transactions) from the previous year
// Helper to calculate historical cadence (sold and UC transactions) from the previous year
    private function calculateHistoricalCadence($agentData)
    {
        $previousYear = Carbon::now()->year - 1;

        $contactId = $agentData['contact_id'];
        $zohoContactId = $agentData['zoho_contact_id'];

        // Fetch historical sold transactions (all sold were UC at some point)
        $historicalSoldTransactions = Deal::whereIn('contactId', [$contactId, $zohoContactId])
            ->whereYear('closing_date', $previousYear)
            ->where('stage', 'Sold')
            ->count();

        // Fetch historical "Dead" transactions (deals that didn’t convert)
        $historicalDeadTransactions = Deal::whereIn('contactId', [$contactId, $zohoContactId])
            ->whereYear('closing_date', $previousYear)
            ->where('stage', 'like', 'Dead%')
            ->count();

        // Estimate the win/loss ratio from the previous year
        $winLossRatio = $this->estimateWinLossRatio($agentData, $historicalSoldTransactions, $historicalDeadTransactions);

        // Estimate how many "Dead" deals were under contract before being lost
        $estimatedDeadAsUC = $historicalDeadTransactions * $winLossRatio;

        // Total historical UC transactions = Sold transactions + Estimated UC from "Dead" deals
        $historicalUCTransactions = $historicalSoldTransactions + $estimatedDeadAsUC;

        return [
            'sold' => $historicalSoldTransactions,
            'uc' => $historicalUCTransactions,
        ];
    }

// Helper function to calculate average sale price based on contact IDs
    private function calculateAverageSalePrice($contactId, $zohoContactId)
    {
        $cacheKey = "average_sale_price_{$contactId}";

        return Cache::remember($cacheKey, 60 * 60 * 24 * 15, function () use ($contactId, $zohoContactId) {
            return Deal::whereIn('contactId', [$contactId, $zohoContactId])
                ->where('stage', 'Sold')
                ->whereBetween('closing_date', [
                    Carbon::now()->subYear()->startOfYear(),
                    Carbon::now(),
                ])
                ->avg('sale_price');
        });
    }

// Helper function to calculate average commission based on contact IDs
    private function calculateAverageCommission($contactId, $zohoContactId)
    {
        $cacheKey = "average_commission_{$contactId}";

        return Cache::remember($cacheKey, 60 * 60 * 24 * 15, function () use ($contactId, $zohoContactId) {
            return Deal::whereIn('contactId', [$contactId, $zohoContactId])
                ->where('stage', 'Sold')
                ->whereBetween('closing_date', [
                    Carbon::now()->subYear()->startOfYear(),
                    Carbon::now(),
                ])
                ->avg('commission');
        });
    }

    private function estimateWinLossRatio($agentData, $soldCount = null, $ucCount = null)
    {
        $previousYear = Carbon::now()->year - 1;

        //Log::info("Agent Data: ", ['data' => $agentData]);

        // Fetch sold transactions and dead/canceled transactions from the previous year
        $soldTransactionsLastYear = $soldCount ?? Deal::whereIn('contactId', [$agentData['contact_id'], $agentData['zoho_contact_id']])
            ->orWhere('contactId', $agentData['zoho_contact_id'])
            ->whereYear('closing_date', $previousYear)
            ->where('stage', 'Sold')
            ->count();

        $canceledTransactionsLastYear = $ucCount ?? Deal::whereIn('contactId', [$agentData['contact_id'], $agentData['zoho_contact_id']])
            ->whereYear('closing_date', $previousYear)
            ->where('stage', 'like', 'Dead%')
            ->count();

        // Calculate the win/loss ratio
        $totalTransactionsLastYear = $soldTransactionsLastYear + $canceledTransactionsLastYear;
        $winRatioLastYear = ($totalTransactionsLastYear > 0) ? $soldTransactionsLastYear / $totalTransactionsLastYear : 0.75;

        return $winRatioLastYear;
    }

    public function renderDealCards(Request $request)
    {
        $deals = $request->input('deals');
        $settings = $request->input('settings');

        // Generate HTML by rendering the partial for each deal
        $html = '';
        foreach ($deals as $index => $deal) {
            $html .= view('reports.partials.productionProjection.deal_card', [
                'deal' => $deal,
                'settings' => $settings,
                'index' => $index,
            ])->render();
        }

        // Return the HTML as a JSON response
        return response()->json(['html' => $html]);
    }

}
