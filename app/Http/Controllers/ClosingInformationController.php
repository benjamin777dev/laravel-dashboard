<?php

namespace App\Http\Controllers;

use App\Models\Aci;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClosingInformationController extends Controller
{
    public function index()
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $contact = $user->contact;
        $teamAndPartnership = null;

        if ($contact && $contact->teamAndPartnership) {
            $teamAndPartnership = $contact->teamAndPartnership;
            Log::info('User is part of the team:', ['team' => $teamAndPartnership->toArray()]);
        } else {
            Log::info('User is not part of any team.');
        }

        // Fetch KPIs
        $transactionCountYTD = $this->calculateTransactionCountYTD($contact, $teamAndPartnership);
        $gciYTD = $this->calculateGCIYTD($contact, $teamAndPartnership) ?? 0;
        $volumeYTD = $this->calculateVolumeYTD($contact, $teamAndPartnership) ?? 0;
        $capAmountPaidYTD = $this->calculateCapAmountPaidYTD($contact, $teamAndPartnership) ?? 0;
        $averageSalePrice = $this->calculateAverageSalePrice($contact, $teamAndPartnership) ?? 0;
        $incomeGoal = $contact->income_goal ?? 0;
        $averageCommissionPercent = $this->calculateAverageCommissionPercent($contact, $teamAndPartnership) ?? 0;
        $initialCap = $contact->initial_cap ?? 0;
        $residualCap = $contact->residual_cap ?? 0;
        $irs1099Amount = $this->calculate1099Amount($contact, $teamAndPartnership) ?? 0;

        // Fetch Table Data
        $agentReport = $this->getAgentReportData($contact, $teamAndPartnership);
        $transactionsSoldYTD = $this->getTransactionsSoldYTD($contact, $teamAndPartnership) ?? 0;
        $soldByYear = $this->getSoldByYearData($contact, $teamAndPartnership) ?? 0;

        return view('closing-information.index', compact(
            'transactionCountYTD',
            'gciYTD',
            'volumeYTD',
            'capAmountPaidYTD',
            'averageSalePrice',
            'incomeGoal',
            'averageCommissionPercent',
            'initialCap',
            'residualCap',
            'irs1099Amount',
            'agentReport',
            'transactionsSoldYTD',
            'soldByYear'
        ));
    }

    private function buildTransactionQuery($contact, $teamAndPartnership, $startDate = null, $endDate = null)
    {
        $query = Aci::where('stage', 'Sold');

        // Apply the date range filter only if both start and end dates are provided
        if ($startDate && $endDate) {
            $query->whereBetween('closing_date', [$startDate, $endDate]);
        }

        // Apply team/partnership or individual agent filter
        if ($teamAndPartnership) {
            $query->where('team_partnership_id', $teamAndPartnership->team_partnership_id);
        } else {
            $query->where('chr_agent_id', $contact->zoho_contact_id);
        }

        return $query;
    }

    private function calculateTransactionCountYTD($contact, $teamAndPartnership)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();
        $previousFiscalYearStart = $fiscalYearStart->copy()->subYear();
        $previousFiscalYearEnd = $fiscalYearEnd->copy()->subYear();

        // Build the queries
        $currentYearQuery = $this->buildTransactionQuery($contact, $teamAndPartnership, $fiscalYearStart, $fiscalYearEnd);
        $previousYearQuery = $this->buildTransactionQuery($contact, $teamAndPartnership, $previousFiscalYearStart, $previousFiscalYearEnd);

        // Execute the queries
        $currentYearCount = $currentYearQuery->sum('sides');
        $previousYearCount = $previousYearQuery->sum('sides');

        $percentageChange = $previousYearCount > 0 
            ? number_format(($currentYearCount / $previousYearCount) * 100, 2) 
            : number_format(($currentYearCount > 0 ? 100 : 0), 2);

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
            'percentageChange' => $percentageChange
        ];
    }

    private function fiscalTransactionQuery($contact, $teamAndPartnership) {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();
        $query = $this->buildTransactionQuery($contact, $teamAndPartnership, $fiscalYearStart, $fiscalYearEnd);
        return $query;
    }

    private function calculateGCIYTD($contact, $teamAndPartnership)
    {
        $query = $this->fiscalTransactionQuery($contact, $teamAndPartnership);

        return $query->sum('adjusted_gross_commission');
    }

    private function calculateVolumeYTD($contact, $teamAndPartnership)
    {
        $query = $this->fiscalTransactionQuery($contact, $teamAndPartnership);

        return $query->sum('calculated_volume');
    }

    private function calculateCapAmountPaidYTD($contact, $teamAndPartnership)
    {
        $query = $this->fiscalTransactionQuery($contact, $teamAndPartnership);

        return $query->sum('less_split_to_chr');
    }

    private function calculateAverageSalePrice($contact, $teamAndPartnership)
    {
        $query = $this->fiscalTransactionQuery($contact, $teamAndPartnership);

        return $query->avg('sale_price');
    }

    private function calculateAverageCommissionPercent($contact, $teamAndPartnership)
    {
        $query = $this->fiscalTransactionQuery($contact, $teamAndPartnership);

        return $query->avg('commission_percent');
    }

    private function calculate1099Amount($contact, $teamAndPartnership)
    {
        $query = $this->fiscalTransactionQuery($contact, $teamAndPartnership);

        return $query->sum('irs_reported_1099_income_for_this_transaction');
    }

    private function getAgentReportData($contact, $teamAndPartnership)
    {
        $query = $this->fiscalTransactionQuery($contact, $teamAndPartnership);

        return $query->groupBy('chr_agent_id')
            ->selectRaw('chr_agent_id, count(*) as record_count, sum(calculated_gci) as total_gci, sum(calculated_volume) as total_volume')
            ->get();
    }

    private function getTransactionsSoldYTD($contact, $teamAndPartnership)
    {
        $query = $this->fiscalTransactionQuery($contact, $teamAndPartnership);

        return $query->groupByRaw('MONTH(closing_date)')
            ->selectRaw('MONTH(closing_date) as month, count(*) as record_count, sum(calculated_gci) as total_gci, sum(calculated_volume) as total_volume')
            ->get();
    }

    private function getSoldByYearData($contact, $teamAndPartnership)
    {
        $query = $this->buildTransactionQuery($contact, $teamAndPartnership, null, null);

        return $query->groupByRaw('YEAR(closing_date)')
            ->selectRaw('YEAR(closing_date) as year, count(*) as record_count, sum(calculated_gci) as total_gci, sum(calculated_volume) as total_volume')
            ->orderBy('year', 'desc')
            ->get();
    }
}
