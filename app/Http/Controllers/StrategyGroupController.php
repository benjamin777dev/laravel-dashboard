<?php

namespace App\Http\Controllers;

use App\Models\Aci;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StrategyGroupController extends Controller
{
    public function index()
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        // Fetching the strategy group from the current user
        $strategyGroup = $user->strategy_group;
        Log::info("Strategy Group for user: $strategyGroup");

        // Get all users who are part of the same strategy group
        $usersInStrategyGroup = User::where('strategy_group', $strategyGroup)->pluck('root_user_id');
        Log::info("Users in that strategy group: ", ['users' => $usersInStrategyGroup]);

        // Fetching metrics, filtered by strategy group
        $contactsAddedLast30Days = Contact::whereIn('contact_owner', $usersInStrategyGroup)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        $transactionsLast30Days = Deal::whereIn('owner_id', $usersInStrategyGroup)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->whereYear('deals.closing_date', '=', Carbon::now()->year) // Ensure transactions are from this year
            ->where('deals.closing_date', '>=', Carbon::today()) // Ensure closing date is today or later
            ->where(function ($query) {
                $query->where('stage', '!=', 'Sold') // Exclude Sold transactions
                    ->where('stage', 'not like', 'Dead%'); // Exclude any stage starting with 'Dead'
            })
            ->count();

        $transactionsInPipeline = Deal::whereIn('owner_id', $usersInStrategyGroup)
            ->whereYear('deals.closing_date', '=', Carbon::now()->year) // Ensure transactions are from this year
            ->where('deals.closing_date', '>=', Carbon::today()) // Ensure closing date is today or later
            ->where(function ($query) {
                $query->where('stage', '!=', 'Sold') // Exclude Sold transactions
                    ->where('stage', 'not like', 'Dead%'); // Exclude any stage starting with 'Dead'
            })
            ->count();

        $transactionsNeedingNewDates = Deal::whereIn('owner_id', $usersInStrategyGroup)
            ->where('deals.closing_date', '<', Carbon::today())
            ->whereYear('deals.closing_date', '=', Carbon::now()->year) // Ensure transactions are from this year
            ->where(function ($query) {
                $query->where('stage', '!=', 'Sold') // Exclude Sold transactions
                    ->where('stage', 'not like', 'Dead%'); // Exclude any stage starting with 'Dead'
            })
            ->count();

        $contactsMissingABCD = Contact::whereIn('contact_owner', $usersInStrategyGroup)
            ->whereNull('abcd') // Replace 'abcd' with the actual column name
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        // Fetching table data
        $individualPipelineData = $this->getIndividualPipelineData($usersInStrategyGroup);
        $sgBadDates = $this->getSGBadDates($usersInStrategyGroup);
        $individualCloseData = $this->getIndividualCloseData($usersInStrategyGroup);

        // Data for dynamic chart
        $pipelineProgressData = $this->getPipelineProgressData($strategyGroup);
        Log::info($pipelineProgressData);

        return view('strategy-group.index', compact(
            'contactsAddedLast30Days', 'transactionsLast30Days',
            'transactionsNeedingNewDates', 'contactsMissingABCD',
            'individualPipelineData', 'sgBadDates', 'individualCloseData',
            'pipelineProgressData', 'strategyGroup', 'transactionsInPipeline'
        ));
    }

    private function getIndividualPipelineData($usersInStrategyGroup)
    {

        DB::listen(function ($query) {
            Log::info("SQL Query: " . $query->sql);
            Log::info("Bindings: " . implode(", ", $query->bindings));
        });

        return Deal::selectRaw('deals.owner_id, users.name as owner_name, count(deals.id) as record_count, sum(deals.pipeline1) as total_gci, avg(deals.pipeline_probability) as avg_probability')
            ->join('users', 'deals.owner_id', '=', 'users.root_user_id')
            ->whereIn('deals.owner_id', $usersInStrategyGroup)
            ->whereYear('deals.closing_date', '=', Carbon::now()->year) // Ensure transactions are from this year
            ->where('deals.closing_date', '>=', Carbon::today()) // Ensure closing date is today or later
            ->where(function ($query) {
                $query->where('stage', '!=', 'Sold') // Exclude Sold transactions
                    ->where('stage', 'not like', 'Dead%'); // Exclude any stage starting with 'Dead'
            })
            ->groupBy('deals.owner_id', 'users.name')
            ->get();
    }

    private function getSGBadDates($usersInStrategyGroup)
    {
        return Deal::selectRaw('deals.owner_id, users.name as owner_name, count(deals.id) as record_count')
            ->join('users', 'deals.owner_id', '=', 'users.root_user_id')
            ->whereIn('deals.owner_id', $usersInStrategyGroup)
            ->whereYear('deals.closing_date', '=', Carbon::now()->year) // Ensure transactions are from this year
            ->where('deals.closing_date', '<', Carbon::today()) // Closing date is yesterday or earlier
            ->where(function ($query) {
                $query->where('deals.stage', '!=', 'Sold') // Exclude Sold transactions
                    ->where('deals.stage', 'not like', 'Dead%'); // Exclude any stage starting with 'Dead'
            })
            ->groupBy('deals.owner_id', 'users.name')
            ->get();
    }

    private function getIndividualCloseData($usersInStrategyGroup)
    {
        return Aci::from('agent_commission_incomes as aci')
            ->selectRaw('users.name as agent_name, count(aci.id) as record_count, sum(aci.calculated_gci) as total_gci, sum(aci.calculated_volume) as total_volume')
            ->join('deals', 'aci.transaction_id', '=', 'deals.id') // Join ACI with Deals using internal ID
            ->join('users', 'aci.chr_agent_id', '=', 'users.id') // Join ACI with Users to get the agent's name
            ->whereIn('aci.chr_agent_id', function ($query) use ($usersInStrategyGroup) {
                $query->select('id') // Select the internal user ID
                    ->from('users') // Ensure we're querying from the 'users' table
                    ->whereIn('root_user_id', function ($subquery) use ($usersInStrategyGroup) {
                        $subquery->select('contact_owner')
                            ->from('contacts')
                            ->whereIn('contact_owner', $usersInStrategyGroup);
                    });
            })
            ->where('deals.stage', '=', 'Sold') // Only include deals that are marked as 'Sold'
            ->whereYear('deals.closing_date', '=', Carbon::now()->year) // Only include deals with a closing date in the current year
            ->groupBy('aci.chr_agent_id', 'users.name')
            ->get();
    }

    private function getPipelineProgressData($strategyGroup)
    {
        // Initialize the array with the stages
        $pipelineData = [
            'Potential' => 0,
            'Pre-Active' => 0,
            'Active' => 0,
            'Under Contract' => 0,
            'Won' => 0,
            'Lost' => 0,
        ];

        // Fetch the zoho_ids of users in the strategy group
        $zohoIdsInStrategyGroup = User::where('strategy_group', $strategyGroup)->pluck('zoho_id');

        // Fetch the counts for each stage
        $deals = Deal::selectRaw('stage, count(*) as count')
            ->whereIn('contact_name_id', $zohoIdsInStrategyGroup) // Filter by zoho_id matching contact_name_id
            ->whereYear('closing_date', '=', Carbon::now()->year)
            ->groupBy('stage')
            ->get();

        // Map the counts to the pipeline data
        foreach ($deals as $deal) {
            switch ($deal->stage) {
                case 'Potential':
                    $pipelineData['Potential'] = $deal->count;
                    break;
                case 'Pre-Active':
                    $pipelineData['Pre-Active'] = $deal->count;
                    break;
                case 'Active':
                    $pipelineData['Active'] = $deal->count;
                    break;
                case 'Under Contract':
                    $pipelineData['Under Contract'] = $deal->count;
                    break;
                case 'Sold':
                    $pipelineData['Won'] = $deal->count;
                    break;
                default:
                    if (strpos($deal->stage, 'Dead') === 0) {
                        $pipelineData['Lost'] += $deal->count; // Summing all 'Dead%' stages under 'Lost'
                    }
                    break;
            }
        }

        return $pipelineData;
    }
}
