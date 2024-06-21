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
        @if ($needsNewDate->isNotEmpty())
            <div class="alert alert-danger text-center">
                You have {{ $needsNewDate->count() }} bad dates!
                &nbsp;&nbsp;<button class="btn btn-dark btn-small" id="btnBadDates">FIX NOW</a>
            </div>
        @endif
        <div class="row mt-3 text-center">
            <div class="col-lg-3 col-md-3 text-start">
                <div class="row g-1">
                    <div>
                        @component('components.button', [
                            'clickEvent' => 'createContact()',
                            'label' => 'New Contact',
                            'icon' => 'fas fa-plus plusicon',
                        ])
                        @endcomponent
                    </div>
                    <div>
                        @component('components.button', [
                            'clickEvent' => 'createTransaction()',
                            'label' => 'New Transaction',
                            'icon' => 'fas fa-plus plusicon',
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
            @component('components.dash-cards', [
                'stageData' => $stageData,
            ])
            @endcomponent
        </div>

        <div class="row unset-bs-gutter-x">
            <div class="col-lg-3 mb-4">
                <div class="card card-body h-100 ">
                    <p class="text-dark font-family-montserrat font-size-16 fw-bolder mb-0">My Pipeline</p>
                    <div id="canvas-holder" style="width:100%">
                        <canvas id="chart" width="100%" height="100%"></canvas>
                    </div>
                    <p class="fs-13 mb-0 text-center font-size-18 fw-bolder mt-auto">
                        ${{ number_format($totalGciForDah, 2, '.', ',') }} of ${{ number_format($goal, 2, '.', ',') }}</p>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card card-body text-center h-100 card-body-padding0">
                    <div
                        class="container d-flex p-4 flex-column justify-content-center align-items-start gap-4 border rounded-3 bg-white shadow-sm">
                        <p class="text-dark text-start font-family-montserrat font-size-16 fw-bolder line-height-18">Monthly
                            Pipeline Comparison</p>
                        <div class="stacked-bar-chart w-100 d-flex flex-column gap-1">
                            @php
                                $gcis = array_column($allMonths, 'gci');
                                $maxGCI = max($gcis);
                            @endphp
                            @foreach ($allMonths as $month => $data)
                                @php
                                    $widthPercentage = $maxGCI != 0 ? ($data['gci'] / $maxGCI) * 91 : 0;
                                @endphp
                                <div class="row">
                                    <div
                                        class="col-md-2 align-self-center text-end text-muted small fw-bold font-montserrat fs-6">
                                        {{ Carbon\Carbon::parse($month)->format('M') }}</div>
                                    <div class="col-md-10 dashchartImg">
                                        <div class="row dgraph-strip">
                                            @php
                                                $formattedGCI = str_replace(
                                                    ['$', ','],
                                                    '',
                                                    number_format($data['gci'], 0),
                                                );
                                            @endphp
                                            <div class="col-md-11 text-end bar-a"
                                                style="width: {{ $formattedGCI < 1000 ? 'auto' : $widthPercentage . '%' }}">
                                                {{ '$' . number_format($data['gci'], 0) }}
                                            </div>
                                            <div class="col-md-1">
                                                <p class="dtransactions-des text-nowrap">{{ $data['deal_count'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 h-100 mb-4">
                <div class="card card-body card-body-padding0" style="background: transparent;">
                    <h4 class="text-start dFont600 mb-4">Notes</h4>
                    @include('common.notes.view', [
                        'notesInfo' => $notesInfo,
                        'retrieveModuleData' => $retrieveModuleData,
                    ])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 dtasksection p-4">
                <div class="d-flex justify-content-between">
                    <p class="dFont800 dFont15">Tasks</p>
                    <button type="button"
                        class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                        data-bs-toggle="modal" data-bs-target="#staticBackdropforTask" data-bs-whatever="@getbootstrap"><i
                            class="fas fa-plus plusicon"></i> New Task</button>
                </div>
                <div class="row">
                    <nav class="dtabs mt-3 ps-0">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link dtabsbtn active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" data-tab='In Progress' type="button" role="tab"
                                aria-controls="nav-home" aria-selected="true" onclick="fetchData('In Progress')">In
                                Progress</button>
                            <button class="nav-link dtabsbtn" data-tab='Upcoming' id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false" onclick="fetchData('Upcoming')">Upcoming</button>
                            <button class="nav-link dtabsbtn" data-tab='Overdue' id="nav-contact-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false" onclick="fetchData('Overdue')">Overdue</button>
                            <button class="nav-link dtabsbtn" data-tab='Completed' id="nav-contact-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false" onclick="fetchData('Completed')">Completed</button>
                        </div>
                    </nav>
                    <div class="task-container">
                        @include('common.tasks', ['tasks' => $tasks])
                    </div>
                </div>
            </div>
            @php
                 $headers = [
                    'Transaction Name',
                    'Client Name',
                    'Stage',
                    'Representing',
                    'Sale Price',
                    'Closing Date',
                    'Commission',
                    'Potential GCI',
                    'Probability',
                    'Probable GCI',
                ];
            @endphp
              @component('components.common-table', [
                'th' => $headers,
                'id'=>'datatable_transaction',
                'commonArr' =>$needsNewDate,
                "type" =>"dash-transaction",
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
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btnBadDates').addEventListener('click', function() {
            const element = document.getElementById('badDates');
            const offset = 100; // Adjust this value as needed
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        });

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
                    return Math.round(value) + "%";
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
