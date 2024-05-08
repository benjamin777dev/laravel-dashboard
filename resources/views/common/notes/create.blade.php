@if(isset($deal))
<div class="modal fade" id="staticBackdropforNote_{{$deal['id']}}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content noteModal">
            <div class="modal-header border-0">
                <p class="modal-title dHeaderText">Note</p>
                <button type="button" onclick="resetFormAndHideSelectDashboard('{{$deal['id']}}');" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="noteForm_dash{{$deal['id']}}" action="{{ route('save.note') }}" method="post">
                @csrf
                @method('POST')
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="note_text" id="note_text{{$deal['id']}}" rows="4" class="dtextarea"></textarea>
                    <div id="note_text_error{{$deal['id']}}" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" id="related_to{{ $deal['id'] }}"
                                name="related_to" aria-label="Select Transaction">
                            @foreach ($retrieveModuleData as $item)
                                @if (in_array($item['api_name'], ['Deals']))
                                    <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-select dmodaltaskSelect" id="noteSelect{{ $deal['id'] }}"
                                name="related_to_parent" aria-label="Select Transaction">
                            <option value="{{ $deal['zoho_deal_id'] }}">{{ $deal['deal_name'] }}</option>
                        </select>
                    </div>
                    <div id="related_to_error{{ $deal['id'] }}" class="text-danger"></div>
                </div>
                <div class="modal-footer dNoteFooter border-0">
                    <button type="button" id="validate-button{{ $deal['id'] }}" onclick="validateNoteDash('{{ $deal['id'] }}')"
                        class="btn btn-secondary dNoteModalmarkBtn">
                        <i class="fas fa-save saveIcon"></i> Add Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@elseif(isset($contact))

<div class="modal fade" onclick="event.preventDefault();" id="staticBackdropforNote_{{$contact['id']}}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content noteModal">
            <div class="modal-header border-0">
                <p class="modal-title dHeaderText">Note</p>
                <button type="button" onclick="resetFormAndHideSelectDashboard('{{$contact['id']}}');" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="noteForm_dash{{$contact['id']}}" action="{{ route('save.note') }}" method="post">
                @csrf
                @method('POST')
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="note_text" id="note_text{{$contact['id']}}" rows="4" class="dtextarea"></textarea>
                    <div id="note_text_error{{$contact['id']}}" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" id="related_to{{$contact['id']}}"
                                name="related_to" aria-label="Select Transaction">
                            @foreach ($retrieveModuleData as $item)
                                @if (in_array($item['api_name'], ['Contacts']))
                                    <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-select dmodaltaskSelect" id="noteSelect{{ $contact['id'] }}"
                                name="related_to_parent" aria-label="Select Transaction">
                            <option value="{{ $contact['zoho_contact_id'] }}">{{ $contact['first_name'] }} {{ $contact['last_name'] }}</option>
                        </select>
                    </div>
                    <div id="related_to_error{{ $contact['id'] }}" class="text-danger"></div>
                </div>
                <div class="modal-footer dNoteFooter border-0">
                    <button type="button" id="validate-button{{$contact['id']}}" onclick="validateNoteDash('{{ $contact['id'] }}')"
                        class="btn btn-secondary dNoteModalmarkBtn">
                        <i class="fas fa-save saveIcon"></i> Add Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
