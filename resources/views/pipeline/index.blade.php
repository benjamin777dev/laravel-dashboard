@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline')

@section('content')
@vite(['resources/css/pipeline.css'])
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container-fluid">
    <div class="commonFlex ppipeDiv">
        <p class="pText">Pipelines</p>
        <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId" onclick="createTransaction()"><i class="fas fa-plus plusicon">
            </i>
            New Pipeline
        </div>
        
    </div>
    <div class="pfilterDiv">
        <div class="pcommonFilterDiv">
            <input placeholder="Search" class="psearchInput" id="pipelineSearch" />
            <i class="fas fa-search search-icon"></i>
        </div>
        <p class="porText">or</p>
        <div class="pcommonFilterDiv">
            <input placeholder="Sort Pipelines by..." id="pipelineSort" class="psearchInput" />
            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon" class="ppipelinesorticon">
        </div>
        <div class="input-group-text pfilterBtn" id="btnGroupAddon"> <i class="fas fa-filter"></i>
            Filter
        </div>
    </div>
     <div class="table-responsive">
            <div class="npcontactsTable">
                <div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Transaction</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" class="ppiplineSwapIcon" alt="Transaction icon"
                            id="pipelineSort" onclick="toggleSort('deal_name')">
                    </div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Client Name</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Client icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('contactName.first_name')">
                    </div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Status </p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Status icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('stage')">
                    </div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Rep</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Rep icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('representing')">
                    </div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Price</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Price icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('sale_price')">
                    </div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Close Date</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Close icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                    </div>
                </div>
                <div></div>

            </div>
            <div class = "psearchandsort">
                @if (count($deals) > 0)
                @foreach ($deals as $deal)
                    <div class="npcontactsBody">
                        <div><input type="checkbox"></div>
                        <div class="commonTextEllipsis">{{ $deal['deal_name'] ?? 'N/A' }}</div>
                        <div class="commonTextEllipsis">{{ $deal->contactName->first_name ?? 'N/A' }}
                            {{ $deal->contactName->last_name ?? '' }}</div>
                        <div>
                            <div class="commonFlex  pipelinestatusdiv">
                                <p style="background-color: {{ $deal['stage'] === 'Potential'
                                    ? '#85A69C'
                                    : ($deal['stage'] === 'Active'
                                        ? '#70BCA5'
                                        : ($deal['stage'] === 'Pre-Active'
                                            ? '#4B8170'
                                            : ($deal['stage'] === 'Under Contract'
                                                ? '#477ABB'
                                                : ($deal['stage'] === 'Dead-Lost To Competition'
                                                    ? '#575B58'
                                                    : '#F18F01')))) }}"
                                    class="pstatusText ">{{ $deal['stage'] ?? 'N/A' }} </p>
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </div>
                        <div>{{ $deal['representing'] ?? 'N/A' }}</div>
                        <div class="commonTextEllipsis">$ {{ $deal['sale_price'] ?? 'N/A' }}</div>
                        <div>{{ $deal['closing_date'] ?? 'N/A' }}</div>
                        <div> <a href="{{ url('/pipeline-view/' . $deal['id']) }}"><img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon"></a>
                            <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                            <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                            <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                        </div>


                    </div>
                @endforeach
                 @else
                <div class="pnofound">
                    <p>No records found</p>
                                </div>
            @endif
            </div>
            <div class="ptableCardDiv">
                @if (count($deals) > 0)
                @foreach ($deals as $deal)
                    <div class="pTableCard">
                        <p class="pTableTransText">Transaction</p>
                        <p class="pTableNameText">{{ $deal['deal_name'] ?? 'N/A' }}</p>
                        <div class="d-flex justify-content-between">
                            <div class="pTableSelect pipelinestatusdiv">
                                <p style="background-color: {{ $deal['stage'] === 'Potential'
                                    ? '#85A69C'
                                    : ($deal['stage'] === 'Active'
                                        ? '#70BCA5'
                                        : ($deal['stage'] === 'Pre-Active'
                                            ? '#4B8170'
                                            : ($deal['stage'] === 'Under Contract'
                                                ? '#477ABB'
                                                : ($deal['stage'] === 'Dead-Lost To Competition'
                                                    ? '#575B58'
                                                    : '#F18F01')))) }}"
                                    class="pstatusText">{{ $deal['stage'] ?? 'N/A' }} </p>
                                <i class="fas fa-angle-down"></i>
                            </div>
                            {{ $deal['closing_date'] ?? 'N/A' }}
                        </div>
                        <div class="d-flex justify-content-between psellDiv">
                            <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A">
                                {{ $deal->contactName->first_name ?? 'N/A' }}
                                {{ $deal->contactName->last_name ?? '' }}
                                {{-- {{ $deal['Primary_Contact'] ?? 'N/A' }} --}}
                            </div>
                            <div>
                                <img src="{{ URL::asset('/images/sell.svg') }}" alt="A">
                                $ {{ $deal['sale_price'] ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="pCardFooter">
                            <div class="pfootericondiv">
                                <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                                    class="pdiversityicon">
                                <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                                    class="pdiversityicon">
                            </div>
                            <div>
                                <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                                    class="pdiversityicon">
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                <div class="pnofound">
                    <p>No records found</p>
                                </div>
            @endif
            </div>
        </div>
</div>
@vite(['resources/js/pipeline.js'])



<script>
    $(document).ready(function() {
        const searchInput = $('#pipelineSearch');
        const sortInput = $('#pipelineSort');
        const ppipelineTableBody = $('.psearchandsort');
        const ptableCardDiv = $('.ptableCardDiv');


        searchInput.on('input', function() {
        fetchData(sortInput.val(), '');
        });
        // Add an event listener to send search term as request
        function fetchData(sortValue, sortType) {
            const searchValue = searchInput.val().trim();
            $.ajax({
                url: '{{ url('/pipeline/deals') }}',
                method: 'GET',
                data: {
                    search: encodeURIComponent(searchValue),
                    sort: sortValue || "",
                    sortType: sortType || ""
                },
                dataType: 'json',
                success: function(data) {
                    ppipelineTableBody.empty();
                    ptableCardDiv.empty();
                    const isMobile = window.innerWidth < 767;
                    if (isMobile) {
                        if (data.length === 0) {
                            // If no data found, display a message
                            ptableCardDiv.append('<div class="pnofound"><p class="text-center">No records found</p></div>');
                            return;
                        }
                    }else{
                        if (data.length === 0) {
                            // If no data found, display a message
                            ppipelineTableBody.append('<div class="pnofound"><p class="text-center">No records found</p></div>');
                            return;
                        }
                    }
                    $.each(data, function(index, item) {
                        if (isMobile) {
                            // Render data in card format
                            const card = $('<div class="pTableCard">').html(`
                                <p class="pTableTransText">Transaction</p>
                                        <p class="pTableNameText">${item.deal_name || 'N/A'}</p>
                                        <div class="d-flex justify-content-between">
                                            <div class="pTableSelect pipelinestatusdiv">
                                                <p style="background-color: ${item.stage === 'Potential'
                                    ? '#85A69C'
                                    : (item.stage === 'Active'
                                        ? '#70BCA5'
                                        : (item.stage === 'Pre-Active'
                                            ? '#4B8170'
                                            : (item.stage === 'Under Contract'
                                                ? '#477ABB'
                                                : (item.stage === 'Dead-Lost To Competition'
                                                    ? '#575B58'
                                                    : '#F18F01'))))}"
                                                    class="pstatusText">${item.stage || 'N/A'}</p>
                                                <i class="fas fa-angle-down"></i>
                                            </div>
                                            ${item.closing_date || 'N/A'}
                                        </div>
                                        <div class="d-flex justify-content-between psellDiv">
                                            <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A"> ${item.contact_name ? (item.contact_name.first_name + ' ' + item.contact_name.last_name) : 'N/A'}
                                            </div>
                                            <div>
                                                <img src="{{ URL::asset('/images/sell.svg') }}" alt="A">$
                                                ${item.sale_price || 'N/A'}
                                            </div>
                                        </div>
                                        <div class="pCardFooter">
                                            <div class="pfootericondiv">
                                                <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                                                    class="pdiversityicon">
                                                <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                                                    class="pdiversityicon">
                                            </div>
                                            <div>
                                                <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                                                    class="pdiversityicon">
                                            </div>
                                        </div>
                                    </div>
                            `);
                            ptableCardDiv.append(card);
                        } else {
                            // Render data in table format
                            const row = $('<div class="psearchandsort">').html(`
                            <div class="npcontactsBody">
                                <div><input type="checkbox"></div>
                                <div class="commonTextEllipsis">${item.deal_name ?? 'N/A' }</div>
                                <div class="commonTextEllipsis">${item.contact_name ? (item.contact_name.first_name + ' ' + item.contact_name.last_name) : 'N/A'}</div>
                                <div>
                                    <div class="commonFlex  pipelinestatusdiv">
                                        <p style="background-color: ${item.stage === 'Potential'
                                            ? '#85A69C'
                                            : (item.stage === 'Active'
                                                ? '#70BCA5'
                                                : (item.stage === 'Pre-Active'
                                                    ? '#4B8170'
                                                    : (item.stage === 'Under Contract'
                                                        ? '#477ABB'
                                                        : (item.stage === 'Dead-Lost To Competition'
                                                            ? '#575B58'
                                                            : '#F18F01')))) }"
                                            class="pstatusText ">${item.stage ?? 'N/A' } </p>
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                </div>
                                <div>${item.representing ?? 'N/A' }</div>
                                <div class="commonTextEllipsis">$ ${item.sale_price ?? 'N/A' }</div>
                                <div>${item.closing_date ?? 'N/A' }</div>
                                <div> <img src="{{ URL::asset('/images/open.svg')}}" alt="Open icon" class="ppiplinecommonIcon">
                                    <img src="{{URL::asset('/images/splitscreen.svg')}}" alt="Open icon" class="ppiplinecommonIcon">
                                    <img src="{{URL::asset('/images/sticky_note.svg')}}" alt="Open icon" class="ppiplinecommonIcon">
                                    <img src="{{URL::asset('/images/noteBtn.svg')}}" alt="Open icon" class="ppiplinecommonIcon">
                                </div>

                            </div>
                            `);
                            ppipelineTableBody.append(row);
                        }
                    });
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
                         "Stage":"Need Analysis"
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

        // Initial sorting direction
        let sortDirection = 'desc';

        // Function to toggle sort
        window.toggleSort = function(sortField) {
            sortDirection = (sortDirection === 'desc') ? 'asc' : 'desc';
            fetchData(sortField, sortDirection);
        };
    });
</script>

@section('pipelineScript')

@endsection
@endsection