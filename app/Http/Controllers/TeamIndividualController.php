<?php
namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TeamIndividualController extends Controller
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
        $averagePipelineProbability = $this->calculateAveragePipelineProbability($contact, $teamAndPartnership);
        $averageCommPercentage = $this->calculateAverageCommPercentage($contact, $teamAndPartnership);
        $averageSalePrice = $this->calculateAverageSalePrice($contact, $teamAndPartnership);
        $pipelineValue = $this->calculatePipelineValue($contact, $teamAndPartnership);
        $incomeGoal = $contact->income_goal ?? 0;

        // Fetch Table Data
        $abcdContacts = $this->getAbcdContacts($contact, $teamAndPartnership);
        $missingAbcd = $this->getMissingAbcd($contact, $teamAndPartnership);
        $needsAddress = $this->getNeedsAddress($contact, $teamAndPartnership);
        $needsPhone = $this->getNeedsPhone($contact, $teamAndPartnership);
        $needsEmail = $this->getNeedsEmail($contact, $teamAndPartnership);
        $openTasks = $this->getOpenTasks($contact, $teamAndPartnership);
        $transactionsPastFourQuarters = $this->getTransactionsPastFourQuarters($contact, $teamAndPartnership);
        $volumePastFourQuarters = $this->getVolumePastFourQuarters($contact, $teamAndPartnership);
        $pipelineByMonth = $this->getPipelineByMonth($contact, $teamAndPartnership);
        $myGroups = $this->getMyGroups($contact, $teamAndPartnership);

        return view('team_individual.index', compact(
            'averagePipelineProbability', 'averageCommPercentage', 'averageSalePrice',
            'pipelineValue', 'incomeGoal', 'abcdContacts', 'missingAbcd',
            'needsAddress', 'needsPhone', 'needsEmail', 'openTasks',
            'transactionsPastFourQuarters', 'volumePastFourQuarters', 'pipelineByMonth',
            'myGroups'
        ));
    }

    private function buildQuery($contact, $teamAndPartnership, $startDate = null, $endDate = null)
    {
        $query = Deal::query();

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

    private function calculateAveragePipelineProbability($contact, $teamAndPartnership)
    {
        $query = $this->buildQuery($contact, $teamAndPartnership);
        $query->whereIn('stage', ['Potential', 'Pre-Active', 'Active', 'Under Contract']);
        $query->whereBetween('closing_date', [Carbon::now(), Carbon::now()->addYear()]);

        return $query->avg('pipeline_probability');
    }

    private function calculateAverageCommPercentage($contact, $teamAndPartnership)
    {
        $query = $this->buildQuery($contact, $teamAndPartnership);
        $query->whereIn('stage', ['Potential', 'Pre-Active', 'Active', 'Under Contract']);
        $query->whereBetween('closing_date', [Carbon::now(), Carbon::now()->addYear()]);

        return $query->avg('commission_percentage');
    }

    private function calculateAverageSalePrice($contact, $teamAndPartnership)
    {
        $query = $this->buildQuery($contact, $teamAndPartnership);
        $query->whereIn('stage', ['Potential', 'Pre-Active', 'Active', 'Under Contract']);
        $query->whereBetween('closing_date', [Carbon::now(), Carbon::now()->addYear()]);

        return $query->avg('sale_price');
    }

    private function calculatePipelineValue($contact, $teamAndPartnership)
    {
        $query = $this->buildQuery($contact, $teamAndPartnership);
        $query->whereIn('stage', ['Potential', 'Pre-Active', 'Active', 'Under Contract']);
        $query->whereBetween('closing_date', [Carbon::now(), Carbon::now()->addYear()]);

        return $query->sum('probable_gci');
    }

    private function getAbcdContacts($contact, $teamAndPartnership)
    {
        $query = Contact::query();
        $query->whereIn('abcd', ['A+', 'A', 'B', 'C']);
        $query->whereYear('created_at', Carbon::now()->year);

        if ($teamAndPartnership) {
            $query->where('team_partnership_id', $teamAndPartnership->team_partnership_id);
        } else {
            $query->where('owner_id', $contact->zoho_contact_id);
        }

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getMissingAbcd($contact, $teamAndPartnership)
    {
        $query = Contact::query();
        $query->whereNull('abcd');
        $query->whereYear('created_at', Carbon::now()->year);

        if ($teamAndPartnership) {
            $query->where('team_partnership_id', $teamAndPartnership->team_partnership_id);
        } else {
            $query->where('owner_id', $contact->zoho_contact_id);
        }

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getNeedsAddress($contact, $teamAndPartnership)
    {
        $query = Contact::query();
        $query->where(function ($q) {
            $q->whereNull('mailing_street')
                ->orWhereNull('mailing_city')
                ->orWhereNull('mailing_state')
                ->orWhereNull('mailing_zip');
        });
        $query->whereYear('created_at', Carbon::now()->year);

        if ($teamAndPartnership) {
            $query->where('team_partnership_id', $teamAndPartnership->team_partnership_id);
        } else {
            $query->where('owner_id', $contact->zoho_contact_id);
        }

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getNeedsPhone($contact, $teamAndPartnership)
    {
        $query = Contact::query();
        $query->whereNull('phone');
        $query->whereYear('created_at', Carbon::now()->year);

        if ($teamAndPartnership) {
            $query->where('team_partnership_id', $teamAndPartnership->team_partnership_id);
        } else {
            $query->where('owner_id', $contact->zoho_contact_id);
        }

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getNeedsEmail($contact, $teamAndPartnership)
    {
        $query = Contact::query();
        $query->whereNull('email');
        $query->whereYear('created_at', Carbon::now()->year);

        if ($teamAndPartnership) {
            $query->where('team_partnership_id', $teamAndPartnership->team_partnership_id);
        } else {
            $query->where('owner_id', $contact->zoho_contact_id);
        }

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getOpenTasks($contact, $teamAndPartnership)
    {
        $query = Task::query();
        $query->where('status', 'open');

        if ($teamAndPartnership) {
            $query->where('team_partnership_id', $teamAndPartnership->team_partnership_id);
        } else {
            $query->where('owner_id', $contact->zoho_contact_id);
        }

        return $query->get();
    }

    private function getTransactionsPastFourQuarters($contact, $teamAndPartnership)
    {
        $startDate = Carbon::now()->subYear()->startOfQuarter();
        $endDate = Carbon::now()->endOfQuarter();

        $query = $this->buildQuery($contact, $teamAndPartnership, $startDate, $endDate);
        $query->where('stage', 'sold');

        return $query->groupByRaw('QUARTER(closing_date)')
            ->selectRaw('QUARTER(closing_date) as quarter, COUNT(*) as count')
            ->get();
    }

    private function getVolumePastFourQuarters($contact, $teamAndPartnership)
    {
        $startDate = Carbon::now()->subYear()->startOfQuarter();
        $endDate = Carbon::now()->endOfQuarter();

        $query = $this->buildQuery($contact, $teamAndPartnership, $startDate, $endDate);
        $query->where('stage', 'sold');

        return $query->groupByRaw('QUARTER(closing_date)')
            ->selectRaw('QUARTER(closing_date) as quarter, SUM(sale_price) as total')
            ->get();
    }

    private function getPipelineByMonth($contact, $teamAndPartnership)
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        $query = $this->buildQuery($contact, $teamAndPartnership, $startDate, $endDate);
        $query->whereNotIn('stage', ['sold', 'dead']);

        return $query->groupByRaw('MONTH(closing_date), YEAR(closing_date)')
            ->selectRaw('MONTH(closing_date) as month, YEAR(closing_date) as year, SUM(probable_gci) as total')
            ->get();
    }

    private function getMyGroups($contact, $teamAndPartnership)
    {
        $query = Contact::query();

        if ($teamAndPartnership) {
            $query->where('team_partnership_id', $teamAndPartnership->team_partnership_id);
        } else {
            $query->where('owner_id', $contact->zoho_contact_id);
        }

        return $query->selectRaw('abcd, COUNT(*) as count')
            ->groupBy('abcd')
            ->get();
    }
}
