<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\DatabaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TeamIndividualController extends Controller
{
    protected $db;

    public function __construct(DatabaseService $db)
    {
        $this->db = $db;
    }

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

        $accessToken = $user->getAccessToken();
        $deals = $this->db->retrieveDeals($user, $accessToken, null, null, null, null, null, true);

        // Fetch KPIs
        $averagePipelineProbability = $this->calculateAveragePipelineProbability($deals);
        $averageCommPercentage = $this->calculateAverageCommPercentage($deals);
        $averageSalePrice = $this->calculateAverageSalePrice($deals);
        $pipelineValue = $this->calculatePipelineValue($deals);
        $incomeGoal = $contact->income_goal ?? 0;

        // Fetch Table Data
        $abcdContacts = $this->getAbcdContacts($user);
        $missingAbcd = $this->getMissingAbcd($user);
        $needsAddress = $this->getNeedsAddress($user);
        $needsPhone = $this->getNeedsPhone($user);
        $needsEmail = $this->getNeedsEmail($user);
        $openTasks = $this->getOpenTasks($user);
        $transactionsPastFourQuarters = $this->getTransactionsPastFourQuarters($deals);
        $volumePastFourQuarters = $this->getVolumePastFourQuarters($deals);
        $pipelineByMonth = $this->getPipelineByMonth($deals);
        $myGroups = $this->getMyGroups($user);

        return view('team_individual.index', compact(
            'averagePipelineProbability', 'averageCommPercentage', 'averageSalePrice',
            'pipelineValue', 'incomeGoal', 'abcdContacts', 'missingAbcd',
            'needsAddress', 'needsPhone', 'needsEmail', 'openTasks',
            'transactionsPastFourQuarters', 'volumePastFourQuarters', 'pipelineByMonth',
            'myGroups'
        ));
    }

    private function calculateAveragePipelineProbability($deals)
    {
        $filteredDeals = $deals->whereIn('stage', ['Potential', 'Pre-Active', 'Active', 'Under Contract'])
            ->whereBetween('closing_date', [Carbon::now(), Carbon::now()->addYear()]);

        return $filteredDeals->avg('pipeline_probability');
    }

    private function calculateAverageCommPercentage($deals)
    {
        $filteredDeals = $deals->whereIn('stage', ['Potential', 'Pre-Active', 'Active', 'Under Contract'])
            ->whereBetween('closing_date', [Carbon::now(), Carbon::now()->addYear()]);

        return $filteredDeals->avg('commission');
    }

    private function calculateAverageSalePrice($deals)
    {
        $filteredDeals = $deals->whereIn('stage', ['Potential', 'Pre-Active', 'Active', 'Under Contract'])
            ->whereBetween('closing_date', [Carbon::now(), Carbon::now()->addYear()]);

        return $filteredDeals->avg('sale_price');
    }

    private function calculatePipelineValue($deals)
    {
        $filteredDeals = $deals->whereIn('stage', ['Potential', 'Pre-Active', 'Active', 'Under Contract'])
            ->whereBetween('closing_date', [Carbon::now(), Carbon::now()->addYear()]);

        $totalProbableGCI = 0;

        foreach ($filteredDeals as $deal) {
            $salePrice = $deal->sale_price ?? 0;
            $commission = $deal->commission ?? 0;
            $pipelineProbability = $deal->pipeline_probability ?? 0;

            $totalProbableGCI += ($salePrice * ($commission / 100)) * ($pipelineProbability / 100);
        }

        return $totalProbableGCI;
    }

    private function getAbcdContacts($user)
    {
        $query = Contact::query();
        $query->whereIn('abcd', ['A+', 'A', 'B', 'C']);
        $query->whereYear('created_at', Carbon::now()->year);

        $query->where('contact_owner', $user->root_user_id);

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getMissingAbcd($user)
    {
        $query = Contact::query();
        $query->where(function ($q) {
            $q->whereNull('abcd')
                ->orWhere('abcd', '');
        });
        $query->whereYear('created_at', Carbon::now()->year);

        $query->where('contact_owner', $user->root_user_id);

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getNeedsAddress($user)
    {
        $query = Contact::query();
        $query->where(function ($q) {
            $q->whereNull('mailing_address')
                ->orWhere('mailing_address', '')
                ->orWhereNull('mailing_city')
                ->orWhere('mailing_city', '')
                ->orWhereNull('mailing_state')
                ->orWhere('mailing_state', '')
                ->orWhereNull('mailing_zip')
                ->orWhere('mailing_zip', '');
        });
        $query->whereYear('created_at', Carbon::now()->year);

        $query->where('contact_owner', $user->root_user_id);

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getNeedsPhone($user)
    {
        $query = Contact::query();
        $query->where(function ($q) {
            $q->whereNull('phone')
                ->orWhere('phone', '');
        });
        $query->whereYear('created_at', Carbon::now()->year);

        $query->where('contact_owner', $user->root_user_id);

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getNeedsEmail($user)
    {
        $query = Contact::query();
        $query->where(function ($q) {
            $q->whereNull('email')
                ->orWhere('email', '');
        });
        $query->whereYear('created_at', Carbon::now()->year);

        $query->where('contact_owner', $user->root_user_id);

        $currentYearCount = $query->count();

        $query->whereYear('created_at', Carbon::now()->subYear()->year);
        $previousYearCount = $query->count();

        return [
            'currentYearCount' => $currentYearCount,
            'previousYearCount' => $previousYearCount,
        ];
    }

    private function getOpenTasks($user)
    {
        $db = new DatabaseService();
        $accessToken = $user->getAccessToken();

        return $db->retreiveTasks($user, $accessToken, 'In Progress');
    }

    private function getTransactionsPastFourQuarters($deals)
    {
        // Group deals by year and quarter
        $data = $deals->groupBy(function ($deal) {
            $date = Carbon::parse($deal['closing_date']);
            $yearQuarter = $date->year . '-Q' . ceil($date->month / 3); // Calculate the quarter
            return $yearQuarter;
        })
        ->map(function ($group, $quarter) {
            return [
                'quarter' => $quarter,
                'count' => $group->count(),
            ];
        })
        ->values(); // Reset the keys to have a clean collection
        Log::info("transactions alst 4 quarters data: ", ['data' => $data]);
        return $data;
    }

    private function getVolumePastFourQuarters($deals)
    {
        $data = $deals->groupBy(function ($deal) {
            $date = Carbon::parse($deal['closing_date']);
            $yearQuarter = $date->year . '-Q' . ceil($date->month / 3); // Calculate the quarter
            return $yearQuarter;
        })
        ->map(function ($group, $quarter) {
            // Filter deals to only include those with the stage 'Sold'
            return [
                'quarter' => $quarter,
                'total' => $group->sum('sale_price'),
            ];
        })
        ->values();; // Reset the keys to have a clean collection
        Log::info("volume alst 4 quarters data: ", ['data' => $data]);
        return $data;
    }

    private function getPipelineByMonth($deals)
    {
        return $deals->whereNotIn('stage', ['Sold', 'Dead'])
            ->groupBy(function ($deal) {
                return Carbon::parse($deal['closing_date'])->format('Y-m'); // Group by year and month
            })
            ->map(function ($group, $month) {
                $total = $group->sum(function ($deal) {
                    return ($deal['sale_price'] * ($deal['commission'] / 100)) * ($deal['pipeline_probability'] / 100);
                });
                return [
                    'month' => $month,
                    'total' => $total,
                ];
            })
            ->values(); // Return as a collection of arrays
    }

    private function getMyGroups($user)
    {
        $query = Contact::query();

        // Filter by the user's root_user_id, assuming that contact_owner represents a user's root_user_id
        $query->where('contact_owner', $user->root_user_id);

        // Log the raw SQL query for debugging
        Log::info('Raw SQL Query:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

        // Fetch the results
        $groups = $query->selectRaw('abcd, COUNT(*) as count')
            ->groupBy('abcd')
            ->get();

        // Log the results for further analysis
        Log::info('Groups Query Result:', ['groups' => $groups->toArray()]);

        // Return the grouped data
        return $groups;
    }
}
