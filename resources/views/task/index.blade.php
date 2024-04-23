@extends('layouts.master')
@section('title', 'Agent Commander | Tasks')

@section('content')
    @vite(['resources/css/custom.css'])
    <div class="row">
        <div class="col-sm-12 dtasksection">
            <div class="d-flex justify-content-between">
                <p class="dFont800 dFont15">Tasks</p>
                <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                    id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId"><i
                        class="fas fa-plus plusicon">
                    </i>
                    New Task
                </div>

            </div>
            <div class="row">
                <nav class="dtabs">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a href="/dashboard?tab=In Progress"> <button class="nav-link dtabsbtn active" id="nav-home-tab"
                                data-bs-toggle="tab" data-bs-target="#nav-home" data-tab='In Progress' type="button"
                                role="tab" aria-controls="nav-home" aria-selected="true">In
                                Progress</button></a>
                        <a href="/dashboard?tab=Not Started"> <button class="nav-link dtabsbtn" data-tab='Not Started'
                                id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button"
                                role="tab" aria-controls="nav-profile" aria-selected="false">Upcoming</button></a>
                        <a href="/dashboard?tab=Completed"><button class="nav-link dtabsbtn" data-tab='Overdue'
                                id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button"
                                role="tab" aria-controls="nav-contact" aria-selected="false">Overdue</button></a>
                    </div>
                </nav>

                <div class="table-responsive dresponsivetable">
                    <table class="table dtableresp">
                        <thead>
                            <tr class="dFont700 dFont10">
                                <th scope="col"><input type="checkbox" onclick="toggleAllCheckboxes()"
                                        id="checkbox_all" id="checkbox_task" /></th>
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
                                        <td><input onchange="triggerCheckbox('{{ $task['zoho_task_id'] }}')"
                                                type="checkbox" class="task_checkbox"
                                                id="{{ $task['zoho_task_id'] }}" /></td>
                                        <td>
                                            <p class="dFont900 dFont14 d-flex justify-content-between dMt16 dSubjectText"
                                                id="editableText{{ $task['id'] }}">
                                                {{ $task['subject'] ?? 'N/A' }}
                                                <i class="fas fa-pencil-alt pencilIcon"
                                                    onclick="makeEditable('{{ $task['id'] }}')"></i>
                                            </p>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <select class="form-select dselect" aria-label="Transaction test"
                                                    id="dropdownMenuButton">
                                                    <option value="{{ $task['Who_Id']['id'] ?? '' }}">
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="datetime-local" id="date_val{{ $task['zoho_task_id'] }}"
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
                                            {{-- <div class="modal fade" id="deleteModalId{{$task['zoho_task_id']}}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered deleteModal">
                                                        <div class="modal-content">
                                                            <div class="modal-header border-0">
                                                                {{-- <h5 class="modal-title">Modal title</h5> --}}
                                            {{-- <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="deleteModalBodyText">Please confirm you’d
                                                                    like to<br />
                                                                    delete this item.</p>
                                                            </div>
                                                            <div class="modal-footer justify-content-evenly border-0">
                                                                <div class="d-grid gap-2 col-5">
                                                                    <button onclick="deleteTask('{{$task['zoho_task_id']}}')" type="button"
                                                                        class="btn btn-secondary deleteModalBtn"
                                                                        data-bs-dismiss="">
                                                                        <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                                                        delete
                                                                    </button>
                                                                </div>
                                                                <div class="d-grid gap-2 col-5">
                                                                    <button type="button"
                                                                        class="btn btn-primary goBackModalBtn">
                                                                        <i class="fas fa-arrow-left goBackIcon"></i>
                                                                        No, go back
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div> --}}
                                            {{-- </div>  --}}
                                            {{-- delete Modal --}}
                                            <div class="modal fade" id="deleteModalId{{ $task['zoho_task_id'] }}"
                                                tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0 deleteModalHeaderDiv">
                                                            {{-- <h5 class="modal-title">Modal title</h5> --}}
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body deletemodalBodyDiv">
                                                            <p class="deleteModalBodyText">Please confirm you’d like
                                                                to<br />
                                                                delete this item.</p>
                                                        </div>
                                                        <div
                                                            class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                                            <div class="d-grid gap-2 col-5">
                                                                <button type="button"
                                                                    onclick="deleteTask('{{ $task['zoho_task_id'] }}')"
                                                                    class="btn btn-secondary deleteModalBtn"
                                                                    data-bs-dismiss="modal">
                                                                    <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                                                    delete
                                                                </button>
                                                            </div>
                                                            <div class="d-grid gap-2 col-5">
                                                                <button type="button" data-bs-dismiss="modal"
                                                                    class="btn btn-primary goBackModalBtn">
                                                                    <img src="{{ URL::asset('/images/reply.svg') }}"
                                                                        data-bs-dismiss="modal" alt="R">No, go
                                                                    back
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
                                        onclick="makeEditable('{{ $task['id'] }}')">
                                        {{ $task['subject'] ?? 'N/A' }}
                                        {{-- <i class="fas fa-pencil-alt pencilIcon "></i> --}}
                                    </p>
                                    <div class="btn-group dcardsselectdiv">
                                        <p class="dcardsTransactionText">Transaction Related</p>
                                        <select class="form-select dselect" aria-label="Transaction test"
                                            id="dropdownMenuButton">
                                            <option value="{{ $task['Who_Id']['id'] ?? '' }}">{{ $task }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="dcardsdateinput">
                                        <p class="dcardsTaskText">Task Date</p>
                                        <input type="datetime-local"
                                            value="{{ \Carbon\Carbon::parse($task['created_time'])->format('Y-m-d\TH:i') }}" />
                                    </div>
                                </div>
                                <div class="dcardsbtnsDiv">
                                    <div id="update_changes" class="input-group-text dcardssavebtn"
                                        id="btnGroupAddon" data-bs-toggle="modal"
                                        onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')"
                                        data-bs-target="#saveModalId">
                                        <i class="fas fa-hdd plusicon"></i>
                                        Save
                                    </div>
                                    <div class="input-group-text dcardsdeletebtn"
                                        onclick="deleteTask('{{ $task['zoho_task_id'] }}')" id="btnGroupAddon"
                                        data-bs-toggle="modal" data-bs-target="#deleteModalId">
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
                    @if (count($tasks) > 0)
                        <div class="dpagination">
                            <div onclick="deleteTask('{{ $task['zoho_task_id'] }}',true)"
                                class="input-group-text text-white justify-content-center removebtn dFont400 dFont13"
                                id="removeBtn">
                                <i class="fas fa-trash-alt plusicon"></i>
                                Remove Selected
                            </div>
                            <nav aria-label="..." class="dpaginationNav">
                                <ul class="pagination ppipelinepage d-flex justify-content-end">
                                    <!-- Previous Page Link -->
                                    @if ($tasks->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $tasks->previousPageUrl() }}&tab={{ request()->query('tab') }}"
                                                rel="prev">Previous</a>
                                        </li>
                                    @endif

                                    <!-- Pagination Elements -->
                                    @php
                                        $currentPage = $tasks->currentPage();
                                        $lastPage = $tasks->lastPage();
                                        $startPage = max($currentPage - 1, 1);
                                        $endPage = min($currentPage + 1, $lastPage);
                                    @endphp

                                    {{-- @if ($startPage > 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif --}}

                                    @for ($page = $startPage; $page <= $endPage; $page++)
                                        <li class="page-item {{ $tasks->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link"
                                                href="{{ $tasks->url($page) }}&tab={{ request()->query('tab') }}">{{ $page }}</a>
                                        </li>
                                    @endfor

                                    {{-- @if ($endPage < $lastPage)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif --}}

                                    <!-- Next Page Link -->
                                    @if ($tasks->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $tasks->nextPageUrl() }}&tab={{ request()->query('tab') }}"
                                                rel="next">Next</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>


                        </div>
                    @endif

                    {{-- <div class="dpagination">
                        <div onclick="removeAllSelected()"
                            class="input-group-text text-white justify-content-center removebtn dFont400 dFont13"> <i
                                class="fas fa-trash-alt plusicon"></i>
                            Remove Selected
                        </div>
                        <nav aria-label="..." class="dpaginationNav">
                            <ul class="pagination d-flex justify-content-end">
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
                    </div> --}}
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
      <div class="modal fade" id="newTaskModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Tasks</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidation()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="subject" onkeyup="validateTextarea();" id="darea" rows="4" class="dtextarea"></textarea>
                    <div id="subject_error" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" onchange="selectedElement(this)" id="who_id"
                            name="who_id" aria-label="Select Transaction">
                            @php
                                $encounteredIds = []; // Array to store encountered IDs
                            @endphp

                            @foreach ($getdealsTransaction as $item)
                                @php
                                    $contactId = $item['userData']['zoho_id'];
                                @endphp

                                {{-- Check if the current ID has been encountered before --}}
                                @if (!in_array($contactId, $encounteredIds))
                                    {{-- Add the current ID to the encountered IDs array --}}
                                    @php
                                        $encounteredIds[] = $contactId;
                                    @endphp

                                    <option value="{{ $contactId }}"
                                        @if (old('related_to') == $item['userData']['name']) selected @endif>
                                        {{ $item['userData']['name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <p class="dDueText">Date due</p>
                    <input type="date" name="due_date" class="dmodalInput" />
                </div>
                <div class="modal-footer ">
                    <button type="button" onclick="addTask()" class="btn btn-secondary taskModalSaveBtn">
                        <i class="fas fa-save saveIcon"></i> Save Changes
                    </button>

                </div>

            </div>
        </div>
    </div>
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
    </div>`
@endsection
<script>

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
                // "Priority": "High",
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
   
    function deleteTask(id,isremoveselected=false) {
        let updateids = removeAllSelected();
        if (updateids === "" && id === 'remove_selected') {
            return;
        }
        if(isremoveselected){
            id = undefined;
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
                    success: function(response) {
                        // Handle success response
                        alert("deleted successfully", response);
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
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
            return alert("Please enter subject value first");
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
