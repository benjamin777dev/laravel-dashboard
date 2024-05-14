@if (isset($type) && $type == 'Deals')
    <div class="modal fade" id="newTaskModalId{{ $deal->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Tasks</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        onclick="resetValidationTask('{{ $deal->id }}')" aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="subject" onkeyup="validateTextareaTask('{{ $deal->id }}');" id="darea{{ $deal['id'] }}"
                        rows="4" class="dtextarea"></textarea>
                    <div id="subject_error{{ $deal['id'] }}" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" name="related_to" aria-label="Select Transaction">
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
    <div class="modal fade" id="newTaskModalId{{ $contact['id'] }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Tasks</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        onclick="resetValidationTask('{{ $contact['id'] }}')" aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="subject" onkeyup="validateTextareaTask();" id="darea{{ $contact['id'] }}" rows="4"
                        class="dtextarea"></textarea>
                    <div id="subject_error{{ $contact['id'] }}" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" name="related_to" aria-label="Select Transaction">
                            <option value="{{ $contact['zoho_contact_id'] }}" selected>
                                {{ $contact['first_name'] }} {{ $contact['last_name'] }}
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
                    <p class="modal-title dHeaderText">Create New Tasks</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidationTask()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="subject" onkeyup="validateTextareaTask()" id="subject" rows="4" class="dtextarea"></textarea>
                    <div id="task_error" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" id="related_to_task"
                            onchange="taskModuleSelected(this)" name="related_to_task_dashboard"
                            aria-label="Select Transaction">
                            <option value="">Please select one</option>
                            @foreach ($retrieveModuleData as $item)
                                @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                    <option value="{{ $item['api_name'] }}">{{ $item['api_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-select dmodaltaskSelect" id="taskSelectForTask"
                            name="related_to_parent_dashboard" aria-label="Select Transaction"
                            style="display: none;">

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
    window.resetValidationTask = function(id) {
        if (id) {
            document.getElementById("subject_error" + id).innerHTML = "";
            document.getElementById('darea' + id).value = "";
        } else {
            document.getElementById("subject_error").innerHTML = "";
            document.getElementById('darea').value = "";
        }

    }

    window.validateTextareaTask = function(id) {
        if (id) {
            var textarea = document.getElementById('darea' + id);
            var textareaValue = textarea.value.trim();
            // Check if textarea value is empty
            if (textareaValue === '') {
                // Show error message or perform validation logic
                document.getElementById("subject_error" + id).innerHTML = "Please enter details";
            } else {
                document.getElementById("subject_error" + id).innerHTML = "";
            }
        } else {
            var textarea = document.getElementById('darea');
            var textareaValue = textarea.value.trim();
            // Check if textarea value is empty
            if (textareaValue === '') {
                // Show error message or perform validation logic
                document.getElementById("subject_error").innerHTML = "Please enter details";
            } else {
                document.getElementById("subject_error").innerHTML = "";
            }
        }

    }

    window.addCommonTask = function(id, type) {
        if (id) {
            var subject = document.getElementsByName("subject")[0].value;
            if (subject.trim() === "") {
                document.getElementById("subject_error" + id).innerHTML = "Please enter details";
                return;
            }
            // var whoSelectoneid = document.getElementsByName("who_id")[0].value;
            // var whoId = window.selectedTransation
            // if (whoId === undefined) {
            //     whoId = whoSelectoneid
            // }
            var dueDate = document.getElementsByName("due_date")[0].value;

            var formData = {
                "data": [{
                    "Subject": subject,
                    // "Who_Id": {
                    //     "id": whoId
                    // },
                    "Status": "Not Started",
                    "Due_Date": dueDate,
                    // "Created_Time":new Date()
                    "Priority": "High",
                    "What_Id": {
                        "id": id
                    },
                    "$se_module": type
                }],
                "_token": '{{ csrf_token() }}'
            };
            console.log("formData", formData);
        } else {
            var subject = document.getElementsByName("subject")[0].value;
            if (subject.trim() === "") {
                document.getElementById("subject_error").innerHTML = "Please enter details";
                return;
            }
            var seModule = document.getElementsByName("related_to_task_dashboard")[0].value;
            var WhatSelectoneid = document.getElementsByName("related_to_parent_dashboard")[0].value;
            console.log("WHAT ID", WhatSelectoneid);
            var dueDate = document.getElementsByName("due_date")[0].value;

            var formData = {
                "data": [{
                    "Subject": subject,
                    // "Who_Id": {
                    //     "id": whoId
                    // },
                    "Status": "Not Started",
                    "Due_Date": dueDate,
                    // "Created_Time":new Date()
                    "Priority": "High",
                    "What_Id": {
                        "id": WhatSelectoneid
                    },
                    "$se_module": seModule
                }],
                "_token": '{{ csrf_token() }}'
            };
            console.log("formData", formData);
        }

        $.ajax({
            url: '{{ route('create.task') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    alert(upperCaseMessage);
                    window.location.reload();
                } else {
                    alert("Response or message not found");
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
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
                taskSelect.select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $(this).parent(),
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
