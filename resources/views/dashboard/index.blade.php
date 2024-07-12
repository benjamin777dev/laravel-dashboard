@extends('layouts.master')

@section('title', 'zPortal | Dashboard')
@section('content')
    @vite(['resources/css/dashboard.css'])
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div id="loader" style="display: none;">
        <img src="{{ URL::asset('/images/Spinner-5.gif') }}" alt="Loading...">
    </div>
    <div class="container-fluid">
    <div class="loader" id="loaderfor" style="display: none;"></div>
    <div class="loader-overlay" id="loaderOverlay" style="display: none;"></div>
        @if ($needsNewDate['count'] > 0)
            <div class="alert alert-danger text-center">
                You have {{ $needsNewDate['count'] }} bad dates!
                &nbsp;&nbsp;<button class="btn btn-dark btn-small" id="btnBadDates">FIX NOW</a>
            </div>
        @endif
        <div class="row mt-3 text-center">
            <div class="col-lg-3 col-md-3 text-start">
                <div class="row g-1">
                    <div>
                        @component('components.button', [
                            'id' => 'create_contact',
                            'label' => 'New Contact',
                            'icon' => 'fas fa-plus plusicon',
                        ])
                        @endcomponent
                    </div>
                    <div>
                        @component('components.button', [
                           'id' => 'create_transaction',
                            'label' => 'New Transaction',
                            'icon' => 'fas fa-plus plusicon',
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
            <div class="col-ld-9 col-md-9 col-sm-12">
                <div class="row dashboard-cards-resp">
                    @foreach ($stageData as $stage => $data)
                        <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols" data-stage="{{ $stage }}">
                            <div class="card dash-card">
                                <div class="card-body dash-front-cards">
                                    <h5
                                        class="card-title dTitle mb-0"
                                        >{{ $stage }}</h5>
                                    <p class="dSumValue">${{ $data['sum'] }}</p>
                                    <p class="card-text dcountText">{{ $data['count'] }} Transactions</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="section pt-0 pb-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title text-center mb-4">My Pipeline</div>
                                    <div id="canvas-holder" style="width:100%">
                                        <canvas id="chart" width="100%" height="100%"></canvas>
                                    </div>
                                    <p class="dFont13 dMb5 dRangeText">${{ number_format($totalGciForDah, 2, '.', ',') }} of ${{ number_format($goal, 2, '.', ',') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title mb-4 text-center fw-bold">Monthly Pipeline Comparison</div>
                                    <div class="stacked-bar-chart w-100 stacked-contain">
                                        @php
                                            $gcis = array_column($allMonths, 'gci');
                                            $maxGCI = max($gcis);
                                        @endphp
                                        @foreach ($allMonths as $month => $data)
                                            @php
                                                $widthPercentage = $maxGCI != 0 ? ($data['gci'] / $maxGCI) * 100 : 0;
                                                $minWidth = $widthPercentage < 10 ? 'min-width: 2.5rem;' : ''; // Ensure minimum width for visibility
                                            @endphp
                                            <div class="row align-items-center">
                                                <div class="col-3 text-center fw-bold">
                                                    {{ Carbon\Carbon::parse($month)->format('M') }}
                                                </div>
                                                <div class="col-7">
                                                    <div class="progress" style="height: 1.5rem;">
                                                        <div class="progress-bar bg-dark text-white d-flex justify-content-center align-items-center" role="progressbar" style="width: {{ $widthPercentage }}%; {{ $minWidth }}" aria-valuenow="{{ $widthPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                                            {{ '$' . number_format($data['gci'], 0) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2 text-center fw-bold">
                                                    {{ $data['deal_count'] }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title text-center mb-4">Upcoming Tasks</div>

                                    <button
                                        class="btn btn-sm btn-dark"
                                        id="btnGroupAddon"
                                        data-bs-toggle="modal"
                                        data-bs-target="#staticBackdropforTask"
                                    >
                                        <i class="fas fa-plus plusicon"></i> New Task
                                    </button>

                                    <a href="/task" class="btn btn-sm btn-dark">View Tasks</a>
                                    <div class="d-flex flex-column">
                                        @if ($upcomingTasks->take(5)->count() > 0)
                                            @foreach ($upcomingTasks->take(5) as $task)
                                                <div class="card mb-2 shadow-sm border-0">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                                            <div class="w-100">
                                                                <h5 class="m-0">
                                                                    <span class="text-dark">{{ $task['subject'] ?? 'General Task' }}</span>
                                                                </h5>
                                                                <h6 class="m-0">
                                                                    <span class="text-dark">Detail: {{ $task['detail'] ?? 'General Detail' }}</span>
                                                                </h6>
                                                                <small class="text-muted">
                                                                    Due: {{ \Carbon\Carbon::parse($task['due_date'])->format('M d, Y') ?? 'N/A' }},
                                                                    related to
                                                                    @if ($task['related_to'] == 'Both' && isset($task->contactData->zoho_contact_id) && isset($task->dealData->zoho_deal_id))
                                                                        <a href="{{ url('/contacts-view/' . $task->contactData->id ?? '') }}" class="text-primary">
                                                                            {{ $task->contactData->first_name ?? '' }} {{ $task->contactData->last_name ?? 'General' }}
                                                                        </a>&nbsp;/&nbsp;
                                                                        <a href="{{ url('/pipeline-view/' . $task->dealData->id ?? '') }}" class="text-primary">
                                                                            {{ $task->dealData->deal_name ?? 'General' }}
                                                                        </a>
                                                                    @elseif ($task['related_to'] == 'Contacts' && isset($task->contactData->zoho_contact_id))
                                                                        <a href="{{ url('/contacts-view/' . $task->contactData->id ?? '') }}" class="text-primary">
                                                                            {{ $task->contactData->first_name ?? '' }}
                                                                        </a>
                                                                    @elseif ($task['related_to'] == 'Deals' && isset($task->dealData->zoho_deal_id))
                                                                        <a href="{{ url('/pipeline-view/' . $task->dealData->id ?? '') }}" class="text-primary">
                                                                            {{ $task->dealData->deal_name ?? 'General' }}
                                                                        </a>
                                                                    @else
                                                                        <span class="text-secondary">General</span>
                                                                    @endif
                                                                </small>
                                                            </div>
                                                            <div class="d-flex flex-md-shrink-0 mt-2 mt-md-0">
                                                                @php
                                                                    $taskzId = $task['zoho_task_id'];
                                                                    $taskId = $task['id'];
                                                                    $subject = $task['subject'];
                                                                @endphp
                                                                <button class="btn btn-dark btn-sm me-2 text-nowrap" onclick="closeTask('{{ $taskzId }}', '{{$taskId}}', '{{$subject}}')">
                                                                    <i class="fas fa-check"></i> Done
                                                                </button>
                                                                <button class="btn btn-secondary btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
                                                                    <i class="fas fa-trash-alt"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered deleteModal">
                                                        <div class="modal-content">
                                                            <div class="modal-header border-0 deleteModalHeaderDiv">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body deletemodalBodyDiv">
                                                                <p class="deleteModalBodyText">Please confirm you’d like to<br />delete this item.</p>
                                                            </div>
                                                            <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                                                <div class="d-grid gap-2 col-5">
                                                                    <button type="button" onclick="deleteTask('{{ $task['zoho_task_id'] }}')" class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                                                                        <i class="fas fa-trash-alt trashIcon"></i> Yes, delete
                                                                    </button>
                                                                </div>
                                                                <div class="d-grid gap-2 col-5">
                                                                    <button type="button" data-bs-dismiss="modal" class="btn btn-primary goBackModalBtn">
                                                                        <img src="{{ URL::asset('/images/reply.svg') }}" data-bs-dismiss="modal" alt="R">No, go back
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center">
                                                <p>No recent tasks found</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card">
                <div class="card-body">
                    <h4 class="card-title mt-0">Notes</h4>
                    <div class="d-flex flex-column">
                        @if ($notes->count() > 0)
                            @foreach ($notesInfo as $note)
                                <div class=" mb-2 ">
                                    <div class="p-3">
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                            <div class="w-100">
                                                <h5 class="m-0">
                                                    <span class="text-dark">{{ $note['note_content'] ?? 'General Note' }}</span>
                                                </h5>
                                                <small class="text-muted">
                                                    Created: {{ \Carbon\Carbon::parse($note['created_time'])->format('M d, Y') ?? '' }},
                                                    related to
                                                    @if ($note['related_to_type'] == 'Contacts' && isset($note->contactData->zoho_contact_id))
                                                        <a href="{{ url('/contacts-view/' . $note->contactData->id ?? '') }}" class="text-primary">
                                                            {{ $note->contactData->first_name ?? '' }} {{ $note->contactData->last_name ?? '' }}
                                                        </a>
                                                    @elseif ($note['related_to_type'] == 'Deals' && isset($note->dealData->zoho_deal_id))
                                                        <a href="{{ url('/pipeline-view/' . $note->dealData->id ?? '') }}" class="text-primary">
                                                            {{ $note->dealData->deal_name ?? 'General Deal' }}
                                                        </a>
                                                    @else
                                                        <span class="text-secondary">General</span>
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="d-flex flex-md-shrink-0 mt-2 mt-md-0">
                                                @php
                                                    $taskzId = $note['zoho_note_id'];
                                                    $taskId = $note['id'];
                                                    $subject = $note['note_content'];
                                                @endphp
                                                <button class="btn btn-secondary btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#deleteModalId{{ $note['zoho_note_id'] }}">                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModalId{{ $note['zoho_note_id'] }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered deleteModal">
                                        <div class="modal-content">
                                            <div class="modal-header border-0 deleteModalHeaderDiv">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body deletemodalBodyDiv">
                                                <p class="deleteModalBodyText">Please confirm you’d like to<br />delete this item.</p>
                                            </div>
                                            <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                                <div class="d-grid gap-2 col-5">
                                                    <button type="button" onclick="deleteNote('{{ $note['zoho_note_id'] }}')" class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                                                        <i class="fas fa-trash-alt trashIcon"></i> Yes, delete
                                                    </button>
                                                </div>
                                                <div class="d-grid gap-2 col-5">
                                                    <button type="button" data-bs-dismiss="modal" class="btn btn-primary goBackModalBtn">
                                                        <img src="{{ URL::asset('/images/reply.svg') }}" data-bs-dismiss="modal" alt="R">No, go back
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            @endforeach
                            <a href="{{ route('show.notes') }}" style="text-align:right;">see more...</a>
                        @else
                            <div class="text-center">
                                <p>No recent notes found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class=" dtranstiontable mt-2" id="badDates">
                @if ($needsNewDate['count'] > 0)
                    <p class="fw-bold">Bad Dates | <span class="text-danger">{{$needsNewDate['count']}} Bad Dates!</span></p>
                @else
                    <p class="fw-bold">Bad Dates | <span class="text-success">No Bad Dates, <strong>Great Job!</strong>!</span></p>
                @endif
                @php
                    $transHeader = [
                        "",
                    "Transaction",
                    "Client Name",
                    "Status",
                    "Representing",
                    "Price",
                    "Close Date",
                    "Commission",
                    "Potential GCI",
                    "Probability",
                    "Probable GCI"
                ]
                @endphp
                @component('components.common-table', [
                    'th' => $transHeader,
                    'id'=>'datatable_transaction',
                    'commonArr' =>$needsNewDate,
                    "type" =>"dash-pipe-transaction",
                ])
                @endcomponent
            </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropforNote">
        <div class="tooltip-wrapper">
            <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
            <span class="tooltiptext">Add Notes</span>
        </div>
    </div>
    {{-- Modals --}}
    @include('common.tasks.create')
    @include('common.notes.create')
@endsection


<script>

    window.deleteNote = function(id) {
        console.log("delete note called",id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        try {
            if (id) {
                $.ajax({
                    url: "{{ route('delete.note', ['id' => ':id']) }}".replace(':id', id),
                    method: 'DELETE', // Change to DELETE method
                    contentType: 'application/json',
                    dataType: 'JSON',
                    data: {
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        // Handle success response
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                })

            }
        } catch (err) {
            console.error("error", err);
        }

    }

    window.closeTask = function(id, indexId, subject) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var formData = {
            "data": [{
                "Subject": subject,
                "Status":"Completed"
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
                     window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                showToastError(xhr.responseJSON.error);
                console.error(xhr.responseText, 'errrorroororooro');



            }
        })
    }

    window.deleteTask = function(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        try {
            if (id) {
                $.ajax({
                    url: "{{ route('delete.task', ['id' => ':id']) }}".replace(':id', id),
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
                        showToastError(xhr.responseText);
                    }
                });
            }
        } catch (err) {
            console.error("error", err);
        }
    }




    document.addEventListener('DOMContentLoaded', function() {
        const btnBadDates = document.getElementById('btnBadDates');
    if (btnBadDates) {
        btnBadDates.addEventListener('click', function() {
            const element = document.getElementById('badDates');
            if (element) {
                const offset = 100; // Adjust this value as needed
                const elementPosition = element.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            } else {
                console.log('No bad dates element found.');
            }
        });
    } else {
        console.log('No btnBadDates element found.');
    }

        var defaultTab = "{{ $tab }}";
        console.log(defaultTab, 'tab is here')
        localStorage.setItem('status', defaultTab);
        // Retrieve the status from local storage
        var status = localStorage.getItem('status');

        // Object to store status information
        var statusInfo = {
            'In Progress': false,
            'Overdue': false,
            'Completed': false,
            'Upcoming': false,
        };

        // Update the status information based on the current status
        statusInfo[status] = true;

        // Loop through statusInfo to set other statuses to false
        for (var key in statusInfo) {
            if (key !== status) {
                statusInfo[key] = false;
            }
        }

        // Remove active class from all tabs
        var tabs = document.querySelectorAll('.nav-link');
        console.log(tabs, 'tabssss')
        tabs.forEach(function(tab) {
            tab.classList.remove('active');
        });

        // Set active class to the tab corresponding to the status
        console.log(status, 'status');
        var activeTab = document.querySelector('.nav-link[data-tab="' + status + '"]');
        if (activeTab) {
            activeTab.classList.add('active');
        }

        // console.log("yes tist woring", @json($allMonths), )
        var ctx = document.getElementById('chart').getContext('2d');
        window.myGauge = new Chart(ctx, config);

    });


    window.fetchData = function(tab = null) {
        $('#spinner').show();
        loading = true;
        // Make AJAX call
        $.ajax({
            url: '{{ url('/dashboard') }}',
            method: 'GET',
            data: {
                tab: tab,
            },
            dataType: 'html',
            success: function(data) {
                $('#spinner').hide();
                loading = false;
                $('.task-container').html(data);

            },
            error: function(xhr, status, error) {
                // Handle errors
                loading = false;
                console.error('Error:', error);
            }
        });

    }
    // var selectedNoteIds = [];

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
    window.selectedTransation;
    // Get the select element

    function selectedElement(element) {
        var selectedValue = element.value;
        window.selectedTransation = selectedValue;
        //    console.log(selectedTransation);
    }

    function resetValidation() {
        document.getElementById("task_error").innerHTML = "";

    }

    function validateTextarea() {
        var textarea = document.getElementById('subject');
        var textareaValue = textarea.value;
        // Check if textarea value is empty
        if (textareaValue === '') {
            // Show error message or perform validation logic
            document.getElementById("task_error").innerHTML = "please enter details";
        } else {
            document.getElementById("task_error").innerHTML = "";
        }
    }

    function addTask() {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("task_error").innerHTML = "please enter details";
            return;
        }
        var seModule = document.getElementsByName("related_to_task")[0].value;
        var WhatSelectoneid = document.getElementsByName("related_to_parent")[0].value;
        // var whoId = window.selectedTransation
        // if (whoId === undefined) {
        //     whoId = whoSelectoneid
        // }
        var dueDate = document.getElementsByName("due_date")[0].value;
        var formData = {
            "data": [{
                "Subject": subject,
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
                    // window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
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

    function createContact() {
        document.getElementById("loaderOverlay").style.display = "block";
        document.getElementById('loaderfor').style.display = "block";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let name = "CHR";
        var formData = {
            "data": [{
                "Relationship_Type": "Primary",
                "Missing_ABCD": true,
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}",
                    "full_name": "{{ auth()->user()->name }}",
                },
                "Unsubscribe_From_Reviews": false,
                "Currency": "USD",
                "Market_Area": "-None-",
                "Lead_Source": "-None-",
                "ABCD": "-None-",
                "Last_Name": name,
                "zia_suggested_users": {}
            }],
            "_token": '{{ csrf_token() }}'
        };
        $.ajax({
            url: '{{ url('/contact/create') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(data) {
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById("loaderfor").style.display = "none";
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/contacts-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById("loaderfor").style.display = "none";
            }
        });
    }


    var randomScalingFactor = function(progressCount = "") {
        // console.log(progressCount,'progressCount')
        return Math.round(Math.random() * 100);
        // return Math.round(progressCount!==""?progressCount:@json($progress) );
    };

    var randomData = function() {
        return [15, 45, 100];
    };
    var randomValue = function(data) {
        if (data) {
            console.log(data, 'data')
            return data;
        }
        return @json($progress);
    };

    var data = randomData();
    var value = randomValue();
    console.log(data, value, 'valueishereeee')
    var config = {
        type: 'gauge',
        data: {
            datasets: [{
                data: data,
                value: value,
                backgroundColor: ['#FE5243', '#FADA05', '#21AC25'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
            },
            layout: {
                padding: {
                    bottom: 30
                }
            },
            needle: {
                radiusPercentage: 2,
                widthPercentage: 3.2,
                lengthPercentage: 80,
            },
            valueLabel: {
                fontSize: "24px", // Change font size here
                formatter: function(value) {
                    return Math.round(value * 10) / 10  + "%";
                }
            },
            chartArea: {
                width: '80%',
                height: '80%'
            },
            // Add callbacks to draw percentage labels
            plugins: {
                afterDraw: function(chart, easing) {
                    var ctx = chart.ctx;
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart
                        .defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    chart.data.datasets.forEach(function(dataset) {
                        for (var i = 0; i < dataset.data.length; i++) {
                            var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
                            var labelText = Math.round(dataset.data[i]) + "%";
                            ctx.fillStyle = '#000'; // set font color
                            ctx.fillText(labelText, model.x, model.y -
                                5); // adjust Y position for label
                        }
                    });
                }
            }
        }
    };
</script>

@section('bladeScripts')
    @vite(['resources/js/dashboard.js'])
@endsection
