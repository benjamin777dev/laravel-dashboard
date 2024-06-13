@if (count($deals) > 0)
    @foreach ($deals as $deal)
        <tr>
            <td>
                <table>
                    <tr>
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
                <div class="commonFlex pipelinestatusdiv">
                    <select class="form-select pstatusText"
                        style="background-color: {{ $deal['stage'] === 'Potential' ? '#dfdfdf' : ($deal['stage'] === 'Active' ? '#afafaf' : ($deal['stage'] === 'Pre-Active' ? '#cfcfcf' : ($deal['stage'] === 'Under Contract' ? '#8f8f8f;color=#fff;' : ($deal['stage'] === 'Dead-Lost To Competition' ? '#efefef' : '#6f6f6f;color=#fff;')))) }}"
                        id="stage{{ $deal['zoho_deal_id'] }}" required
                        onchange="updateDealData('stage','{{ $deal['id'] }}','{{ $deal['zoho_deal_id'] }}',this.value)">
                        @foreach ($allstages as $stage)
                            <option value="{{ $stage }}" {{ $deal['stage'] == $stage ? 'selected' : '' }}>
                                {{ $stage }}
                            </option>
                        @endforeach
                    </select>
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
                    onchange="updateDeal('{{ $deal['zoho_deal_id'] }}','closing_date','{{ $deal['id'] }}')"
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
    @endforeach
@else
    <div class="pnofound">
        <p>No records found</p>
    </div>
@endif
