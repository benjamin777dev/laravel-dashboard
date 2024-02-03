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
    <div class="row mt-4">
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

    // Dynamically adjust the canvas size to the parent container
    function resizeCanvas() {
        var container = canvas.parentElement;
        // Set canvas dimensions proportionally to the container size
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight;
        drawGauge(@json($progress));
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas(); // Set initial size

    function drawGauge(progress) {
        // Clear the entire canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        var centerX = canvas.width / 2;
        var centerY = canvas.height * 0.8; // Position the center slightly higher
        var radius = canvas.width / 3; // Gauge radius

        // Draw the segments
        drawSegment(centerX, centerY, radius, 0.75 * Math.PI, 1 * Math.PI, '#FF0000'); // Red segment
        drawSegment(centerX, centerY, radius, 0.5 * Math.PI, 0.75 * Math.PI, '#FFFF00'); // Yellow segment
        drawSegment(centerX, centerY, radius, 0.25 * Math.PI, 0.5 * Math.PI, '#00FF00'); // Green segment

        // Draw the needle
        drawNeedle(centerX, centerY, radius, progress);

        // Draw the percentage text below the gauge
        ctx.font = 'bold ' + (canvas.width / 15) + 'px Arial'; // Font size is responsive to the canvas width
        ctx.fillStyle = 'black';
        ctx.textAlign = 'center';
        ctx.fillText(progress + '%', centerX, centerY + radius / 2);
    }

    function drawSegment(cx, cy, r, startAngle, endAngle, color) {
        ctx.beginPath();
        ctx.arc(cx, cy, r, startAngle, endAngle, false);
        ctx.lineWidth = r * 0.2; // Thickness of the gauge
        ctx.strokeStyle = color;
        ctx.stroke();
    }

    function drawNeedle(cx, cy, r, value) {
        ctx.save();
        ctx.translate(cx, cy);
        var angle = Math.PI + (Math.PI * value / 100); // Convert percentage to angle
        ctx.rotate(angle); // Rotate the canvas by the calculated angle

        // Draw the needle triangle
        ctx.beginPath();
        ctx.moveTo(-r * 0.05, 0);
        ctx.lineTo(r * 0.9, 0); // Adjust needle length
        ctx.lineTo(-r * 0.05, -r * 0.05); // Adjust needle width
        ctx.fillStyle = '#000';
        ctx.fill();

        ctx.restore(); // Restore canvas rotation to its original state
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
