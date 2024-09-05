@if (count($deals) > 0)
    @foreach ($deals as $deal)
        @if($deal['locked_s'])
        <tr class ="lockedRow">
            <td>
                <table>
                    <tr>
                        <td>
                            @if($deal['locked_s'])
                                <i class="fas fa-lock"></i>
                                @endif
                        </td>
                        <td>
                            <div class="tooltip-wrapper">
                                <a href="{{ url('/pipeline-view/' . $deal['id']) }}" target="_blank">
                                    <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon"
                                        class="ppiplinecommonIcon" title="Transaction Details">
                                    <span class="tooltiptext">Transaction Details</span>
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="tooltip-wrapper">
                                <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Split screen icon"
                                    class="ppiplinecommonIcon" data-bs-toggle="modal"
                                    data-bs-target="#newTaskModalId{{ $deal['id'] }}" title="Add Task">
                                <span class="tooltiptext">Add Task</span>
                                {{-- Create New Task Modal --}}
                                @include('common.tasks.create', ['deal' => $deal, 'type' => 'Deals'])
                            </div>
                        </td>
                        <td>
                            <div class="tooltip-wrapper">
                                <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Sticky note icon"
                                    class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#"
                                    onclick="fetchNotesForDeal('{{ $deal['id'] }}','{{ $deal['zoho_deal_id'] }}')">
                                <span class="tooltiptext">View Notes</span>
                                {{-- fetch details notes related 0 --}}
                                <div class="modal fade testing" onclick="event.preventDefault();"
                                    id="notefetchrelatedDeal{{ $deal['zoho_deal_id'] }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered deleteModal">
                                        <div class="modal-content dtaskmodalContent">
                                            <div class="modal-header border-0">
                                                <p class="modal-title dHeaderText">Notes</p>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    onclick="resetValidation()" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="notesContainer{{ $deal['zoho_deal_id'] }}">

                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="tooltip-wrapper">
                                <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Note icon"
                                    class="ppiplinecommonIcon" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdropforNote_{{ $deal['id'] }}">
                                <span class="tooltiptext">Add Note</span>
                                {{-- Notes Modal --}}
                                @include('common.notes.create', ['deal' => $deal, 'type' => 'Deals'])
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
             <td>
                @if(!($deal['tm_preference']=='Non-TM' && $deal['representing']=='Buyer'))
                    @if (!$deal->submittals()->exists() || $deal->submittals->isEmpty())
                        <div class="input-group-text npcontactbtn" id="addSubmittal" onclick="showSubmittalFormTypeadd({{$deal}})">
                            <i class="fas fa-plus plusicon"></i>
                                Add New Submittal
                        </div>
                    @else
                    <a href="{{ url('/submittal-view/' . $deal->submittals['0']['submittalType'] . '/' . $deal->submittals[0]['id']) }}" target="_blank">
                        <div class="input-group-text npcontactbtn" id="addSubmittal">
                        <i class="fas fa-plus plusicon"></i>
                            Show Submittal
                        </div>
                    </a>
                    @endif
                @endif
            </td>
            <td>
                <p class="pdealName"
                    id="deal_name{{ $deal['zoho_deal_id'] }}">{{ $deal['deal_name'] ?? 'N/A' }}</p>
                <p class="paddressName"
                    id="address{{ $deal['zoho_deal_id'] }}">{{ $deal['address'] ?? '' }}</p>
            </td>
            <td
                id="client_name_primary">
                {{ $deal->client_name_primary ?? 'N/A' }}
            </td>
            <td>
                <div class="commonFlex pipelinestatusdiv">
                    <select class="form-select pstatusText"
                        style="background-color: {{ $deal['stage'] === 'Potential' ? '#dfdfdf' : ($deal['stage'] === 'Active' ? '#afafaf' : ($deal['stage'] === 'Pre-Active' ? '#cfcfcf' : ($deal['stage'] === 'Under Contract' ? '#8f8f8f;color=#fff;' : ($deal['stage'] === 'Dead-Lost To Competition' ? '#efefef' : '#6f6f6f;color=#fff;')))) }}"
                        id="stage{{ $deal['zoho_deal_id'] }}" required disabled>
                        @foreach ($allstages as $stage)
                            <option value="{{ $stage }}" {{ $deal['stage'] == $stage ? 'selected' : '' }}>
                                {{ $stage }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>
                <select class="form-select npinputinfo" id="representing{{ $deal['zoho_deal_id'] }}" required disabled>
                    <option value="Buyer" {{ $deal['representing'] == 'Buyer' ? 'selected' : '' }}>
                        Buyer
                    </option>
                    <option value="Seller" {{ $deal['representing'] == 'Seller' ? 'selected' : '' }}>
                        Seller
                    </option>
                </select>
            </td>

            <td
                id="sale_price{{ $deal['zoho_deal_id'] }}">$
                {{ number_format($deal['sale_price'] ?? '0', 0, '.', ',') }}
            </td>
            <td>

                <input type="date"
                    id="closing_date{{ $deal['zoho_deal_id'] }}"
                    value="{{ $deal['closing_date'] ? \Carbon\Carbon::parse($deal['closing_date'])->format('Y-m-d') : '' }}" disabled>

            </td>
            <td>
                <div 
                    id="commission{{ $deal['zoho_deal_id'] }}">
                    {{ number_format($deal['commission'] ?? '0', 2) }}%
                </div>
            </td>
            <td>
                <div>
                    ${{ number_format($deal['potential_gci'] ?? '0', 0, '.', ',') }}</div>
            </td>
            <td>
                <div 
                    id="pipeline_probability{{ $deal['zoho_deal_id'] }}">
                    {{ number_format($deal['pipeline_probability'] ?? '0', 2) }}%
                </div>
            </td>
            <td>
                <div>
                    ${{ number_format(($deal->sale_price ?? 0) * (($deal->commission ?? 0) / 100) * (($deal->pipeline_probability ?? 0) / 100), 0, '.', ',') }}
                </div>
            </td>

            {{-- Update Notification --}}
            <div class="modal fade p-5" id="savemakeModalId{{ $deal['zoho_deal_id'] }}" tabindex="-1">
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
                                <button type="button" class="btn btn-secondary saveModalBtn" data-bs-dismiss="modal">
                                    <i class="fas fa-check trashIcon"></i>
                                    Understood
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </tr>
        @else
        @php
            $endDate30Days = \Carbon\Carbon::now()->addMonth();
            $now = \Carbon\Carbon::now();
            $closingDate = \Carbon\Carbon::parse($deal['closing_date']);
            $deal['isBadDate'] = ($closingDate->lt($now) || $closingDate->between($now, $endDate30Days))
                    && !Str::startsWith($deal['stage'], 'Dead')
                    && $deal['stage'] !== 'Sold'
                    && $deal['stage'] !== "Under Contract";
        @endphp
        <tr>
            <td>
                <table>
                    <tr>
                        <td>
                        </td>
                        
                        <td>
                            <a href="{{ url('/pipeline-view/' . $deal['id']) }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon"
                                    class="ppiplinecommonIcon">
                            </a>
                            
                        </td>
                        <td>
                            <div class="tooltip-wrapper">
                                <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Split screen icon"
                                    class="ppiplinecommonIcon" data-bs-toggle="modal"
                                    data-bs-target="#newTaskModalId{{ $deal['id'] }}" title="Add Task">
                                <span class="tooltiptext">Add Task</span>
                                {{-- Create New Task Modal --}}
                                @include('common.tasks.create', ['deal' => $deal, 'type' => 'Deals'])
                            </div>
                        </td>
                        <td>
                            <div class="tooltip-wrapper">
                                <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Sticky note icon"
                                    class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#"
                                    onclick="fetchNotesForDeal('{{ $deal['id'] }}','{{ $deal['zoho_deal_id'] }}')">
                                <span class="tooltiptext">View Notes</span>
                                {{-- fetch details notes related 0 --}}
                                <div class="modal fade testing" onclick="event.preventDefault();"
                                    id="notefetchrelatedDeal{{ $deal['zoho_deal_id'] }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered deleteModal">
                                        <div class="modal-content dtaskmodalContent">
                                            <div class="modal-header border-0">
                                                <p class="modal-title dHeaderText">Notes</p>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    onclick="resetValidation()" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="notesContainer{{ $deal['zoho_deal_id'] }}">

                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="tooltip-wrapper">
                                <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Note icon"
                                    class="ppiplinecommonIcon" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdropforNote_{{ $deal['id'] }}">
                                <span class="tooltiptext">Add Note</span>
                                {{-- Notes Modal --}}
                                @include('common.notes.create', ['deal' => $deal, 'type' => 'Deals'])
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                @if (!$deal->submittals()->exists() || $deal->submittals->isEmpty())
                    <div class="input-group-text npcontactbtn" id="addSubmittal" onclick="showSubmittalFormType({{$deal}})">
                        <i class="fas fa-plus plusicon"></i>
                            Add New Submittal
                    </div>
                @else
                <a href="{{ url('/submittal-view/' . $deal->submittals['0']['submittalType'] . '/' . $deal->submittals[0]['id']) }}" target="_blank">
                    <div class="input-group-text npcontactbtn" id="addSubmittal">
                    <i class="fas fa-plus plusicon"></i>
                        Show Submittal
                    </div>
                </a>
                @endif
            </td>
            <td>
                <p class="pdealName"
                    onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','deal_name','{{ $deal['id'] }}')"
                    id="deal_name{{ $deal['zoho_deal_id'] }}">{{ $deal['deal_name'] ?? 'N/A' }}</p>
                <p class="paddressName"
                    onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','address','{{ $deal['id'] }}')"
                    id="address{{ $deal['zoho_deal_id'] }}">{{ $deal['address'] ?? '' }}</p>
            </td>
            <td onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','client_name_primary','{{ $deal['id'] }}')"
                id="client_name_primary">
                {{ $deal->client_name_primary ?? 'N/A' }}
            </td>
            <td>
                <div class="commonFlex pipelinestatusdiv form-select"  style="background-color: {{ $deal['stage'] === 'Potential' ? '#dfdfdf' : ($deal['stage'] === 'Active' ? '#afafaf' : ($deal['stage'] === 'Pre-Active' ? '#cfcfcf' : ($deal['stage'] === 'Under Contract' ? '#8f8f8f;color=#fff;' : ($deal['stage'] === 'Dead-Lost To Competition' ? '#efefef' : '#6f6f6f;color=#fff;')))) }}" onchange="updateDealData('stage','{{ $deal['id'] }}','{{ $deal['zoho_deal_id'] }}',this.value)" id="stage{{ $deal['zoho_deal_id'] }}">
                        @foreach ($allstages as $stage)
                            <p >
                                {{ $deal['stage'] == $stage ? $stage : '' }}
                            </p>
                        @endforeach
                </div>
            </td>
            <td>
                <select class="form-select npinputinfo" id="representing{{ $deal['zoho_deal_id'] }}" required
                    onchange="updateDealData('representing','{{ $deal['id'] }}','{{ $deal['zoho_deal_id'] }}',this.value)">
                    <option value="Buyer" {{ $deal['representing'] == 'Buyer' ? 'selected' : '' }}>
                        Buyer
                    </option>
                    <option value="Seller" {{ $deal['representing'] == 'Seller' ? 'selected' : '' }}>
                        Seller
                    </option>
                </select>
            </td>

            <td onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','sale_price','{{ $deal['id'] }}')"
                id="sale_price{{ $deal['zoho_deal_id'] }}">$
                {{ number_format($deal['sale_price'] ?? '0', 0, '.', ',') }}
            </td>
            <td>
                <input type="date"
                    class="{{ $deal['isBadDate'] ?? 0 ? 'badDateInput' : '' }}"
                    onchange="updateDeal('{{ $deal['zoho_deal_id'] }}', 'closing_date', '{{ $deal['id'] }}')"
                    id="closing_date{{ $deal['zoho_deal_id'] }}"
                    value="{{ $deal['closing_date'] ? \Carbon\Carbon::parse($deal['closing_date'])->format('Y-m-d') : '' }}">
            </td>
            <td>
                <div onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','commission','{{ $deal['id'] }}')"
                    id="commission{{ $deal['zoho_deal_id'] }}">
                    {{ number_format($deal['commission'] ?? '0', 2) }}%
                </div>
            </td>
            <td>
                <div>
                    ${{ number_format($deal['potential_gci'] ?? '0', 0, '.', ',') }}</div>
            </td>
            <td>
                <div onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','pipeline_probability','{{ $deal['id'] }}')"
                    id="pipeline_probability{{ $deal['zoho_deal_id'] }}">
                    {{ number_format($deal['pipeline_probability'] ?? '0', 2) }}%
                </div>
            </td>
            <td>
                <div>
                    ${{ number_format(($deal->sale_price ?? 0) * (($deal->commission ?? 0) / 100) * (($deal->pipeline_probability ?? 0) / 100), 0, '.', ',') }}
                </div>
            </td>

            {{-- Update Notification --}}
            <div class="modal fade p-5" id="savemakeModalId{{ $deal['zoho_deal_id'] }}" tabindex="-1">
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
                                <button type="button" class="btn btn-secondary saveModalBtn" data-bs-dismiss="modal">
                                    <i class="fas fa-check trashIcon"></i>
                                    Understood
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </tr>
        @endif
    @endforeach
  
@else
    <div class="pnofound">
        <p>No records found</p>
    </div>
@endif

<script>
    $(document).ready(function(){
       $('[data-toggle="tooltip"]').tooltip();
      
    })

    function showSubmittalFormTypeadd(deal) {
        console.log("SUBMITTAL DATA",deal.representing,deal.tm_preference);
        // deal = JSON.parse(deal);
        let submittalData;
        if (deal.representing === "Buyer" && deal.tm_preference === "CHR TM") {
            addSubmittal('buyer-submittal',deal);
        }else if(deal.representing === "Seller" && deal.tm_preference === "CHR TM"){
            addSubmittal('listing-submittal',deal)
        }else if(deal.representing === "Seller" && deal.tm_preference === "Non-TM"){
            addSubmittal('listing-submittal',deal,'Non-TM');
        }
    }

    function redirectUrl(submittalType=null,submittalData = null,formType =null){
       const url = `{{ url('submittal-create/${submittalType}/${submittalData.id}?formType=${formType}')}}`
       window.open(url,'_blank')
    }

    function generateRandom4DigitNumber() {
            return Math.floor(1000 + Math.random() * 9000);
        }

    function addSubmittal (type,deal,formType=null){
        if(type == "buyer-submittal"){
            var formData = {
                "data": [{
                    "Transaction_Name": {
                        "id":deal.zoho_deal_id,
                        "name":deal.deal_name
                    },
                    "TM_Name": deal.tmName,
                    'Name':'BS-'+(generateRandom4DigitNumber()),
                    "Owner": {
                        "id": "{{ auth()->user()->root_user_id }}",
                        "name": "{{ auth()->user()->name }}",
                        "email": "{{ auth()->user()->email }}",
                    },
                    'formType':formType
                }]
            };
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                // Send AJAX request
            $.ajax({
                url: "/buyer/submittal/create/"+deal.zoho_deal_id,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function (response) {
                    console.log("response",response);
                    redirectUrl(type,response,formType)
                    if (response?.data && response.data[0]?.message) {
                        // Convert message to uppercase and then display
                        const upperCaseMessage = response.data[0].message.toUpperCase();
                        showToast(upperCaseMessage);
                        // window.location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                }
            })
        }else if(type == "listing-submittal"){
            var formData = {
                "data": [{
                    "Transaction_Name": {
                        "id":deal.zoho_deal_id,
                        "name":deal.deal_name
                    },
                    "TM_Name": deal.tmName,
                    'Name':'LS-'+(generateRandom4DigitNumber()),
                    "Owner": {
                        "id": "{{ auth()->user()->root_user_id }}",
                        "name": "{{ auth()->user()->name }}",
                        "email": "{{ auth()->user()->email }}",
                    },
                    'formType':formType
                }]
            };
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                // Send AJAX request
            $.ajax({
                url: "/listing/submittal/create/"+deal.zoho_deal_id,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function (response) {
                    console.log("response",response);
                    redirectUrl(type,response,formType)
                    if (response?.data && response.data[0]?.message) {
                        // Convert message to uppercase and then display
                        const upperCaseMessage = response.data[0].message.toUpperCase();
                        showToast(upperCaseMessage);
                        // window.location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                }
            })
        }

    }

</script>