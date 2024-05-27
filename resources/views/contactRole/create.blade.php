<div class="modal fade" id="contactRoleModal{{ $deal['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Contact Roles Mapping</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contactForm{{ $deal['id'] }}">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Contacts</th>
                                    <th>Account Name</th>
                                    <th>Contact Roles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="contact_{{ $contact['zoho_contact_id'] }}" id="contact_{{ $contact['zoho_contact_id'] }}">
                                        <label for="contact_{{ $contact['zoho_contact_id'] }}">{{ $contact['first_name'] }} {{ $contact['last_name'] }}</label>
                                    </td>
                                    <td>{{ $contact['userData']['name'] }}</td>
                                    <td>
                                        <select name="role_{{ $contact['zoho_contact_id'] }}" class="form-control">
                                            @foreach($contactRoles as $contactRole)
                                            <option value="{{$contactRole['id']}}">{{$contactRole['name']}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary taskModalSaveBtn" onclick="submitContactRoles('{{ $deal['id'] }}')">
                    <i class="fas fa-save saveIcon"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
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

    }
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
