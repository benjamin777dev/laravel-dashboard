@extends('layouts.master')

@section('title', 'Closing Information')

@section('content')

<div class="container">
    <h2>Production Projections Report</h2>

    <!-- Data Table -->
    <table id="productionTable" class="table table-striped">
        <thead>
            <tr>
                <th>Agent Name</th>
                <th>Stage</th>
                <th>Agent Check Amount</th>
                <th>CHR Split</th>
                <th>Total Commission</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
                <tr>
                    <td>{{ $data['agentName'] }}</td>
                    <td>{{ $data['stage'] }}</td>
                    <td>${{ number_format($data['agentCheckAmount'], 2) }}</td>
                    <td>${{ number_format($data['chrSplit'], 2) }}</td>
                    <td>${{ number_format($data['totalCommission'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Chart.js section -->
    <canvas id="productionChart"></canvas>
</div>
@endsection

@section('scripts')
<!-- Include DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#productionTable').DataTable();

        // Prepare data for Chart.js
        var agentNames = @json($reportData->pluck('agentName'));
        var agentCheckAmounts = @json($reportData->pluck('agentCheckAmount'));
        var chrSplits = @json($reportData->pluck('chrSplit'));

        // Chart.js bar chart
        var ctx = document.getElementById('productionChart').getContext('2d');
        var productionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: agentNames,
                datasets: [
                    {
                        label: 'Agent Check Amount',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        data: agentCheckAmounts
                    },
                    {
                        label: 'CHR Split',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        data: chrSplits
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
