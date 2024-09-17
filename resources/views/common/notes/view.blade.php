<div class="col-md-4 
">
<h4 class="text-start dFont600 mb-4">Notes</h4>
    @if ($notesInfo->isEmpty())
        <p class="text-center">No notes found.</p>
    @else
        <ul class="list-group dnotesUl">
            @foreach ($notesInfo as $note)
                <li
                    class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list" data-bs-toggle="modal"
                        data-bs-target="#staticBackdropnoteupdate{{ $note['id'] }}">
                    <div class="text-start"
                        class="form-check-input checkbox{{ $note['id'] }}"
                        id="editButton{{ $note['id'] }}" class="btn btn-primary position-fixed bottom-5 end-0 p-3 bg-dark rounded-circle"
                        type="button" >
                        @if ($note['related_to_type'] === 'Deals'&& $note['dealData'])
                        <div onclick = "event.stopPropagation();">
                            <span class="dFont800 dFont13">Related to:</span>
                             <a href="{{ route('pipeline.view', ['dealId' => $note->dealData->id]) }}">{{ $note->dealData->deal_name ?? '' }}</a>
                        </div>
                            
                        @elseif ($note['related_to_type'] === 'Contacts' && $note['ContactData'])
                        <div onclick = "event.stopPropagation();">
                            <span class="dFont800 dFont13">Related to:</span>
                            <a href="{{ route('contacts.show', ['contactId' => $note->ContactData->id]) }}">{{ $note->contactData->first_name ?? '' }}
                            {{ $note->contactData->last_name ?? '' }}</a>
                        </div>
                            
                        @else
                            <span class="dFont800 dFont13">Related to:</span>
                            Global
                        @endif
                        <p class="dFont400 fs-4 mb-0">
                            {{ $note['note_content'] }}
                        </p>
                        <p class="dFont400 fs-4 mb-1">
                            {{ $note['updated_at'] }}
                        </p>
                    </div>

                    {{-- dynamic edit modal --}}


                    <div class="d-flex align-items-center gx-2"
                        data-bs-toggle="modal"
                        id="editButton{{ $note['id'] }}"
                        data-bs-target="#staticBackdropnoteupdate{{ $note['id'] }}">
                        <i class="fas fa-edit"></i>

                    </div>
                </li>
                {{-- note view modal --}}
                <div class="modal fade p-5" id="staticBackdropnoteview{{ $note['zoho_note_id'] }}" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered deleteModal">
                        <div class="modal-content noteModal">
                            <div class="modal-header border-0">
                                <p class="modal-title dHeaderText">Note</p>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
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

                                    @if($note['related_to_type'] === 'Deals')                                         <select class="form-select dmodaltaskSelect" id="related_to_{{ $note['zoho_note_id'] }}" name="related_to"
                                            aria-label="Select Transaction">
                                            @foreach ($retrieveModuleData as $item)
                                                @if (in_array($item['api_name'], ['Deals']))
                                                    <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <select class="form-select dmodaltaskSelect" id="noteSelect_{{ $note['id'] }}"
                                            name="related_to_parent" aria-label="Select Transaction">
                                            <option value="{{ $note->dealData['zoho_deal_id']??'' }}">{{ $note->dealData['deal_name'] ??''}}
                                            </option>
                                        </select>
                                    @elseif($note['related_to_type'] === 'Contacts')
                                        <select class="form-select dmodaltaskSelect" id="related_to_{{ $note['zoho_note_id'] }}" name="related_to"
                                            aria-label="Select Transaction">
                                            @foreach ($retrieveModuleData as $item)
                                                @if (in_array($item['api_name'], ['Contacts']))
                                                    <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <select class="form-select dmodaltaskSelect" id="noteSelect_{{ $note['id'] }}"
                                            name="related_to_parent" aria-label="Select Transaction">
                                            <option value="{{ $note->contactData['zoho_deal_id'] ?? '' }}">
                                                {{ $note->contactData['first_name'] ?? '' }} {{ $note->contactData['last_name'] ?? '' }}
                                            </option>

                                        </select>
                                    @else
                                        <div class="btn-group dmodalTaskDiv">
                                            <select class="form-select dmodaltaskSelect" id="related_to_{{ $note['zoho_note_id'] }}" name="related_to"
                                                aria-label="Select Transaction">
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
                {{-- note update modal --}}
                <div class="modal fade p-5" id="staticBackdropnoteupdate{{ $note['id'] }}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered deleteModal">
                        <!-- Font Awesome delete icon -->
                        <div class="modal-content noteModal">
                            <div class="modal-header justify-content-between border-0">
                                <p class="modal-title dHeaderText">Note</p>
                                <div>
                                    <i class="fa fa-trash trash-icon"
                                        onclick="openConfirmationModal('{{ $note['zoho_note_id'] }}')"></i>

                                    <button type="button" class="btn-close closeIcon" data-bs-dismiss="modal" aria-label="Close"
                                        onclick="document.getElementById('editButton{{ $note['id'] }}').checked=false;"></button>
                                </div>
                                <!-- Your modal markup (assuming it has an id 'confirmModal') -->
                                @include('common.confirm-modal', ['targetId' => $note['zoho_note_id']])
                            </div>
                            <form action="{{ route('update.note', ['id' => $note['zoho_note_id']]) }}" method="post">
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
                                            <select class="form-select dmodaltaskSelect" id="related_to_{{ $note['id'] }}" name="related_to" 
                                                aria-label="Select Transaction">
                                                <!-- @foreach ($retrieveModuleData as $item)
                                                    @if (in_array($item['api_name'], ['Deals'])) -->
                                                        <option value="{{ $note }}" selected>{{ $note['dealData']['deal_name'] ??'N/A'}}</option>
                                                    <!-- @endif
                                                @endforeach -->
                                            </select>
                                        @elseif($note['related_to_type'] === 'Contacts')
                                            <select class="form-select dmodaltaskSelect" id="related_to_{{ $note['id'] }}" name="related_to"
                                                aria-label="Select Transaction">
                                                <!-- @foreach ($retrieveModuleData as $item)
                                                    @if (in_array($item['api_name'], ['Contacts'])) -->
                                                        <option value="{{ $note }}" {{ isset($note['ContactData']) ? 'selected' : '' }}>
                                                            {{ $note['ContactData']['first_name'] ?? '' }} {{ $note['ContactData']['last_name'] ?? '' }}
                                                        </option>

                                                    <!-- @endif
                                                @endforeach -->
                                            </select>
                                        @else
                                            <div class="btn-group dmodalTaskDiv">
                                                <select class="form-select dmodaltaskSelect" id="related_to_{{ $note['id'] }}"
                                                    name="related_to" aria-label="Select Transaction">
                                                    <option value="">General</option>
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
                                        <button type="submit" class="btn btn-secondary dNoteModalmarkBtns">
                                            <i class="fas fa-save saveIcon"></i> Update Note
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

        </ul>
    @endif
</div>

<script>
 
    // Function to open the confirmation modal
    function openConfirmationModal(id) {
        var modal = document.getElementById(id);
        modal.style.display = 'block';
    }
    // Function to close the confirmation modal
    function closeConfirmationModal(id) {
        var modal = document.getElementById(id);
        modal.style.display = 'none';
    }

    // Function to handle deletion
    function deleteNoteItem(id) {
        closeConfirmationModal(id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        try {
            if (id) {
                $.ajax({
                    url: "{{ route('delete.note', ['id' => ':id']) }}".replace(':id', id),
                    method: 'DELETE', // Change to DELETE method
                    contentType: 'application/json',
                    dataType: 'JSON',
                    data: {
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log(response);
                        // Handle success response
                        if(response.data[0].code === 'SUCCESS'){
                        window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                })

            }
        } catch (err) {
            console.error("error", err);
        }

    }
    
</script>