@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline')

@section('content')
    @vite(['resources/css/pipeline.css'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <div class="container-fluid">
        <div class="loader" id="loaderfor" style="display: none;"></div>
        <div class="loader-overlay" id="loaderOverlay" style="display: none;"></div>
        <div class="dbgroupsFlex">
            <p class="ngText">Pipeline</p>
            <div class="pipeline-btns-container">

                <div>
                    @component('components.button', [
                        'clickEvent' => 'createTransaction()',
                        'label' => 'New Transaction',
                        'icon' => 'fas fa-plus plusicon'
                    ])
                    @endcomponent
                </div>
                
                
                <div>
                    @component('components.button', [
                        'clickEvent' => 'createSubmittals()',
                        'label' => 'New Submittal',
                        'icon' => 'fas fa-plus plusicon'
                    ])
                    @endcomponent
                </div>
            </div>
        </div>



        <div class="pipeline-cards-container">
            @component('components.pipe-cards', [
                'title' => 'Sales Volume',
                'value' => '$' . number_format($totalSalesVolume, 0, '.', ','),
            ])
            @endcomponent

            @component('components.pipe-cards', [
                'title' => 'Avg Commission',
                'value' => number_format($averageCommission, 2) . '%',
            ])
            @endcomponent

            @component('components.pipe-cards', [
                'title' => 'Potential GCI',
                'value' => '$' . number_format($totalPotentialGCI, 0, '.', ','),
            ])
            @endcomponent

            @component('components.pipe-cards', [
                'title' => 'Avg Probability',
                'value' => number_format($averageProbability, 2) . '%',
            ])
            @endcomponent

            @component('components.pipe-cards', [
                'title' => 'Probable GCI',
                'value' => '$' . number_format($totalProbableGCI, 0, '.', ','),
            ])
            @endcomponent
        </div>

        <div class="pfilterDiv">
            <div class="pcommonFilterDiv dataTables_filter">
                <input placeholder="Search" class="psearchInput" id="pipelineSearch"/>
                <i class="fas fa-search search-icon"></i>
            </div>
            <p class="porText">or</p>
            <div class="psortingFilterDiv">
                <select class="form-select dmodaltaskSelect" id="related_to_stage" name="related_to_stage"
                    aria-label="Select Transaction">
                    <option value="">Sort Pipeline by...</option>
                    @php
                        $excludedItems = ['Sold', 'Dead-Lost To Competition', 'Dead-Contract Terminated'];
                    @endphp
                    @foreach ($allstages as $item)
                        @if (!in_array($item, $excludedItems))
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endif
                    @endforeach
                </select>
                {{-- <input placeholder="Sort Pipelines by..." id="pipelineSort" class="psearchInput" />
            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon" class="ppipelinesorticon">
            --}}
            </div>
            <div>
                 @component('components.button', [
                    'clickEvent' => 'fetchDeal()',
                    'label' => 'Filter',
                    'icon' => 'fas fa-filter'
                ])
                @endcomponent
            </div>
            <div>
                   @component('components.button', [
                    'clickEvent' => 'fetchDeal(\'\', \'\', \'reset_all\')',
                    'label' => 'Reset All',
                    'icon' => 'fas fa-sync'
                ])
                @endcomponent
            </div>

        </div>
        @php
            $transHeader = [
                "",
                "Transaction",
                "Client Name",
                "Status",
                "Representing",
                "Price",
                "Close Date",
                "Commission",
                "Potential GCI",
                "Probability",
                "Probable GCI"
            ]
        @endphp
        <div class="transaction-container">
            @if (count($deals) > 0)
            @component('components.common-table', [
                'th' => $transHeader,
                'id'=>'datatable_pipe_transaction',
                'commonArr' =>$deals,
                "type" =>"dash-pipe-transaction",
                "retrieveModuleData" => $retrieveModuleData,
                "allstages" => $allstages
            ])
            @endcomponent
            @else
                <div class="pnofound">
                    <p>No records found</p>
                </div>
            @endif
        </div>
    </div>
    @vite(['resources/js/pipeline.js'])



    <script>
        var prevSelectedColumn = null;
        var prevSortDirection = "";
        // Add an event listener to send search term as request
        function fetchData(sortValue, sortType, filter = null, searchInput, ppipelineTableBody, ptableCardDiv, resetall,
            clickedColumn = "") {
            let searchValue = searchInput.val().trim();
            if (resetall === "reset_all") {
                document.getElementById("loaderOverlay").style.display = "block";
                document.getElementById('loaderfor').style.display = "block";
                $("#pipelineSearch").val("");
                $("#related_to_stage").val("");
                searchValue = "";
                sortValue = "";
                filter = "";
            }
            $.ajax({
                url: '{{ url('/pipeline/deals') }}',
                method: 'GET',
                data: {
                    search: encodeURIComponent(searchValue),
                    sort: sortValue || "",
                    sortType: sortType || "",
                    filter: filter
                },

                success: function(data) {
                    if (resetall === "reset_all") {
                        document.getElementById("loaderOverlay").style.display = "none";
                        document.getElementById('loaderfor').style.display = "none";
                    }

                    const card = $('.transaction-container').html(data);
                    if (card) {
                        if (prevSelectedColumn !== null) {
                            if (prevSortDirection === "asc") {
                                $(prevSelectedColumn).find(".down-arrow").css("color", "#fff");
                                $(prevSelectedColumn).find(".up-arrow").css("color", "#fff");
                            } else {
                                $(prevSelectedColumn).find(".up-arrow").css("color", "#fff");
                                $(prevSelectedColumn).find(".down-arrow").css("color", "#fff");
                            }
                        }
                        if (sortType === "asc") {
                            $(clickedColumn).find(".down-arrow").css("color", "#D3D3D3");
                            $(clickedColumn).find(".up-arrow").css("color", "#fff");
                        } else {
                            $(clickedColumn).find(".up-arrow").css("color", "#D3D3D3");
                            $(clickedColumn).find(".down-arrow").css("color", "#fff");
                        }

                        // Update the previously selected column and its sorting direction
                        prevSelectedColumn = clickedColumn;
                        prevSortDirection = sortType;
                    }

                },
                error: function(xhr, status, error) {
                    if (resetall === "reset_all") {
                        document.getElementById("loaderOverlay").style.display = "none";
                        document.getElementById('loaderfor').style.display = "none";
                    }
                    console.error('Error:', error);
                }
            });
        }

        window.fetchDeal = function(sortField, sortDirection, resetall = "", clickedCoulmn) {
            let searchInput = $('#pipelineSearch');
            let sortInput = $('#pipelineSort');
            let ppipelineTableBody = $('.psearchandsort');
            let ptableCardDiv = $('.ptableCardDiv');
            let selectedModule = $('#related_to_stage');
            let selectedText = selectedModule.val();
            // Call fetchData with the updated parameters
            fetchData(sortField, sortDirection, selectedText, searchInput, ppipelineTableBody, ptableCardDiv, resetall,
                clickedCoulmn);
        }
    </script>

@section('pipelineScript')

@endsection
@endsection
