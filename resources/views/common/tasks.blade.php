<div class="table-responsive dresponsivetable">
    <table class="table dtableresp">
        <thead>
            <tr class="dFont700 dFont10 dtableHeaderTr">
                <th scope="col"><input type="checkbox" onclick="toggleAllCheckboxes()" id="checkbox_all"
                        id="checkbox_task" /></th>
                <th scope="col">Subject</th>
                <th scope="col">Transaction Related</th>
                <th scope="col">Task Date</th>
                <th scope="col">Options</th>
            </tr>
        </thead>
        <tbody>

            @if (count($tasks) > 0)
                @foreach ($tasks as $task)
                    <tr class="dresponsivetableTr">
                        <td><input onchange="triggerCheckbox('{{ $task['zoho_task_id'] }}')" type="checkbox"
                                class="task_checkbox" id="{{ $task['zoho_task_id'] }}" /></td>
                        <td>
                            <p class="dFont900 dFont14 d-flex justify-content-between dMt16 dSubjectText"
                                id="editableText{{ $task['id'] }}">
                                {{ $task['subject'] ?? 'N/A' }}
                                <i class="fas fa-pencil-alt pencilIcon"
                                    onclick="makeEditable('{{ $task['id'] }}','subject','{{ $task['zoho_task_id'] }}','editableText{{ $task['id'] }}')"></i>
                            </p>
                        </td>
                        <td>
                            <div class="btn-group btnTaskSelects">
                                <select class="form-select dealTaskSelect" id="related_to_rem{{ $task['id'] }}"
                                    onclick="getModule('{{ $task['id'] }}','related_to_rem{{ $task['id'] }}')"
                                    name="related_to_rem{{ $task['id'] }}">
                                    @if ($task['related_to'] == 'Contacts')
                                        <option value="" {{ empty($task['contactData']) ? 'selected' : '' }}>
                                            {{ $task['contactData']['first_name'] ?? '' }}
                                            {{ $task['contactData']['last_name'] ?? 'Please select' }}
                                        </option>
                                    @elseif ($task['related_to'] == 'Deals')
                                        <option value="" {{ empty($task['dealData']) ? 'selected' : '' }}>
                                            {{ $task['dealData']['deal_name'] ?? 'Please select' }}
                                        </option>
                                    @else
                                        <option value="" selected>Please select</option>
                                    @endif
                                </select>
                                <select class="form-select dmodaltaskSelect" id="taskSelect{{ $task['id'] }}"
                                    onchange="testFun('{{ $task['id'] }}','deals','{{ $task['zoho_task_id'] }}')"
                                    name="related_to_parent{{ $task['id'] }}" aria-label="Select Transaction"
                                    style="display: none;">
                                    <option value="">Please Select</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <input type="datetime-local" id="date_local_web{{ $task['id'] }}"
                                onchange="makeEditable('{{ $task['id'] }}','date','{{ $task['zoho_task_id'] }}','date_local_web{{ $task['id'] }}')"
                                id="date_local{{ $task['zoho_task_id'] }}"
                                value="{{ \Carbon\Carbon::parse($task['due_date'])->format('Y-m-d\TH:i') }}" />
                        </td>
                        <td>
                            <div class="d-flex ">
                                <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                    id="btnGroupAddon" data-bs-toggle="modal"
                                    onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')">
                                    <i class="fas fa-hdd plusicon"></i>
                                    Save
                                </div>
                                <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                                    id="btnGroupAddon" data-bs-toggle="modal"
                                    data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
                                    <i class="fas fa-trash-alt plusicon"></i>
                                    Delete
                                </div>
                            </div>
                            {{-- delete Modal --}}
                            <div class="modal fade" id="deleteModalId{{$task['zoho_task_id']}}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 deleteModalHeaderDiv">
                                            {{-- <h5 class="modal-title">Modal title</h5> 
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body deletemodalBodyDiv">
                                            <p class="deleteModalBodyText">Please confirm you’d
                                                like
                                                to<br />
                                                delete this item.</p>
                                        </div>
                                        <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                            <div class="d-grid gap-2 col-5">
                                                <button type="button" onclick="deleteTask('{{ $task['zoho_task_id'] }}')"
                                                    class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                                                    <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                                    delete
                                                </button>
                                            </div>
                                            <div class="d-grid gap-2 col-5">
                                                <button type="button" data-bs-dismiss="modal"
                                                    class="btn btn-primary goBackModalBtn">
                                                    <img src="{{ URL::asset('/images/reply.svg') }}" data-bs-dismiss="modal"
                                                        alt="R">No,
                                                    go
                                                    back
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="saveModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content">
                                        <div class="modal-header saveModalHeaderDiv border-0">
                                            {{-- <h5 class="modal-title">Modal title</h5> --}}
                                            {{-- <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body saveModalBodyDiv">
                                            <p class="saveModalBodyText" id="updated_message">Changes
                                                have been saved</p>
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
                            </div> --}}
                            <div class="modal fade" id="savemakeModalId{{ $task['id'] }}" tabindex="-1">
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
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="12">No records found</td>
                </tr>
            @endif

        </tbody>

    </table>
    <div class="dprogressCards">
        @if (count($tasks) > 0)
            @foreach ($tasks as $task)
                <div class="dcardscheckbox">
                    <input type="checkbox" />
                </div>
                <div class="dcardssubjectdiv">
                    <p class="dcardSubject" id="editableTextCard{{ $task['id'] }}"
                        onclick="makeEditable('{{ $task['id'] }}','subject','{{ $task['zoho_task_id'] }}','editableTextCard{{ $task['id'] }}')">
                        {{ $task['subject'] ?? 'N/A' }}
                       
                    </p>
                    <div class="btn-group dcardsselectdiv">
                        <p class="dcardsTransactionText">Transaction Related</p>
                        <select class="form-select" id="related_to_rem_card{{ $task['id'] }}"
                            onclick="getModule('{{ $task['id'] }}','related_to_rem_card{{ $task['id'] }}')"
                            name="related_to_rem{{ $task['id'] }}">
                            @if ($task['related_to'] == 'Contacts')
                                <option value="" {{ empty($task['contactData']) ? 'selected' : '' }}>
                                    {{ $task['contactData']['first_name'] ?? '' }}
                                    {{ $task['contactData']['last_name'] ?? 'Please select' }}
                                </option>
                            @elseif ($task['related_to'] == 'Deals')
                                <option value="" {{ empty($task['dealData']) ? 'selected' : '' }}>
                                    {{ $task['dealData']['deal_name'] ?? 'Please select' }}
                                </option>
                            @else
                                <option value="" selected>Please select</option>
                            @endif
                        </select>
                        <select class="form-select dmodaltaskSelect" id="taskSelectcard{{ $task['id'] }}"
                            onchange="testFun('{{ $task['id'] }}','deals','{{ $task['zoho_task_id'] }}')"
                            name="related_to_parent{{ $task['id'] }}" aria-label="Select Transaction" style="display: none;">
                            <option value="">Please Select</option>
                        </select>
                    </div>
                    <div class="dcardsdateinput">
                        <p class="dcardsTaskText">Task Date</p>
                        <input type="datetime-local"
                            onchange="makeEditable('{{ $task['id'] }}','date','{{ $task['zoho_task_id'] }}','date_val_card{{ $task['zoho_task_id'] }}')"
                            id="date_val_card{{ $task['zoho_task_id'] }}"
                            value="{{ \Carbon\Carbon::parse($task['due_date'])->format('Y-m-d\TH:i') }}" />
                    </div>
                </div>
                <div class="dcardsbtnsDiv">
                    <div id="update_changes" class="input-group-text dcardssavebtn" id="btnGroupAddon" data-bs-toggle="modal"
                        onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')" data-bs-target="#saveModalId">
                        <i class="fas fa-hdd plusicon"></i>
                        Save
                    </div>
                    <div class="input-group-text dcardsdeletebtn" onclick="deleteTask('{{ $task['zoho_task_id'] }}')"
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#deleteModalId">
                        <i class="fas fa-trash-alt plusicon"></i>

                        Delete
                    </div>
                </div>
            @endforeach
        @else
            <div>
                <div class="text-center">No records found</div>
            </div>
        @endif
    </div>
</div>
@if (count($tasks) > 0)
    <div class="dpagination">
        <div onclick="deleteTask('{{ $task['zoho_task_id'] }}',true)"
            class="input-group-text text-white justify-content-center removebtn dFont400 dFont13" id="removeBtn">
            <i class="fas fa-trash-alt plusicon"></i>
            Delete Selected
        </div>
        @include('common.pagination', ['module' => $tasks])
    </div>
@endif
<script src="{{ URL::asset('http://[::1]:5173/resources/js/toast.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var defaultTab = "{{ $tab }}";
        console.log(defaultTab, 'taskbladetab is here')
        localStorage.setItem('status', defaultTab);
        // Retrieve the status from local storage
        var status = localStorage.getItem('status');

        // Object to store status information
        var statusInfo = {
            'In Progress': false,
            'Completed': false,
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
        console.log(status, 'statussssss');
        var activeTab = document.querySelector('.nav-link[data-tab="' + status + '"]');
        if (activeTab) {
            activeTab.classList.add('active');
            activeTab.style.backgroundColor = "#222"
            activeTab.style.color = "#fff";
            activeTab.style.borderRadius = "4px";
        }

    });

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

    var textElement;
    
    function makeEditable(id, textfield, zohoID, textid) {
        if (textfield === "subject") {
            textElement = document.getElementById(textid);
            //For Table data                
            var text = textElement.textContent.trim();
            textElement.innerHTML = '<input type="text" id="editableInput' + textid + id + '" value="' + text + '" />';

            let inputElementmake = document.getElementById('editableInput' + textid + id);
            inputElementmake.focus();
            inputElementmake.addEventListener('change', function () {
                textElement.innerHTML = '<p id="editableText' + id + '" value="' + text + '">' +
                    inputElementmake.value + '</p>';
                updateText(inputElementmake.value, textfield, zohoID);
            });
        }
        if (textfield === "date") {
            let dateLocal = document.getElementById(textid);
            console.log(textid, 'dateLocal')
            var text = dateLocal.value.trim();
            updateText(text, textfield, zohoID);
        }


    }

    function getModule(id, elementID) {
        console.log(elementID + id, 'yes triggerwed')
        // Get the select element
        var selectElement = document.getElementById(elementID);
        console.log(selectElement, 'selectElement');
        // Check if it's the first click
        // Remove the existing option
        selectElement.innerHTML = "";
        // Add a default option
        var option1 = document.createElement("option");
        option1.value = "";
        option1.text = "Please select";
        selectElement.appendChild(option1);
        // Populate select with new options
        @if (!empty($retrieveModuleData))
            @foreach ($retrieveModuleData as $item)
                @if (!empty($item['api_name']) && in_array($item['api_name'], ['Deals', 'Contacts']))
                    var option = document.createElement("option");
                    option.id = "{{ $item['zoho_module_id'] }}";
                    option.value = "{{ $item['api_name'] }}";
                    option.text = "{{ $item['api_name'] }}";
                    selectElement.appendChild(option);
                @endif
            @endforeach
        @endif


        // Change the flag to indicate that it's no longer the first click
        isFirstClick = false;
        selectElement.addEventListener('change', function () {
            // Remove the onclick attribute 
            selectElement.removeAttribute("onclick");
            // Set the onchange attribute to call moduleSelected function passing this as a parameter
        });
        selectElement.setAttribute("onchange", `moduleSelected(this,${id})`);

    }

    function testFun(id, textfield, zohoID) {
        if (textfield === "deals") {
            var related_to_rem = document.getElementsByName("related_to_rem" + id)[0].value;
            if (!related_to_rem) {
                var related_to_rem1 = document.getElementsByName("related_to_rem" + id)[1].value;
                related_to_rem = related_to_rem1;
            }
            var WhatSelectoneid = document.getElementsByName("related_to_parent" + id)[0].value;
            if (!WhatSelectoneid) {
                var WhatSelectoneid1 = document.getElementsByName("related_to_parent" + id)[1].value;
                WhatSelectoneid = WhatSelectoneid1;
            }

            updateText(related_to_rem, textfield, zohoID, WhatSelectoneid);
        }
    }

    function formatSentence(sentence) {
        // Convert the first character to uppercase and the rest to lowercase
        return sentence.charAt(0).toUpperCase() + sentence.slice(1).toLowerCase();
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

    function updateText(newText, textfield, id, WhatSelectoneid = "") {
        let inputElementtext;
        let dateLocal;
        if (textfield === "subject") {
            inputElementtext = document.getElementById('editableText' + id);
        } else if (textfield === "date") {
            dateLocal = document.getElementById('date_local' + id);
            console.log(dateLocal, newText, 'checlout');
            dateLocal = dateLocal?.substring(0, 10);
            newText = newText?.substring(0, 10);
        } else {

        }
        if (newText == "") {
            showToastError("Empty text feild");
            return;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = {
            "data": [{
                "Subject": textfield === "subject" ? newText : inputElementtext?.value,
                "Due_Date": textfield === "date" ? newText : dateLocal?.value,
                "What_Id": WhatSelectoneid ? {
                    "id": WhatSelectoneid
                } : undefined,
                "$se_module": textfield === "deals" ? newText : undefined,
            }]
        };
        // Filter out undefined values
        formData.data[0] = Object.fromEntries(
            Object.entries(formData.data[0]).filter(([_, value]) => value !== undefined)
        );
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
                    if (!document.getElementById('savemakeModalId' + id).classList.contains('show')) {
                        var modalTarget = document.getElementById('savemakeModalId' + id);
                        var update_message = document.getElementById('updated_message_make');
                        update_message.textContent = formatSentence(response?.data[0]?.message);
                        // Show the modal
                        $(modalTarget).modal('show');
                        // window.location.reload();
                    }

                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                showToastError(error);
                console.error(xhr.responseText, 'errrorroororooro');
            }
        })
    }

    function moduleSelected(selectedModule, id = "") {
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
            success: function (response) {
                console.log(response, 'resoponse')
                // Handle successful response
                var tasks = response;
                var taskSelect;
                // Assuming you have another select element with id 'taskSelect'
                var taskSelectid = ""; // Initialize id variable
                if ($(window).innerWidth() <= 767) {
                    taskSelect = $("#taskSelect" + id);
                    taskSelect.removeAttr('id');
                    taskSelectcard = $("#taskSelectcard" + id);
                    taskSelect = taskSelectcard;
                    console.log(taskSelect, 'taskSelect' + id)

                } else {
                    taskSelectcard = $("#taskSelectcard" + id);
                    console.log(taskSelectcard, 'taskSelectcard')
                    taskSelectcard.removeAttr('id');
                    taskSelect = $("#taskSelect" + id);
                    taskSelect = taskSelect;
                    console.log(taskSelect, 'taskSelect')
                }
                // Clear existing options
                taskSelect.empty();
                // Populate select options with tasks
                $.each(tasks, function (index, task) {
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
                            text: task?.first_name ?? "" + ' ' + task?.last_name ?? ""
                        }));
                    }
                });

                taskSelect.select2();
                taskSelect.show();
                taskSelect.next(".select2-container").addClass("form-select");
                // Do whatever you want with the response data here
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });

    }

    function deleteTask(id) {
        let updateids = removeAllSelected();
        if (updateids === "" && id === undefined) {
            return;
        }
        if (updateids !== "") {
            if (showConfirmation()) {

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
                        showToast("deleted successfully");
                        // window.location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        showToastError(xhr.responseText)
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

    
</script>