{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@vite(['resources/css/dashboard.css?v=12124.1933'])

<div class="container">
    <div class="goal-thermometer">
        <div class="progress-bar">
            <div class="progress {{ $progressClass}}" style="width: {{ $progress }}%;color:{{$progressTextColor}}!important;">{{ $progress }}%</div>
        </div>
        <div class="goal-markers">
            <div class="marker" style="left: 15%;"></div> <!-- Marker for 15% -->
            <div class="marker" style="left: 45%;"></div> <!-- Marker for 45% -->
        </div>
    </div>
</div>
@vite(['resources/js/dashboard.js'])

@endsection


