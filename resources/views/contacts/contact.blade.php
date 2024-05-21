
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3 ">

        @foreach ($contacts as $contact)
            <a id="taskRoute" href="{{ route('contacts.show', $contact['id']) }}">
                <div class="col">
                    <div class="card dataCardDiv">
                        <div class="card-body dacBodyDiv">
                            <div class="d-flex justify-content-between align-items-center dacHeaderDiv">
                                <div class="d-flex gap-2"
                                    onclick="editText('{{ $contact['zoho_contact_id'] }}','first_name','{{ $contact['first_name'] . ' ' . $contact['last_name'] ?? 'N/A' }}')">
                                    <h5 class="card-title" id="first_name{{ $contact['zoho_contact_id'] }}">
                                        {{ $contact['first_name'] . ' ' . $contact['last_name'] ?? 'N/A' }}</h5>
                                </div>

                                <p class="databaseCardWord">
                                    {{ $contact['abcd'] ?? '-' }}</p>
                            </div>
                            <div class="dataPhoneDiv">
                                <img src="{{ URL::asset('/images/phoneb.svg') }}" alt=""
                                    class="dataphoneicon">

                                <div class="d-flex gap-2"
                                    onclick="editText('{{ $contact['zoho_contact_id'] }}','mobile','{{ $contact['mobile'] ?? 'N/A' }}')">
                                    <p id="mobile{{ $contact['zoho_contact_id'] }}" class="card-text">
                                        {{ $contact['mobile'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="datamailDiv">
                                <img src="{{ URL::asset('/images/mail.svg') }}" alt=""
                                    class="datamailicon">
                                <div class="d-flex gap-2 overflow-hidden"
                                    onclick="editText('{{ $contact['zoho_contact_id'] }}','email','{{ $contact['email'] ?? 'N/A' }}')">
                                    <p id="email{{ $contact['zoho_contact_id'] }}" class="dataEmailtext">
                                        {{ $contact['email'] ?? 'N/A' }}</p>

                                </div>
                            </div>
                            <div class="datadiversityDiv">
                                <img src="{{ URL::asset('/images/diversity.svg') }}" alt=""
                                    class="datadiversityicon">
                                <p class="datadiversitytext"> {{ $contact['relationship_type'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="card-footer dataCardFooter">
                            <div class="datafootericondiv" onclick="event.preventDefault();">

                                <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                                    class="datadiversityicon" data-bs-toggle="modal"
                                    data-bs-target="#newTaskModalId{{ $contact['id'] }}">
                                <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                                    class="datadiversityicon"
                                    onclick="fetchNotesForContact('{{ $contact['id'] }}','{{ $contact['zoho_contact_id'] }}')">
                            </div>
                            <div onclick="event.preventDefault();" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropforNote_{{ $contact['id'] }}">
                                <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                                    class="datadiversityicon">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- task create model --}}
                @include('common.tasks.create', ['contact' => $contact, 'type' => 'Contacts'])

                {{-- fetch details notes related 0 --}}
                <div class="modal fade testing" onclick="event.preventDefault();"
                    id="notefetchrelatedContact{{ $contact['zoho_contact_id'] }}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered deleteModal">
                        <div class="modal-content dtaskmodalContent">
                            <div class="modal-header border-0">
                                <p class="modal-title dHeaderText">Notes</p>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    onclick="resetValidation()" aria-label="Close"></button>
                            </div>
                            <div class="modal-body dtaskbody" id="notesContainer">

                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal fade" id="savemakeModalId{{ $contact['zoho_contact_id'] }}" tabindex="-1">
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
                @include('common.notes.create', ['contact' => $contact, 'type' => 'Contacts'])
            </a>
        @endforeach
    </div>

