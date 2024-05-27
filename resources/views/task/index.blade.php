@extends('layouts.master')
@section('title', 'Agent Commander | Tasks')

@section('content')
    @vite(['resources/css/custom.css'])
    <div class="row">
        <div class="col-sm-12 dtasksection">
            <div class="d-flex justify-content-between">
                <p class="dFont800 dFont15">Tasks</p>
                <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#staticBackdropforTask"><i class="fas fa-plus plusicon">
                    </i>
                    New Task
                </div>

            </div>
            <div class="row">
                <nav class="dtabs">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link dtabsbtn active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" data-tab='In Progress' type="button" role="tab"
                            aria-controls="nav-home" aria-selected="true" onclick="fetchData('In Progress')">In
                            Progress</button>
                        <button class="nav-link dtabsbtn" data-tab='Not Started' id="nav-profile-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab"
                            aria-controls="nav-profile" aria-selected="false"
                            onclick="fetchData('Not Started')">Upcoming</button>
                        <button class="nav-link dtabsbtn" data-tab='Completed' id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false" onclick="fetchData('Completed')">Overdue</button>
                    </div>
                </nav>
               <div class= "task-container">
                        @include('common.tasks', [
                            'tasks' => $tasks,
                        ])
                </div>

            </div>

        </div>


        {{-- <div class="table-responsive dtranstiontable mt-3">
            <p class="fw-bold">Transactions closing soon</p>
            <table class="table dtabletranstion">
                <thead>
                    <tr>
                        <th scope="col">Transaction Name</th>
                        <th scope="col">Contact Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Closing Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($closedDeals) === 0)
                        <tr>
                            <td class="text-center" colspan="5">No records found</td>
                        </tr>
                    @else
                        @foreach ($closedDeals as $deal)
                            <tr>
                                <td>{{ $deal['deal_name'] ?? 'N/A' }}</td>
                                <td>{{ $deal->contactName->first_name ?? 'N/A' }}
                                    {{ $deal->contactName->last_name ?? '' }}</td>
                                <td>{{ $deal->contactName->phone ?? 'N/A' }}</td>
                                <td>{{ $deal->contactName->email ?? 'N/A' }}</td>
                                <td>{{ $deal['closing_date'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div> --}}
    </div>
    {{-- Create New Task Modal --}}
    @include('common.tasks.create')
    {{-- save Modal --}}
    <div class="modal fade" id="saveModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content">
                <div class="modal-header saveModalHeaderDiv border-0">
                    {{-- <h5 class="modal-title">Modal title</h5> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body saveModalBodyDiv">
                    <p class="saveModalBodyText" id="updated_message">Changes have been saved</p>
                </div>
                <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                    <div class="d-grid col-12">
                        <button type="button" class="btn btn-secondary saveModalBtn" data-bs-dismiss="modal">
                            <i class="fas fa-check trashIcon"></i>
                            Understood
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.nav-link');
        let activeTab = localStorage.getItem('activeTab');

        if (activeTab) {
            tabs.forEach(tab => {
                if (tab.getAttribute('href') === activeTab) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                localStorage.setItem('activeTab', this.getAttribute('href'));
            });
        });
        });

    window.selectedTransation;

    function selectedElement(element) {
        var selectedValue = element.value;
        window.selectedTransation = selectedValue;
        //    console.log(selectedTransation);
    }

    function addTask() {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML = "please enter details";
            return;
        }
        var seModule = document.getElementsByName("related_to")[0].value;
        var WhatSelectoneid = document.getElementsByName("related_to_parent")[0].value;
        var whoId = window.selectedTransation
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
                "Priority": "High",
                "What_Id": {
                    "id": WhatSelectoneid
                },
                "$se_module": seModule
            }],
            "_token": '{{ csrf_token() }}'
        };

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
                    showToast(upperCaseMessage);
                    window.location.reload();
                } else {
                    showToastError("Response or message not found");
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                showToastError(xhr.responseText);
            }
        })
    }

    function resetValidation() {
        document.getElementById("subject_error").innerHTML = "";
        document.getElementById('darea').value = "";
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

    window.fetchData = function(tab = null) {
        $('#spinner').show();
        // Make AJAX call
        $.ajax({
            url: '{{ url('/dashboard') }}',
            method: 'GET',
            data: {
                tab: tab,
            },
            success: function(data) {
                $('#spinner').hide();
                $('.task-container').html(data);

            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error('Error:', error);
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


    function validateTextarea() {
        var textarea = document.getElementById('darea');
        var textareaValue = textarea.value.trim();
        // Check if textarea value is empty
        if (textareaValue === '') {
            // Show error message or perform validation logic
            document.getElementById("subject_error").innerHTML = "please enter details";
        } else {
            document.getElementById("subject_error").innerHTML = "";
        }
    }

    const ui = {
  confirm: async (message) => createConfirm(message)
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
            if (save()) {

            } else {
                return;
            }
        }
        if (id === undefined) {
            id = updateids;
        }
        //remove duplicate ids
        ids = id.replace(/(\b\w+\b)(?=.*\b\1\b)/g, '').replace(/^,|,$/g, '');
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
        // console.log(formattedDateTime);
        //         alert(formattedDateTime);
        //         return;
        if (!inputElement) {
            console.error("Input element not found for indexid:", indexid);
            return;
        }
        var elementValue = inputElement.textContent;
        // return;
        if (elementValue.trim() === "") {
            // console.log("chkockdsjkfjksdh")
            return showToastError("Please enter subject value first");
        }
        // console.log("inputElementval",elementValue!==undefined,elementValue)
        if (elementValue !== undefined) { // return;
            var formData = {
                "data": [{
                    "Subject": elementValue,
                    // "Remind_At": {
                    //     "ALARM": `FREQ=NONE;ACTION=EMAIL;TRIGGER=DATE-TIME:${taskDate.value}`
                    // }
                }]
            };
            // console.log("ys check ot")
            $.ajax({
                url: "{{ route('update.task', ['id' => ':id']) }}".replace(':id', id),
                method: 'PUT',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function(response) {
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
                            window.location.reload();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    showToastError("Something went wrong");
                    console.error(xhr.responseText, 'errrorroororooro');



                }
            })
        }
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

    function convertDateTime(inputDateTime) {

        // Parse the input date string
        let dateObj = new Date(inputDateTime);

        // Format the date components
        let year = dateObj.getFullYear();
        let month = (dateObj.getMonth() + 1).toString().padStart(2, '0'); // Month is 0-indexed, so we add 1
        let day = dateObj.getDate().toString().padStart(2, '0');
        let hours = dateObj.getHours().toString().padStart(2, '0');
        let minutes = dateObj.getMinutes().toString().padStart(2, '0');
        let seconds = dateObj.getSeconds().toString().padStart(2, '0');

        // Format the timezone offset
        let timezoneOffsetHours = Math.abs(dateObj.getTimezoneOffset() / 60).toString().padStart(2, '0');
        let timezoneOffsetMinutes = (dateObj.getTimezoneOffset() % 60).toString().padStart(2, '0');
        let timezoneOffsetSign = dateObj.getTimezoneOffset() > 0 ? '-' : '+';

        // Construct the formatted datetime string
        let formattedDateTime =
            `${year}-${month}-${day}T${hours}:${minutes}:${seconds}${timezoneOffsetSign}${timezoneOffsetHours}:${timezoneOffsetMinutes}`;

        return formattedDateTime;
    }

    function updateText(newText) {
        //  textElement = document.getElementById('editableText');
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
        inputElement.addEventListener('blur', function() {
            updateText(inputElement.value);
        });
    }
</script>
