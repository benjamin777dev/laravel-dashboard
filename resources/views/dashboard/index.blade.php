{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@vite(['resources/css/dashboard.css'])
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<div class="container">
    <div class="row mt-4">
        <!-- Goal Thermometer -->
        <div class="card widget-thermometer col-4">
            <div class="card-header">
                My Pipeline - Next 12 Months
            </div>
            <div class="card-body">
                <div class="thermometer-chart-container">
                    <canvas id="thermometerChart"></canvas>
                </div>
                <div class="thermometer-table mt-3">
                    <!-- Table of values -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Potential</th>
                                <th scope="col">Active</th>
                                <th scope="col">Pre-Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${{ number_format($stageData['Potential']['sum'], 2) }}<br>{{ $stageData['Potential']['count'] }} Deals</td>
                                <td>${{ number_format($stageData['Active']['sum'], 2) }}<br>{{ $stageData['Active']['count'] }} Deals</td>
                                <td>${{ number_format($stageData['Pre-Active']['sum'], 2) }}<br>{{ $stageData['Pre-Active']['count'] }} Deals</td>
                            </tr>
                        </tbody>
                    </table>
                    <h4>Not in Pipeline</h4>
                    <table>
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
    </div>

   

    <div class="row mt-4">
        <div class="card widget-monthly-comparison col-4">
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
    $(document).ready(function() {
        var ctx = document.getElementById('thermometerChart').getContext('2d');
        var progress = {{ $progress }};
        var data = [25, 25, 50 - progress, progress]; // Data for doughnut chart
        var thermometerChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: data,
                    backgroundColor: ['#dc3545', '#ffc107', '#28a745', '#fff'],
                    borderColor: '#fff'
                }]
            },
            options: {
                rotation: -90 * Math.PI / 180,
                circumference: 180 * Math.PI / 180,
                cutout: '90%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        var monthlyCtx = document.getElementById('monthlyComparisonChart').getContext('2d');
        var monthlyComparisonChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($allMonths->keys()) !!},
                datasets: [{
                    label: 'Monthly GCI',
                    data: {!! json_encode($allMonths->values()) !!},
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
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection

@endsection
