@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline')

@section('content')
    @vite(['resources/css/pipeline.css'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <div class="container-fluid">
        <div class="loader" id="loaderfor" style="display: none;"></div>
        <div class="loader-overlay" id="loaderOverlay" style="display: none;"></div>
        <div class="dbgroupsFlex">
            <p class="ngText">Pipelines</p>
            <div class="pipeline-btns-container">

                <div class="input-group-text text-white justify-content-center pcontactBtn" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#newTaskModalId" onclick="createTransaction()">
                    <i class="fas fa-plus plusicon">
                    </i> New Transaction
                </div>
                <div class="input-group-text text-white justify-content-center pTransactionBtn">
                    <i class="fas fa-plus plusicon">
                    </i>
                    New Submittal

                </div>
            </div>
        </div>

        <div class="pipeline-cards-container">
            <div class="progressCardsContainer">
                <p class="proCardsText">Sales Volume</p>
                ${{ number_format($totalSalesVolume, 0, '.', ',') }}
            </div>
            <div class="progressCardsContainer">
                <p class="proCardsText">Avg Commission</p>
                {{ number_format($averageCommission, 2) }}%
            </div>
            <div class="progressCardsContainer">
                <p class="proCardsText">Potential GCI</p>
                ${{ number_format($totalPotentialGCI, 0, '.', ',') }}
            </div>
            <div class="progressCardsContainer">
                <p class="proCardsText">Avg Probability</p>
                {{ number_format($averageProbability, 2) }}%
            </div>
            <div class="progressCardsContainer">
                <p class="proCardsText">Probable GCI</p>
                ${{ number_format($totalProbableGCI, 0, '.', ',') }}
            </div>

        </div>

        <div class="pfilterDiv">
            <div class="pcommonFilterDiv">
                <input placeholder="Search" class="psearchInput" id="pipelineSearch" oninput="fetchDeal()" />
                <i class="fas fa-search search-icon"></i>
            </div>
            <p class="porText">or</p>
            <div class="psortingFilterDiv">
                <select class="form-select dmodaltaskSelect" id="related_to_stage" name="related_to_stage"
                    aria-label="Select Transaction" onchange="fetchDeal()">
                    <option value="">Sort Pipelines by...</option>
                    @foreach ($allstages as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
                {{-- <input placeholder="Sort Pipelines by..." id="pipelineSort" class="psearchInput" />
            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon" class="ppipelinesorticon">
            --}}
            </div>
            <div class="input-group-text pfilterBtn" id="btnGroupAddon" onclick="fetchDeal()"> <i class="fas fa-filter"></i>
                Filter
            </div>
            <div class="input-group-text pfilterBtn" id="btnGroupAddon" onclick="fetchDeal('','','reset_all')"> <i
                    class="fas fa-sync"></i>
                Reset All
            </div>

        </div>

        <div class="transaction-container">
            @include('pipeline.transaction')
        </div>
    </div>
    @vite(['resources/js/pipeline.js'])



    <script>
        // Add an event listener to send search term as request
        function fetchData(sortValue, sortType, filter = null, searchInput, ppipelineTableBody, ptableCardDiv, resetall) {
           
            // console.log("filter",filter);
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

        window.createTransaction = function() {
            console.log("Onclick");
            var formData = {
                "data": [{
                    "Deal_Name": "{{ config('variables.dealName') }}",
                    "Owner": {
                        "id": "{{ auth()->user()->root_user_id }}"
                    },
                    "Stage": "Potential"
                }],
                "_token": '{{ csrf_token() }}'
            };
            $.ajax({
                url: '{{ url('/pipeline/create') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify(formData),
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    // Handle success response, such as redirecting to a new page
                    window.location.href = `{{ url('/pipeline-create/${data.id}') }}`;
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        window.fetchDeal = function(sortField, sortDirection, resetall = "") {
            let searchInput = $('#pipelineSearch');
            let sortInput = $('#pipelineSort');
            let ppipelineTableBody = $('.psearchandsort');
            let ptableCardDiv = $('.ptableCardDiv');
            let selectedModule = $('#related_to_stage');
            let selectedText = selectedModule.val();
            // Call fetchData with the updated parameters
            fetchData(sortField, sortDirection, selectedText, searchInput, ppipelineTableBody, ptableCardDiv, resetall);
        }
    </script>

@section('pipelineScript')

@endsection
@endsection
