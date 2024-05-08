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

     <div class="table-responsive">
            <div class="npcontactsTable">
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
                            id="pipelineSort" onclick="toggleSort('client_name_primary')">
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
                    <div class="commonFlex" style="width: 75px;">
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
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Commission</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Commission" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                    </div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Potential GCI</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Potential GCI" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                    </div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Probability</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Probability" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                    </div>
                </div>
                <div>
                    <div class="commonFlex">
                        <p class="mb-0">Probable GCI</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Probable GCI" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                    </div>
                </div>
                <div></div>
            </div>
            <div class = "psearchandsort">
                @if (count($deals) > 0)
               @foreach ($deals as $deal)
                    <div class="npcontactsBody">
                        <div class="commonTextEllipsis" onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','deal_name','{{$deal['id']}}')" id="deal_name{{ $deal['zoho_deal_id'] }}">{{ $deal['deal_name'] ?? 'N/A' }}</div>
                        <div class="commonTextEllipsis" onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','client_name_primary','{{$deal['id']}}')" id="client_name_primary{{ $deal['zoho_deal_id'] }}">{{ $deal->client_name_primary ?? 'N/A' }}</div>
                        <div>
                            <div class="commonFlex pipelinestatusdiv">
                                <select class="form-select pstatusText" style="background-color: {{ $deal['stage'] === 'Potential'
                                        ? '#dfdfdf'
                                        : ($deal['stage'] === 'Active'
                                            ? '#afafaf'
                                            : ($deal['stage'] === 'Pre-Active'
                                                ? '#cfcfcf'
                                                : ($deal['stage'] === 'Under Contract'
                                                    ? '#8f8f8f;color=#fff;'
                                                    : ($deal['stage'] === 'Dead-Lost To Competition'
                                                        ? '#efefef'
                                                        : '#6f6f6f;color=#fff;')))) }}" id="stage{{ $deal['zoho_deal_id'] }}" required onchange="updateDealData('stage','{{$deal['id']}}','{{ $deal['zoho_deal_id'] }}',this.value)">
                                    @foreach($allstages as $stage)
                                        <option value="{{$stage}}" {{$deal['stage'] == $stage ? 'selected' : ''}}>{{$stage}}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="" style="width: 75px;">
                            <select class="form-select npinputinfo" id="representing{{ $deal['zoho_deal_id'] }}" required onchange="updateDealData('representing','{{$deal['id']}}','{{ $deal['zoho_deal_id'] }}',this.value)">
                                <option value="Buyer" {{$deal['representing'] == 'Buyer' ? 'selected' : ''}}>Buyer</option>
                                <option value="Seller" {{$deal['representing'] == 'Seller' ? 'selected' : ''}}>Seller</option>
                            </select>
                        </div>
                        <div class="commonTextEllipsis" onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','sale_price','{{$deal['id']}}')" id="sale_price{{ $deal['zoho_deal_id'] }}">$ {{ $deal['sale_price'] ?? 'N/A' }}</div>
                        <div onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','closing_date','{{$deal['id']}}')" id="closing_date{{ $deal['zoho_deal_id'] }}">{{ $deal['closing_date'] ?? 'N/A' }}</div>
                        <div>
                            <div class="commonTextEllipsis" onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','commission','{{$deal['id']}}')" id="commission{{ $deal['zoho_deal_id'] }}">{{ $deal['commission'] ?? '0' }}%</div>
                        </div>
                        <div>
                            <div class="commonTextEllipsis">${{ $deal['potential_gci'] ?? '0' }}</div>
                        </div>
                        <div>
                            <div class="commonTextEllipsis" onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','probability','{{$deal['id']}}')" id="probability{{ $deal['zoho_deal_id'] }}">{{ $deal['probability'] ?? '0' }}%</div>
                        </div>
                        <div>
                            <div class="commonTextEllipsis">${{ $deal['probable_gci'] ?? '0' }}</div>
                        </div>
                        <div>
                            <a href="{{ url('/pipeline-view/' . $deal['id']) }}" target="_blank">
                                <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                            </a>
                            <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Open icon" class="ppiplinecommonIcon"
                                data-bs-toggle="modal" data-bs-target="#newTaskModalId{{ $deal['id'] }}">
                            <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Open icon" class="ppiplinecommonIcon"
                                data-bs-toggle="modal" data-bs-target="#newTaskModalId{{ $deal['id'] }}">
                            <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Note icon" class="ppiplinecommonIcon"
                                data-bs-toggle="modal" data-bs-target="#staticBackdropforNote_{{ $deal['id'] }}">
                        </div>
                    </div>
                    {{-- Create New Task Modal --}}
                    <div class="modal fade" id="newTaskModalId{{ $deal['id'] }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered deleteModal">
                            <div class="modal-content dtaskmodalContent">
                                <div class="modal-header border-0">
                                    <p class="modal-title dHeaderText">Create New Tasks</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidation()"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body dtaskbody">
                                    <p class="ddetailsText">Details</p>
                                    <textarea name="subject" onkeyup="validateTextarea();" id="darea" rows="4" class="dtextarea"></textarea>
                                    <div id="subject_error" class="text-danger"></div>
                                    <p class="dRelatedText">Related to...</p>
                                    <div class="btn-group dmodalTaskDiv">
                                        <select class="form-select dmodaltaskSelect" name="related_to"
                                            aria-label="Select Transaction">
                                            <option value="{{ $deal['zoho_deal_id'] }}" selected>
                                                {{ $deal['deal_name'] }}
                                            </option>
                                        </select>
                                    </div>
                                    <p class="dDueText">Date due</p>
                                    <input type="date" name="due_date" class="dmodalInput" />
                                </div>
                                <div class="modal-footer ">
                                    <button type="button" onclick="addTask('{{ $deal['zoho_deal_id'] }}')" class="btn btn-secondary taskModalSaveBtn">
                                        <i class="fas fa-save saveIcon"></i> Save Changes
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- Notes Modal --}}
                    @include('common.notes.create',['deal'=>$deal])
                    {{-- Update Notification--}}
                    <div class="modal fade" id="savemakeModalId{{ $deal['zoho_deal_id'] }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered deleteModal">
                            <div class="modal-content">
                                <div class="modal-header saveModalHeaderDiv border-0">
                                    {{-- <h5 class="modal-title">Modal title</h5> --}}
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body saveModalBodyDiv">
                                    <p class="saveModalBodyText" id="updated_message_make">
                                        Changes have been saved</p>
                                </div>
                                <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                                    <div class="d-grid col-12">
                                        <button type="button" class="btn btn-secondary saveModalBtn"
                                            data-bs-dismiss="modal">
                                            <i class="fas fa-check trashIcon"></i>
                                            Understood
                                        </button>
                                    </div>

                                </div>

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
            <div class="ptableCardDiv">
                @if (count($deals) > 0)
                @foreach ($deals as $deal)
                    <div class="pTableCard">
                        <p class="pTableTransText">Transaction</p>
                        <p class="pTableNameText">{{ $deal['deal_name'] ?? 'N/A' }}</p>
                        <div class="d-flex justify-content-between">
                            <div class="pTableSelect pipelinestatusdiv">
                                <p style="background-color: {{ $deal['stage'] === 'Potential'
                                    ? '#dfdfdf'
                                    : ($deal['stage'] === 'Active'
                                        ? '#afafaf'
                                        : ($deal['stage'] === 'Pre-Active'
                                            ? '#cfcfcf'
                                            : ($deal['stage'] === 'Under Contract'
                                                ? '#8f8f8f;color=#fff;'
                                                : ($deal['stage'] === 'Dead-Lost To Competition'
                                                    ? '#efefef'
                                                    : '#6f6f6f;color=#fff;')))) }}"
                                    class="pstatusText">{{ $deal['stage'] ?? 'N/A' }} </p>
                                <i class="fas fa-angle-down"></i>
                            </div>
                            {{ $deal['closing_date'] ?? 'N/A' }}
                        </div>
                        <div class="d-flex justify-content-between psellDiv">
                            <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A">
                                {{ $deal->client_name_primary ?? 'N/A' }}
                                {{-- {{ $deal->contactName->last_name ?? '' }} --}}
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
                                    ? '#dfdfdf'
                                    : (item.stage === 'Active'
                                        ? '#afafaf'
                                        : (item.stage === 'Pre-Active'
                                            ? '#cfcfcf'
                                            : (item.stage === 'Under Contract'
                                                ? '#8f8f8f;color=#fff;'
                                                : (item.stage === 'Dead-Lost To Competition'
                                                    ? '#efefef'
                                                    : '#6f6f6f;color=#fff;'))))}"
                                                    class="pstatusText">${item.stage || 'N/A'}</p>
                                                <i class="fas fa-angle-down"></i>
                                            </div>
                                            ${item.closing_date || 'N/A'}
                                        </div>
                                        <div class="d-flex justify-content-between psellDiv">
                                            <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A"> ${item.client_name_primary?? 'N/A'}
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
                                <div class="commonTextEllipsis">${item.client_name_primary?? 'N/A'}</div>
                                <div>
                                    <div class="commonFlex  pipelinestatusdiv">
                                        <p style="background-color: ${item.stage === 'Potential'
                                            ? '#dfdfdf'
                                            : (item.stage === 'Active'
                                                ? '#afafaf'
                                                : (item.stage === 'Pre-Active'
                                                    ? '#cfcfcf'
                                                    : (item.stage === 'Under Contract'
                                                        ? '#8f8f8f;color=#fff;'
                                                        : (item.stage === 'Dead-Lost To Competition'
                                                            ? '#efefef'
                                                            : '#6f6f6f;color=#fff;')))) }"
                                            class="pstatusText ">${item.stage ?? 'N/A' } </p>
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                </div>
                                <div>${item.representing ?? 'N/A' }</div>
                                <div class="commonTextEllipsis">$ ${item.sale_price ?? 'N/A' }</div>
                                <div>${item.closing_date ?? 'N/A' }</div>
                                <div> <img src="{{ URL::asset('/images/open.svg')}}" alt="Open icon" class="ppiplinecommonIcon">
                                    <img src="{{URL::asset('/images/splitscreen.svg')}}" alt="Open icon" class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#newTaskModalId${item.id}">
                                    <img src="{{URL::asset('/images/sticky_note.svg')}}" alt="Open icon" class="ppiplinecommonIcon">
                                    <img src="{{URL::asset('/images/noteBtn.svg')}}" alt="Open icon" class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#staticBackdrop_${item.id}">
                                </div>

                                {{-- Notes Modal --}}
                    <div class="modal fade" id="staticBackdrop_${item.id}" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel_{{ $deal['id'] }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered deleteModal">
                            <div class="modal-content noteModal">
                                <div class="modal-header border-0">
                                    <p class="modal-title dHeaderText">Note</p>
                                    <button type="button" onclick="resetFormAndHideSelect();" class="btn-close"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="noteForm_{{ $deal['id'] }}" action="{{ route('save.note') }}" method="post">
                                    @csrf
                                    <div class="modal-body dtaskbody">
                                        <p class="ddetailsText">Details</p>
                                        <textarea name="note_text" id="note_text_{{ $deal['id'] }}" rows="4"
                                            class="dtextarea"></textarea>
                                        <div id="note_text_error_{{ $deal['id'] }}" class="text-danger"></div>
                                        <p class="dRelatedText">Related to...</p>
                                        <div class="btn-group dmodalTaskDiv">
                                            <select class="form-select dmodaltaskSelect" id="related_to_{{ $deal['id'] }}"
                                                onchange="moduleSelected(this,'{{$deal}}')" name="related_to"
                                                aria-label="Select Transaction">
                                                <option value="">Please select one</option>
                                                @foreach ($retrieveModuleData as $item)
                                                    @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                                        <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <select class="form-select dmodaltaskSelect" id="taskSelect_{{ $deal['id'] }}"
                                                name="related_to_parent" aria-label="Select Transaction" style="display: none;">
                                                <option value="{{$deal['zoho_deal_id']}}">{{$deal['deal_name']}}</option>
                                            </select>
                                        </div>
                                        <div id="related_to_error_{{ $deal['id'] }}" class="text-danger"></div>
                                    </div>
                                    <div class="modal-footer dNoteFooter border-0">
                                        <button type="button" id="validate-button_{{ $deal['id'] }}"
                                            onclick="validateForm('{{ $deal['id'] }}')"
                                            class="btn btn-secondary dNoteModalmarkBtn">
                                            <i class="fas fa-save saveIcon"></i> Add Note
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Create New Task Modal --}}
                    <div class="modal fade" id="newTaskModalId${item.id}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered deleteModal">
                            <div class="modal-content dtaskmodalContent">
                                <div class="modal-header border-0">
                                    <p class="modal-title dHeaderText">Create New Tasks</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidation()"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body dtaskbody">
                                    <p class="ddetailsText">Details</p>
                                    <textarea name="subject" onkeyup="validateTextarea();" id="darea" rows="4" class="dtextarea"></textarea>
                                    <div id="subject_error" class="text-danger"></div>
                                    <p class="dRelatedText">Related to...</p>
                                    <div class="btn-group dmodalTaskDiv">
                                        <select class="form-select dmodaltaskSelect" name="related_to"
                                            aria-label="Select Transaction">
                                            <option value="${item.zoho_deal_id}" selected>
                                                ${item.deal_name}
                                            </option>
                                        </select>
                                    </div>
                                    <p class="dDueText">Date due</p>
                                    <input type="date" name="due_date" class="dmodalInput" />
                                </div>
                                <div class="modal-footer ">
                                    <button type="button" onclick="addTask('${item.zoho_deal_id}')" class="btn btn-secondary taskModalSaveBtn">
                                        <i class="fas fa-save saveIcon"></i> Save Changes
                                    </button>

                                </div>

                            </div>
                        </div>
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

        function validateTextarea() {
        var textarea = document.getElementById('darea');
        var textareaValue = textarea.value.trim();
        // Check if textarea value is empty
        if (textareaValue === '') {
            // Show error message or perform validation logic
            document.getElementById("subject_error").innerHTML = "please enter details";
        } else {
            document.getElementById("subject_error").innerHTML = "";
        }
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

        // Initial sorting direction
        let sortDirection = 'desc';

        // Function to toggle sort
        window.toggleSort = function(sortField) {
            // Toggle the sort direction
            sortDirection = (sortDirection === 'desc') ? 'asc' : 'desc';
            // Call fetchDeal with the sortField parameter
            fetchDeal(sortField, sortDirection);
        };

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

    window.moduleSelected = function(selectedModule, deal) {
        console.log("dealId",deal);
        deal = JSON.parse(deal)
        var selectedOption = selectedModule.options[selectedModule.selectedIndex];
        var selectedText = selectedOption.text;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/task/get-' + selectedText + '?dealId=' + deal.zoho_deal_id, // Fixed the concatenation
            method: "GET",
            dataType: "json",
            success: function(response) {
                var tasks = response;
                var taskSelect = $('#taskSelect_'+deal.id);
                taskSelect.empty();
                $.each(tasks, function(index, task) {
                    if (selectedText === "Tasks") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_task_id,
                            text: task?.subject
                        }));
                    }
                    if (selectedText === "Deals") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_deal_id,
                            text: task?.deal_name
                        }));
                    }
                    if (selectedText === "Contacts") {
                        taskSelect.append($('<option>', {
                            value: task?.contactData?.zoho_contact_id,
                            text: task?.contactData?.first_name + ' ' + task?.contactData?.last_name
                        }));
                    }
                });
                taskSelect.show();
            },
            error: function(xhr, status, error) {
                console.error("Ajax Error:", error);
            }
        });
    }

    window.resetFormAndHideSelect = function(dealId) {
        $('#noteForm_'+dealId).get(0).reset(); // Changed to jQuery method
        $('#taskSelect_'+_dealId).hide();
        clearValidationMessages(dealId);
    }

    window.clearValidationMessages = function(dealId) {
        $("#note_text_error_"+dealId).text("");
        $("#related_to_error_"+dealId).text("");
    }

    window.validateForm = function(dealId) {
        let noteText = $("#note_text_"+dealId).val().trim();
        let relatedTo = $("#related_to_notes_"+dealId).val();
        let isValid = true;

        // Reset errors
        clearValidationMessages(dealId);

        // Validate note text length
        if (noteText.length > 100) {
            $("#note_text_error_"+dealId).text("Note text must be 100 characters or less");
            isValid = false;
        }
        // Validate note text
        if (noteText === "") {
            $("#note_text_error_"+dealId).text("Note text is required");
            isValid = false;
        }

        // Validate related to
        if (relatedTo === "") {
            $("#related_to_error_"+dealId).text("Related to is required");
            $('#taskSelect_'+dealId).hide();
            isValid = false;
        }
        if (isValid) {
            let changeButton = $('#validate-button_'+dealId);
            changeButton.prop("type", "submit"); // Changed to jQuery method
        }
        return isValid;
    }

    window.addTask= function(deal) {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML = "please enter details";
            return;
        }
        // var whoSelectoneid = document.getElementsByName("who_id")[0].value;
        // var whoId = window.selectedTransation
        // if (whoId === undefined) {
        //     whoId = whoSelectoneid
        // }
        var dueDate = document.getElementsByName("due_date")[0].value;
        
        var formData = {
            "data": [{
                "Subject": subject,
                // "Who_Id": {
                //     "id": whoId
                // },
                "Status": "Not Started",
                "Due_Date": dueDate,
                // "Created_Time":new Date()
                "Priority": "High",
                "What_Id":{
                    "id":deal
                },
                "$se_module":"Deals"
            }],
            "_token": '{{ csrf_token() }}'
        };
        console.log("formData",formData);
        $.ajax({
            url: '{{ route('create.task') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    alert(upperCaseMessage);
                    window.location.reload();
                } else {
                    alert("Response or message not found");
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }
   
</script>

@section('pipelineScript')

@endsection
@endsection