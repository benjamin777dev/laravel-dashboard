@if (isset($type) && $type == 'Deals')
    <div class="modal fade p-5" id="newTaskModalId{{ $deal->id }}" tabindex="-1">
        <div class="modal-dialog d-flex justify-content-center align-items-center vh-100 deleteModal">
            <div class="modal-content p-1">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Task</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="closing_btnnnnn"
                        onclick="resetValidationTask('{{ $deal->zoho_deal_id }}')" aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Subject</p>
                    <input name="subject" onkeyup="validateTextareaTask('{{ $deal->zoho_deal_id }}');" id="sarea{{ $deal['zoho_deal_id'] }}"
                        rows="4" class="dtextarea"></input>
                        <div id="subject_error{{ $deal['zoho_deal_id'] }}" class="text-danger"></div>
                    <p class="ddetailsText">Details</p>
                    <textarea name="detail" id="darea{{ $deal['zoho_deal_id'] }}"
                        rows="4" class="dtextarea"></textarea>
                    <div id="detail_error{{ $deal['zoho_deal_id'] }}" class="text-danger"></div>
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
<div class="modal fade p-5" id="newTaskModalId{{ $contact->id }}" tabindex="-1">
    <div class="modal-dialog d-flex justify-content-center align-items-center vh-100 deleteModal">
        <div class="modal-content p-1">
            <div class="modal-header border-0">
                <p class="modal-title dHeaderText">Create New Task</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    onclick="resetValidationTask('{{ $contact->zoho_contact_id }}')" id="closing_btnnnnn" aria-label="Close"></button>
            </div>
            <div class="modal-body dtaskbody">
                <p class="ddetailsText">Subject</p>
                <input name="subject" onkeyup="validateTextareaTask('{{ $contact->zoho_contact_id }}');" id="sarea{{ $contact['zoho_contact_id'] }}"
                    rows="4" class="dtextarea"></input>
                    <div id="subject_error{{ $contact['zoho_contact_id'] }}" class="text-danger"></div>
                <p class="ddetailsText">Details</p>
                <textarea name="detail" id="darea{{ $contact['zoho_contact_id'] }}"
                    rows="4" class="dtextarea"></textarea>
                <div id="detail_error{{ $contact['zoho_contact_id'] }}" class="text-danger"></div>
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
    <div class="modal fade p-5" id="staticBackdropforTask" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog d-flex justify-content-center align-items-center vh-100 deleteModal">
            <div class="modal-content p-1">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Task</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeModal" onclick="resetValidationTask()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Subject</p>
                    <input name="subject" onkeyup="validateTextareaTask()" id="sarea" rows="4" class="dtextarea"></input>
                    <div id="subject_error" class="text-danger"></div>
                    <p class="ddetailsText">Details</p>
                    <textarea name="detail"id="darea" rows="4" class="dtextarea"></textarea>
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
            
        } else {
            document.getElementById("subject_error").innerHTML = "";
            document.getElementById('sarea').value = "";
            
        }

    }
    window.validateTextareaTask = function(id) {
        if (id) {
            var subjectarea = document.getElementById('sarea' + id);
            
             var subjectareaValue = subjectarea.value.trim();
            // Check if textarea value is empty
            if (subjectareaValue === '') {
                // Show error message or perform validation logic
                document.getElementById("subject_error" + id).innerHTML = "Please enter subject";
            } else {
                document.getElementById("subject_error" + id).innerHTML = "";
            }
        } else {
            var subjectarea = document.getElementById('sarea');
            var subjectareaValue = subjectarea.value.trim();

            // Check if textarea value is empty
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
