@extends('layouts.master')

@section('title', 'Team & Individual Information')

@section('content')

<!-- Start Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-weight-bold">Team & Individual Information</h4>
        </div>
    </div>
</div>
<!-- End Page Title -->

<!-- KPIs Section -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Average Pipeline Probability</h6>
                <h2 class="font-weight-bold">{{ number_format($averagePipelineProbability, 2) }}%</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Average Commission %</h6>
                <h2 class="font-weight-bold">{{ number_format($averageCommPercentage, 2) }}%</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Average Sale Price</h6>
                <h2 class="font-weight-bold">${{ number_format($averageSalePrice, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Pipeline Value</h6>
                <h2 class="font-weight-bold">${{ number_format($pipelineValue, 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Income Goal</h6>
                <h2 class="font-weight-bold">${{ number_format($incomeGoal, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Contacts and Needs Section -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">ABCD Contacts (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $abcdContacts['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $abcdContacts['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Missing ABCD (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $missingAbcd['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $missingAbcd['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Needs Address (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $needsAddress['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $needsAddress['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Needs Phone (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $needsPhone['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $needsPhone['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Needs Email (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $needsEmail['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $needsEmail['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>
</div>

<!-- Open Tasks Section -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">My Open Tasks</h5>
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Task Name</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($openTasks as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->due_date->format('Y-m-d') }}</td>
                                    <td>{{ ucfirst($task->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @foreach($openTasks as $task)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="text-muted">Task Name</h6>
                                <p>{{ $task->name }}</p>
                                <h6 class="text-muted">Due Date</h6>
                                <p>{{ $task->due_date->format('Y-m-d') }}</p>
                                <h6 class="text-muted">Status</h6>
                                <p>{{ ucfirst($task->status) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions and Volume Section -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Transactions - Past 4 Quarters</h5>
                <canvas id="transactionsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Volume - Past 4 Quarters</h5>
                <canvas id="volumeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Pipeline by Month Section -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Pipeline $ by Month</h5>
                <canvas id="pipelineChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- My Groups Section -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">My Groups</h5>
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Group</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myGroups as $group)
                                <tr>
                                    <td>{{ $group->abcd }}</td>
                                    <td>{{ $group->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @foreach($myGroups as $group)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="text-muted">Group</h6>
                                <p>{{ $group->abcd }}</p>
                                <h6 class="text-muted">Count</h6>
                                <p>{{ $group->count }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Assuming you're using Chart.js for the charts
    var ctx = document.getElementById('transactionsChart').getContext('2d');
    var transactionsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($transactionsPastFourQuarters->pluck('quarter')),
            datasets: [{
                label: 'Transactions',
                data: @json($transactionsPastFourQuarters->pluck('count')),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });

    var ctx2 = document.getElementById('volumeChart').getContext('2d');
    var volumeChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($volumePastFourQuarters->pluck('quarter')),
            datasets: [{
                label: 'Volume',
                data: @json($volumePastFourQuarters->pluck('total')),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        }
    });

    var ctx3 = document.getElementById('pipelineChart').getContext('2d');
    var pipelineChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: @json($pipelineByMonth->pluck('month')),
            datasets: [{
                label: 'Pipeline $',
                data: @json($pipelineByMonth->pluck('total')),
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        }
    });
</script>

@endsection
