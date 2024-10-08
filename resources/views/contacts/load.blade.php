@foreach ($contacts as $contact)
    <tr>
        <td>
            <table>
                <tr>
                    <td>
                        <div class="tooltip-wrapper">
                            <a href="{{ url('/contacts-view/' . $contact['id']) }}" target="_blank">
                                <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon"
                                    title="Contact Details">
                                <span class="tooltiptext">Contact Details</span>
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="tooltip-wrapper">
                            <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Split screen icon" class="ppiplinecommonIcon"
                                data-bs-toggle="modal" data-bs-target="#newTaskModalId{{ $contact['id'] }}" title="Add Task">
                            <span class="tooltiptext">Add Task</span>
                            {{-- task create model --}}
                            @include('common.tasks.create', ['contact' => $contact, 'type' => 'Contacts'])
                        </div>
                    </td>
                    <td>
                        <div class="tooltip-wrapper">
                            <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Sticky note icon" class="ppiplinecommonIcon"
                                data-bs-toggle="modal" data-bs-target="#"
                                onclick="fetchNotesForContact('{{ $contact['id'] }}','{{ $contact['zoho_contact_id'] }}')">
                            <span class="tooltiptext">View Notes</span>
                            {{-- fetch details notes related 0 --}}
                            <div class="modal fade testing" onclick="event.preventDefault();"
                                id="notefetchrelatedContact{{ $contact['zoho_contact_id'] }}" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content dtaskmodalContent">
                                        <div class="modal-header border-0">
                                            <p class="modal-title dHeaderText">Notes</p>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                onclick="resetValidation()" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body dtaskbody" id="notesContainer{{ $contact['zoho_contact_id'] }}">

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="tooltip-wrapper">
                            <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Note icon" class="ppiplinecommonIcon"
                                data-bs-toggle="modal" data-bs-target="#staticBackdropforNote_{{ $contact['id'] }}">
                            <span class="tooltiptext">Add Note</span>
                            @include('common.notes.create', ['contact' => $contact, 'type' => 'Contacts'])
                        </div>
                    </td>
                </tr>
            </table> 
        </td>
        <td>{{ $contact['first_name']}} {{$contact['last_name'] }}</td>
        <td>
            <div class="d-flex gap-2">
            @php
                $abcds = [
                    '--None--',
                    'A+',
                    'A',
                    'B',
                    'C',
                    'D',
                ];
            @endphp
            <select class="form-select"
                required
                onchange="updateContact('{{ $contact['zoho_contact_id'] }}',null,this.value)">
                @foreach ($abcds as $abcd)
                    <option value="{{ $abcd }}" {{  $contact['abcd'] == $abcd ? 'selected' : '' }}>
                        {{ $abcd }}
                    </option>
                @endforeach
            </select>
            </div>
        </td>
        <td>{{ $contact['relationship_type'] ?? 'N/A' }}</td>
        <td>
            <div class="datamailDiv px-0">
                <img src="{{ URL::asset('/images/mail.svg') }}" alt="" class="datamailicon">
                <div class="d-flex gap-2 overflow-hidden"
                    onclick="editText('{{ $contact['zoho_contact_id'] }}','email_web','{{ $contact['email'] ?? 'N/A' }}')">
                    <p id="email_web{{ $contact['zoho_contact_id'] }}" class="dataEmailtext">
                        {{ $contact['email'] ?? 'N/A' }}</p>

                </div>
            </div>
        </td>
        <td>
            <div class="d-flex gap-2"
                onclick="editText('{{ $contact['zoho_contact_id'] }}','mobile_web','{{ $contact['mobile'] ?? 'N/A' }}')">
                <p id="mobile_web{{ $contact['zoho_contact_id'] }}" class="card-text">
                    {{ $contact['mobile'] ?? 'N/A' }}</p>

            </div>
        </td>
        <td>
            <div class="d-flex gap-2"
                onclick="editText('{{ $contact['zoho_contact_id'] }}','phone_web','{{ $contact['phone'] ?? 'N/A' }}')">
                <p id="phone_web{{ $contact['zoho_contact_id'] }}" class="card-text">
                    {{ $contact['phone'] ?? 'N/A' }}</p>
            </div>
        </td>
       
        <td>
            <div class="datamailDiv px-0">
                <i class="fas fa-map-marker-alt"></i>
                <div class="d-flex gap-2 overflow-hidden">
                    <p id="address{{ $contact['zoho_contact_id'] }}" class="dataEmailtext">
                        @if ($contact['mailing_address'])
                            {{ $contact['mailing_address'] }}
                        @endif
                        @if ($contact['mailing_city'])
                            {{ $contact['mailing_address'] ? ', ' : '' }}
                            {{ $contact['mailing_city'] }}
                        @endif
                        @if ($contact['mailing_state'])
                            {{ $contact['mailing_city'] || $contact['mailing_address'] ? ', ' : '' }}
                            {{ $contact['mailing_state'] }}
                        @endif
                        @if ($contact['mailing_zip'])
                            {{ $contact['mailing_city'] || $contact['mailing_address'] || $contact['mailing_state'] ? ', ' : '' }}
                            {{ $contact['mailing_zip'] }}
                        @endif
                        @if (empty($contact['mailing_address']) &&
                                empty($contact['mailing_city']) &&
                                empty($contact['mailing_state']) &&
                                empty($contact['mailing_zip']))
                            N/A
                        @endif

                    </p>
                </div>
            </div>
        </td>
        <td><div class="d-flex gap-2"
                onclick="editText('{{ $contact['zoho_contact_id'] }}','salutation_s','{{ $contact['salutation_s'] ?? 'N/A' }}')">
                <p id="salutation_s{{ $contact['zoho_contact_id'] }}" class="card-text">
                    {{ $contact['salutation_s'] ?? 'N/A' }}</p>
            </div>
        </td>




    </tr>
@endforeach
