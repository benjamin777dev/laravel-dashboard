{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@vite(['resources/css/dashboard.css'])
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>


<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    My Pipeline - Next 12 Months
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="customGaugeChart"></canvas>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Stage</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Deals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stageData as $stage => $data)
                                    <tr>
                                        <td>{{ $stage }}</td>
                                        <td>${{ $data['sum'] }}</td>
                                        <td>{{ $data['count'] }} Deals</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="row">Current Pipeline Value</th>
                                    <td colspan="2">${{ $currentPipelineValue }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">My Projected Income</th>
                                    <td colspan="2">${{ $projectedIncome }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">My Income Goal</th>
                                    <td colspan="2">${{ $goal }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Not in Pipeline</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Beyond 12 Months</td>
                                    <td>${{ $beyond12MonthsData['sum'] }}</td>
                                    <td>{{ $beyond12MonthsData['count'] }} Deals</td>
                                </tr>
                                <tr>
                                    <td>Needs New Date</td>
                                    <td>${{ $needsNewDateData['sum'] }}</td>
                                    <td>{{ $needsNewDateData['count'] }} Deals</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ url('/manage-pipeline') }}" class="btn btn-primary">Manage Pipeline</a>
                </div>
            </div>
        </div>
    </div>
</div>



@vite(['resources/js/dashboard.js'])

@section('dashboardScript')

<script>
document.addEventListener('DOMContentLoaded', function() {
    var canvas = document.getElementById('customGaugeChart');
    var ctx = canvas.getContext('2d');
    var progress = @json($progress); // Your progress value from the server

    // Resize the canvas and draw the gauge accordingly
    function resizeCanvas() {
        var container = document.querySelector('.widget-thermometer');
        canvas.width = container.offsetWidth /1.1; // Set the canvas width to the width of the container
        canvas.height = container.offsetWidth / 2; // Keep the canvas height half of the width
        drawGauge();
    }

    function drawGauge() {
    ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas before redrawing

    var centerX = canvas.width / 2;
    var centerY = canvas.height * 0.95; // Lower the center to give more space at the top
    var radius = canvas.width * 0.45; // Reduce the radius to ensure it fits in the canvas

    // Draw the red segment
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, Math.PI, Math.PI * 1.25, false);
    ctx.strokeStyle = 'red';
    ctx.lineWidth = radius * 0.2;
    ctx.stroke();

    // Draw the yellow segment
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, Math.PI * 1.25, Math.PI * 1.5, false);
    ctx.strokeStyle = 'yellow';
    ctx.lineWidth = radius * 0.2;
    ctx.stroke();

    // Draw the green segment
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, Math.PI * 1.5, 2 * Math.PI, false);
    ctx.strokeStyle = 'green';
    ctx.lineWidth = radius * 0.2;
    ctx.stroke();

    // Draw the needle
    var needleAngle = Math.PI + (progress / 100) * Math.PI;
    ctx.beginPath();
    ctx.moveTo(centerX, centerY);
    ctx.lineTo(centerX + radius * Math.cos(needleAngle), centerY + radius * Math.sin(needleAngle));
    ctx.strokeStyle = '#333';
    ctx.lineWidth = 5;
    ctx.stroke();

    // Draw the center circle for the needle
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius * 0.05, 0, Math.PI * 2, false);
    ctx.fillStyle = '#333';
    ctx.fill();

    // Draw the progress text
    ctx.fillStyle = '#000';
    ctx.font = 'bold 20px Arial';
    ctx.textAlign = 'center';
    ctx.fillText(progress + '%', centerX, centerY - radius / 2);
}

    function drawSegment(x, y, r, startAngle, endAngle, color, lineWidth) {
        ctx.beginPath();
        ctx.arc(x, y, r, startAngle, endAngle, false);
        ctx.strokeStyle = color;
        ctx.lineWidth = lineWidth;
        ctx.stroke();
    }

    function drawNeedle(x, y, angle, length) {
        ctx.beginPath();
        ctx.moveTo(x, y);
        ctx.lineTo(x + length * Math.cos(angle), y + length * Math.sin(angle));
        ctx.strokeStyle = '#333';
        ctx.lineWidth = 5;
        ctx.stroke();
    }

    function drawProgressText(x, y, text) {
        ctx.fillStyle = '#000';
        ctx.font = 'bold 20px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(text, x, y);
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas); // Redraw the gauge on window resize
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
