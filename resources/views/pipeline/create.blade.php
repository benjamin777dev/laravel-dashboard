@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline Create')

@section('content')
    @vite(['resources/css/pipeline.css'])

<script>
    function updateText(newText) {
        //  textElement = document.getElementById('editableText');
        console.log("newText", newText);
        textElement.innerHTML = newText;
    }

    function makeEditable(id) {
        textElement = document.getElementById('editableText' + id);
        textElementCard = document.getElementById('editableTextCard' + id);
        //For Table data                
        var text = textElement.textContent.trim();
        textElement.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

        //For card data
        var text = textElementCard.textContent.trim();
        textElementCard.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

        var inputElement = document.getElementById('editableInput' + id);
        inputElement.focus();
        inputElement.addEventListener('blur', function () {
            updateText(inputElement.value);
        });


    }

    function updateTask(id, indexid) {
        // console.log(id, indexid, 'chekcdhfsjkdh')
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var inputElement = document.getElementById('editableText' + indexid);
        var taskDate = document.getElementById('date_val' + id);
        let formattedDateTime = convertDateTime(taskDate.value);
        console.log(formattedDateTime);
        /*  alert(formattedDateTime);
         return; */
        if (!inputElement) {
            console.error("Input element not found for indexid:", indexid);
            return;
        }
        var elementValue = inputElement.textContent;
        // return;
        if (elementValue.trim() === "") {
            // console.log("chkockdsjkfjksdh")
            return alert("Please enter subject value first");
        }
        // console.log("inputElementval",elementValue!==undefined,elementValue)
        if (elementValue !== undefined) { // return;
            var formData = {
                "data": [{
                    "Subject": elementValue,
                    "Due_Date": formattedDateTime
                }]
            };
            // console.log("ys check ot")
            $.ajax({
                url: "{{ route('update.task', ['id' => ':id']) }}".replace(':id', id),
                method: 'PUT',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function (response) {
                    // Handle success response

                    if (response?.data[0]?.status == "success") {
                        // console.log(response?.data[0], 'sdfjkshdjkfshd')
                        // Get the button element by its ID
                        if (!document.getElementById('saveModalId').classList.contains('show')) {
                            var button = document.getElementById('update_changes');
                            var update_message = document.getElementById('updated_message');
                            // Get the modal target element by its ID
                            var modalTarget = document.getElementById('saveModalId');
                            console.log(modalTarget, 'modalTarget')
                            // Set the data-bs-target attribute of the button to the ID of the modal
                            button.setAttribute('data-bs-target', '#' + modalTarget.id);
                            update_message.textContent = response?.data[0]?.message;
                            // Trigger a click event on the button to open the modal
                            button.click();
                            // alert("updated success", response)
                            // window.location.reload();
                        }
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText, 'errrorroororooro');


                }
            })
        }
    }

    function convertDateTime(dateTimeString) {
        // Assuming dateTimeString is in a format like 'YYYY-MM-DDTHH:MM:SS'
        var date = new Date(dateTimeString);
        // Format the date into a desired format
        var formattedDateTime = date.toLocaleString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Split the dateTimeString into date and time parts
        var parts = formattedDateTime.split(', ');
        var datePart = parts[0]; // "04/19/2024"
        var timePart = parts[1]; // "06:27 AM"

        // Split the date part into month, day, and year
        var dateParts = datePart.split('/');
        var month = dateParts[0]; // months are zero-based (0 - 11)
        var day = dateParts[1];
        var year = dateParts[2];

        // Split the time part into hour, minute, and AM/PM
        var timeParts = timePart.split(' ');
        var time = timeParts[0]; // "06:27"
        var ampm = timeParts[1]; // "AM"

        // Split the time into hour and minute
        var timeComponents = time.split(':');
        var hour = parseInt(timeComponents[0]);
        var minute = timeComponents[1];

        // Adjust hour if it's PM
        if (ampm === 'PM' && hour < 12) {
            hour += 12;
        }
        console.log("month", month.length);
        // Zero-pad month and day if necessary
        if (month.length === 1) {
            month = '0' + month;
        }
        if (day.length === 1) {
            day = '0' + day;
        }

        // Construct the date string in "YYYY-MM-DD" format
        var formattedDate = year + '-' + month + '-' + day;

        return formattedDate;
    }

    function deleteTask(id) {
        let updateids = removeAllSelected();
        if (updateids === "" && id === undefined) {
            return;
        }
        if (updateids !== "") {
            if (confirm("Are you sure you want to delete selected task?")) {

            } else {
                return;
            }
        }
        if (id === undefined) {
            id = updateids;
        }
        //remove duplicate ids
        ids = id.replace(/(\b\w+\b)(?=.*\b\1\b)/g, '').replace(/^,|,$/g, '');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        try {
            if (id) {
                $.ajax({
                    url: "{{ route('delete.task', ['id' => ':id']) }}".replace(':id', ids),
                    method: 'DELETE', // Change to DELETE method
                    contentType: 'application/json',
                    dataType: 'JSON',
                    data: {
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        // Handle success response
                        alert("deleted successfully", response);
                        window.location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        alert(xhr.responseText)
                    }
                })

            }
        } catch (err) {
            console.error("error", err);
        }
    }

    function removeAllSelected() {
        // Select all checkboxes
        var checkboxes = document.querySelectorAll('input[class="task_checkbox"]');
        var ids = ""; // Initialize ids variable to store concatenated IDs
        // Iterate through each checkbox
        checkboxes.forEach(function (checkbox) {
            // console.log(checkboxes,'checkboxes')
            // Check if the checkbox is checked
            if (checkbox.checked) {
                if (checkbox.id !== "light-mode-switch" && checkbox.id !== "dark-rtl-mode-switch" && checkbox
                    .id !== "rtl-mode-switch" && checkbox.id !== "dark-mode-switch" && checkbox.id !==
                    "checkbox_all") {
                    // Concatenate the checkbox ID with a comma
                    ids += checkbox.id + ",";
                    document.getElementById("removeBtn").style.backgroundColor = "rgb(37, 60, 91);"
                    }
                }
            });

            // Remove the trailing comma
            if (ids !== "") {
                ids = ids.replace(/,+(?=,|$)/g, "");
            }

            return ids;
        }
        function toggleAllCheckboxes() {
            // console.log("yes it")
            let state = false;
            let updateColor = document.getElementById("removeBtn");
            var allCheckbox = document.getElementById('checkbox_all');
            var checkboxes = document.querySelectorAll('input[class="task_checkbox"]');

            checkboxes.forEach(function(checkbox) {
                // Set the state of each checkbox based on the state of the "checkbox_all"
                checkbox.checked = allCheckbox.checked;
                if (checkbox.checked) {

                    state = true;

                } else {
                    state = false;
                }
            });
            if (state) {
                updateColor.style.backgroundColor = "rgb(37, 60, 91)";
            } else {

                updateColor.style.backgroundColor = "rgb(192 207 227)";
            }
        }
        function triggerCheckbox(checkboxid) {
            let updateColor = document.getElementById("removeBtn");
            var allCheckbox = document.getElementById('checkbox_all');
            var checkboxes = document.querySelectorAll('input[class="task_checkbox"]');
            var allChecked = true;
            var anyUnchecked = false; // Flag to track if any checkbox is unchecked
            var anyChecked = false;
            checkboxes.forEach(function(checkbox) {
                if (!checkbox.checked) {
                    anyUnchecked = true; // Set flag to true if any checkbox is unchecked
                    // updateColor.style.backgroundColor = "rgb(192 207 227)";
                } else {
                    // updateColor.style.backgroundColor = "rgb(37, 60, 91)";
                    anyChecked = true;
                }
            });

            if (anyChecked) {
                updateColor.style.backgroundColor = "rgb(37, 60, 91)"; // Checked color
            } else {
                updateColor.style.backgroundColor = "rgb(192, 207, 227)"; // Unchecked color
            }
            allCheckbox.checked = !anyUnchecked; // Update "Select All" checkbox based on the flag
        }
        function resetFormAndHideSelect() {
            document.getElementById('noteForm').reset();
            document.getElementById('taskSelect').style.display = 'none';
            clearValidationMessages();
        }
        function clearValidationMessages() {
            document.getElementById("note_text_error").innerText = "";
            document.getElementById("related_to_error").innerText = "";
        }
        function validateForm() {
            let noteText = document.getElementById("note_text").value;
            let relatedTo = document.getElementById("related_to").value;
            let isValid = true;

            // Reset errors
            document.getElementById("note_text_error").innerText = "";
            document.getElementById("related_to_error").innerText = "";

            // Validate note text length
            if (noteText.trim().length > 100) {
                document.getElementById("note_text_error").innerText = "Note text must be 100 characters or less";
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
                document.getElementById("taskSelect").style.display = "none";
                isValid = false;
            }
            if (isValid) {
                let changeButton = document.getElementById('validate-button');
                changeButton.type = "submit";
            }
            return isValid;
        }
        function handleDeleteCheckbox(id) {
            // Get all checkboxes
            const checkboxes = document.querySelectorAll('.checkbox' + id);
            // Get delete button
            const deleteButton = document.getElementById('deleteButton' + id);
            const editButton = document.getElementById('editButton' + id);
            console.log(checkboxes, 'checkboxes')
            // Add event listener to checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Check if any checkbox is checked
                    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                    // Toggle delete button visibility
                    editButton.style.display = anyChecked ? 'block' : 'none';
                    // if (deleteButton.style.display === 'block') {
                    //     selectedNoteIds.push(id)
                    // }
                });
            });

        }
        function moduleSelected(selectedModule, accessToken) {
            // console.log(accessToken,'accessToken')
            var selectedOption = selectedModule.options[selectedModule.selectedIndex];
            var selectedText = selectedOption.text;
            //    var id = '{{ request()->route('id') }}'; 
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/task/get-' + selectedText+'?dealId={{$deal['zoho_deal_id']}}',
                method: "GET",
                dataType: "json",

                success: function(response) {
                    // Handle successful response
                    var tasks = response;
                    // Assuming you have another select element with id 'taskSelect'
                    var taskSelect = $('#taskSelect');
                    // Clear existing options
                    taskSelect.empty();
                    // Populate select options with tasks
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
                                value: task?.contactData?.zoho_contact_id,
                                text: task?.contactData?.first_name + ' ' + task?.contactData?.last_name
                            }));
                        }
                    });
                    taskSelect.show();
                    // Do whatever you want with the response data here
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error("Ajax Error:", error);
                }
            });

        }
    </script>
    <div class="container-fluid">
        <div class="commonFlex ppipeDiv">
            <input type="text" value="{{$deal['deal_name']}}" class="cpinput">
            <div>
                <div class="input-group-text text-white justify-content-center npeditBtn" id="btnGroupAddon"
                    data-bs-toggle="modal"onclick="updateDataDeal('{{$deal['zoho_deal_id']}}')">
                    <img src="{{ URL::asset('/images/edit.svg') }}" alt="Edit">
                    Save
                </div>
            </div>
        </div>
         
        {{-- information form --}}
        <div class="row">
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Client Information</p>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label nplabelText">Client Name</label>
                        <input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                            id="validationDefault01" required value = "{{$deal['client_name_primary']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Representing</label>
                        <select class="form-select npinputinfo" id="validationDefault02" required>
                            <option value="">Select</option>
                            <option value="Buyer" {{$deal['representing'] == 'Buyer' ? 'selected' : ''}}>Buyer</option>
                            <option value="Seller" {{$deal['representing'] == 'Seller' ? 'selected' : ''}}>Seller</option>
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Transaction Name</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Transaction Name"
                            id="validationDefault03" required value = "{{$deal['deal_name']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault04" class="form-label nplabelText">Stage</label>
                        <select class="form-select npinputinfo" id="validationDefault04" required>
                            <option value="">Select</option>
                            @foreach($allStages as $stage)
                                <option value="{{$stage}}" {{$deal['stage'] == $stage ? 'selected' : ''}}>{{$stage}}</option>
                            @endforeach
                        </select>
                    </div>  

                    <div class="col-md-6">
                        <label for="validationDefault05" class="form-label nplabelText">Sale Price</label>
                        <input type="text" class="form-control npinputinfo" placeholder="$ 725,000.00"
                            id="validationDefault05" required value = "{{$deal['sale_price']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault06" class="form-label nplabelText">Closing Date</label>
                        <input type="date" class="form-control npinputinfo" id="validationDefault06" required value = "{{$deal['closing_date']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault07" class="form-label nplabelText">Address</label>
                        <input type="text" class="form-control npinputinfo" placeholder="52 Realand Road"
                            id="validationDefault07" required value = "{{$deal['address']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault08" class="form-label nplabelText">City</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Highlands Ranch"
                            id="validationDefault08" required value = "{{$deal['city']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault09" class="form-label nplabelText">State</label>
                        {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                        <input type="text" class="form-control npinputinfo" placeholder="Highlands Ranch"
                            id="validationDefault09" required value = "{{$deal['state']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault10" class="form-label nplabelText">ZIP</label>
                        <input type="text" class="form-control npinputinfo" placeholder="80129"
                            id="validationDefault10" required value = "{{$deal['zip']}}">
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

                <p class="npinfoText">Earnings Information</p>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault11" class="form-label nplabelText">Commission %</label>
                        <input type="text" class="form-control npinputinfo" id="validationDefault11" required value = "{{$deal['commission']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault12" class="form-label nplabelText">Property Type</label>
                        {{-- <select class="form-select npinputinfo" id="validationDefault12" required >
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                        <input type="text" class="form-control npinputinfo" id="validationDefault12" required value = "{{$deal['property_type']}}">
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault13" class="form-label nplabelText">Ownership Type</label>
                        {{-- <select class="form-select npinputinfo" id="validationDefault13" required >
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                        <input type="text" class="form-control npinputinfo" id="validationDefault13" required value = "{{$deal['ownership_type']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault14" class="form-label nplabelText">Potential GCI</label>
                        <p class="form-control-plaintext npinputinfo" id="validationDefault16">
                            {{ $deal['potential_gci'] }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault15" class="form-label nplabelText">Pipeline Probability (%)</label>
                        <input type="text" class="form-control npinputinfo" placeholder="15" id="validationDefault15"
                            required value = "{{$deal['pipeline_probability']}}">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault16" class="form-label nplabelText">Probable GCI</label>
                        <p class="form-control-plaintext npinputinfo" id="validationDefault16">
                            {{ $deal['pipeline1'] }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" type="checkbox" value = "" id="flexCheckChecked01" <?php if ($deal['personal_transaction'])
    echo 'checked'; ?>>
                        <label class="form-check-label nplabelText" for="flexCheckChecked01">
                            Personal Transaction
                        </label>
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" type="checkbox" value = "" id="flexCheckChecked02" <?php if ($deal['double_ended'])
    echo 'checked'; ?>>
                        <label class="form-check-label nplabelText" for="flexCheckChecked02">
                            Double ended
                        </label>
                    </div>
                </form>
            </div>
        </div>

        {{-- contact roles --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Contact Roles</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    Add Contact Role
                </div>

            </div>

            <div class="row npRoleTable">
                <div class="col-md-3 ">Role</div>
                <div class="col-md-2 ">Role Name</div>
                <div class="col-md-3 ">Phone</div>
                <div class="col-md-4 ">Email</div>
            </div>
            @if ($dealContacts->isEmpty())
                <div>
                    <p class="text-center notesAsignedText">No contacts assigned</p>
                </div>
            @else
                @foreach ($dealContacts as $dealContact)
                    <div class="row npRoleBody">
                        <div class="col-md-3 ">{{$dealContact->contactData ? $dealContact->contactData->first_name . ' ' . $dealContact->contactData->last_name : ($dealContact->userData ? $dealContact->userData->name : 'N/A')}}</div>
                        <div class="col-md-2 ">{{$dealContact['contactRole']}}</div>
                        <div class="col-md-3 ">{{$dealContact->contactData ? ($dealContact->contactData->phone ? $dealContact->contactData->phone : 'N/A') : 'N/A'}}</div>
                        <div class="col-md-4 commonTextEllipsis">{{$dealContact->contactData ? $dealContact->contactData->email : ($dealContact->userData ? $dealContact->userData->email : 'N/A')}}</div>
                    </div>
                @endforeach
            @endif
           
                @foreach ($dealContacts as $dealContact)
                    <div class="npRoleCard vprolecard">
                        <div>
                            <p class="npcommonheaderText">Role</p>
                            <p class="npcommontableBodytext">{{$dealContact->contactData ? $dealContact->contactData->first_name . ' ' . $dealContact->contactData->last_name : ($dealContact->userData ? $dealContact->userData->name : 'N/A')}}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center npCardPhoneDiv">
                            <div>
                                <p class="npcommonheaderText">Role Name</p>
                                <p class="npcommontableBodyDatetext">{{$dealContact['contactRole']}}</p>
                            </div>
                            <div>
                                <p class="npcommonheaderText">Phone</p>
                                <p class="npcommontableBodyDatetext">{{$dealContact->contactData ? ($dealContact->contactData->phone ? $dealContact->contactData->phone : 'N/A') : 'N/A'}}</p>
                            </div>
                        </div>
                        <div>
                            <p class="npcommonheaderText">Email</p>
                            <p class="npcommontableBodyDatetext">{{$dealContact->contactData ? $dealContact->contactData->email : ($dealContact->userData ? $dealContact->userData->email : 'N/A')}}</p>
                        </div>
                    </div>
                @endforeach
           
            <div class="dpagination">
                <div onclick="removeAllSelected()"
                    class="input-group-text text-white justify-content-center removebtn dFont400 dFont13">
                    <i class="fas fa-trash-alt plusicon"></i>
                    Remove Selected
                </div>
                <nav aria-label="..." class="dpaginationNav">
                    <ul class="pagination ppipelinepage d-flex justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>


        </div>

        {{-- Non-TM Check request --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Non-TM Check request</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    Add Non-TM Check request
                </div>

            </div>
            <div class="row npNom-TM-Table">
                <div class="col-md-4 ">Number</div>
                <div class="col-md-4 ">Close Date</div>
                <div class="col-md-4 ">Created Time</div>
            </div>
             @if ($nontms->isEmpty())
                <div>
                    <p class="text-center notesAsignedText">No Non-TM assigned</p>
                </div>
            @else
                @foreach($nontms as $nontm)                 
                    <div class="row npNom-TM-Body">
                            <div class="col-md-4 ">{{$nontm['name']}}</div>
                            <div class="col-md-4 ">{{$nontm['closed_date']}}</div>
                            <div class="col-md-4 commonTextEllipsis">{{$nontm['created_at']}}</div>
                        </div>
                @endforeach
            @endif

             @foreach($nontms as $nontm)                     
                <div class="npNom-TM-Card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="npcommonheaderText">Number</p>
                            <p class="npcommontableBodytext">{{$nontm['name']}}</p>
                        </div>
                        <div>
                            <p class="npcommonheaderText">Close Date</p>
                            <p class="npcommontableBodyDatetext">{{$nontm['closed_date']}}</p>
                        </div>
                    </div>
                    <div class="npCardPhoneDiv">
                        <p class="npcommonheaderText">Created Time</p>
                        <p class="npcommontableBodyDatetext">{{$nontm['created_at']}}</p>
                    </div>
                </div>
            @endforeach
            <div class="dpagination">
                <nav aria-label="..." class="dpaginationNav">
                    <ul class="pagination ppipelinepage d-flex justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>


        </div>

        {{-- Agent’s Commissions --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Agent’s Commissions</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    Add Agent’s Commissions
                </div>

            </div>

            <div class="row npAgentTable">
                <div class="col-md-3 ">Agent’s Name</div>
                <div class="col-md-3 ">IRS 1099 Income for this Transaction</div>
                <div class="col-md-3 ">Less Split to CHR</div>
                <div class="col-md-3 ">Modified Time</div>
            </div>
            @if ($dealaci->isEmpty())
                <div>
                    <p class="text-center notesAsignedText">No ACI assigned</p>

                </div>
            @else
                @foreach ($dealaci as $aci)
                    <div class="row npAgentBody">
                        <div class="col-md-3 ">{{$aci['agentName']}}</div>
                        <div class="col-md-3 ">${{$aci['irs_reported_1099_income_for_this_transaction']}}</div>
                        <div class="col-md-3 ">${{$aci['less_split_to_chr']}}</div>
                        <div class="col-md-3 commonTextEllipsis">{{$aci['closing_date']}}</div>
                    </div>
                @endforeach
            @endif
             
                @foreach ($dealaci as $aci)
                    <div class="npAgentCard">
                        <div>
                            <p class="npcommonheaderText">Agent’s Name</p>
                            <p class="npcommontableBodytext">{{$aci['agentName']}}</p>
                        </div>
                        <div class="npCardPhoneDiv">
                            <p class="npcommonheaderText">IRS 1099 Income for this Transaction</p>
                            <p class="npcommontableBodytext">${{$aci['irs_reported_1099_income_for_this_transaction']}}</p>
                        </div>
                        <div>
                            <p class="npcommonheaderText">Less Split to CHR</p>
                            <p class="npcommontableBodytext">${{$aci['less_split_to_chr']}}</p>
                        </div>
                        <div class="npCardPhoneDiv">
                            <p class="npcommonheaderText">Modified Time</p>
                            <p class="npcommontableBodyDatetext">{{$aci['closing_date']}}</p>
                        </div>
                    </div>
                @endforeach
           
            <div class="dpagination">
                <nav aria-label="..." class="dpaginationNav">
                    <ul class="pagination ppipelinepage d-flex justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>


        </div>

        {{-- Add New Attachment --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Attachments</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    Add New Attachment
                </div>

            </div>

            <div class="row npAttachmentTable">
                <div class="col-md-3">Attachment Name</div>
                <div class="col-md-3 ">Type</div>
                <div class="col-md-3 ">Owner</div>
                <div class="col-md-3 ">Uploaded On</div>
            </div>
            @if ($attachments->isEmpty())
                <div>
                    <p class="text-center notesAsignedText">No Attachments assigned</p>

                </div>
            @else
                @foreach($attachments as $attachment)
                    <div class="row npAttachmentBody">
                        <div class="col-md-3 npcommontableBodytext">{{$attachment['file_name']}}</div>
                        <div class="col-md-3 npcommontableBodytext">PDF</div>
                        <div class="col-md-3 npcommontableBodytext">{{$attachment['userData']['name']}}</div>
                        <div class="col-md-3 commonTextEllipsis npcommontableBodyDatetext">{{$attachment['modified_time']}}</div>
                    </div>
                @endforeach
            @endif                
            @foreach($attachments as $attachment)
                <div class="npContactCard">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="npcommonheaderText">Attachment Name</p>
                        <p class="npcommontableBodytext">{{$attachment['file_name']}}</p>
                        </div>
                        <div>
                            <p class="npcommonheaderText">Type</p>
                            <p class="npcommontableBodytext">PDF</p>
                        </div>
                    </div>
                    <div class="npCardPhoneDiv">
                        <p class="npcommonheaderText">Owner</p>
                        <p class="npcommontableBodytext">{{$attachment['userData']['name']}}</p>
                    </div>
                    <div>
                        <p class="npcommonheaderText">Uploaded On</p>
                        <p class="npcommontableBodyDatetext">{{$attachment['modified_time']}}</p>
                    </div>
                </div>
            @endforeach
            <div class="dpagination">

                <nav aria-label="..." class="dpaginationNav">
                    <ul class="pagination ppipelinepage d-flex justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        {{-- Add New Submittal --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Submittals</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    Add New Submittal
                </div>

            </div>
            <div class="row npNom-TM-Table">
                <div class="col-md-4 ">Submittal Name</div>
                <div class="col-md-4 ">Owner</div>
                <div class="col-md-4 ">Created Time</div>
            </div>
            @if ($submittals->isEmpty())
                <div>
                    <p class="text-center notesAsignedText">No Submittal assigned</p>

                </div>
            @else
                    @foreach($submittals as $submittal)
                        <div class="row npNom-TM-Body">
                                <div class="col-md-4 ">{{$submittal['name']}}</div>
                                <div class="col-md-4 ">{{$submittal['userData']['name']}}</div>
                                <div class="col-md-4 commonTextEllipsis">{{$submittal['created_at']}}</div>
                            </div>
                    @endforeach               
            @endif              
             @foreach($submittals as $submittal)
                <div class="npNom-TM-Card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="npcommonheaderText">Submittal Name</p>
                            <p class="npcommontableBodytext">{{$submittal['name']}}</p>
                        </div>
                        <div>
                            <p class="npcommonheaderText">Owner</p>
                            <p class="npcommontableBodyDatetext">{{$submittal['closed_date']}}</p>
                        </div>
                    </div>
                    <div class="npCardPhoneDiv">
                        <p class="npcommonheaderText">Created Time</p>
                        <p class="npcommontableBodyDatetext">{{$submittal['created_at']}}</p>
                    </div>
                </div>
            @endforeach
            <div class="dpagination">
                <nav aria-label="..." class="dpaginationNav">
                    <ul class="pagination ppipelinepage d-flex justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>


        </div>
</div>
<div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropforNote_{{$deal['id']}}">
    <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
</div>
{{-- Create New Task Modal --}}
@include('common.tasks.create', ['deal' => $deal])
{{-- Notes Model --}}
@include('common.notes.create', ['deal' => $deal])
    
    @vite(['resources/js/pipeline.js'])

    <script>
         document.addEventListener('DOMContentLoaded', function () {
                var defaultTab = "{{ $tab }}";
                console.log(defaultTab, 'tab is here')
                localStorage.setItem('status', defaultTab);
                // Retrieve the status from local storage
                var status = localStorage.getItem('status');

                // Object to store status information
                var statusInfo = {
                    'In Progress': false,
                    'Overdue': false,
                    'Not Started': false,
                };

                // Update the status information based on the current status
                statusInfo[status] = true;

                // Loop through statusInfo to set other statuses to false
                for (var key in statusInfo) {
                    if (key !== status) {
                        statusInfo[key] = false;
                    }
                }

                // Example of accessing status information
                console.log(statusInfo);

                // Remove active class from all tabs
                var tabs = document.querySelectorAll('.nav-link');
                console.log(tabs, 'tabssss')
                tabs.forEach(function (tab) {
                    tab.classList.remove('active');
                });

                // Set active class to the tab corresponding to the status
                console.log(status, 'status');
                var activeTab = document.querySelector('.nav-link[data-tab="' + status + '"]');
                if (activeTab) {
                    activeTab.classList.add('active');
                    activeTab.style.backgroundColor = "#253C5B"
                    activeTab.style.color = "#fff";
                    activeTab.style.borderRadius = "4px";
                }




            });
            // Function to populate client information
            window.addTask= function(deal) {
                var subject = document.getElementsByName("subject")[0].value;
                if (subject.trim() === "") {
                    document.getElementById("subject_error").innerHTML = "please enter details";
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
                        "Status": "Upcoming",
                        "Due_Date": dueDate,
                        // "Created_Time":new Date()
                        "Priority": "High",
                        "What_Id":{
                            "id":deal
                        },
                        "$se_module":"Deals"
                    }],
                    "_token": '{{ csrf_token() }}'
                };
                console.log("formData",formData);
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

            {{-- window.updateNote= function(noteId) {
                var subject = document.getElementsByName("subject")[0].value;
                if (subject.trim() === "") {
                    document.getElementById("subject_error").innerHTML = "please enter details";
                }
                var whoSelectoneid = document.getElementsByName("who_id")[0].value;
                var whoId = window.selectedTransation
                if (whoId === undefined) {
                    whoId = whoSelectoneid
                }
                var dueDate = document.getElementsByName("due_date")[0].value;
                
                var formData = {
                    "data": [{
                        "Subject": subject,
                        "Who_Id": {
                            "id": whoId
                        },
                        "Status": "In Progress",
                        "Due_Date": dueDate,
                        // "Created_Time":new Date()
                        "Priority": "High",
                        "What_Id":{
                            "id":deal
                        },
                        "$se_module":"Deals"
                    }],
                    "_token": '{{ csrf_token() }}'
                };
                console.log("formData",formData);
                $.ajax({
                    url: '{{ route('update.note') }}',
                    type: 'PUT',
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
            } --}}

           window.updateDataDeal = function(dealId) {
                console.log(dealId);
                // Retrieve values from form fields
                var client_name_primary = $('#validationDefault01').val();
                var representing = $('#validationDefault02').val();
                var deal_name = $('#validationDefault03').val();
                var stage = $('#validationDefault04').val();
                var sale_price = $('#validationDefault05').val();
                var closing_date = $('#validationDefault06').val();
                var address = $('#validationDefault07').val();
                var city = $('#validationDefault08').val();
                var state = $('#validationDefault09').val();
                var zip = $('#validationDefault10').val();
                var commission = $('#validationDefault11').val();
                var property_type = $('#validationDefault12').val();
                var ownership_type = $('#validationDefault13').val();
                var potential_gci = $('#validationDefault14').val();
                var pipeline_probability = $('#validationDefault15').val();
                var probable_gci = $('#validationDefault16').val();
                var personal_transaction = $('#flexCheckChecked01').prop('checked');
                var double_ended = $('#flexCheckChecked02').prop('checked');

                // Create formData object
                var formData = {
                    "data": [{
                        "Client_Name_Primary": client_name_primary,
                        "Representing": representing,
                        "Deal_Name": deal_name,
                        "Stage": stage,
                        "Sale_Price": sale_price,
                        "Closing_Date": closing_date,
                        "Address": address,
                        "City": city,
                        "State": state,
                        "Zip": zip,
                        "Commission": commission,
                        "Property_Type": property_type,
                        "Ownership_Type": ownership_type,
                        "Potential_GCI": potential_gci,
                        "Pipeline_Probability": pipeline_probability,
                        "Pipeline1": probable_gci,
                        "Personal_Transaction": personal_transaction,
                        "Double_Ended": double_ended
                    }],
                    "_token": '{{ csrf_token() }}'
                };
                console.log("formData", formData, dealId);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('pipeline.update', ['dealId' => ':id']) }}".replace(':id', dealId),
                    type: 'PUT',
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
                            alert("Deal Updated Successfully");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                })
            }


        
        


    </script>
@section('pipelineScript')

@endsection
@endsection
