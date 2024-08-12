@extends('layouts.master')

@section('title', 'Closing Information')

@section('content')

<!-- Start Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-weight-bold">Closing Information</h4>
        </div>
    </div>
</div>
<!-- End Page Title -->

<!-- Financial Overview -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Financial Overview</h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Income Goal</h6>
                        <h2 class="font-weight-bold">${{ number_format($incomeGoal, 2) }}</h2>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ ($irs1099Amount / $incomeGoal) * 100 }}%" 
                                aria-valuenow="{{ ($irs1099Amount / $incomeGoal) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mt-1 text-muted">1099 Amount Paid: ${{ number_format($irs1099Amount, 2) }}</p>
                    </div>

                    <div class="col-md-3">
                        <h6 class="text-muted">Initial Cap</h6>
                        <h2 class="font-weight-bold">${{ number_format($initialCap, 2) }}</h2>
                    </div>

                    <div class="col-md-3">
                        <h6 class="text-muted">Residual Cap</h6>
                        <h2 class="font-weight-bold">${{ number_format($residualCap, 2) }}</h2>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <h6 class="text-muted">Cap Amount Paid YTD</h6>
                        <h2 class="font-weight-bold">${{ number_format($capAmountPaidYTD, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Overview -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Transaction Overview</h5>
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted">Transaction Count YTD</h6>
                        <h2 class="font-weight-bold">{{ $transactionCountYTD['currentYearCount'] }}</h2>
                        <p class="text-muted">Last Year: {{ $transactionCountYTD['previousYearCount'] }}</p>
                        <span class="badge {{ $transactionCountYTD['percentageChange'] >= 0 ? 'badge-success' : 'badge-danger' }}">
                            <i class="mdi mdi-arrow-{{ $transactionCountYTD['percentageChange'] >= 0 ? 'up' : 'down' }}-bold"></i>
                            {{ $transactionCountYTD['percentageChange'] }}%
                        </span>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-muted">GCI YTD</h6>
                        <h2 class="font-weight-bold">${{ number_format($gciYTD, 2) }}</h2>
                    </div>

                    <div class="col-md-4">
                        <h6 class="text-muted">Volume YTD</h6>
                        <h2 class="font-weight-bold">${{ number_format($volumeYTD, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Metrics -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Average Sale Price & Commission</h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Average Sale Price</h6>
                        <h2 class="font-weight-bold">${{ number_format($averageSalePrice, 2) }}</h2>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Average Commission %</h6>
                        <h2 class="font-weight-bold">{{ number_format($averageCommissionPercent, 2) }}%</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Agent Report</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Co-Listing Agent</th>
                                <th>Record Count</th>
                                <th>Sum of Calculated GCI</th>
                                <th>Sum of Calculated Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agentReport as $report)
                                <tr>
                                    <td>{{ $report->co_listing_agent }}</td>
                                    <td>{{ $report->record_count }}</td>
                                    <td>${{ number_format($report->total_gci, 2) }}</td>
                                    <td>${{ number_format($report->total_volume, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Transactions Sold YTD</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Month</th>
                                <th>Record Count</th>
                                <th>Sum of Calculated GCI</th>
                                <th>Sum of Calculated Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactionsSoldYTD as $transaction)
                                <tr>
                                    <td>{{ Carbon\Carbon::create()->month($transaction->month)->format('F') }}</td>
                                    <td>{{ $transaction->record_count }}</td>
                                    <td>${{ number_format($transaction->total_gci, 2) }}</td>
                                    <td>${{ number_format($transaction->total_volume, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Sold by Year</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Year</th>
                                <th>Record Count</th>
                                <th>Sum of Calculated GCI</th>
                                <th>Sum of Calculated Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($soldByYear as $yearlyData)
                                <tr>
                                    <td>{{ $yearlyData->year }}</td>
                                    <td>{{ $yearlyData->record_count }}</td>
                                    <td>${{ number_format($yearlyData->total_gci, 2) }}</td>
                                    <td>${{ number_format($yearlyData->total_volume, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
