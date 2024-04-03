@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline')

@section('content')
    @vite(['resources/css/pipeline.css'])

    <div class="container-fluid">
        <div class="commonFlex ppipeDiv">
            <p class="pText">Pipelines</p>
            <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon" data-bs-toggle="modal"
                data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
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
                            <img src="{{ URL::asset('/images/swap_vert.svg') }}" class="ppiplineSwapIcon"
                                alt="Transaction icon">
                        </div>
                    </th>

                    <th scope="col">
                        <div class="commonFlex">
                            <p class="mb-0">Client Name</p>
                            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Client icon" class="ppiplineSwapIcon">
                        </div>
                    </th>
                    <th scope="col">
                        <div class="commonFlex">
                            <p class="mb-0">Status </p>
                            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Status icon" class="ppiplineSwapIcon">
                        </div>
                    </th>
                    <th scope="col">
                        <div class="commonFlex">
                            <p class="mb-0">Rep</p>
                            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Rep icon" class="ppiplineSwapIcon">
                        </div>
                    </th>
                    <th scope="col">
                        <div class="commonFlex">
                            <p class="mb-0">Price</p>
                            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Price icon" class="ppiplineSwapIcon">
                        </div>
                    </th>
                    <th scope="col">
                        <div class="commonFlex">
                            <p class="mb-0">Close Date</p>
                            <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Close icon" class="ppiplineSwapIcon">
                        </div>
                    </th>
                    <th scope="col"></th>
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
                                                : ($deal['tage'] === 'Under Contract'
                                                    ? '#477ABB'
                                                    : ($deal['Stage'] === 'Dead'
                                                        ? '#575B58'
                                                        : '#F18F01')))) }}"
                                        class="pstatusText">{{ $deal['stage'] ?? 'N/A' }} </p>
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </td>
                            <td>{{ $deal['representing'] ?? 'N/A' }}</td>
                            <td>{{ $deal['sale_price'] ?? 'N/A' }}</td>
                            <td>{{ $deal['closing_date'] ?? 'N/A' }}</td>
                            <td>
                                <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Open icon"
                                    class="ppiplinecommonIcon">
                                <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Open icon"
                                    class="ppiplinecommonIcon">
                                <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Open icon"
                                    class="ppiplinecommonIcon">


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
        </div>
    </div>
    @vite(['resources/js/pipeline.js'])

    <script>
        /* document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('pipelineSearch');
            const sortSelect = document.getElementById('pipelineSort');

            function filterPipeline() {
                const filterValue = searchInput.value.toUpperCase();
                const rows = document.querySelectorAll('.pppipelineTableBody tr');

                rows.forEach(row => {
                    const transactionCell = row.querySelector('td:nth-child(2)');
                    const clientNameCell = row.querySelector('td:nth-child(3)');
                    const statusCell = row.querySelector('td:nth-child(4)');

                    if (transactionCell.textContent.toUpperCase().includes(filterValue) ||
                        clientNameCell.textContent.toUpperCase().includes(filterValue) ||
                        statusCell.textContent.toUpperCase().includes(filterValue)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }


            // function sortPipeline() {
            //     const sortValue = sortSelect.value;
            //     const rows = document.querySelectorAll('.ppipelineTableBody tr');
            //     const sortedRows = Array.from(rows);
            //     console.log("sortedRows", sortedRows, "rows", rows, "sortValue", sortValue)
            //     sortedRows.sort((a, b) => {
            //         const aValue = a.querySelector(`td:nth-child(${sortValue})`).textContent.trim()
            //             .toLowerCase();
            //         const bValue = b.querySelector(`td:nth-child(${sortValue})`).textContent.trim()
            //             .toLowerCase();
            //         return aValue.localeCompare(bValue);
            //     });

            //     const tbody = document.querySelector('.ppipelineTableBody');
            //     tbody.innerHTML = "";
            //     sortedRows.forEach(row => tbody.appendChild(row));
            // }
                
            searchInput.addEventListener('keyup', filterPipeline);
            // sortSelect.addEventListener('change', sortPipeline);
        }); */
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('pipelineSearch');
            // Add an event listener to send search term as request
            searchInput.addEventListener('input', function() {
                const searchValue = this.value.trim();
                if (searchValue.length > 0) {
                    // Make an AJAX request to the server with the search term
                    fetch(`{{ route('pipeline.index') }}?search=${encodeURIComponent(searchValue)}`)
                        .then(response => response.text())
                        .then(data => {
                            // Replace the table body with the updated data from the server
                            ppipelineTableBody.innerHTML = data;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    // Reset the table to its original state if search input is empty
                    ppipelineTableBody.innerHTML = '';
                }
            });
        });
        
    </script>
@section('pipelineScript')

@endsection
@endsection
