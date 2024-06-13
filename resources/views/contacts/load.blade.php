@foreach ($contacts as $contact)
        <tr>
            <td>

                <div class="tooltip-wrapper">
                    <a href="{{ url('/contacts-view/' . $contact['id']) }}" target="_blank">
                        <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon"
                            class="ppiplinecommonIcon" title="Contact Details">
                        <span class="tooltiptext">Contact Details</span>
                    </a>
                </div>

                <div class="tooltip-wrapper">
                    <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Split screen icon"
                        class="ppiplinecommonIcon" data-bs-toggle="modal"
                        data-bs-target="#newTaskModalId{{ $contact['id'] }}" title="Add Task">
                    <span class="tooltiptext">Add Task</span>
                </div>


                <div class="tooltip-wrapper">
                    <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Sticky note icon"
                        class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#"
                        onclick="fetchNotesForContact('{{ $contact['id'] }}','{{ $contact['zoho_contact_id'] }}')">
                    <span class="tooltiptext">View Notes</span>
                </div>

                <div class="tooltip-wrapper">
                    <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Note icon"
                        class="ppiplinecommonIcon" data-bs-toggle="modal"
                        data-bs-target="#staticBackdropforNote_{{ $contact['id'] }}">
                    <span class="tooltiptext">Add Note</span>
                </div>
            </td>
            <td>{{ $contact['first_name'] ?? $contact['last_name'] }}</td>
            <td>{{ $contact['relationship_type'] ?? 'N/A' }}</td>
            <td>
                <div class="datamailDiv px-0">
                    <img src="{{ URL::asset('/images/mail.svg') }}" alt=""
                        class="datamailicon">
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
            {{-- <td>{{ $contact['abcd'] ?? '-' }}</td> --}}
           
            
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
            
            

        </tr>
@endforeach
