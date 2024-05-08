@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline')

@section('content')
@vite(['resources/css/pipeline.css'])
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container-fluid">
    <div class="commonFlex ppipeDiv">
        <p class="pText">My Pipeline</p>
        <div class="alert alert-secondary text-center">
            <strong>Sales Volume</strong><br>
            ${{ number_format($totalSalesVolume, 0, '.', ',') }}
        </div>
        <div class="alert alert-secondary text-center">
            <strong>Avg Commission</strong><br>
            {{ number_format($averageCommission, 2) }}%
        </div>
        <div class="alert alert-secondary text-center">
            <strong>Potential GCI</strong><br>
            ${{ number_format($totalPotentialGCI, 0, '.', ',') }}
        </div>
        <div class="alert alert-secondary text-center">
            <strong>Avg Probability</strong><br>
            {{ number_format($averageProbability, 2) }}%
        </div>
        <div class="alert alert-secondary text-center">
            <strong>Probable GCI</strong><br>
            ${{ number_format($totalProbableGCI, 0, '.', ',') }}
        </div>
        <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId" onclick="createTransaction()">
            <i class="fas fa-plus plusicon">
            </i>
            New Transaction
        </div>

    </div>
    <div class="pfilterDiv">
        <div class="pcommonFilterDiv">
            <input placeholder="Search" class="psearchInput" id="pipelineSearch" oninput="fetchDeal()"/>
            <i class="fas fa-search search-icon"></i>
        </div>
        <p class="porText">or</p>
        <div class="psortingFilterDiv">
            <select class="form-select dmodaltaskSelect" id="related_to_stage" name="related_to_stage" aria-label="Select Transaction" onchange="fetchDeal()">
                <option value="">Please select one</option>
                @foreach ($allstages as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
            {{-- <input placeholder="Sort Pipelines by..." id="pipelineSort" class="psearchInput" />
            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon" class="ppipelinesorticon">--}}
        </div>
       <div class="input-group-text pfilterBtn" id="btnGroupAddon" onclick="fetchDeal()"> <i class="fas fa-filter"></i>
            Filter
        </div>

    </div>

        <div class="transaction-container">
            @include('pipeline.transaction')
        </div>
</div>
@vite(['resources/js/pipeline.js'])



<script>
        // Add an event listener to send search term as request
        function fetchData(sortValue, sortType,filter=null,searchInput,ppipelineTableBody,ptableCardDiv) {
            // console.log("filter",filter);
            const searchValue = searchInput.val().trim();
            $.ajax({
                url: '{{ url('/pipeline/deals') }}',
                method: 'GET',
                data: {
                    search: encodeURIComponent(searchValue),
                    sort: sortValue || "",
                    sortType: sortType || "",
                    filter:filter
                },
                
                success: function(data) {
                    const card = $('.transaction-container').html(data);
                    // ppipelineTableBody.empty();
                    // ptableCardDiv.empty();
                    // const isMobile = window.innerWidth < 767;
                    // if (isMobile) {
                    //     if (data.length === 0) {
                    //         // If no data found, display a message
                    //         ptableCardDiv.append('<div class="pnofound"><p class="text-center">No records found</p></div>');
                    //         return;
                    //     }
                    // }else{
                    //     if (data.length === 0) {
                    //         // If no data found, display a message
                    //         ppipelineTableBody.append('<div class="pnofound"><p class="text-center">No records found</p></div>');
                    //         return;
                    //     }
                    // }
                    // $.each(data, function(index, item) {
                    //     if (isMobile) {
                    //         // Render data in card format
                    //         const card = $('<div class="pTableCard">').html(`
                    //             <p class="pTableTransText">Transaction</p>
                    //                     <p class="pTableNameText">${item.deal_name || 'N/A'}</p>
                    //                     <div class="d-flex justify-content-between">
                    //                         <div class="pTableSelect pipelinestatusdiv">
                    //                             <p style="background-color: ${item.stage === 'Potential'
                    //                 ? '#dfdfdf'
                    //                 : (item.stage === 'Active'
                    //                     ? '#afafaf'
                    //                     : (item.stage === 'Pre-Active'
                    //                         ? '#cfcfcf'
                    //                         : (item.stage === 'Under Contract'
                    //                             ? '#8f8f8f;color=#fff;'
                    //                             : (item.stage === 'Dead-Lost To Competition'
                    //                                 ? '#efefef'
                    //                                 : '#6f6f6f;color=#fff;'))))}"
                    //                                 class="pstatusText">${item.stage || 'N/A'}</p>
                    //                             <i class="fas fa-angle-down"></i>
                    //                         </div>
                    //                         ${item.closing_date || 'N/A'}
                    //                     </div>
                    //                     <div class="d-flex justify-content-between psellDiv">
                    //                         <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A"> ${item.client_name_primary?? 'N/A'}
                    //                         </div>
                    //                         <div>
                    //                             <img src="{{ URL::asset('/images/sell.svg') }}" alt="A">$
                    //                             ${item.sale_price || 'N/A'}
                    //                         </div>
                    //                     </div>
                    //                     <div class="pCardFooter">
                    //                         <div class="pfootericondiv">
                    //                             <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                    //                                 class="pdiversityicon">
                    //                             <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                    //                                 class="pdiversityicon">
                    //                         </div>
                    //                         <div>
                    //                             <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                    //                                 class="pdiversityicon">
                    //                         </div>
                    //                     </div>
                    //                 </div>
                    //         `);
                    //         ptableCardDiv.append(card);
                    //     } else {
                    //         deal = item
                    //         // Render data in table format
                    //         const row = $('<div class="psearchandsort">').html(Item);
                    //         ppipelineTableBody.append(row);
                    //     }
                    // });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        window.createTransaction= function() {
            console.log("Onclick");
            var formData = {
            "data": [{
                        "Deal_Name": "{{ config('variables.dealName') }}",
                        "Owner": {
                            "id": "{{ auth()->user()->root_user_id }}"
                        },
                        "Stage":"Potential"
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

        window.fetchDeal = function(sortField, sortDirection) {
            const searchInput = $('#pipelineSearch');
            const sortInput = $('#pipelineSort');
            const ppipelineTableBody = $('.psearchandsort');
            const ptableCardDiv = $('.ptableCardDiv');
            var selectedModule = $('#related_to_stage');
            var selectedText = selectedModule.val();
            // Call fetchData with the updated parameters
            fetchData(sortField, sortDirection, selectedText, searchInput, ppipelineTableBody, ptableCardDiv);
        }
   
</script>

@section('pipelineScript')

@endsection
@endsection