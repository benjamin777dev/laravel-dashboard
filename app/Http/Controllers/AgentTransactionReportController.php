<?php

namespace App\Http\Controllers;

use App\Models\LocalMLS\LocalMLSRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentTransactionReportController extends Controller
{
    protected $intervals = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'this_week' => 'This Week',
        'last_week' => 'Last 7 Days',
        'last_two_weeks' => 'Last 14 Days',
        'last_month' => 'Last 30 Days',
        'this_month' => 'This Month',
        'last_quarter' => 'Last 90 Days',
        'this_quarter' => 'This Quarter',
        'last_six_months' => 'Last 180 Days',
        'last_year' => 'Last 365 Days',
        'this_year' => 'This Year',
        'custom' => 'Custom Range',
    ];

    public function index()
    {
        // Get all intervals for selection in the blade dropdown
        $intervals = $this->intervals;

        $startDate = date('Y-m-d');
        $endDate = '';
        $minTransactions = 1;
        $maxTransactions = '';

        // Get current year and the last few years for selection
        $years = range(date('Y'), date('Y') - 5);

        // Return the view without results
        return view('reports.localmls.index', compact(
            'intervals',
            'years',
            'startDate',
            'endDate',
            'minTransactions',
            'maxTransactions'
        ));
    }

    public function search(Request $request)
    {
        // Validate input
        $request->validate([
            'interval' => 'required|string',
            'year' => 'required|integer',
            'min_transactions' => 'required|integer|min:1',
            'max_transactions' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $intervals = $this->intervals;
        $interval = $request->input('interval');
        $year = $request->input('year');
        $years = range(date('Y'), date('Y') - 5);

        $minTransactions = $request->input('min_transactions', 1);
        $maxTransactions = $request->input('max_transactions', null);

        // Determine the date range based on the interval
        $dateRange = $this->getDateRange($interval, $request);

        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // Pass the parameters to the view
        return view('reports.localmls.index', compact(
            'years',
            'intervals',
            'year',
            'interval',
            'minTransactions',
            'maxTransactions',
            'startDate',
            'endDate'
        ));
    }

    public function data(Request $request)
    {
        // Retrieve parameters from the request
        $year = $request->input('year');
        $interval = $request->input('interval');
        $minTransactions = $request->input('minTransactions', 1);
        $maxTransactions = $request->input('maxTransactions', null);

        // Determine the date range
        $dateRange = $this->getDateRange($interval, $request);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // Retrieve DataTables parameters
        $draw = $request->input('draw');
        $start = $request->input('start'); // Offset
        $length = $request->input('length'); // Limit
        $searchValue = $request->input('search.value'); // Search value
        $orderColumnIndex = $request->input('order.0.column', 5); // Default to Transaction Count
        $orderDirection = $request->input('order.0.dir', 'desc'); // 'asc' or 'desc'

        // Columns to select
        $columns = [
            0 => 'ListAgentMlsId',
            1 => 'ListAgentFullName',
            2 => 'ListAgentEmail',
            3 => 'ListAgentDirectPhone',
            4 => 'ListOfficeName',
            5 => 'close_count',
            6 => 'close_volume',
        ];

        $orderColumn = $columns[$orderColumnIndex];

        // Build the base query
        $query = LocalMLSRecord::select(
            'ListAgentMlsId',
            DB::raw('MAX(ListAgentFullName) AS ListAgentFullName'),
            DB::raw('MAX(ListAgentEmail) AS ListAgentEmail'),
            DB::raw('MAX(ListAgentDirectPhone) AS ListAgentDirectPhone'),
            DB::raw('MAX(ListOfficeName) AS ListOfficeName'),
            DB::raw('COUNT(*) AS close_count'),
            DB::raw('SUM(ClosePrice) AS close_volume')
        )
            ->where('MlsStatus', 'Closed')
            ->whereBetween('CloseDate', [$startDate, $endDate])
            ->groupBy('ListAgentMlsId');

        // Apply year filter if provided
        if (!empty($year)) {
            $query->whereYear('CloseDate', $year);
        }

        // Apply min and max transactions
        if (!empty($minTransactions) && !empty($maxTransactions)) {
            $query->havingBetween('close_count', [$minTransactions, $maxTransactions]);
        } elseif (!empty($minTransactions)) {
            $query->having('close_count', '>=', $minTransactions);
        }

        // Apply search filter if provided
        if (!empty($searchValue)) {
            $query->having(function ($q) use ($searchValue) {
                $q->orHaving('ListAgentMlsId', 'like', "%{$searchValue}%")
                    ->orHaving('ListAgentFullName', 'like', "%{$searchValue}%")
                    ->orHaving('ListAgentEmail', 'like', "%{$searchValue}%")
                    ->orHaving('ListAgentDirectPhone', 'like', "%{$searchValue}%")
                    ->orHaving('ListOfficeName', 'like', "%{$searchValue}%")
                    ->orHaving('close_count', 'like', "%{$searchValue}%")
                    ->orHaving('close_volume', 'like', "%{$searchValue}%");
            });
        }

        // Get total records count before filtering
        $totalRecordsQuery = clone $query;
        $totalRecords = $totalRecordsQuery->count();

        // Apply ordering
        $query->orderBy($orderColumn, $orderDirection);

        // Apply pagination
        $data = $query->skip($start)->take($length)->get();

        // Prepare the response
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data->map(function ($item) {
                return [
                    $item->ListAgentMlsId,
                    $item->ListAgentFullName,
                    $item->ListAgentEmail,
                    $item->ListAgentDirectPhone,
                    $item->ListOfficeName,
                    $item->close_count,
                    $item->close_volume,
                ];
            }),
        ];

        return response()->json($response);
    }

    public function export(Request $request)
    {
        // Retrieve parameters from the request
        $year = $request->input('year');
        $interval = $request->input('interval');
        $minTransactions = $request->input('minTransactions', 1);
        $maxTransactions = $request->input('maxTransactions', null);

        // Determine the date range
        $dateRange = $this->getDateRange($interval, $request);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // Build the query
        $query = LocalMLSRecord::select(
            'ListAgentMlsId',
            DB::raw('MAX(ListAgentFullName) AS ListAgentFullName'),
            DB::raw('MAX(ListAgentEmail) AS ListAgentEmail'),
            DB::raw('MAX(ListAgentDirectPhone) AS ListAgentDirectPhone'),
            DB::raw('MAX(ListOfficeName) AS ListOfficeName'),
            DB::raw('COUNT(*) AS close_count'),
            DB::raw('SUM(ClosePrice) AS close_volume')
        )
            ->where('MlsStatus', 'Closed')
            ->whereBetween('CloseDate', [$startDate, $endDate])
            ->groupBy('ListAgentMlsId');

        // Apply year filter if provided
        if (!empty($year)) {
            $query->whereYear('CloseDate', $year);
        }

        // Apply min and max transactions
        if (!empty($minTransactions) && !empty($maxTransactions)) {
            $query->havingBetween('close_count', [$minTransactions, $maxTransactions]);
        } elseif (!empty($minTransactions)) {
            $query->having('close_count', '>=', $minTransactions);
        }

        $query->orderBy('close_count', 'DESC');

        $filename = 'agent_transactions_' . date('YmdHis') . '.csv';

        $columns = ['MLS ID', 'Agent Name', 'Email', 'Phone', 'Brokerage', 'Transaction Count', 'Volume'];

        $callback = function () use ($query, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $query->chunk(1000, function ($results) use ($file) {
                foreach ($results as $result) {
                    fputcsv($file, [
                        $result->ListAgentMlsId,
                        $result->ListAgentFullName,
                        $result->ListAgentEmail,
                        $result->ListAgentDirectPhone,
                        $result->ListOfficeName,
                        $result->close_count,
                        $result->close_volume,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    // Helper method to determine date range based on interval
    private function getDateRange($interval, $request)
    {
        $endDate = date('Y-m-d'); // Default end date is today
        $startDate = null;

        switch ($interval) {
            case 'today':
                $startDate = $endDate;
                break;
            case 'yesterday':
                $startDate = date('Y-m-d', strtotime('-1 day'));
                $endDate = $startDate;
                break;
            case 'this_week':
                $startDate = date('Y-m-d', strtotime('monday this week'));
                break;
            case 'last_week':
                $startDate = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'last_two_weeks':
                $startDate = date('Y-m-d', strtotime('-14 days'));
                break;
            case 'last_month':
                $startDate = date('Y-m-d', strtotime('-30 days'));
                break;
            case 'this_month':
                $startDate = date('Y-m-d', strtotime('first day of this month'));
                break;
            case 'last_quarter':
                $startDate = date('Y-m-d', strtotime('-90 days'));
                break;
            case 'this_quarter':
                $startDate = date('Y-m-d', strtotime('first day of ' . $this->getCurrentQuarter()));
                break;
            case 'last_six_months':
                $startDate = date('Y-m-d', strtotime('-180 days'));
                break;
            case 'last_year':
                $startDate = date('Y-m-d', strtotime('-365 days'));
                break;
            case 'this_year':
                $startDate = date('Y-m-d', strtotime('first day of January ' . date('Y')));
                break;
            case 'custom':
                // For custom range, get the start and end dates from the request
                $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                ]);
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');
                break;
            default:
                // Default to last week
                $startDate = date('Y-m-d', strtotime('-7 days'));
                break;
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    // Helper method to get the start date of the current quarter
    private function getCurrentQuarter()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        if ($currentMonth >= 1 && $currentMonth <= 3) {
            return 'January ' . $currentYear;
        } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
            return 'April ' . $currentYear;
        } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
            return 'July ' . $currentYear;
        } else {
            return 'October ' . $currentYear;
        }
    }
}
