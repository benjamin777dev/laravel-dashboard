@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline')

@section('content')
@vite(['resources/css/pipeline.css'])

<div class="container-fluid">
    <h1>Pipeline</h1>
    <div class="scrollable-table">
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
            <tfoot>
                <tr>
                    <th colspan="5">Summary</th>
                    <th>
                        <strong>${{ number_format($summary['salesPriceTotal'], 2) }}</strong><br/>
                        <strong>${{ number_format($summary['salesPriceAverage'], 2) }}</strong>
                    </th>
                    <th><strong>{{ number_format($summary['commissionAverage'], 2) }}%</strong></th>
                    <th>
                        <strong>${{ number_format($summary['potentialGCITotal'], 2) }}</strong><br/>
                        <strong>${{ number_format($summary['potentialGCIAverage'], 2) }}</strong>
                    </th>
                    <th><strong>{{ number_format($summary['pipelineProbabilityAverage'], 2) }}%</strong></th>
                    <th>
                        <strong>${{ number_format($summary['probableGCITotal'], 2) }}</strong><br/>
                        <strong>${{ number_format($summary['probableGCIAverage'], 2) }}</strong>
                    </th>
                </tr>

            </tfoot>
        </table>
    </div>
</div>
@vite(['resources/js/pipeline.js'])
    @section('pipelineScript')
    <script></script>
    @endsection
@endsection
