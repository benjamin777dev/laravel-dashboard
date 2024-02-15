@extends('layouts.app')

@section('title', 'Agent Commander | Pipeline')

@section('content')
<div class="container">
    <h1>Pipeline</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Transaction Name</th>
                <th>Client Name</th>
                <th>Status</th>
                <th>Representing</th>
                <th>Closing Date</th>
                <th>Sales Price</th>
                <th>Commission %</th>
                <th>Potential GCI</th>
                <th>Pipeline Probability</th>
                <th>Probable GCI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deals as $deal)
            <tr>
                <td>{{ $deal['Deal_Name'] ?? 'N/A' }}</td>
                <td>{{ $deal['Primary_Contact'] ?? 'N/A' }}</td>
                <td>{{ $deal['Stage'] ?? 'N/A' }}</td>
                <td>{{ $deal['Representing'] ?? 'N/A' }}</td>
                <td>{{ $deal['Closing_Date'] ?? 'N/A' }}</td>
                <td>{{ $deal['Sale_Price'] ?? 'N/A' }}</td>
                <td>{{ $deal['Commission'] ?? 'N/A' }}%</td>
                <td>{{ $deal['Potential_GCI'] ?? 'N/A' }}</td>
                <td>{{ $deal['Pipeline_Probability'] ?? 'N/A' }}%</td>
                <td>{{ $deal['Pipeline1'] ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection