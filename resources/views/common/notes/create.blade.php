@if (isset($type) && $type == 'Deals')
    <div class="modal fade" id="staticBackdropforNote_{{ $deal['id'] }}" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content noteModal">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Note</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="noteForm_dash{{ $deal['id'] }}" action="{{ route('save.note') }}" method="post" onsubmit="enableSelect({{ $deal['id'] }})">
                    @csrf
                    @method('POST')
                    <div class="modal-body dtaskbody">
                        <p class="ddetailsText">Details</p>
                        <textarea name="note_text" id="note_text{{ $deal['id'] }}" rows="4" class="dtextarea"></textarea>
                        <div id="note_text_error{{ $deal['id'] }}" class="text-danger"></div>
                        <p class="dRelatedText">Related to...</p>
                        <div class="btn-group dmodalTaskDiv">
                            <select class="form-select dmodaltaskSelect"  id="related_to{{ $deal['id'] }}"
                                name="related_to" style="display:none;" aria-label="Select Transaction" >
                                @foreach ($retrieveModuleData as $item)
                                    @if (in_array($item['api_name'], ['Deals']))
                                        <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="form-select dmodaltaskSelect" id="noteSelect{{ $deal['id'] }}"
                                name="related_to_parent" aria-label="Select Transaction" disabled>
                                <option value="{{ $deal['zoho_deal_id'] }}" >{{ $deal['deal_name'] }}</option>
                            </select>
                        </div>
                        <div id="related_to_error{{ $deal['id'] }}" class="text-danger"></div>
                    </div>
                    <div class="modal-footer dNoteFooter border-0">
                        <button type="submit" id="validate-button{{ $deal['id'] }}"
                            onclick="validateNoteDash('{{ $deal['id'] }}')"
                            class="btn btn-secondary dNoteModalmarkBtn">
                            <i class="fas fa-save saveIcon"></i> Add Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@elseif(isset($type) && $type == 'Contacts')
    <div class="modal fade" onclick="event.preventDefault();" id="staticBackdropforNote_{{ $contact['id'] }}"
        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content noteModal">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Note</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="noteForm_dash{{ $contact['id'] }}" action="{{ route('save.note') }}" method="post" onsubmit="enableSelect({{ $contact['id'] }})">
                    @csrf
                    @method('POST')
                    <div class="modal-body dtaskbody">
                        <p class="ddetailsText">Details</p>
                        <textarea name="note_text" id="note_text{{ $contact['id'] }}" rows="4" class="dtextarea"></textarea>
                        <div id="note_text_error{{ $contact['id'] }}" class="text-danger"></div>
                        <p class="dRelatedText">Related to...</p>
                        <div class="btn-group dmodalTaskDiv">
                            <select class="form-select dmodaltaskSelect" style="display:none;" id="related_to{{ $contact['id'] }}"
                                name="related_to" aria-label="Select Transaction">
                                @foreach ($retrieveModuleData as $item)
                                    @if (in_array($item['api_name'], ['Contacts']))
                                        <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="form-select dmodaltaskSelect" id="noteSelect{{ $contact['id'] }}"
                                name="related_to_parent" aria-label="Select Transaction" disabled>
                                <option value="{{ $contact['zoho_contact_id'] }}">{{ $contact['first_name'] }}
                                    {{ $contact['last_name'] }}
                                </option>
                            </select>
                        </div>
                        <div id="related_to_error{{ $contact['id'] }}" class="text-danger"></div>
                    </div>
                    <div class="modal-footer dNoteFooter border-0">
                        <button  type="submit" id="validate-button{{ $contact['id'] }}"
                            onclick="validateNoteDash('{{ $contact['id'] }}')"
                            class="btn btn-secondary dNoteModalmarkBtn">
                            <i class="fas fa-save saveIcon"></i> Add Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
    <div class="modal fade" id="staticBackdropforNote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                            <select class="form-select dmodaltaskSelect" id="related_to_note" name="related_to"
                                aria-label="Select Transaction">
                                <option value="">Please select one</option>
                                @foreach ($retrieveModuleData as $item)
                                    @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                        <option value="{{ $item['zoho_module_id'] }}">{{ $item['api_name'] }}</option>
                                    @endif
                                @endforeach
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
                    <input type="hidden" name="merged_data" id="merged_data">
                </form>
            </div>
        </div>
    </div>