<div class="modal fade" id="staticBackdropforNote" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content noteModal">
            <div class="modal-header border-0">
                <p class="modal-title dHeaderText">Note</p>
                <button type="button" onclick="resetFormAndHideSelectDashboard();" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="noteForm_dash" action="{{ route('save.note') }}" method="post">
                @csrf
                @method('POST')
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="note_text" id="note_text" rows="4" class="dtextarea"></textarea>
                    <div id="note_text_error" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" id="related_to" onchange="moduleSelectedNote(this)"
                            name="related_to" aria-label="Select Transaction">
                            <option value="">Please select one</option>
                            @foreach ($retrieveModuleData as $item)
                                @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                    <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-select dmodaltaskSelect" id="noteSelect" name="related_to_parent"
                            aria-label="Select Transaction" style="display: none;">
                            <option value="">Please Select one</option>
                        </select>
                    </div>
                    <div id="related_to_error" class="text-danger"></div>
                </div>
                <div class="modal-footer dNoteFooter border-0">
                    <button type="button" id="validate-button" onclick="validateNoteDash('')"
                        class="btn btn-secondary dNoteModalmarkBtn">
                        <i class="fas fa-save saveIcon"></i> Add Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    function clearValidationMessages(id) {
         if(id){
            document.getElementById("note_text_error"+id).innerText = "";
            document.getElementById("related_to_error"+id).innerText = "";
        }else{
            document.getElementById("note_text_error").innerText = "";
            document.getElementById("related_to_error").innerText = "";
        }
        
    }
    function resetFormAndHideSelectDashboard(id) {
        if(id){
            document.getElementById('noteForm_dash'+id)?.reset();
            document.getElementById('noteSelect'+id).style.display = 'none';
            clearValidationMessages(id);
        }else{
            document.getElementById('noteForm_dash')?.reset();
            document.getElementById('noteSelect').style.display = 'none';
            clearValidationMessages();
        }
       
    }
    function moduleSelectedNote(selectedModule,id=null) {
        // console.log(accessToken,'accessToken')
        var selectedOption = selectedModule.options[selectedModule.selectedIndex];
        var selectedText = selectedOption.text;
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
                // Assuming you have another select element with id 'noteSelect'
                let noteSelect
                if(id){
                    noteSelect = $('#noteSelect'+id);
                }else{
                    noteSelect = $('#noteSelect');
                }
                console.log(noteSelect,"noteSelect");

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

    // validation function onsubmit
    function validateNoteDash(noteId=null) {
        console.log("sdfsdfsdfsdfdsfdsfdsfsdfsdfsdfsdf",id);
        let noteText,relatedTo,changeButton
         if(id){
            noteText = document.getElementById("note_text"+id).value;
            relatedTo = document.getElementById("related_to"+id).value;
            changeButton = document.getElementById('validate-button'+id);
            let isValid = true;

            // Reset errors
            document.getElementById("note_text_error"+id).innerText = "";
            document.getElementById("related_to_error"+id).innerText = "";

            // Validate note text length
            if (noteText.trim().length > 10) {
                document.getElementById("note_text_error"+id).innerText = "Note text must be 10 characters or less";
                isValid = false;
            }
            // Validate note text
            if (noteText.trim() === "") {
                document.getElementById("note_text_error"+id).innerText = "Note text is required";
                isValid = false;
            }

            // Validate related to
            if (relatedTo === "") {
                document.getElementById("related_to_error"+id).innerText = "Related to is required";
                document.getElementById("noteSelect"+id).style.display = "none";
                isValid = false;
            }
            if (isValid) {
                changeButton.type = "submit";
                document.getElementById("staticBackdropforNote_" + id).removeAttribute("onclick");
            }
            return isValid;
        }else{
            noteText = document.getElementById("note_text").value;
            relatedTo = document.getElementById("related_to").value;
            changeButton = document.getElementById('validate-button');
            let isValid = true;

            // Reset errors
            document.getElementById("note_text_error").innerText = "";
            document.getElementById("related_to_error").innerText = "";

            // Validate note text length
            if (noteText.trim().length > 10) {
                document.getElementById("note_text_error").innerText = "Note text must be 10 characters or less";
                isValid = false;
            }
            // Validate note text
            if (noteText.trim() === "") {
                document.getElementById("note_text_error").innerText = "Note text is required";
                isValid = false;
            }

            // Validate related to
            if (relatedTo === "") {
                document.getElementById("related_to_error").innerText = "Related to is required";
                document.getElementById("noteSelect").style.display = "none";
                isValid = false;
            }
            if (isValid) {
                changeButton.type = "submit";
            }
            return isValid;
            }
        
        
    }
</script>