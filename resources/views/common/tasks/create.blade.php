@if (isset($type) && $type == 'Deals')
    <div class="modal fade" id="newTaskModalId{{ $deal->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Task</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        onclick="resetValidationTask('{{ $deal->id }}')" aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Subject</p>
                    <input name="subject" onkeyup="validateTextareaTask('{{ $deal->id }}');" id="sarea{{ $deal['id'] }}"
                        rows="4" class="dtextarea"></input>
                        <div id="subject_error{{ $deal['id'] }}" class="text-danger"></div>
                    <p class="ddetailsText">Details</p>
                    <textarea name="detail" onkeyup="validateTextareaTask('{{ $deal->id }}');" id="darea{{ $deal['id'] }}"
                        rows="4" class="dtextarea"></textarea>
                    <div id="detail_error{{ $deal['id'] }}" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" id="related_to" name="related_to" aria-label="Select Transaction">
                            <option value="{{ $deal['zoho_deal_id'] }}" selected>
                                {{ $deal['deal_name'] }}
                            </option>
                        </select>
                    </div>
                    <p class="dDueText">Date due</p>
                    <input type="date" name="due_date" class="dmodalInput" />
                </div>
                <div class="modal-footer ">
                    <button type="button" onclick="addCommonTask('{{ $deal['zoho_deal_id'] }}','Deals')"
                        class="btn btn-secondary taskModalSaveBtn">
                        <i class="fas fa-save saveIcon"></i> Save Changes
                    </button>

                </div>

            </div>
        </div>
    </div>
@elseif(isset($type) && $type == 'Contacts')
<div class="modal fade" id="newTaskModalId{{ $contact->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content dtaskmodalContent">
            <div class="modal-header border-0">
                <p class="modal-title dHeaderText">Create New Task</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    onclick="resetValidationTask('{{ $contact->id }}')" aria-label="Close"></button>
            </div>
            <div class="modal-body dtaskbody">
                <p class="ddetailsText">Subject</p>
                <input name="subject" onkeyup="validateTextareaTask('{{ $contact->id }}');" id="sarea{{ $contact['id'] }}"
                    rows="4" class="dtextarea"></input>
                    <div id="subject_error{{ $contact['id'] }}" class="text-danger"></div>
                <p class="ddetailsText">Details</p>
                <textarea name="detail" onkeyup="validateTextareaTask('{{ $contact->id }}');" id="darea{{ $contact['id'] }}"
                    rows="4" class="dtextarea"></textarea>
                <div id="detail_error{{ $contact['id'] }}" class="text-danger"></div>
                <p class="dRelatedText">Related to...</p>
                <div class="btn-group dmodalTaskDiv">
                    <select class="form-select dmodaltaskSelect" id="related_to" name="related_to" aria-label="Select Transaction">
                        <option value="{{ $contact['zoho_contact_id'] }}" selected>
                            {{ $contact['last_name'] }}
                        </option>
                    </select>
                </div>
                <p class="dDueText">Date due</p>
                <input type="date" name="due_date" class="dmodalInput" />
            </div>
            <div class="modal-footer ">
                <button type="button" onclick="addCommonTask('{{ $contact['zoho_contact_id'] }}','Contacts')"
                    class="btn btn-secondary taskModalSaveBtn">
                    <i class="fas fa-save saveIcon"></i> Save Changes
                </button>

            </div>

        </div>
    </div>
</div>
@else
    <div class="modal fade" id="staticBackdropforTask" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Task</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidationTask()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Subject</p>
                    <input name="subject" onkeyup="validateTextareaTask()" id="sarea" rows="4" class="dtextarea"></input>
                    <div id="subject_error" class="text-danger"></div>
                    <p class="ddetailsText">Details</p>
                    <textarea name="detail" onkeyup="validateTextareaTask()" id="darea" rows="4" class="dtextarea"></textarea>
                    <div id="detail_error" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" id="related_to_rem_create"
                            name="related_to_task_dashboard" aria-label="Select Transaction">
                        </select>
                    </div>
                    <p class="dDueText">Date due</p>
                    <input type="date" name="due_date" class="dmodalInput" />
                </div>
                <div class="modal-footer ">
                    <button type="button" onclick="addCommonTask()" class="btn btn-secondary taskModalSaveBtn">
                        <i class="fas fa-save saveIcon"></i> Save Changes
                    </button>

                </div>

            </div>
        </div>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalSelectMap = [{
                modalID: 'staticBackdropforTask',
                selectElementId: 'related_to_rem_create'
            },
            {
                modalID: 'staticBackdropforNote',
                selectElementId: 'related_to_note'
            }
        ];
     
        modalSelectMap.forEach(({
            modalID,
            selectElementId
        }) => {
            const selectElement = $(`#${selectElementId}`);
            showDropdown(modalID, selectElement);
        });

    });
    window.resetValidationTask = function(id) {
        if (id) {
            document.getElementById("subject_error" + id).innerHTML = "";
            document.getElementById('sarea' + id).value = "";
            document.getElementById("detail_error" + id).innerHTML = "";
            document.getElementById('darea' + id).value = "";
        } else {
            document.getElementById("subject_error").innerHTML = "";
            document.getElementById('sarea').value = "";
             document.getElementById("detail_error").innerHTML = "";
            document.getElementById('darea').value = "";
        }

    }

    window.validateTextareaTask = function(id) {
        if (id) {
            var textarea = document.getElementById('darea' + id);
            var subjectarea = document.getElementById('sarea' + id);
            
            var textareaValue = textarea.value.trim();
             var subjectareaValue = subjectarea.value.trim();
            // Check if textarea value is empty
            if (textareaValue === '') {
                // Show error message or perform validation logic
                document.getElementById("detail_error" + id).innerHTML = "Please enter details";
            } else {
                document.getElementById("detail_error" + id).innerHTML = "";
            }

            if (subjectareaValue === '') {
                // Show error message or perform validation logic
                document.getElementById("subject_error" + id).innerHTML = "Please enter subject";
            } else {
                document.getElementById("subject_error" + id).innerHTML = "";
            }
        } else {
            var textarea = document.getElementById('darea');
            var subjectarea = document.getElementById('sarea');
            var textareaValue = textarea.value.trim();
            var subjectareaValue = subjectarea.value.trim();

            // Check if textarea value is empty
            if (textareaValue === '') {
                // Show error message or perform validation logic
                document.getElementById("detail_error").innerHTML = "Please enter details";
            } else {
                document.getElementById("detail_error").innerHTML = "";
            }

            if (subjectareaValue === '') {
                // Show error message or perform validation logic
                document.getElementById("subject_error").innerHTML = "Please enter subject";
            } else {
                document.getElementById("subject_error").innerHTML = "";
            }
        }

    }
    
    window.taskModuleSelected = function(selectedModule) {
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
                var tasks = response;
                // Assuming you have another select element with id 'taskSelect'
                var taskSelect = $('#taskSelectForTask');
                // Clear existing options
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
                            value: task?.zoho_contact_id,
                            text: (task?.first_name ?? '') + ' ' + (task
                                ?.last_name ??
                                '')
                        }));
                    }
                });
                taskSelect.show();
                taskSelect.each(function() {
                    $(this).select2({
                        theme: 'bootstrap-5',
                        dropdownParent: $(this).parent(),
                    });
                });
                taskSelect.next(".select2-container").addClass("form-select");
                // Do whatever you want with the response data here
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });

    }
</script>
