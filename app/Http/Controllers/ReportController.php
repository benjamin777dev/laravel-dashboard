<?php
namespace App\Http\Controllers;

use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function productionProjections()
    {
        Log::info("At production projection report");

        // Current year (e.g., 2024)
        $currentYear = Carbon::now()->year;
        Log::info("-- current Year: $currentYear");

        // Fetch sold and UC (Under Contract) deals with ACI and agent information
        $deals = Deal::with(['leadAgent', 'contactName', 'acis'])
            ->whereYear('closing_date', $currentYear)
            ->whereIn('stage', ['Under Contract', 'Sold'])
            ->get();

        Log::info("-- fetched deals count: " . $deals->count());

        // Initialize report data structure
        $reportData = [];

        // Group deals by agent (e.g., John Doe)
        $dealsGroupedByAgent = $deals->groupBy(function ($deal) {
            return $deal->contactName->first_name . " " . $deal->contactName->last_name;
        });

        // Initialize totals for the entire report
        $totalTransactions = 0;
        $totalSoldTransactions = 0;
        $totalUCTransactions = 0;
        $totalSoldCheckAmount = 0;
        $totalSoldCHRSplit = 0;
        $totalUnderContractCheckAmount = 0;
        $totalUnderContractCHRSplit = 0;
        $totalUCInitialSplitCount = 0;
        $totalUCResidualSplitCount = 0; 


        // Process each agent's deals
        foreach ($dealsGroupedByAgent as $agentName => $agentDeals) {
            Log::info("Processing deals for agent: $agentName");

            // Initialize agent's report data if not already set
            if (!isset($reportData[$agentName])) {
                $initialCap = $agentDeals->first()->contactName->initial_cap ?? 27500;
                $residualCap = $agentDeals->first()->contactName->residual_cap ?? 37500;
                if (empty($initialCap)) {
                    $initialCap = 27500;
                }
                if (empty($residualCap)) {
                    $residualCap = 37500;
                }

                // Set up initial data structure for the agent
                $reportData[$agentName] = [
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
                    ],
                ];

                Log::info("-- Agent $agentName settings: Initial Cap: $initialCap, Residual Cap: $residualCap");
            }

            // Initialize running totals for the agent's caps if not set
            if (!isset($reportData[$agentName]['running'])) {
                $reportData[$agentName]['running'] = [
                    'initial_cap_remaining' => $reportData[$agentName]['settings']['initial_cap'],
                    'residual_cap_remaining' => $reportData[$agentName]['settings']['residual_cap'] - $reportData[$agentName]['settings']['initial_cap'],
                ];
            }

            // Process each deal for the agent
            foreach ($agentDeals as $deal) {
                $stage = $deal->stage;
                Log::info("-- Processing $stage transaction for $agentName, deal ID: {$deal->id}");

                // ** Handle Sold Transactions **
                if ($stage === 'Sold') {
                    $acis = $deal->acis;
                    $agentCheckAmount = $acis->sum('agent_check_amount') ?? 0;
                    $chrSplit = $acis->sum('less_split_to_chr') ?? 0;
                    $totalCommission = $acis->sum('total_gross_commission') ?? 0;

                    Log::info("-- Sold transaction for $agentName: Check Amount: $agentCheckAmount, CHR Split: $chrSplit, Total Commission: $totalCommission");

                    // Increment Sold transaction count for agent and global totals
                    $reportData[$agentName]['Sold']['count'] += 1;
                    $totalSoldTransactions += 1;

                    // Update agent's Sold values
                    $reportData[$agentName]['Sold']['agent_check_amount'] += $agentCheckAmount;
                    $reportData[$agentName]['Sold']['chr_split'] += $chrSplit;
                    $reportData[$agentName]['Sold']['total_commission'] += $totalCommission;

                    // Update global totals for Sold transactions
                    $totalSoldCheckAmount += $agentCheckAmount;
                    $totalSoldCHRSplit += $chrSplit;

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

                    Log::info("-- Updated running totals after Sold transaction: Initial Cap Before: $initialCapBefore, Initial Cap After: $initialCapAfter, Residual Cap Before: $residualCapBefore, Residual Cap After: $residualCapAfter");
                }

                // ** Handle Under Contract Transactions **
                if ($stage === 'Under Contract') {
                    $salePrice = $deal->sale_price ?? 0;
                    $commissionPercent = $deal->commission ?? 0;

                    // Calculate full agent earnings based on sale price and commission percentage
                    $fullAgentEarnings = $salePrice * ($commissionPercent / 100);
                    Log::info("-- Under Contract transaction for $agentName: Sale Price: $salePrice, Commission Percent: $commissionPercent, Full Agent Earnings: $fullAgentEarnings");

                    // Get the initial and residual split percentages
                    $initialSplitPercent = $deal->contactName->initial_split ?? 20; // Default to 20% if not set
                    $residualSplitPercent = $deal->contactName->residual_split ?? 3; // Default to 3% if not set

                    if (empty($initialSplitPercent)) {
                        $initialSplitPercent = 20;
                    }
                    if (empty($residualSplitPercent)) {
                        $residualSplitPercent = 3;
                    }

                    Log::info("-- Splits for $agentName: Initial Split Percent: $initialSplitPercent%, Residual Split Percent: $residualSplitPercent%");

                    // Fetch remaining initial and residual caps from the running totals
                    $remainingInitialCap = $reportData[$agentName]['running']['initial_cap_remaining'];
                    $remainingResidualCap = $reportData[$agentName]['running']['residual_cap_remaining'];
                    Log::info("-- Caps for $agentName: Initial Cap Remaining: $remainingInitialCap, Residual Cap Remaining: $remainingResidualCap");

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
                        Log::info("-- Initial CHR split: $initialCHRSplit, Remaining Initial Cap: $remainingInitialCap");
                    }

                    // ** Handle Residual Cap: Apply only if the initial cap is exhausted and the residual cap is not fully used **
                    if ($remainingInitialCap <= 0 && $remainingResidualCap > 0) {
                        // Calculate residual split based on full agent earnings
                        $residualSplitAmount = $fullAgentEarnings * ($residualSplitPercent / 100);

                        // Apply the minimum of the residual split and the remaining residual cap
                        $residualCHRSplit = min($residualSplitAmount, $remainingResidualCap);

                        // Update the remaining residual cap
                        $remainingResidualCap -= $residualCHRSplit;
                        Log::info("-- Residual CHR split: $residualCHRSplit, Remaining Residual Cap: $remainingResidualCap");
                    }

                    // ** Store cap details before and after this transaction to track progress **
                    $reportData[$agentName]['Under Contract']['info'][] = [
                        'deal_name' => $deal->deal_name,
                        'deal_link' => route('pipeline.view', $deal->id),
                        'closing_date' => Carbon::parse($deal->closing_date)->format('Y-m-d'),
                        'sale_price' => $salePrice,
                        'commission_percent' => $commissionPercent,
                        'fullAgentEarnings' => $fullAgentEarnings,
                        'initialCHRSplit' => $initialCHRSplit,
                        'residualCHRSplit' => $residualCHRSplit,
                        'initialCHRSplitPercent' => $initialSplitPercent,
                        'resitualCHRSplitPercent' => $residualSplitPercent,
                        'projectedAgentEarnings' => $fullAgentEarnings - ($initialCHRSplit + $residualCHRSplit),
                        'initialCapBefore' => $reportData[$agentName]['running']['initial_cap_remaining'],
                        'initialCapAfter' => max(0, $remainingInitialCap),
                        'residualCapBefore' => $reportData[$agentName]['running']['residual_cap_remaining'],
                        'residualCapAfter' => max(0, $remainingResidualCap),
                    ];

                    $totalUnderContractCheckAmount += $fullAgentEarnings - ($initialCHRSplit + $residualCHRSplit);
                    $totalUnderContractCHRSplit += ($initialCHRSplit + $residualCHRSplit);
                    // Update remaining caps in the report data for future transactions
                    $reportData[$agentName]['running']['initial_cap_remaining'] = max(0, $remainingInitialCap);
                    $reportData[$agentName]['running']['residual_cap_remaining'] = max(0, $remainingResidualCap);

                    // Increment Under Contract transaction count for agent and global totals
                    $reportData[$agentName]['Under Contract']['count'] += 1;
                    $totalUCTransactions += 1;

                    // Update projected earnings and CHR splits for Under Contract transactions
                    $reportData[$agentName]['Under Contract']['projected_agent_earnings'] += $fullAgentEarnings - ($initialCHRSplit + $residualCHRSplit);
                    $reportData[$agentName]['Under Contract']['projected_chr_split'] += $initialCHRSplit + $residualCHRSplit;

                    if ($initialCHRSplit > 0) {
                        $totalUCInitialSplitCount++;
                    }
                    if ($residualCHRSplit > 0) {
                        $totalUCResidualSplitCount++;
                    }
                
                }

            }
        }

        $totalUCSplitTransactions = $totalUCInitialSplitCount + $totalUCResidualSplitCount;
        $ucInitialTransactionPercentage = $totalUCSplitTransactions > 0 ? ($totalUCInitialSplitCount / $totalUCSplitTransactions) * 100 : 0;
        $ucResidualTransactionPercentage = $totalUCSplitTransactions > 0 ? ($totalUCResidualSplitCount / $totalUCSplitTransactions) * 100 : 0;

        Log::info("-- Final Totals: Sold Transactions: $totalSoldTransactions, Sold Check Amount: $totalSoldCheckAmount, Sold CHR Split: $totalSoldCHRSplit, Under Contract Transactions: $totalUCTransactions, Under Contract Check Amount: $totalUnderContractCheckAmount, Under Contract CHR Split: $totalUnderContractCHRSplit, Total UC Split Transactions: $totalUCSplitTransactions, UCInitial %: $ucInitialTransactionPercentage, UCResidual %: $ucResidualTransactionPercentage");

        // Return the view with the data and totals
        return view('reports.productionProjections', compact(
            'reportData',
            'totalTransactions',
            'totalSoldTransactions',
            'totalUCTransactions',
            'totalSoldCheckAmount',
            'totalSoldCHRSplit',
            'totalUnderContractCheckAmount',
            'totalUnderContractCHRSplit', 
            'totalUCSplitTransactions',
            'ucInitialTransactionPercentage',
            'ucResidualTransactionPercentage',
        ));
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
