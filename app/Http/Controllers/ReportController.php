<?php
namespace App\Http\Controllers;

use App\Models\Aci;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class ReportController extends Controller
{
    public function productionProjections()
    {

        Log::info("At production projection report");

        // Current year
        $currentYear = Carbon::now()->year;
        Log::info("-- current Year: $currentYear");

        // Fetch sold and UC deals with ACI and agent information
        $deals = Deal::with(['leadAgent', 'contactName', 'acis'])->whereYear('closing_date', $currentYear)->whereIn('stage', ['Under Contract', 'Sold'])->get();

        Log::info("-- fetched deals: ", ['deals' => $deals]);

        Log::info("-- mapping data");

        $thisYearStart = Carbon::now()->startOfYear();

        // Prepare data for the view
        $reportData = $deals->map(function($deal) use ($thisYearStart) {
            $agentName = $deal->leadAgent ? $deal->leadAgent->name . " (CHR)"  : $deal->contactName->first_name . " " . $deal->contactName->last_name;
        
            $agent_check_amount = 0;
            $less_split_to_chr = 0;
            $totalCommission = 0;
            $initialCapContribution = 0;
            $residualCapContribution = 0;
            $agentEarnings = 0;
            $acis = [];
        
            // Handle Sold transactions
            if ($deal->stage == 'Sold') {
                $acis = $deal->acis;
                $agent_check_amount += $acis->sum('agent_check_amount') ?? 0;
                $less_split_to_chr += $acis->sum('less_split_to_chr') ?? 0;
                $totalCommission += $acis->sum('total_gross_commission') ?? 0;

                Log::info("-- agent check amount: $agent_check_amount");
                Log::info("-- less_split_to_ch: $less_split_to_chr");
                Log::info("-- totalCommission: $totalCommission");
            } 
            // Handle UC transactions
            else {
                $contact = $deal->contactName; 
                $initialCap = $contact->initial_cap ?? 0;
                $residualCap = $contact->residual_cap ?? 0;

                Log::info("contact for uc: ", ['contact', $contact]);
                Log::info("initialCap: $initialCap");
                Log::info("residualCap: $residualCap");
        
                // Get YTD earnings (team or individual)
                if ($contact->isPartOfTeam()) {
                    $teamAcis = Aci::where('team_partnership_id', $contact->team_partnership)->where('closing_date', '>=', $thisYearStart)->get();
                    $agent_check_amount += $teamAcis->sum('agent_check_amount') ?? 0;
                    $less_split_to_chr += $teamAcis->sum('less_split_to_chr') ?? 0;
                } else {
                    $acisThisYear = Aci::where('chr_agent_id', $contact->userData->id)->where('closing_date', '>=', $thisYearStart)->get();
                    $agent_check_amount += $acisThisYear->sum('agent_check_amount') ?? 0;
                    $less_split_to_chr += $acisThisYear->sum('less_split_to_chr') ?? 0;
                }

                Log::info("-- agent check amount: $agent_check_amount");
                Log::info("-- less_split_to_ch: $less_split_to_chr");
        
                // Calculate cap contributions for UC
                $remainingInitialCap = max(0, $initialCap - $less_split_to_chr);
                $remainingResidualCap = max(0, $residualCap - $less_split_to_chr);
                $commissionFromUC = $deal->total_gross_commission * ($deal->split_percent / 100);
        
                Log::info("-- commission from UC: $commissionFromUC");
                Log::info("-- remaining initial cap: $remainingInitialCap");
                Log::info("-- remaining residual cap: $remainingResidualCap");

                $initialCapContribution = min($commissionFromUC, $remainingInitialCap);
                $remainingCommissionAfterInitial = $commissionFromUC - $initialCapContribution;
                $residualCapContribution = min($remainingCommissionAfterInitial, $remainingResidualCap);
                $agentEarnings = $remainingCommissionAfterInitial - $residualCapContribution;
                Log::info("-- initial cap contribution: $initialCapContribution");
                Log::info("-- residual cap contribution: $residualCapContribution");
                Log::info("-- agent earnings: $agentEarnings");
                Log::info("-- remaining Commission After Initial: $remainingCommissionAfterInitial");
            }
        
            $retObj = [
                'hasLeadAgent' => isset($deal->leadAgent), 
                'name' => $agentName ?? '',
                'id' => $deal->id,
                'stage' => $deal->stage ?? '',
                'soldData' => [
                    'aci_record' => $acis ?? [],
                    'check_amount' => $agent_check_amount ?? 0,
                    'split_to_chr' => $less_split_to_chr ?? 0,
                    'total_commission' => $totalCommission ?? 0
                ], 
                'ucData' => [
                    'contact' => $contact ?? [],
                    'initial_cap' => $initialCap ?? 0,
                    'residual_cap' => $residualCap ?? 0,
                    'initialCapContribution' => $initialCapContribution ?? 0,
                    'residualCapContribution' => $residualCapContribution ?? 0,
                    'agentEarnings' => $agentEarnings ?? 0
                ]
            ];

            Log::info("Return: ", ['retObj' => $retObj]);
            return $retObj;
        });

        // Return the view with the data
        return view('reports.productionProjections', compact('reportData'));
    }
}
