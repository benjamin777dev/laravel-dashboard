@extends('layouts.master')

@section('title', 'MLS Agent Transaction Report')
@section('css')
    <style>
        .container-full-width {
            max-width: 100% !important;
        }
        #agents-table_filter  {
            display: block !important;
        }
    </style>
    <!-- DataTables CSS -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    @vite(['resources/css/custom.css'])
@endsection

@section('content')
<div class="container mt-4 container-full-width">
    <h1>MLS Agent Transaction Report</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Search Form -->
    <form id="search-form" action="{{ route('agent.transactions.search') }}" method="POST" class="mb-4">
        @csrf

        <div class="row g-3">
            <div class="col-md-3">
                <select name="preset" id="preset" class="form-select">
                    <option>-- Preset --</option>
                    <option>6+ in Last 12 Months</option>
                    <option>2-5 Last 12 Months</option>
                </select>
            </div>
            <!-- Year Selection -->
            <div class="col-md-2">
                <select name="year" id="year" class="form-select">
                    @foreach($years as $yearOption)
                        <option value="{{ $yearOption }}" {{ old('year', $year ?? date('Y')) == $yearOption ? 'selected' : '' }}>
                            {{ $yearOption }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Interval Selection -->
            <div class="col-md-3">
                <select name="interval" id="interval" class="form-select">
                    @foreach($intervals as $key => $label)
                        <option value="{{ $key }}" {{ old('interval', $interval ?? 'today') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Min and Max Transactions -->
            <div class="col-md-2">
                <input type="number" name="min_transactions" id="min_transactions" class="form-control" value="{{ old('min_transactions', $minTransactions ?? 1) }}" min="1" placeholder="Min">            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <input type="number" name="max_transactions" id="max_transactions" class="form-control" value="{{ old('max_transactions', $maxTransactions ?? '') }}" min="1" placeholder="Max">
                    <div class="input-group-append">
                        <button id="clear-max" class="btn btn-outline-secondary" type="button">Clear</button>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Custom Date Range -->
        <div id="custom-date-range" class="row g-3 mt-3" style="display: none;">
            <div class="col-md-6">
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $startDate ?? '') }}">
            </div>
            <div class="col-md-6">
                <label for="end_date" class="form-label">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $endDate ?? '') }}">
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" id="generate-report" class="btn btn-primary">Generate Report</button>
            <button type="button" id="export-csv" class="btn btn-success" style="display: none;">Export to CSV</button>
        </div>
    </form>

    <!-- Results Table -->
    <table id="agents-table" class="table table-striped table-bordered" style="display: none;">
        <thead>
            <tr>
                <th>MLS ID</th>
                <th>Agent Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Brokerage</th>
                <th>Count</th>
                <th>Volume</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
        </tbody>
    </table>
</div>

@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            // Show or hide custom date range inputs based on interval
            function toggleCustomDateRange() {
                if ($('#interval').val() === 'custom') {
                    $('#custom-date-range').show();
                } else {
                    $('#custom-date-range').hide();
                }
            }

            toggleCustomDateRange();

            $('#interval').on('change', function() {
                toggleCustomDateRange();
            });

            $("#preset").on('change', function(){
                if ($("#preset").val() == '6+ in Last 12 Months') {
                    $("#min_transactions").attr("min", "6");
                    $("#min_transactions").val(6);
                    $("#max_transactions").attr("min", "");
                    $("#max_transactions").val('');
                    // set interval to last 365 days
                    $("#interval").val('last_year');
                }else if ($("#preset").val() == '2-5 Last 12 Months') {
                    $("#min_transactions").attr("min", "2");
                    $("#min_transactions").val(2);
                    $("#max_transactions").attr("min", "5");
                    $("#max_transactions").val(5);
                    $("#interval").val('last_year');
                } else {
                    $("#min_transactions").attr("min", "1");
                    $("#min_transactions").val(1);
                    $("#max_transactions").attr("min", "");
                    $("#max_transactions").val('');
                    $("#interval").val('today');
                }
            })

            $("#clear-max").on('click', function() {
                $("#max_transactions").val('');
            })

            // Initialize DataTable
            var table = $('#agents-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('agent.transactions.data') }}",
                    "type": "GET",
                    "data": function(d) {
                        // Read values from form inputs
                        d.year = $('#year').val();
                        d.interval = $('#interval').val();
                        d.minTransactions = $('#min_transactions').val();
                        d.maxTransactions = $('#max_transactions').val();

                        if ($('#interval').val() === 'custom') {
                            d.startDate = $('#start_date').val();
                            d.endDate = $('#end_date').val();
                        } else {
                            // For predefined intervals, leave startDate and endDate empty
                            d.startDate = d.startDate = $('#start_date').val();;
                            d.endDate = '';
                        }
                    }
                },
                "columns": [
                    { "data": 0 }, // MLS ID
                    { "data": 1 }, // Agent Name
                    { "data": 2 }, // Email
                    { "data": 3 }, // Phone
                    { "data": 4 }, // Brokerage
                    { "data": 5 }, // Transaction Count
                    { "data": 6 }, // Volume
                ],
                "order": [[5, "desc"]], // Order by Transaction Count descending
                "pageLength": 25,
                "bLengthChange": false, // Disable page length change
                "bFilter": true, // Enable search box
                "bInfo": true, // Show info text
                "bAutoWidth": false, // Disable auto width
                "language": {
                    "emptyTable": "Please use the form above to generate the report."
                }
            });

            // Handle form submission
            $('#search-form').on('submit', function(e) {
                e.preventDefault();

                // Reload DataTable with new parameters
                table.ajax.reload();

                // Show the table
                $('#agents-table').show();

                // Show the export button
                $('#export-csv').show();
            });

            // Handle Export to CSV
            $('#export-csv').on('click', function() {
                var params = {
                    year: $('#year').val(),
                    interval: $('#interval').val(),
                    minTransactions: $('#min_transactions').val(),
                    maxTransactions: $('#max_transactions').val(),
                };

                if ($('#interval').val() === 'custom') {
                    params.startDate = $('#start_date').val();
                    params.endDate = $('#end_date').val();
                }

                // Build the query string
                var queryString = $.param(params);

                // Redirect to the export route with query parameters
                window.location.href = "{{ route('agent.transactions.export') }}?" + queryString;
            });
        });
    </script>
@endsection
@endsection
