{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@vite(['resources/css/dashboard.css'])

<div class="container">
    <!-- Goal Thermometer -->
    <div class="goal-thermometer">
        <div class="progress-bar">
            <div class="progress {{ $progressClass }}" style="width: {{ $progress }}%; color: {{ $progressTextColor }} !important;">
                {{ $progress }}%
            </div>
        </div>
        <div class="goal-markers">
            <div class="marker" style="left: 15%;"></div> <!-- Marker for 15% -->
            <div class="marker" style="left: 45%;"></div> <!-- Marker for 45% -->
        </div>
    </div>

    <!-- Dashboard Data -->
    <div class="row mt-4">
        @foreach ($stageData as $stage => $data)
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-header">{{ $stage }}</div>
                    <div class="card-body">
                        <h5 class="card-title">${{ number_format($data['sum'], 2) }}</h5>
                        <p class="card-text">{{ $data['count'] }} Deals</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Additional Information -->
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="alert alert-info">Current Pipeline Value: ${{ number_format($currentPipelineValue, 2) }}</div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success">Projected Income: ${{ number_format($projectedIncome, 2) }}</div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-secondary">My Income Goal: ${{ number_format($goal, 2) }}</div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="alert alert-warning">Beyond 12 Months: ${{ number_format($beyond12MonthsData['sum'], 2) }} ({{ $beyond12MonthsData['count'] }} Deals)</div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-danger">Needs New Date: ${{ number_format($needsNewDateData['sum'], 2) }} ({{ $needsNewDateData['count'] }} Deals)</div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-8">
            <canvas id="monthlyGciChart"></canvas>
        </div>
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
    <div class="card">
                <div class="card-header">Task Management</div>
                <div class="card-body">
                </div>
            </div>
    </div>
</div>

@vite(['resources/js/dashboard.js'])

@section('dashboardScript')
    <script>
        $(document).ready(function() {

            var ctx = document.getElementById('monthlyGciChart').getContext('2d');
            if (!ctx) return;
            var monthlyGciChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($allMonths->keys()) !!},
                    datasets: [{
                        label: 'Monthly GCI',
                        data: {!! json_encode($allMonths->values()) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
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

@endsection
