@extends('layouts.master')

@section('title', 'Closing Information')

@section('content')

<!-- Start Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Closing Information</h4>
        </div>
    </div>
</div>
<!-- End Page Title -->

<div class="row">
    <!-- Transaction Count YTD -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Transaction Count YTD</h4>
                <h2>{{ $transactionCountYTD['currentYearCount'] }}</h2>
                <p class="{{ $transactionCountYTD['percentageChange'] >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="mdi mdi-arrow-{{ $transactionCountYTD['percentageChange'] >= 0 ? 'up' : 'down' }}-bold"></i>
                    {{ $transactionCountYTD['percentageChange'] }}%
                </p>
                <p>Last Year: {{ $transactionCountYTD['previousYearCount'] }}</p>
            </div>
        </div>
    </div>

    <!-- GCI YTD -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">GCI YTD</h4>
                <h2>${{ number_format($gciYTD, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- Volume YTD -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Volume YTD</h4>
                <h2>${{ number_format($volumeYTD, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Cap Amount Paid YTD -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cap Amount Paid YTD</h4>
                <h2>${{ number_format($capAmountPaidYTD, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- Average Sale Price -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Average Sale Price</h4>
                <h2>${{ number_format($averageSalePrice, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- Average Commission Percent -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Average Commission %</h4>
                <h2>{{ number_format($averageCommissionPercent, 2) }}%</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Initial Cap -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Initial Cap</h4>
                <h2>${{ number_format($initialCap, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- Residual Cap -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Residual Cap</h4>
                <h2>${{ number_format($residualCap, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- IRS 1099 Amount -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">1099 Amount</h4>
                <h2>${{ number_format($irs1099Amount, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Agent Report Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Agent Report</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
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
</div>

<div class="row">
    <!-- Transactions Sold YTD -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Transactions Sold YTD</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
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
</div>

<div class="row">
    <!-- Sold by Year -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Sold by Year</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
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
