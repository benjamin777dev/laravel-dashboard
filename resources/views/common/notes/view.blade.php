<div class="col-md-4">
        <h4 class="text-start dFont600 mb-4">Notes</h4>
        @if ($notesInfo->isEmpty())
            <p class="text-center">No notes found.</p>
        @else
            <ul class="list-group dnotesUl">
                @foreach ($notesInfo as $note)
                    <li
                        class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                        <div class="text-start" onclick="handleDeleteCheckbox('{{ $note['id'] }}')"
                            class="form-check-input checkbox{{ $note['id'] }}"
                            id="editButton{{ $note['id'] }}" class="btn btn-primary dnotesBottomIcon"
                            type="button" data-bs-toggle="modal"
                            data-bs-target="#staticBackdropnoteview{{ $note['id'] }}">
                            @if ($note['related_to_type'] === 'Deals')
                                <span class="dFont800 dFont13">Related to:</span>
                                {{ $note->dealData->deal_name ?? '' }}<br />
                            @elseif ($note['related_to_type'] === 'Contacts')
                                <span class="dFont800 dFont13">Related to:</span>
                                {{ $note->contactData->first_name ?? '' }}
                                {{ $note->contactData->last_name ?? '' }}<br />
                            @else
                                <span class="dFont800 dFont13">Related to:</span>
                                Global
                            @endif
                            <p class="dFont400 fs-4 mb-0">
                                {{ $note['note_content'] }}
                            </p>
                        </div>

                        {{-- dynamic edit modal --}}
                        {{-- note update modal --}}
                        <div class="modal fade" id="staticBackdropnoteupdate{{ $note['id'] }}"
                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <!-- Font Awesome delete icon -->
                                <div class="modal-content noteModal">
                                    <div class="modal-header justify-content-between border-0">


                                        <p class="modal-title dHeaderText">Note</p>


                                        <div>
                                            <i class="fa fa-trash trash-icon"
                                                onclick="openConfirmationModal('confirmModal{{ $note['id'] }}')"></i>

                                            <button type="button" class="btn-close closeIcon"
                                                data-bs-dismiss="modal" aria-label="Close"
                                                onclick="document.getElementById('editButton{{ $note['id'] }}').checked=false;"></button>
                                        </div>
                                        <!-- Your modal markup (assuming it has an id 'confirmModal') -->
                                        <div id="confirmModal{{ $note['id'] }}" class="modal">
                                            <!-- Modal content -->
                                            <div class="modal-content">
                                                <span class="close"
                                                    onclick="closeConfirmationModal('confirmModal{{ $note['id'] }}')">&times;</span>
                                                <p>Are you sure you want to delete?</p>
                                                <!-- Add buttons for confirmation -->
                                                <button onclick="deleteNoteItem()">Yes</button>
                                                <button
                                                    onclick="closeConfirmationModal('confirmModal{{ $note['id'] }}')">No</button>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('update.note', ['id' => $note['zoho_note_id']]) }}"
                                        method="post">
                                        @csrf
                                        @method('POST')
                                        <div class="modal-body dtaskbody">
                                            <p class="ddetailsText">Details</p>
                                            <textarea name="note_text" rows="4" class="dtextarea">{{ $note['note_content'] }}</textarea>
                                            @error('note_content')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <p class="dRelatedText">Related to...</p>
                                            <div class="btn-group dmodalTaskDiv">
                                                
                                                @if($note['related_to_type'] === 'Deals') 
                                                    <select class="form-select dmodaltaskSelect" id="related_to_{{ $note->dealData['id'] }}"
                                                            name="related_to" aria-label="Select Transaction">
                                                        @foreach ($retrieveModuleData as $item)
                                                            @if (in_array($item['api_name'], ['Deals']))
                                                                <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <select class="form-select dmodaltaskSelect" id="taskSelect_{{ $note->dealData['id'] }}"
                                                            name="related_to_parent" aria-label="Select Transaction">
                                                        <option value="{{ $note->dealData['zoho_deal_id'] }}">{{ $note->dealData['deal_name'] }}</option>
                                                    </select>
                                                @elseif($note['related_to_type'] === 'Contacts')
                                                <select class="form-select dmodaltaskSelect" id="related_to_{{ $note->contactData['id'] }}"
                                                            name="related_to" aria-label="Select Transaction">
                                                        @foreach ($retrieveModuleData as $item)
                                                            @if (in_array($item['api_name'], ['Contacts']))
                                                                <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <select class="form-select dmodaltaskSelect" id="taskSelect_{{ $note->contactData['id'] }}"
                                                            name="related_to_parent" aria-label="Select Transaction">
                                                        <option value="{{ $note->contactData['zoho_deal_id'] }}">{{ $note->contactData['deal_name'] }}</option>
                                                    </select>
                                                @else
                                                    <div class="btn-group dmodalTaskDiv">
                                                        <select class="form-select dmodaltaskSelect" id="related_to{{$note['id']}}" onchange="moduleSelectedNote(this,'{{$note['id']}}')"
                                                            name="related_to" aria-label="Select Transaction">
                                                            <option value="">Please select one</option>
                                                            @foreach ($retrieveModuleData as $item)
                                                                @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                                                    <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <select class="form-select dmodaltaskSelect" id="noteSelect{{$note['id']}}" name="related_to_parent"
                                                            aria-label="Select Transaction" style="display: none;">
                                                            <option value="">Please Select one</option>
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>

                                            @error('related_to')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 modal-footer dNoteFooters border-0">
                                                <button type="submit"
                                                    class="btn btn-secondary dNoteModalmarkBtns">
                                                    <i class="fas fa-save saveIcon"></i> Update Note
                                                </button>
                                            </div>
                                            {{-- <div class="col-md-6 modal-footer dNoteFooters border-0">
                                                <button type="button"
                                                    onclick="markAsDone({{ $note['id'] }})"
                                                    class="btn btn-secondary dNoteModalmarkBtns">
                                                    <i class="fas fa-save saveIcon"></i> Mark as Done
                                                </button>
                                            </div> --}}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- note view modal --}}
                        <div class="modal fade" id="staticBackdropnoteview{{ $note['id'] }}"
                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content noteModal">
                                    <div class="modal-header border-0">
                                        <p class="modal-title dHeaderText">Note</p>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"
                                            onclick="document.getElementById('editButton{{ $note['id'] }}').checked=false;"></button>
                                    </div>
                                    <div class="modal-body dtaskbody">
                                        <p class="ddetailsText">Details</p>
                                        <textarea name="note_text" rows="4" class="dtextarea" readonly>{{ $note['note_content'] }}</textarea>
                                        @error('note_content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <p class="dRelatedText">Related to...</p>
                                        <div class="btn-group dmodalTaskDiv">
                                                
                                                @if($note['related_to_type'] === 'Deals') 
                                                    <select class="form-select dmodaltaskSelect" id="related_to_{{ $note->dealData['id'] }}"
                                                            name="related_to" aria-label="Select Transaction">
                                                        @foreach ($retrieveModuleData as $item)
                                                            @if (in_array($item['api_name'], ['Deals']))
                                                                <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <select class="form-select dmodaltaskSelect" id="taskSelect_{{ $note->dealData['id'] }}"
                                                            name="related_to_parent" aria-label="Select Transaction">
                                                        <option value="{{ $note->dealData['zoho_deal_id'] }}">{{ $note->dealData['deal_name'] }}</option>
                                                    </select>
                                                @elseif($note['related_to_type'] === 'Contacts')
                                                <select class="form-select dmodaltaskSelect" id="related_to_{{ $note->contactData['id'] }}"
                                                            name="related_to" aria-label="Select Transaction">
                                                        @foreach ($retrieveModuleData as $item)
                                                            @if (in_array($item['api_name'], ['Contacts']))
                                                                <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <select class="form-select dmodaltaskSelect" id="taskSelect_{{ $note->contactData['id'] }}"
                                                            name="related_to_parent" aria-label="Select Transaction">
                                                        <option value="{{ $note->contactData['zoho_deal_id'] }}">{{ $note->contactData['deal_name'] }}</option>
                                                    </select>
                                                @else
                                                    <div class="btn-group dmodalTaskDiv">
                                                        <select class="form-select dmodaltaskSelect" id="related_to"
                                                            name="related_to" aria-label="Select Transaction">
                                                            <option value="">Global</option>
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>
                                        @error('related_to')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gx-2"
                            onclick="handleDeleteCheckbox('{{ $note['id'] }}')" data-bs-toggle="modal"
                            id="editButton{{ $note['id'] }}"
                            data-bs-target="#staticBackdropnoteupdate{{ $note['id'] }}">
                            {{-- <input type="button" 
                                class="checkboxupdate{{ $note['id'] }}"
                                    class="btn btn-primary dnotesBottomIcon"
                                type="button" 
                                {{-- {{ $note['mark_as_done'] == 1 ? 'checked' : '' }} --}}
                            {{-- /> --}}
                            <i class="fas fa-edit"></i>

                        </div>
                    </li>
                @endforeach
                {{-- <button id="deleteButton{{ $note['id'] }}" onclick="deleteNote('{{ $note['id'] }}')"
                class="btn btn-danger" style="display: none;">Delete</button> --}}
            </ul>
        @endif
    </div>

    <script>
        function moduleSelectedNote(selectedModule,id) {
        // console.log(accessToken,'accessToken')
        var selectedOption = selectedModule.options[selectedModule.selectedIndex];
        var selectedText = selectedOption.text;
        console.log(selectedText,"selectedText");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/task/get-' + selectedText,
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Handle successful response
                var notes = response;
                // Assuming you have another select element with id 'taskSelect'
                var noteSelect = $('#noteSelect'+id);
                // Clear existing options
                noteSelect.empty();
                // Populate select options with tasks
                $.each(notes, function(index, note) {
                    if (selectedText === "Tasks") {
                        noteSelect.append($('<option>', {
                            value: note?.zoho_task_id,
                            text: note?.subject
                        }));
                    }
                    if (selectedText === "Deals") {
                        noteSelect.append($('<option>', {
                            value: note?.zoho_deal_id,
                            text: note?.deal_name
                        }));
                    }
                    if (selectedText === "Contacts") {
                        noteSelect.append($('<option>', {
                            value: note?.zoho_contact_id,
                            text: (note?.first_name??'') + ' ' + (note?.last_name??'')
                        }));
                    }
                });
                noteSelect.show();
                // Do whatever you want with the response data here
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });

    }
    </script>