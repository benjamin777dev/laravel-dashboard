@extends('layouts.master')

@section('title', 'Strategy Group')

@section('content')

<!-- Start Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-weight-bold">{{ $strategyGroup }} Strategy Group</h4>
        </div>
    </div>
</div>
<!-- End Page Title -->

<!-- Metrics Overview -->
<div class="row">
    <div class="col-md-3">
        <h6 class="text-muted">Contacts Added Last 30 Days</h6>
        <h2 class="font-weight-bold">{{ number_format($contactsAddedLast30Days) }}</h2>
    </div>
    <div class="col-md-3">
        <h6 class="text-muted">Transactions Last 30 Days</h6>
        <h2 class="font-weight-bold">{{ number_format($transactionsLast30Days) }} <small>of {{ number_format($transactionsInPipeline) }} in pipeline</small></h2>
    </div>
    <div class="col-md-3">
        <h6 class="text-muted">Transactions Needing New Dates</h6>
        <h2 class="font-weight-bold">{{ number_format($transactionsNeedingNewDates) }}</h2>
    </div>
    <div class="col-md-3">
        <h6 class="text-muted">Contacts Missing ABCD</h6>
        <h2 class="font-weight-bold">{{ number_format($contactsMissingABCD) }}</h2>
    </div>
</div>

<!-- Tables Overview -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Individual Pipeline Data</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Transaction Owner</th>
                                <th>Record Count</th>
                                <th>Sum of Probable GCI</th>
                                <th>Average of Pipeline Probability</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($individualPipelineData as $data)
                                <tr>
                                    <td>{{ $data->owner_name }}</td>
                                    <td>{{ number_format($data->record_count) }}</td>
                                    <td>${{ number_format($data->total_gci, 2) }}</td>
                                    <td>{{ number_format($data->avg_probability, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">SG Bad Dates</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Transaction Owner</th>
                                <th>Record Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sgBadDates as $data)
                                <tr>
                                    <td>{{ $data->owner_name }}</td>
                                    <td>{{ number_format($data->record_count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Individual Close Data</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Agent User</th>
                                <th>Record Count</th>
                                <th>Sum of Calculated GCI</th>
                                <th>Sum of Calculated Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($individualCloseData as $data)
                                <tr>
                                    <td>{{ $data->agent_name }}</td>
                                    <td>{{ number_format($data->record_count) }}</td>
                                    <td>${{ number_format($data->total_gci, 2) }}</td>
                                    <td>${{ number_format($data->total_volume, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Funnel Chart -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Pipeline Funnel</h5>
                <div id="pipelineProgressFunnel" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript">
    var options = {
        chart: {
            type: 'funnel',
            height: 400,
        },
        series: [{
            name: 'Deals',
            data: [
                @json($pipelineProgressData['Potential']),
                @json($pipelineProgressData['Pre-Active']),
                @json($pipelineProgressData['Active']),
                @json($pipelineProgressData['Under Contract']),
                @json($pipelineProgressData['Won']),
                @json($pipelineProgressData['Lost'])
            ]
        }],
        labels: ['Potential', 'Pre-Active', 'Active', 'Under Contract', 'Won', 'Lost'],
        plotOptions: {
            funnel: {
                curve: {
                    enabled: true
                },
                dynamicSlope: true,
                dynamicHeight: true,
            }
        },
        fill: {
            colors: ['#007bff', '#17a2b8', '#28a745', '#ffc107', '#dc3545', '#6c757d']
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
                return opts.w.globals.labels[opts.dataPointIndex] + ": " + val;
            },
            style: {
                fontSize: '14px',
                colors: ['#000']
            }
        },
        legend: {
            show: false
        }
    };

    var chart = new ApexCharts(document.querySelector("#pipelineProgressFunnel"), options);
    chart.render();
</script>

@endsection
