<?php
namespace App\Http\Controllers;

use App\Models\DealStageHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportStageHistoryController extends Controller
{
    public function showJourneyMap(Request $request)
    {
        $selectedYear = $request->get('year', now()->year);
        $agentId = $request->get('agent_id', null); // Get the selected agent ID, if any

        // Get unique years from the deal_stage_histories table
        $years = DealStageHistory::selectRaw('YEAR(modified_time) as year')
            ->groupBy('year')
            ->pluck('year')
            ->toArray();

        // Fetch agent list by joining users and contacts where users have a valid zoho_id
        $agents = DB::table('users')
            ->join('contacts', 'users.zoho_id', '=', 'contacts.zoho_contact_id')
            ->join('deals', 'deals.contact_name_id', '=', 'users.zoho_id')
            ->join('deal_stage_histories', 'deal_stage_histories.zoho_deal_id', '=', 'deals.zoho_deal_id') // Ensure only deals with stage histories
            ->whereNotNull('users.zoho_id')
            ->whereYear('deal_stage_histories.modified_time', '=', $selectedYear) // Ensure the deal history is in the selected year
            ->select(
                'users.id',
                'contacts.zoho_contact_id',
                DB::raw("CONCAT(contacts.first_name, ' ', contacts.last_name, ' - ', COUNT(DISTINCT deals.zoho_deal_id)) AS full_name") // Concatenate last name with transaction count, ensuring distinct deal IDs
            )
            ->groupBy('users.id', 'contacts.zoho_contact_id', 'contacts.first_name', 'contacts.last_name') // Group by necessary columns
            ->orderBy('contacts.first_name') // Sort by first name
            ->orderBy('contacts.last_name') // Then sort by last name
            ->get();

        // SQL query to get the data for the selected year and optionally the selected agent
        $query = DB::select(<<<SQL
            WITH InitialStages AS (
                -- Find the earliest stage for each deal (the initial stage)
                SELECT 
                    d.zoho_deal_id,
                    MIN(dsh.modified_time) AS first_modified_time,
                    CASE
                        WHEN dsh.stage IN ('Pre Active', 'Pre-Active') THEN 'Pre-Active'
                        WHEN dsh.stage IN ('Dead-Lost To Competition', 'Dead-Contract Terminated') THEN 'Dead'
                        ELSE dsh.stage
                    END AS initial_stage
                FROM deal_stage_histories dsh
                JOIN deals d ON dsh.zoho_deal_id = d.zoho_deal_id
                WHERE YEAR(dsh.modified_time) = ?
                AND (d.contact_name_id = ? OR ? IS NULL)
                GROUP BY d.zoho_deal_id, dsh.stage
            ),
            StageTransitions AS (
                -- Get all transitions for each deal
                SELECT
                    d.zoho_deal_id,
                    MIN(dsh.modified_time) AS min_time,
                    CASE
                        WHEN dsh.stage IN ('Pre Active', 'Pre-Active') THEN 'Pre-Active'
                        WHEN dsh.stage IN ('Dead-Lost To Competition', 'Dead-Contract Terminated') THEN 'Dead'
                        ELSE dsh.stage
                    END AS current_stage,
                    LEAD(
                        CASE
                            WHEN dsh.stage IN ('Pre Active', 'Pre-Active') THEN 'Pre-Active'
                            WHEN dsh.stage IN ('Dead-Lost To Competition', 'Dead-Contract Terminated') THEN 'Dead'
                            ELSE dsh.stage
                        END
                    ) OVER (PARTITION BY d.zoho_deal_id ORDER BY dsh.modified_time) AS next_stage
                FROM deal_stage_histories dsh
                JOIN deals d ON dsh.zoho_deal_id = d.zoho_deal_id
                WHERE YEAR(dsh.modified_time) = ?
                AND (d.contact_name_id = ? OR ? IS NULL)
                GROUP BY dsh.zoho_deal_id, dsh.stage, dsh.modified_time, d.zoho_deal_id
            ),
            Aggregated AS (
                SELECT
                    current_stage,
                    CASE
                        WHEN next_stage IS NULL AND current_stage IN ('Sold', 'Dead') THEN 'Complete'
                        WHEN next_stage IS NULL THEN 'Stalled'
                        ELSE next_stage
                    END AS next_stage,
                    COUNT(DISTINCT zoho_deal_id) AS count
                FROM StageTransitions
                GROUP BY current_stage, next_stage
            ),
            TotalByStage AS (
                SELECT
                    CASE
                        WHEN stage IN ('Pre Active', 'Pre-Active') THEN 'Pre-Active'
                        WHEN stage IN ('Dead-Lost To Competition', 'Dead-Contract Terminated') THEN 'Dead'
                        ELSE stage
                    END AS current_stage,
                    COUNT(DISTINCT zoho_deal_id) AS total_entered
                FROM deal_stage_histories
                WHERE YEAR(modified_time) = ?
                GROUP BY current_stage
            )
            SELECT
                Aggregated.current_stage,
                Aggregated.next_stage,
                Aggregated.count AS transition_count,
                COALESCE(TotalByStage.total_entered, 0) AS total_entered_stage,
                COALESCE(
                    (SELECT COUNT(*) FROM InitialStages WHERE initial_stage = Aggregated.current_stage),
                    0
                ) AS started_at_stage -- Count how many deals started at each stage
            FROM Aggregated
            LEFT JOIN TotalByStage ON Aggregated.current_stage = TotalByStage.current_stage
            ORDER BY Aggregated.current_stage;
        SQL, [$selectedYear, $agentId, $agentId, $selectedYear, $agentId, $agentId, $selectedYear]);

        // Collect the results from the query
        $sqlData = collect($query);
        Log::info("SQL Data: ", ['sqlData' => $sqlData]);

        // Transform the raw SQL results into a usable array for the view
        $journeyData = $sqlData->map(function ($item) {
            // If `next_stage` is null, handle it based on the current stage
            if (is_null($item->next_stage)) {
                // If the current stage is terminal (Sold or Dead), mark as 'Complete'
                if ($item->current_stage === 'Sold' || $item->current_stage === 'Dead') {
                    $item->next_stage = 'Complete';
                } else {
                    // Otherwise, it's a stalled deal
                    $item->next_stage = 'Stalled';
                }
            } elseif ($item->current_stage === $item->next_stage) {
                $item->next_stage = 'Unchanged';
            }

            // Return the transformed data
            return [
                'current_stage' => $item->current_stage,
                'next_stage' => $item->next_stage,
                'transition_count' => $item->transition_count, // Use the correct column from the query
                'total_entered_stage' => $item->total_entered_stage, // Include total deals that entered this stage
                'started_at_stage' => $item->started_at_stage // Include total deals that started at this stage
            ];
        });

        Log::info("Journey Data: ", ['journeyData' => $journeyData]);

        if ($request->ajax()) {
            return response()->json([
                'journeyData' => $journeyData,
                'selectedYear' => $selectedYear,
            ]);
        }

        // Pass data to the view
        return view('reports.dealstages.deal_stage_journey', [
            'data' => $journeyData,
            'years' => $years,
            'agents' => $agents,
            'selectedYear' => $selectedYear,
            'selectedAgentId' => $agentId,
        ]);
    }
}
