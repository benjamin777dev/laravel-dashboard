<?php

namespace App\Http\Controllers;

use App\Models\Aci;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClosingInformationController extends Controller
{
    public function index()
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        // Fetch KPIs
        $transactionCountYTD = $this->calculateTransactionCountYTD($user);
        $gciYTD = $this->calculateGCIYTD($user);
        $volumeYTD = $this->calculateVolumeYTD($user);
        $capAmountPaidYTD = $this->calculateCapAmountPaidYTD($user);
        $averageSalePrice = $this->calculateAverageSalePrice($user);
        $incomeGoal = $user->contact->income_goal ?? 0;
        $averageCommissionPercent = $this->calculateAverageCommissionPercent($user);
        $initialCap = $user->contact->initial_cap ?? 0;
        $residualCap = $user->contact->residual_cap ?? 0;
        $irs1099Amount = $this->calculate1099Amount($user);

        // Fetch Table Data
        $agentReport = $this->getAgentReportData($user);
        $transactionsSoldYTD = $this->getTransactionsSoldYTD($user);
        $soldByYear = $this->getSoldByYearData($user);

        return view('closing_information.index', compact(
            'transactionCountYTD', 'gciYTD', 'volumeYTD',
            'capAmountPaidYTD', 'averageSalePrice', 'incomeGoal',
            'averageCommissionPercent', 'initialCap', 'residualCap',
            'irs1099Amount', 'agentReport', 'transactionsSoldYTD', 'soldByYear'
        ));
    }

    private function calculateTransactionCountYTD($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();
        $previousFiscalYearStart = $fiscalYearStart->copy()->subYear();
        $previousFiscalYearEnd = $fiscalYearEnd->copy()->subYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            Log::info("User is part of team: " . $user->teamPartnership);
            
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        $currentYearCount = $query->sum('sides');

        $previousYearCount = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$previousFiscalYearStart, $previousFiscalYearEnd])
            ->sum('sides');

        $percentageChange = $this->calculatePercentageChange($currentYearCount, $previousYearCount);

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
            'percentageChange' => $percentageChange
        ];
    }

    private function calculateGCIYTD($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->sum('adjusted_gross_commission');
    }

    private function calculateVolumeYTD($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->sum('calculated_volume');
    }

    private function calculateCapAmountPaidYTD($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->sum('less_split_to_chr');
    }

    private function calculateAverageSalePrice($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->avg('sale_price');
    }

    private function calculateAverageCommissionPercent($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->avg('commission_percent');
    }

    private function calculate1099Amount($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->sum('irs_reported_1099_income_for_this_transaction');
    }

    private function calculatePercentageChange($currentYear, $previousYear)
    {
        if ($previousYear == 0) {
            return $currentYear == 0 ? 0 : 100;
        }
        return (($currentYear - $previousYear) / $previousYear) * 100;
    }

    private function getAgentReportData($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->groupBy('chr_agent_id')
            ->selectRaw('chr_agent_id, count(*) as record_count, sum(calculated_gci) as total_gci, sum(calculated_volume) as total_volume')
            ->get();
    }

    private function getTransactionsSoldYTD($user)
    {
        $fiscalYearStart = Carbon::now()->startOfYear();
        $fiscalYearEnd = Carbon::now()->endOfYear();

        $query = Aci::where('stage', 'Sold')
            ->whereBetween('closing_date', [$fiscalYearStart, $fiscalYearEnd]);

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->groupByRaw('MONTH(closing_date)')
            ->selectRaw('MONTH(closing_date) as month, count(*) as record_count, sum(calculated_gci) as total_gci, sum(calculated_volume) as total_volume')
            ->get();
    }

    private function getSoldByYearData($user)
    {
        $query = Aci::where('stage', 'Sold');

        if ($user->isPartOfTeam()) {
            $query->where('team_partnership_id', $user->teamPartnership->id);
        } else {
            $query->where('chr_agent_id', $user->zoho_id);
        }

        return $query->groupByRaw('YEAR(closing_date)')
            ->selectRaw('YEAR(closing_date) as year, count(*) as record_count, sum(calculated_gci) as total_gci, sum(calculated_volume) as total_volume')
            ->orderBy('year', 'desc')
            ->get();
    }
}