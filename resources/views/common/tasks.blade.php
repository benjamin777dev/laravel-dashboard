<script>
    // Store tasks data in a JavaScript variable
    var taskIDSS = [];
    var selectElement;
    var tasks = @json($tasks);
     tasks?.data?.forEach((task)=>{
        taskIDSS.push(task?.id)
     })
     var modalSelectMaptsk = []
     taskIDSS.forEach((id) => {
        modalSelectMaptsk.push({
                modalID: id,
                selectElementId: 'related_to_rem' + id
            })
        });
        modalSelectMaptsk.forEach(({
            modalID,
            selectElementId
        }) => {
            const selectElement = $(`#${selectElementId}`);
            showDropdownForId(modalID, selectElement);
        });
</script>
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
                            <div class="btn-group btnTaskSelects dealTaskfordropdown">
                                <select onchange="testFun('{{ $task['id'] }}','related_to_rem','{{ $task['zoho_task_id'] }}')" class="form-select dealTaskSelect related_to_rem{{ $task['id'] }}"
                                id="related_to_rem{{ $task['id'] }}" name="related_to_rem{{ $task['id'] }}">
                            @if ($task['related_to'] == 'Contacts')
                                <option value="{{ $task->contactData->zoho_contact_id ?? '' }}" selected>
                                    {{ $task->contactData->first_name ?? '' }} {{ $task->contactData->last_name ?? 'Please Select' }}
                                </option>
                            @elseif ($task['related_to'] == 'Deals')
                                <option value="{{ $task->dealData->zoho_deal_id ?? '' }}" selected>
                                    {{ $task->dealData->deal_name ?? 'Please select' }}
                                </option>
                            @else
                                <option value="" selected>Please select</option>
                            @endif
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
                            <div class="d-flex btn-save-del">
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
                            <div class="modal fade" id="deleteModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 deleteModalHeaderDiv">
                                             <h5 class="modal-title">Modal title</h5> 
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
                                            <h5 class="modal-title">Modal title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
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
                            </div>
                                            <div class="modal fade" id="savemakeModalId{{ $task['id'] }}"
                                                tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                                    <div class="modal-content">
                                                        <div class="modal-header saveModalHeaderDiv border-0">
                                                            {{-- <h5 class="modal-title">Modal title</h5> --}}
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body saveModalBodyDiv">
                                                            <p class="saveModalBodyText" id="updated_message_make">
                                                                Changes have been saved</p>
                                                        </div>
                                                        <div
                                                            class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                                                            <div class="d-grid col-12">
                                                                <button type="button"
                                                                    class="btn btn-secondary saveModalBtn"
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
                    
                    <td class="text-center" colspan="12">No records found
                        <div id="spinner" class="spinner_task" style="display: none;">
                        </div>
                    </td>
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
                            onclick="testFun('{{ $task['id'] }}','related_to_rem_card{{ $task['id'] }}')"
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
                    <div id="update_changes" class="input-group-text dcardssavebtn" id="btnGroupAddon"
                        data-bs-toggle="modal"
                        onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')"
                        data-bs-target="#saveModalId">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        tabs.forEach(function(tab) {
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

    function updateSelectOptions(id, taskArr, selectedText) {
        var selectElement = $("#related_to_rem" + id);
        let selecttext = selectedText;
        // Clear existing options
        selectElement.empty();

        var selectedFound = false; // Flag to track if the selected item has been found and added

        taskArr.forEach(function(state) {
            var optgroup = $('<optgroup>', {
                label: state.text
            });
            selectElement.append(optgroup);

            var count = 0; // Counter to track the number of records appended for each label

            if (state.text === "Contacts") {
                state.children.forEach(function(contact) {
                    if (count < 5 || (selecttext && contact.first_name + ' ' + contact.last_name ===
                            selecttext)) {

                        var option = $('<option>', {
                            value: contact.zoho_contact_id,
                            text: (contact.first_name || "") + " " + (contact.last_name || "")
                        });
                        optgroup.append(option);
                        if (selecttext && contact.first_name + ' ' + contact.last_name ===
                            selecttext) {
                            option.prop('selected', true);
                            selectedFound = true;
                        }
                        count++;
                    }
                });
            }

            if (state.text === "Deals") {
                state.children.forEach(function(deal) {
                    if (count < 5 || (selecttext && deal.deal_name === selecttext)) {
                        var option = $('<option>', {
                            value: deal.zoho_deal_id,
                            text: deal.deal_name,
                        });
                        optgroup.append(option);
                        if (selecttext && deal.deal_name === selecttext) {
                            option.prop('selected', true);
                            selectedFound = true;
                        }
                        count++;
                    }
                });
            }
        });

        // Reinitialize Select2 after updating options
        // selectElement.trigger('select2:updated');
        var search = $("#inputhidden input.select2-input");
        search.trigger("input");
    }



    function updateTaskArr(id, search, selectedText) {

        // Populate select with new options
        $.ajax({
            url: '/task/get-Modules?search=' + search,
            method: "GET",
            dataType: "json",
            success: function(response) {
                $(document).trigger('customAjaxResponse', [id, response, selectedText]);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });
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
            } else {
                anyChecked = true;
            }
        });

        if (anyChecked) {
            updateColor.style.backgroundColor = "#222"; // Checked color
        } else {
            updateColor.style.backgroundColor = "#dfdfdf"; // Unchecked color
        }
        allCheckbox.checked = !anyUnchecked; // Update "Select All" checkbox based on the flag
    }

    function removeAllSelected() {
        // Select all checkboxes
        var checkboxes = document.querySelectorAll('input[class="task_checkbox"]');
        var ids = ""; // Initialize ids variable to store concatenated IDs
        // Iterate through each checkbox
        checkboxes.forEach(function(checkbox) {
            // console.log(checkboxes,'checkboxes')
            // Check if the checkbox is checked
            if (checkbox.checked) {
                if (checkbox.id !== "light-mode-switch" && checkbox.id !== "dark-rtl-mode-switch" && checkbox
                    .id !== "rtl-mode-switch" && checkbox.id !== "dark-mode-switch" && checkbox.id !==
                    "checkbox_all") {
                    // Concatenate the checkbox ID with a comma
                    ids += checkbox.id + ",";
                    document.getElementById("removeBtn").style.backgroundColor = "#222;"
                }
            }
        });

        // Remove the trailing comma
        if (ids !== "") {
            ids = ids.replace(/,+(?=,|$)/g, "");
        }

        return ids;
    }

                     

    function deleteTask(id, isremoveselected = false) {
        let updateids = removeAllSelected();
        if (updateids === "" && id === 'remove_selected') {
            return;
        }
        if (isremoveselected) {
            id = undefined;
        }
       
        if (updateids !== "") {
            if (saveDataaaa()) {
                console.log("yes delete");

            } else {
                console.log("dfshdfjkshd")
                return;
            }
        }
        if (id === undefined) {
            id = updateids;
        }
        //remove duplicate ids
        ids = id.replace(/(\b\w+\b)(?=.*\b\1\b)/g, '').replace(/^,|,$/g, '');
        console.log(ids,'idssssss');
        return;
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
                    success: function(response) {
                        // Handle success response
                        showToast("deleted successfully");
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
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
            updateColor.style.backgroundColor = "#222";
        } else {

            updateColor.style.backgroundColor = "#dfdfdf";
        }
    }

    function format(state) {
        if (!state.id) return state.text; // optgroup
        return state.text + " <i class='info'>link</i>";
    }

    function testFun(id, textfield, zohoID) {
        if (textfield === "deals") {
            var related_to_rem = document.getElementsByName("related_to_rem" + id)[0].value;
            if (!related_to_rem) {
                var related_to_rem1 = document.getElementsByName("related_to_rem" + id)[1].value;
                related_to_rem = related_to_rem1;
            }
            console.log(window.groupLabel,'window.groupLabelwindow.groupLabel')

            updateText(related_to_rem, textfield, id, WhatSelectoneid="");
        }
    }

    var textElement;

    function makeEditable(id, textfield, zohoID, textid) {

        if (textfield === "subject") {
            textElement = document.getElementById(textid);
            //For Table data                
            var text = textElement.textContent.trim();
            let isEditable=true;
           
            var newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.id = 'editableInput'+ id;
            newInput.value = text;
            textElement.parentNode.replaceChild(newInput, textElement);

            let inputElementmake = document.getElementById('editableInput' + id);
            inputElementmake.focus();
            inputElementmake.addEventListener('blur', function() {
                    var newParagraph = document.createElement('p');
                    newParagraph.id = 'editableText' + id;
                    newParagraph.classList = "dSubjectText dFont900 dFont14 dMt16 justify-content-between"
                    newParagraph.textContent = inputElementmake.value;

                // Replace textElement with the new paragraph element
                inputElementmake.parentNode.replaceChild(newParagraph, inputElementmake);
               
                updateText(inputElementmake.value, textfield, zohoID);
            });
        }
        if (textfield === "date") {
            let dateLocal = document.getElementById(textid);
            console.log(textid, 'dateLocal')
            var text = dateLocal.value.trim();
            updateText(text, textfield, zohoID);
        }
        if(textfield==="relatedTo"){
            let relatedTO = document.getElementById("related_to_rem"+id);

            
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


    function updateText(newText, textfield, id, WhatSelectoneid = "", whoID = "") {
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
 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
   console.log(newText,'sjdhfjksdgfk')
    var formData = {
        "data": [{
            "Subject": textfield === "subject" ? newText : inputElementtext?.value,
            "Due_Date": textfield === "date" ? newText : dateLocal?.value,
            "What_Id": WhatSelectoneid ? {
                "id": WhatSelectoneid
            } : undefined,
            "Who_Id": whoID ? {
                "id": whoID
            } : undefined,
            "$se_module": textfield === "Deals" || newText === "Deals" || newText === "Contacts" ?
                newText : undefined,
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
        success: function(response) {
            // Handle success response
            showToast(response?.data[0]?.message.toUpperCase());
        },
        error: function(xhr, status, error) {
            // Handle error response
            showToastError(error);
            console.error(xhr.responseText, 'errrorroororooro');
        }
    })
}

    

    // function moduleSelected(selectedModule, id = "") {
    //     // console.log(accessToken,'accessToken')
    //     var selectedOption = selectedModule.options[selectedModule.selectedIndex];
    //     var selectedText = selectedOption.text;
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $.ajax({
    //         url: '/task/get-' + selectedText,
    //         method: "GET",
    //         dataType: "json",
    //         success: function(response) {
    //             console.log(response, 'resoponse')
    //             // Handle successful response
    //             var tasks = response;
    //             var taskSelect;
    //             // Assuming you have another select element with id 'taskSelect'
    //             var taskSelectid = ""; // Initialize id variable
    //             if ($(window).innerWidth() <= 767) {
    //                 taskSelect = $("#taskSelect" + id);
    //                 taskSelect.removeAttr('id');
    //                 taskSelectcard = $("#taskSelectcard" + id);
    //                 taskSelect = taskSelectcard;
    //                 console.log(taskSelect, 'taskSelect' + id)

    //             } else {
    //                 taskSelectcard = $("#taskSelectcard" + id);
    //                 console.log(taskSelectcard, 'taskSelectcard')
    //                 taskSelectcard.removeAttr('id');
    //                 taskSelect = $("#taskSelect" + id);
    //                 taskSelect = taskSelect;
    //                 console.log(taskSelect, 'taskSelect')
    //             }
    //             // Clear existing options
    //             taskSelect.empty();
    //             // Populate select options with tasks
    //             $.each(tasks, function(index, task) {
    //                 if (selectedText === "Tasks") {
    //                     taskSelect.append($('<option>', {
    //                         value: task?.zoho_task_id,
    //                         text: task?.subject
    //                     }));
    //                 }
    //                 if (selectedText === "Deals") {
    //                     taskSelect.append($('<option>', {
    //                         value: task?.zoho_deal_id,
    //                         text: task?.deal_name
    //                     }));
    //                 }
    //                 if (selectedText === "Contacts") {
    //                     taskSelect.append($('<option>', {
    //                         value: task?.zoho_contact_id,
    //                         text: task?.first_name ?? "" + ' ' + task?.last_name ?? ""
    //                     }));
    //                 }
    //             });

    //             taskSelect.select2();
    //             taskSelect.show();
    //             taskSelect.next(".select2-container").addClass("form-select");
    //             // Do whatever you want with the response data here
    //         },
    //         error: function(xhr, status, error) {
    //             // Handle error
    //             console.error("Ajax Error:", error);
    //         }
    //     });

    // }
</script>
