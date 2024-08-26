@extends('layouts.master')

@section('title', 'zPortal | Pipeline')

@section('content')
    @vite(['resources/css/pipeline.css'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <div class="container-fluid">
        <div class="loader" id="loaderfor" style="display: none;"></div>
        <div class="loader-overlay" id="loaderOverlay" style="display: none;"></div>
        <div class="dbgroupsFlex">
            <p class="ngText text-center">Pipeline</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <div class="input-group-text text-white justify-content-center pTransactionBtn"
                data-bs-toggle="modal" data-bs-target="#transaction" id="create_transaction">
                <i class="fas fa-plus plusicon">
                </i>
                New Transaction
            </div>
               
            </div>
        </div>



        <div class="pipeline-cards-container">
             @include('components.pipe-cards')
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
            <div class="w-control">
                   @component('components.button', [
                    'label' => 'Reset All',
                    'icon' => 'fas fa-sync'
                ])
                @endcomponent
            </div>

        </div>
        <div class="transaction-container">
            @if (count($deals) > 0)
            @component('components.common-table', [
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
        window.fetchData=function(sortValue, sortType, filter = null, searchInput, ppipelineTableBody, ptableCardDiv, resetall,
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

        function generateRandom4DigitNumber() {
            return Math.floor(1000 + Math.random() * 9000);
        }
        window.showSubmittalFormType = function (element) {
            let deal = JSON.parse(decodeURIComponent(element.getAttribute("data-row")));
            console.log("Deal", deal);
            let submittalData;
            if (deal?.representing === "Buyer" && deal?.tm_preference === "CHR TM") {
                addSubmittal("buyer-submittal", deal);
            } else if (
                deal?.representing === "Seller" &&
                deal?.tm_preference === "CHR TM"
            ) {
                addSubmittal("listing-submittal", deal);
            } else if (
                deal?.representing === "Seller" &&
                deal?.tm_preference === "Non-TM"
            ) {
                addSubmittal("listing-submittal", deal, "Non-TM");
            }
        };

        function redirectUrl(
            submittalType = null,
            submittalData = null,
            formType = null
        ) {
            const url = `submittal-create/${submittalType}/${submittalData.id}?formType=${formType}`;
            window.open(url, "_blank");
        }

        function addSubmittal(type, deal, formType = null) {
            let formData = {
                data: [
                    {
                        Transaction_Name: {
                            id: deal.zoho_deal_id,
                            name: deal.deal_name,
                        },
                        TM_Name: deal.tmName,
                        Name:
                            type === "buyer-submittal"
                                ? "BS-" + generateRandom4DigitNumber()
                                : "LS-" + generateRandom4DigitNumber(),
                        Owner: {
                            id: "{{ auth()->user()->root_user_id }}",
                            name: "{{ auth()->user()->name }}",
                            email: "{{ auth()->user()->email }}",
                        },
                        formType: formType,
                    },
                ],
            };

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            const baseUrl = window.location.origin;
            const endpoint = `${
                type === "buyer-submittal" ? "buyer" : "listing"
            }/submittal/create`;
            const url = `${baseUrl}/${endpoint}/${deal.zoho_deal_id}`;

            $.ajax({
                url: url,
                type: "POST",
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify(formData),
                success: function (response) {
                    console.log("response", response);
                    redirectUrl(type, response, formType);
                    if (response?.data && response.data[0]?.message) {
                        const upperCaseMessage = response.data[0].message.toUpperCase();
                        showToast(upperCaseMessage);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                },
            });
        }
        
        function addNonTmForIndex(id="",dealname="") {
            let formData = {
                "data": [{
                    "Owner": {
                        "id": "{{ auth()->user()->root_user_id }}",
                        "full_name": "{{ auth()->user()->name }}"
                    },
                    "Exchange_Rate": 1,
                    "Currency": "USD",
                    "Related_Transaction": {
                        "id": id,
                        "name": dealname
                    },
                    "Name": 'N'+(generateRandom4DigitNumber()),
                    "$zia_owner_assignment": "owner_recommendation_unavailable",
                    "zia_suggested_users": {}
                }],
                "skip_mandatory": false
            }
            console.log(formData, 'sdfjsdfjsd');
            
            $.ajax({
                url: '/create-nontm',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response) {
                        const url = `{{ url('/nontm-create/${response?.id}') }}`
                        window.open(url,'_blank')
                        // window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                }
            })
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
@section('script')
    <!-- Responsive Table js -->
    <script src="{{ URL::asset('build/libs/admin-resources/rwd-table/rwd-table.min.js') }}"></script>

    <!-- Init js 
    <script src="{{ URL::asset('build/js/pages/table-responsive.init.js') }}"></script>-->
@endsection
@endsection
@endsection
