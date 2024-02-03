{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@vite(['resources/css/dashboard.css'])
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<div class="container">
    <div class="row mt-4">
        <div class="card widget-thermometer col-4">
            <div class="card-header">
                My Pipeline - Next 12 Months
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="customGaugeChart"></canvas>
                </div>
                <div class="thermometer-table mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                @foreach ($stageData as $stage => $data)
                                    <th scope="col">{{ $stage }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($stageData as $data)
                                    <td>${{ number_format($data['sum'], 2) }}<br>{{ $data['count'] }} Deals</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h4>Not in Pipeline</h4>
                <table class="table">
                    <thead>
                        <th scope="col"></th>
                        <th scope="col">Amount</th>
                        <th scope="col">Count</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Beyond 12 Months</td>
                            <td>${{ number_format($beyond12MonthsData['sum'], 2) }}</td>
                            <td>{{ $beyond12MonthsData['count'] }} Deals</td>
                        </tr>
                        <tr>
                            <td>Needs New Date</td>
                            <td>${{ number_format($needsNewDateData['sum'], 2) }}</td>
                            <td>{{ $needsNewDateData['count'] }} Deals</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card widget-monthly-comparison">
            <div class="card-header">
                My Pipeline - Monthly Comparison
            </div>
            <div class="card-body">
                <canvas id="monthlyComparisonChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-4">
            <div class="card">
                <div class="card-header">Database Maintenance</div>
                <div class="card-body">
                    <p>ABC Contacts: {{ $contactData['abcContacts'] }}</p>
                    <p>Needs Email: {{ $contactData['needsEmail'] }}</p>
                    <p>Needs Address: {{ $contactData['needsAddress'] }}</p>
                    <p>Needs Phone: {{ $contactData['needsPhone'] }}</p>
                    <p>Missing ABCD: {{ $contactData['missingAbcd'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="alert alert-info">Average Pipeline Probability: {{ number_format($averagePipelineProbability, 0) }}%</div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success">Transactions Last 30 Days: {{ number_format($newContactsLast30Days, 0) }}</div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-secondary">Contacts Last 30 Days: {{ number_format($newDealsLast30Days, 0) }}</div>
        </div>
    </div>
    <div class="row mt-3">
    <div class="row mt-4">
    {{-- Task Section --}}
    <div class="row mt-4">
        <div class="col-4">
            <div class="card">
                <div class="card-header">Cap data</div>
                <div class="card-body">
                    <p>This is where cap data would be</p>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">Action to Take</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th> <!-- For checkbox -->
                                <th>Subject</th>
                                <th>Due Date</th>
                                <th>Related To</th>
                                <th>Assigned To</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td><input type="checkbox" name="taskCompleted[]" value="{{ $task['id'] }}"></td>
                                    <td>{{ $task['Subject'] ?? 'N/A' }}</td>
                                    <td>{{ $task['Due_Date'] ? Carbon\Carbon::parse($task['Due_Date'])->format('m/d/Y') : 'N/A' }}</td>
                                    <td>{{ $task['Who_Id']['name'] ?? 'N/A' }}</td>
                                    <td>{{ $task['Owner']['name'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-4">
            <div class="card">
                <div class="card-header">Performance Metrics</div>
                <div class="card-body">
                <p><a href="https://analytics.zoho.com/open-view/2487682000008614470/3c546af6361400d5afd39fa034e3f1b9">CHR Rankings Report</a></p>
                <p><a href="https://analytics.zoho.com/open-view/2487682000008657377/8b86fc2667f41985c4de6ebf80d00ba7">CHR Company Production</a></p>
                <p><a href="#">Strategy Group Production (Coming Soon)</a></p>
                <p><a href="https://analytics.zoho.com/open-view/2487682000008655113/74f218cdf16cc52f2a54e19c1f5fdc83">Co-Op Agent Analysis</a></p>

</div>

@vite(['resources/js/dashboard.js'])

@section('dashboardScript')

<script>
document.addEventListener('DOMContentLoaded', function() {
    var canvas = document.getElementById('customGaugeChart');
    var ctx = canvas.getContext('2d');

    // Set the canvas size based on its container
    function resizeCanvas() {
        var container = canvas.parentElement;
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight * 0.75; // Height is 75% of width to maintain aspect ratio
        drawGauge(@json($progress)); // Your progress value from the server
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    function drawGauge(progress) {
        // Clear the canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Variables for gauge drawing
        var centerX = canvas.width / 2;
        var centerY = canvas.height * 0.7;
        var radius = canvas.width * 0.3;

        // Gauge colors
        var colors = {
            red: '#FF0000',
            yellow: '#FFFF00',
            green: '#008000'
        };

        // Draw the background arc
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI, 0, false);
        ctx.lineWidth = 30;
        ctx.strokeStyle = '#ddd';
        ctx.stroke();

        // Draw the colored segments
        ctx.lineWidth = 28;
        ctx.strokeStyle = colors.red;
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI, Math.PI + (0.25 * Math.PI), false);
        ctx.stroke();

        ctx.strokeStyle = colors.yellow;
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI + (0.25 * Math.PI), Math.PI + (0.5 * Math.PI), false);
        ctx.stroke();

        ctx.strokeStyle = colors.green;
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI + (0.5 * Math.PI), 0, false);
        ctx.stroke();

        // Draw the needle
        var needleAngle = Math.PI + (progress / 100) * Math.PI;
        ctx.translate(centerX, centerY);
        ctx.rotate(needleAngle);
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.lineTo(radius * 0.8, 0); // Needle length
        ctx.lineWidth = 5;
        ctx.strokeStyle = '#000000';
        ctx.stroke();
        ctx.rotate(-needleAngle);
        ctx.translate(-centerX, -centerY);

        // Draw the progress text
        ctx.fillStyle = '#000000';
        ctx.font = 'bold ' + (canvas.width / 20) + 'px Arial'; // Font size is relative to canvas width
        ctx.textAlign = 'center';
        ctx.fillText(progress + '%', centerX, centerY + radius);
    }
});
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var monthlyCtx = document.getElementById('monthlyComparisonChart').getContext('2d');
        var monthlyComparisonChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($allMonths->keys()), // Laravel Blade directive
                datasets: [{
                    label: 'Monthly GCI',
                    data: @json($allMonths->values()), // Laravel Blade directive
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        color: '#444',
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value, context) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

@endsection
