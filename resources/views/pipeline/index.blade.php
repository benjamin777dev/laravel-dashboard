@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline')

@section('content')
@vite(['resources/css/pipeline.css'])

<div class="container-fluid">
    <div class="commonFlex ppipeDiv">
        <p class="pText">Pipelines</p>
        <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
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
        <table class="table ppipelineTable">
            <thead>
                <th scope="col"></th>
                {{-- <th scope="col">Transaction <i class="fa-solid fa-arrow-up-arrow-down"></i></th> --}}
                <th scope="col">
                    <div class="commonFlex">
                        <p class="mb-0">Transaction</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" class="ppiplineSwapIcon" alt="Transaction icon" id="pipelineSort" onclick="toggleSort('deal_name')">
                    </div>
                </th>

                <th scope="col">
                    <div class="commonFlex">
                        <p class="mb-0">Client Name</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Client icon" class="ppiplineSwapIcon" id="pipelineSort" onclick="toggleSort('contactName.first_name')">
                    </div>
                </th>
                <th scope=" col">
                    <div class="commonFlex">
                        <p class="mb-0">Status </p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Status icon" class="ppiplineSwapIcon" id="pipelineSort" onclick="toggleSort('stage')">
                    </div>
                </th>
                <th scope=" col">
                    <div class="commonFlex">
                        <p class="mb-0">Rep</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Rep icon" class="ppiplineSwapIcon" id="pipelineSort" onclick="toggleSort('representing')">
                    </div>
                </th>
                <th scope=" col">
                    <div class="commonFlex">
                        <p class="mb-0">Price</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Price icon" class="ppiplineSwapIcon" id="pipelineSort" onclick="toggleSort('sale_price')">
                    </div>
                </th>
                <th scope=" col">
                    <div class="commonFlex">
                        <p class="mb-0">Close Date</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Close icon" class="ppiplineSwapIcon" id="pipelineSort" onclick="toggleSort('closing_date')">
                    </div>
                </th>
                <th scope=" col">
                </th>
            </thead>
            <tbody class="ppipelineTableBody">
                @foreach ($deals as $deal)
                <tr>
                    <td><input type="checkbox" /></td>
                    <td>{{ $deal['deal_name'] ?? 'N/A' }}</td>
                    <td>{{ $deal->contactName->first_name ?? 'N/A' }} {{ $deal->contactName->last_name ?? '' }}</td>
                    <td>
                        <div class="commonFlex pipelinestatusdiv">
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
                                                        : '#F18F01')))) }}" class="pstatusText">{{ $deal['stage'] ?? 'N/A' }} </p>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </td>
                    <td>{{ $deal['representing'] ?? 'N/A' }}</td>
                    <td>{{ $deal['sale_price'] ?? 'N/A' }}</td>
                    <td>{{ $deal['closing_date'] ?? 'N/A' }}</td>
                    <td>
                        <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                        <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                        <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                        <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Open icon" class="ppiplinecommonIcon">


                        {{-- {{ $deal['Commission'] ?? 'N/A' }}%
                        {{ $deal['Potential_GCI'] ?? 'N/A' }}
                        {{ $deal['Pipeline_Probability'] ?? 'N/A' }}
                        {{ $deal['Pipeline1'] ?? 'N/A' }} --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
            {{-- <tfoot>
                    <tr>
                        <th colspan="5">Summary
                        </th>
                        <th>
                            <strong>${{ number_format($summary['salesPriceTotal'], 2) }}</strong><br />
            <strong>${{ number_format($summary['salesPriceAverage'], 2) }}</strong>
            </th>
            <th><strong>{{ number_format($summary['commissionAverage'], 2) }}%</strong></th>
            <th>
                <strong>${{ number_format($summary['potentialGCITotal'], 2) }}</strong><br />
                <strong>${{ number_format($summary['potentialGCIAverage'], 2) }}</strong>
            </th>
            <th><strong>{{ number_format($summary['pipelineProbabilityAverage'], 2) }}%</strong></th>
            <th>
                <strong>${{ number_format($summary['probableGCITotal'], 2) }}</strong><br />
                <strong>${{ number_format($summary['probableGCIAverage'], 2) }}</strong>
            </th>
            </tr>

            </tfoot> --}}
        </table>
        <div class="ptableCardDiv">
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
                                                    : '#F18F01')))) }}" class="pstatusText">{{ $deal['stage'] ?? 'N/A' }} </p>
                        <i class="fas fa-angle-down"></i>
                    </div>
                    {{ $deal['closing_date'] ?? 'N/A' }}
                </div>
                <div class="d-flex justify-content-between psellDiv">
                    <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A"> {{ $deal->contactName->first_name ?? 'N/A' }} {{ $deal->contactName->last_name ?? '' }}
                    </div>
                    <div>
                        <img src="{{ URL::asset('/images/sell.svg') }}" alt="A">
                        {{ $deal['sale_price'] ?? 'N/A' }}
                    </div>
                </div>
                <div class="pCardFooter">
                    <div class="pfootericondiv">
                        <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt="" class="pdiversityicon">
                        <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="" class="pdiversityicon">
                    </div>
                    <div>
                        <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="" class="pdiversityicon">
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@vite(['resources/js/pipeline.js'])

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('pipelineSearch');
    const sortInput = document.getElementById('pipelineSort');
    const ppipelineTableBody = document.querySelector('.ppipelineTableBody');
    const ptableCardDiv = document.querySelector('.ptableCardDiv');
    // Add event listener for search input
    searchInput.addEventListener('input', fetchData);

    function fetchData(sortValue, sortType) {
        const searchValue = searchInput.value.trim();
        // const sortValue = sortInput.value.trim();
        console.log("sortValue", sortValue, sortType);
        fetch(`{{ url('/pipeline/deals') }}?search=${encodeURIComponent(searchValue) ? encodeURIComponent(searchValue) : ""}&sort=${sortValue ?sortValue : ""}&sortType=${sortType ? sortType : ""}`)
            .then(response => response.json())
            .then(data => {
                // Clear previous results
                ppipelineTableBody.innerHTML = '';
                ptableCardDiv.innerHTML = ''
                // Render new results
                const isMobile = window.innerWidth < 767; // Check if viewport width is less than 767 pixels
                console.log("ISMOBILE", isMobile);
                data.forEach(item => {
                    if (isMobile) {
                        // Render data in card format
                        const card = document.createElement('div');
                        card.classList.add('pTableCard');
                        card.innerHTML = `
                                        <div class="pTableCard">
                                        <p class="pTableTransText">Transaction</p>
                                        <p class="pTableNameText">${item.deal_name || 'N/A' }</p>
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
                                                                    : '#F18F01')))) }"
                                                    class="pstatusText">${item.stage|| 'N/A' }</p>
                                                <i class="fas fa-angle-down"></i>
                                            </div>
                                            ${item.closing_date|| 'N/A' }
                                        </div>
                                        <div class="d-flex justify-content-between psellDiv">
                                            <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A"> ${item.contact_name ? (item.contact_name.first_name + ' ' + item.contact_name.last_name) : 'N/A'}
                                            </div>
                                            <div>
                                                <img src="{{ URL::asset('/images/sell.svg') }}" alt="A">
                                                ${item.sale_price|| 'N/A' }
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
                                    `;
                        ptableCardDiv.appendChild(card);
                    } else {
                        // Render data in table format
                        const row = document.createElement('tr');
                        row.innerHTML = `
                                        <td><input type="checkbox" /></td>
                                        <td>${item.deal_name || 'N/A'}</td>
                                        <td>${item.contact_name ? (item.contact_name.first_name + ' ' + item.contact_name.last_name) : 'N/A'}</td>
                                        <td>
                                            <div class="commonFlex pipelinestatusdiv">
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
                                        class="pstatusText">${item.stage || 'N/A' } </p>
                                                <i class="fas fa-angle-down"></i>
                                            </div>
                                        </td>
                                        <td>${item.representing || 'N/A'}</td>
                                        <td>${item.sale_price || 'N/A'}</td>
                                        <td>${item.closing_date || 'N/A'}</td>
                                        <td>
                                            <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                            <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                            <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                            <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                        </td>
                                    `;
                        ppipelineTableBody.appendChild(row);
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    let sortDirection = 'desc'; // Initial sorting direction

    function toggleSort(sortField) {
        // Toggle the sorting direction
        sortDirection = (sortDirection === 'desc') ? 'asc' : 'desc';

        // Call fetchData with the updated sorting direction
        fetchData(sortField, sortDirection);
    }
    // });
</script>
@section('pipelineScript')

@endsection
@endsection