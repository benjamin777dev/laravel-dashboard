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
        <div class="col-4">
            <canvas id="monthlyGciChart"></canvas>
        </div>
    </div>
</div>

@vite(['resources/js/dashboard.js'])

@endsection
