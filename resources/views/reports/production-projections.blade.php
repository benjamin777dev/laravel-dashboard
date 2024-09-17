@extends('layouts.master')

@section('title', 'Production Projections & Report')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    @vite(['resources/css/custom.css'])
    <style>
        #productionTable_filter {
            display: block !important;
        }
        /* Custom modal styling for the height and alignment */
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            max-height: 500px;
            overflow-y: auto;
            padding: 20px;
        }

        .soldbg {
            background-color: #222 !important;
        }
        .ucbg {
            background-color: #444 !important;
        }
        .capbg {
            background-color: #222 !important
        }

        .border-left {
            border-left: 1px solid #444 !important;
        }
    </style>
@endsection

@section('content')
@if (!empty($reportData))
    <div style="margin-left: 2vw; margin-right: 2vw;">
        <!-- Year Selection Dropdown -->
        <form method="GET" action="{{ route('reports.productionProjections') }}" id="yearSelectForm">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="yearSelect">Select Year:</label>
                    <select name="year" id="yearSelect" class="form-control" onchange="document.getElementById('yearSelectForm').submit();">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ request('year', $currentYear) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <!-- Projected Volume Summary -->
         @if ($projectionData['status']['completed'] == true )
            <div class="row dashboard-cards-resp">
                <div class="col-lg-6 col-md-6 col-sm-12 text-center dCardsCols">
                    <div class="card dash-card">
                        <div class="card-body dash-front-cards">
                            <h5 class="card-title dTitle mb-0">Projected Volume</h5>
                            <h4 class="dSumValue">Sold: {{ $projectionData['sold']['transactions'] }} - ${{ number_format($projectionData['sold']['volume'], 2) }}</h4>
                            <p class="dcountText">To CHR: ${{ number_format($projectionData['sold']['chr_split'], 2) }}<br/>To Agent: ${{ number_format($projectionData['sold']['agent_earnings'], 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 text-center dCardsCols">
                    <div class="card dash-card">
                        <div class="card-body dash-front-cards">
                            <h5 class="card-title dTitle mb-0">Projected Volume</h5>
                            <h4 class="dSumValue">UC: {{ $projectionData['uc']['transactions'] }} - ${{ number_format($projectionData['uc']['volume'], 2) }}</h4>
                            <p class="dcountText">To CHR: ${{ number_format($projectionData['uc']['chr_split'], 2) }}<br/>To Agent: ${{ number_format($projectionData['uc']['agent_earnings'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

        @endif
        <!-- YTD Totals Summary -->
        <div class="row dashboard-cards-resp">
            <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols">
                <div class="card dash-card">
                    <div class="card-body dash-front-cards">
                        <h5 class="card-title dTitle mb-0"># of Tx YTD as of {{ $currentYear }}</h5>
                        <h4 class="dSumValue">{{ number_format($totalSoldTransactions + $totalUCTransactions) }}</h4>
                        <p class="dcountText">Sold: {{ number_format($totalSoldTransactions) }}<br/>UC: {{ number_format($totalUCTransactions) }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols">
                <div class="card dash-card">
                    <div class="card-body dash-front-cards">
                        <h5 class="card-title dTitle mb-0">Total of Agent Check Amounts YTD</h5>
                        <h4 class="dSumValue">${{ number_format($totalSoldCheckAmount + $totalUnderContractCheckAmount) }}</h4>
                        <p class="dcountText">Sold: ${{ number_format($totalSoldCheckAmount) }}<br/>UC: ${{ number_format($totalUnderContractCheckAmount) }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols">
                <div class="card dash-card">
                    <div class="card-body dash-front-cards">
                        <h5 class="card-title dTitle mb-0">Total of Split to CHR YTD</h5>
                        <h4 class="dSumValue">${{ number_format($totalSoldCHRSplit + $totalUnderContractCHRSplit) }}</h4>
                        <p class="dcountText">
                            Sold: ${{ number_format($totalSoldCHRSplit) }}
                            <br/>
                            UC: ${{ number_format($totalUnderContractCHRSplit) }} <small>({{ number_format($ucInitialTransactionPercentage) }}% Initial / {{ number_format($ucResidualTransactionPercentage) }}% residual)</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Data -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="productionTable" class="table table-bordered table-striped table-responsive nowrap w-100 min-mobile-p">
                            <thead>
                                <tr>
                                    <th rowspan="2">Agent Name</th>
                                    <th colspan="4" class="soldbg">Sold</th>
                                    <th colspan="3" class="ucbg">Under Contract</th>
                                    <th colspan="2" class="capbg">Caps Remaining</th>
                                </tr>
                                <tr>
                                    <th class="soldbg">Stage</th>
                                    <th class="soldbg">Agent Check Amount</th>
                                    <th class="soldbg">CHR Split</th>
                                    <th class="soldbg">Total Commission</th>
                                    <th class="ucbg">Stage</th>
                                    <th class="ucbg">Agent Earnings</th>
                                    <th class="ucbg">CHR Split</th>
                                    <th class="capbg">Initial</th>
                                    <th class="capbg">Residual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $agentName => $stages)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if (!empty($stages['Under Contract']['info']))
                                                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#infoModal"
                                                    data-agent="{{ $agentName }}">
                                                    <i class="fas fa-info"></i>
                                                </button>
                                            @endif
                                            <div>
                                                <strong>{{ $agentName }}</strong>
                                                <br/>
                                                @if (isset($stages['settings']['initial_cap']))
                                                    <span>Initial Cap: ${{ number_format($stages['settings']['initial_cap'], 2) }}</span>
                                                @else
                                                    <span>Initial Cap: $0</span>
                                                @endif
                                                <br/>
                                                @if (isset($stages['settings']['residual_cap']))
                                                    <span>Residual Cap: ${{ number_format($stages['settings']['residual_cap'], 2) }}</span>
                                                @else
                                                    <span>Residual Cap: $0</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td data-sort="{{ $stages['Sold']['count'] ?? 0 }}">
                                        {{ $stages['Sold']['count'] ?? 0 }}
                                        @if (isset($projectionData['status']['completed']) && $projectionData['status']['completed'] == true && isset($stages['settings']['projection_sold']))
                                            <small class="text-primary">(+{{ $stages['settings']['projection_sold']['count'] }})</small>
                                        @endif
                                    </td>
                                    <td>${{ number_format($stages['Sold']['agent_check_amount'] ?? 0, 2) }}</td>
                                    <td>${{ number_format($stages['Sold']['chr_split'] ?? 0, 2) }}</td>
                                    <td>${{ number_format($stages['Sold']['total_commission'] ?? 0, 2) }}</td>
                                    <td class="border-left" data-sort="{{ $stages['Under Contract']['count'] ?? 0 }}">
                                        {{ $stages['Under Contract']['count'] ?? 0 }}
                                        @if (isset($projectionData['status']['completed']) && $projectionData['status']['completed'] == true && isset($stages['settings']['projection_uc']))
                                            <small class="text-primary">(+{{ $stages['settings']['projection_uc']['count'] }})</small>
                                        @endif
                                    </td>
                                    <td>${{ number_format($stages['Under Contract']['projected_agent_earnings'] ?? 0, 2) }}</td>
                                    <td>${{ number_format($stages['Under Contract']['projected_chr_split'] ?? 0, 2) }}</td>
                                    
                                    <td class="border-left" data-sort="{{ $stages['running']['initial_cap_remaining'] ?? 0 }}">
                                        @php
                                            // Calculate Initial Cap Remaining percentage
                                            $initialCapRemaining = $stages['running']['initial_cap_remaining'] ?? 0;
                                            $initialCap = $stages['settings']['initial_cap'] ?? 0;
                                            $initialCapUsedPercentage = $initialCap > 0 ? (($initialCap - $initialCapRemaining) / $initialCap) * 100 : 100;
                                        @endphp

                                        <!-- Display cap amount and remaining percentage above the progress bar -->
                                        <span>${{ number_format($initialCapRemaining, 2) }} ({{ number_format(100 - $initialCapUsedPercentage, 2) }}%)</span>
                                        <div class="progress mt-2" style="height: 20px;"> <!-- Custom base background color -->
                                            <div class="progress-bar {{ $initialCapRemaining == 0 ? 'bg-danger' : 'bg-success' }}" 
                                                role="progressbar" 
                                                style="width: {{ 100 - $initialCapUsedPercentage }}%;" 
                                                aria-valuenow="{{ 100 - $initialCapUsedPercentage }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </td>

                                    <td data-sort="{{ $stages['running']['residual_cap_remaining'] ?? 0 }}">
                                        @php
                                            // Calculate Residual Cap Remaining percentage
                                            $residualCapRemaining = $stages['running']['residual_cap_remaining'] ?? 0;
                                            $residualCap = $stages['settings']['residual_cap'] ?? 0;
                                            $residualCapDiff = ($stages['settings']['residual_cap'] ?? 0) - ($stages['settings']['initial_cap'] ?? 0);
                                            $residualCapUsedPercentage = $residualCap > 0 ? (($residualCapDiff - $residualCapRemaining) / $residualCapDiff) * 100 : 100;
                                        @endphp

                                        <!-- Display cap amount and remaining percentage above the progress bar -->
                                        <span>${{ number_format($residualCapRemaining, 2) }} ({{ number_format(100 - $residualCapUsedPercentage, 2) }}%)</span>
                                        <div class="progress mt-2" style="height: 20px;"> <!-- Custom base background color -->
                                            <div class="progress-bar {{ $residualCapRemaining == 0 ? 'bg-danger' : 'bg-success' }}" 
                                                role="progressbar" 
                                                style="width: {{ 100 - $residualCapUsedPercentage }}%;" 
                                                aria-valuenow="{{ 100 - $residualCapUsedPercentage }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </td>




                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                <th>Total</th>
                                <th></th> <!-- For Agent Check Amount (Sold) Total -->
                                <th></th> <!-- For CHR Split (Sold) Total -->
                                <th></th> <!-- For Total Commission (Sold) Total -->
                                <th></th> <!-- For Stage (Under Contract) -->
                                <th></th> <!-- For Agent Earnings (Under Contract) Total -->
                                <th></th> <!-- For CHR Split (Under Contract) Total -->
                                <th></th> <!-- For Initial Cap Remaining Total -->
                                <th></th> <!-- For Residual Cap Remaining Total -->
                                <th></th> <!-- For Residual Cap Remaining Total -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoModalLabel">Agent Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="agentDetails"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart.js section -->
        <canvas id="productionChart"></canvas>
    </div>
@else
    <p>No data available for production projections.</p>
@endif
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var reportData = @json($reportData);

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $(document).ready(function() {
            var table = $('#productionTable').DataTable({
                "responsive": true,
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": true,
                "dom": '<"top"f>rt<"bottom"lip><"clear">',
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api();

                    // Function to remove formatting and convert to float
                    var intVal = function ( i ) {
                        if (typeof i === 'string') {
                            // Remove any HTML tags
                            var textContent = i.replace(/<\/?[^>]+(>|$)/g, "").trim();
                            // Remove $ and commas
                            var numericString = textContent.replace(/[\$,]/g, '');
                            return parseFloat(numericString) || 0;
                        } else if (typeof i === 'number') {
                            return i;
                        } else {
                            return 0;
                        }
                    };

                    // Function to extract counts from cell content
                    function extractCountsFromCell(cellContent) {
                        // Remove any HTML tags
                        var textContent = cellContent.replace(/<\/?[^>]+(>|$)/g, "").trim();
                        // Extract the main number (first number in the string)
                        var mainNumberMatch = textContent.match(/^(\d+)/);
                        var mainNumber = mainNumberMatch ? parseInt(mainNumberMatch[1]) : 0;

                        // Extract the projection number from the small tag
                        var projectionMatch = cellContent.match(/\(\+(\d+)\)/);
                        var projectionNumber = projectionMatch ? parseInt(projectionMatch[1]) : 0;

                        return {
                            main: mainNumber,
                            projection: projectionNumber
                        };
                    }

                    // Total Sold Transactions (Column 1)
                    var totalSoldTransactions = 0;
                    var totalSoldProjections = 0;

                    api.column(1).nodes().each(function(cell, i) {
                        var cellContent = $(cell).html();
                        var counts = extractCountsFromCell(cellContent);
                        totalSoldTransactions += counts.main;
                        totalSoldProjections += counts.projection;
                    });

                    // Update the footer cell for Sold Transactions
                    var soldTotalText = totalSoldTransactions;
                    if (totalSoldProjections > 0) {
                        soldTotalText += ' <small class="text-warning">(+' + totalSoldProjections + ')</small>';
                    }
                    $(api.column(1).footer()).html(soldTotalText);

                    // Total Under Contract Transactions (Column 5)
                    var totalUnderContractTransactions = 0;
                    var totalUnderContractProjections = 0;

                    api.column(5).nodes().each(function(cell, i) {
                        var cellContent = $(cell).html();
                        var counts = extractCountsFromCell(cellContent);
                        totalUnderContractTransactions += counts.main;
                        totalUnderContractProjections += counts.projection;
                    });

                    // Update the footer cell for Under Contract Transactions
                    var ucTotalText = totalUnderContractTransactions;
                    if (totalUnderContractProjections > 0) {
                        ucTotalText += ' <small class="text-warning">(+' + totalUnderContractProjections + ')</small>';
                    }
                    $(api.column(5).footer()).html(ucTotalText);

                    // Total Agent Check Amount (Sold) Column 2
                    var totalSoldCheckAmount = api.column(2).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Total CHR Split (Sold) Column 3
                    var totalSoldCHRSplit = api.column(3).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Total Total Commission (Sold) Column 4
                    var totalSoldCommission = api.column(4).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Total Agent Earnings (Under Contract) Column 6
                    var totalUnderContractEarnings = api.column(6).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Total CHR Split (Under Contract) Column 7
                    var totalUnderContractCHRSplit = api.column(7).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Total Initial Cap Remaining (Column 9)
                    var totalInitialCapRemaining = api.column(8).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Total Residual Cap Remaining (Column 10)
                    var totalResidualCapRemaining = api.column(9).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Update footer cells for monetary values
                    $(api.column(2).footer()).html('$' + numberWithCommas(totalSoldCheckAmount.toFixed(2)));
                    $(api.column(3).footer()).html('$' + numberWithCommas(totalSoldCHRSplit.toFixed(2)));
                    $(api.column(4).footer()).html('$' + numberWithCommas(totalSoldCommission.toFixed(2)));

                    $(api.column(6).footer()).html('$' + numberWithCommas(totalUnderContractEarnings.toFixed(2)));
                    $(api.column(7).footer()).html('$' + numberWithCommas(totalUnderContractCHRSplit.toFixed(2)));

                    $(api.column(8).footer()).html('$' + numberWithCommas(totalInitialCapRemaining.toFixed(2)));
                    $(api.column(9).footer()).html('$' + numberWithCommas(totalResidualCapRemaining.toFixed(2)));
                }

            });

            // Prepare data for Chart.js
            var agentNames = @json(array_keys($reportData));
            var soldCheckAmounts = @json(array_map(fn($stages) => $stages['Sold']['agent_check_amount'] ?? 0, $reportData));
            var underContractCheckAmounts = @json(array_map(fn($stages) => $stages['Under Contract']['projected_agent_earnings'] ?? 0, $reportData));

            var ctx = document.getElementById('productionChart').getContext('2d');
            var productionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: agentNames,
                    datasets: [
                        {
                            label: 'Sold - Agent Check Amount',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            data: soldCheckAmounts
                        },
                        {
                            label: 'Under Contract - Projected Agent Earnings',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            data: underContractCheckAmounts
                        }
                    ]
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

        // Populate modal dynamically with agent details
        $('#infoModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var agentName = button.data('agent');
            var info = reportData[agentName]['Under Contract']['info'] || [];
            let settings = reportData[agentName]['settings'];

            var modal = $(this);
            modal.find('.modal-title').text('Agent Details: ' + agentName);
            modal.find('#agentDetails').html('<p>Loading details...</p>');  // Show a loading message

            // Send the deal data to the backend to get the HTML
            $.ajax({
                url: '{{ route("agent.deal.cards") }}',  // Using named route here
                method: 'POST',
                data: {
                    deals: info,
                    settings: settings,
                    _token: '{{ csrf_token() }}'  // Include CSRF token
                },
                success: function(response) {
                    // Populate modal with the returned HTML
                    modal.find('#agentDetails').html(response.html);
                },
                error: function() {
                    modal.find('#agentDetails').html('<p>Error loading details. Please try again.</p>');
                }
            });
        });
    </script>
@endsection