@endif
<script>
    function enableSelect(id) {
        // Enable the select element before form submission
        document.getElementById('noteSelect'+id).removeAttribute('disabled');
        // Return true to allow form submission
        return true;
    }


    function clearValidationMessages(id) {
        if (id) {
            document.getElementById("note_text_error" + id).innerText = "";
            document.getElementById("related_to_error" + id).innerText = "";
        } else {
            document.getElementById("note_text_error").innerText = "";
            document.getElementById("related_to_error").innerText = "";
        }

    }

    function resetFormAndHideSelectDashboard(id) {
        if (id) {
            document.getElementById('noteForm_dash' + id)?.reset();
            document.getElementById('noteSelect' + id).style.display = 'none';
            clearValidationMessages(id);
        } else {
            document.getElementById('noteForm_dash')?.reset();
            clearValidationMessages();
        }

    }

    // validation function onsubmit
    function validateNoteDash(id = null) {
        console.log(window.groupLabel, window.whoid, 'checkouttttttt');
        let noteText, relatedTo, changeButton
        if (id) {
            noteText = document.getElementById("note_text" + id).value;
            relatedTo = document.getElementById("related_to" + id).value;
            changeButton = document.getElementById('validate-button' + id);
            let isValid = true;

            // Reset errors
            document.getElementById("note_text_error" + id).innerText = "";
            document.getElementById("related_to_error" + id).innerText = "";

            /* // Validate note text length
            if (noteText.trim().length > 10) {
                document.getElementById("note_text_error"+id).innerText = "Note text must be 10 characters or less";
                isValid = false;
            } */
            // Validate note text
            if (noteText.trim() === "") {
                document.getElementById("note_text_error" + id).innerText = "Note text is required";
                isValid = false;
            }

            // Validate related to
            if (relatedTo === "") {
                document.getElementById("related_to_error" + id).innerText = "Related to is required";
                document.getElementById("noteSelect" + id).style.display = "none";
                isValid = false;
            }
            if (isValid) {
                changeButton.type = "submit";
                document.getElementById("staticBackdropforNote_" + id).removeAttribute("onclick");
            }
            return isValid;
        } else {
            noteText = document.getElementById("note_text").value;
            relatedTo = document.getElementById("related_to_note").value;
            changeButton = document.getElementById('validate-button');
            let isValid = true;

            // Reset errors
            document.getElementById("note_text_error").innerText = "";
            document.getElementById("related_to_error").innerText = "";
            let mergerdata = document.getElementById('merged_data');
            console.log(mergerdata, 'emrefkdjklfsd');
            // Validate note text
            if (noteText.trim() === "") {
                document.getElementById("note_text_error").innerText = "Note text is required";
                isValid = false;
            } else {
                document.getElementById("note_text_error").innerText = "";
            }

            // Validate related to
            if (relatedTo === "") {
                document.getElementById("related_to_error").innerText = "Related to is required";
                isValid = false;
            } else {
                document.getElementById("related_to_error").innerText = "";
            }
            if (isValid) {
                const mergedData = {
                    groupLabel: window.groupLabel,
                    whoid: window.whoid,
                    relatedTo: window.relatedTo,
                    moduleId: window.moduelID
                };
                // Serialize the array to a JSON string
                const mergedDataJson = JSON.stringify(mergedData);
                mergerdata.value = mergedDataJson;
                changeButton.type = "submit";
            }
            return isValid;
        }


    }
</script>
