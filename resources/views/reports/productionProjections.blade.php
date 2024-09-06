@extends('layouts.master')

@section('title', 'Closing Information')

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
                            <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>
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
                                    <th rowspan="2">Stage</th>
                                    <th colspan="3" style="background-color: rgb(2, 2, 2);">Sold</th>
                                    <th colspan="4" style="background-color: rgb(33, 33, 33);">Under Contract</th>
                                </tr>
                                <tr>
                                    <th>Agent Check Amount</th>
                                    <th>CHR Split</th>
                                    <th>Total Commission</th>
                                    <th style="border-left: 3px solid #000 !important;">Stage</th>
                                    <th>Agent Earnings</th>
                                    <th>CHR Split</th>
                                    <th>Total Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $agentName => $stages)
                                <tr>
                                    <td>
                                        {{ $agentName }}
                                        <br/>{{ $stages['settings']['initial_cap'] ?? 0 }}/{{ $stages['settings']['residual_cap'] ?? 0 }}
                                        @if (!empty($stages['Under Contract']['info']))
                                            <br/>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal"
                                                data-agent="{{ $agentName }}">
                                                Info
                                            </button>
                                        @endif
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
                                    <td data-sort="{{ $stages['Under Contract']['count'] ?? 0 }}">
                                        {{ $stages['Under Contract']['count'] ?? 0 }}
                                        @if (isset($projectionData['status']['completed']) && $projectionData['status']['completed'] == true && isset($stages['settings']['projection_uc']))
                                            <small class="text-primary">(+{{ $stages['settings']['projection_uc']['count'] }})</small>
                                        @endif
                                    </td>
                                    <td>${{ number_format($stages['Under Contract']['projected_agent_earnings'] ?? 0, 2) }}</td>
                                    <td>${{ number_format($stages['Under Contract']['projected_chr_split'] ?? 0, 2) }}</td>
                                    <td>${{ number_format($stages['Under Contract']['total_commission'] ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th id="totalSoldCheckAmountFooter"></th>
                                    <th id="totalSoldCHRSplitFooter"></th>
                                    <th id="totalSoldCommissionFooter"></th>
                                    <th id="totalUnderContractEarningsFooter"></th>
                                    <th id="totalUnderContractCHRSplitFooter"></th>
                                    <th id="totalUnderContractCommissionFooter"></th>
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

        $(document).ready(function() {
            var table = $('#productionTable').DataTable({
                "responsive": true,
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": true,
                "dom": '<"top"f>rt<"bottom"lip><"clear">'
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
