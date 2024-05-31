{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.master')

@section('title', 'Agent Commander | Dashboard')
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
        <div class="row mt-4 text-center">
            {{-- <div class="col-lg-3 col-md-3 col-sm-6 text-start">
                <p class="dFont900 dFont15 dMb10">Welcome Back, {{ $user['name'] }} <br />
                    <span class="dFont400 dFont13">{{ date('l, F j, Y') }}</span>
                </p>
                <p class="dFont800 dFont13 dMb5">Pipeline stats date ranges</p>
                <div class="d-flex justify-content-between align-items-center dCalander">
                    <input class="dFont400 dFont13 mb-0 ddaterangepicker" type="text" name="daterange"
                        value="{{ $startDate }} - {{ $endDate }}" />
                    <img class="celendar_icon" src="{{ URL::asset('/images/calendar.svg') }}" alt=""
                        onclick="triggerDateRangePicker()">
                </div>

            </div> --}}
            <div class="col-lg-3 col-md-3 text-start dcontactbtns-div">
                <div class="row g-1">
                    <div>
                        <div class="input-group-text dcontactBtns" id="btnGroupAddon" onclick="createContact();"><i
                                class="fas fa-plus plusicon">
                            </i>
                            New Contact
                        </div>
                    </div>

                    <div>
                        <div class="input-group-text dcontactBtns" id="btnGroupAddon" data-bs-toggle="modal"
                            data-bs-target="#" onclick="createTransaction()"><i class="fas fa-plus plusicon">
                            </i>
                            New Transaction
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-ld-9 col-md-9 col-sm-12">
                <div class="row dashboard-cards-resp">
                    @foreach ($stageData as $stage => $data)
                        <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols" data-stage="{{ $stage }}">
                            <div class="card dash-card">
                                <div class="card-body dash-front-cards">
                                    <h5 class="card-title dTitle mb-0">{{ $stage }}</h5>


                                    {{-- <div class="d-flex justify-content-center align-items-center dCenterText"> --}}

                                    <p class="dSumValue">${{ $data['sum'] }}</p>
                                    {{-- <i class = "{{ $data['stageProgressIcon'] }}" style = "font-size:25px"></i>
                                        <p class="mb-0 dpercentage {{ $data['stageProgressClass'] }}">
                                            {{ $data['stageProgressExpr'] }}{{ $data['stageProgress'] }}%</p> --}}

                                    {{-- </div> --}}
                                    <p class="card-text dcountText">{{ $data['count'] }} Transactions
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
        <div class="row dmain-Container">
            <div class="row dgraphdiv">
                <div class="col-md-8">
                    <div class="row dspeedn-month-camaparison">
                        <div class="col-md-4 dspeedometersection">
                            <p class="dpipetext">My Pipeline</p>
                            <div id="canvas-holder" style="width:100%">
                                <canvas id="chart" width="100%" height="100%"></canvas>
                            </div>
                            {{-- <button id="randomizeData" onclick="randomDatassss();">Randomize Data</button> --}}
                            {{-- <div class="wrapper">
                                <div class="gauge">
                                    <div class="slice-colors">
                                        <div class="st slice-item"></div>
                                        <div class="st slice-item"></div>
                                        <div class="st slice-item"></div>
                                    </div>
                                    <div class="needle"></div>
                                    <div class="gauge-center"></div>
                                </div>
                            </div> --}}
                            <p class="dFont13 dMb5 dRangeText">{{ '$' . $totalGciForDah . ' of 250,000 Goal' }}</p>
                            <div>
                                {{-- <div class="d-flex justify-content-between align-items-center dCalander">
                                    <input class="dFont400 dFont13 mb-0 ddaterangepicker" type="text" name="daterange"
                                        value="{{ $startDate }} - {{ $endDate }}" />
                                    <img class="celendar_icon" src="{{ URL::asset('/images/calendar.svg') }}" alt=""
                                        onclick="triggerDateRangePicker()">
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-md-8 graphp-dash">
                            <div class="container  dgraphpstackContainer">
                                <p class="dcamptext">Monthly Pipeline Comparison</p>
                                <!-- Stacked Bar Chart -->
                                <div class="stacked-bar-chart w-100 stacked-contain">

                                    @php
                                        // Extracting the 'gci' values from the nested arrays
                                        $gcis = array_column($allMonths, 'gci');
                                        // Finding the maximum 'gci' value
                                        $maxGCI = max($gcis);
                                    @endphp

                                    @foreach ($allMonths as $month => $data)
                                        @php
                                            $widthPercentage = $maxGCI != 0 ? ($data['gci'] / $maxGCI) * 91 : 0;
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-1 align-self-center dmonth-design">
                                                {{ Carbon\Carbon::parse($month)->format('M') }}</div>
                                            <div class="col-md-11 dashchartImg">
                                                <div class="row dgraph-strip">
                                                    @php
                                                        // Remove the currency symbol ('$') and commas from the formatted value
                                                        $formattedGCI = str_replace(
                                                            ['$', ','],
                                                            '',
                                                            number_format($data['gci'], 0),
                                                        );
                                                    @endphp
                                                    <div class="col-md-10 text-end bar-a" {{-- style="width: {{ $widthPercentage }}%"
                                                  --}}
                                                        style="width: {{ $formattedGCI < 1000 ? 'auto' : $widthPercentage . '%' }}">
                                                        {{ '$' . number_format($data['gci'], 0) }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <p class="dtransactions-des">{{ $data['deal_count'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @include('common.notes.view', [
                    'notesInfo' => $notesInfo,
                    'retrieveModuleData' => $retrieveModuleData,
                ])
            </div>
            <div class="col-sm-12 dtasksection">
                <div class="d-flex justify-content-between">
                    <p class="dFont800 dFont15">Tasks</p>
                    <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#staticBackdropforTask"><i
                            class="fas fa-plus plusicon">
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
            <div class="table-responsive dtranstiontable mt-2">
                <p class="fw-bold">Transactions closing soon</p>
                <div class="row dtabletranstion">
                    <div class="col-md-2">Transaction Name</div>
                    <div class="col-md-2">Owner</div>
                    <div class="col-md-2">Contact Name</div>
                    <div class="col-md-2">Phone</div>
                    <div class="col-md-2">Email</div>
                    <div class="col-md-2">Closing Date</div>
                </div>
                @if (count($closedDeals) === 0)
                    <div>
                        <p class="text-center mt-4" colspan="12">No records found</p>
                    </div>
                @else
                    @foreach ($closedDeals as $deal)
                        <div class="row npAttachmentBody">
                            <div class="col-md-2 npcommontableBodytext">
                                <div class="dTContactName">
                                    {{ $deal['deal_name'] }}
                                </div>
                            </div>
                            <div class="col-md-2 npcommontableBodytext">
                                <div class="dTContactName">
                                    <img src="{{ URL::asset('/images/account_box.svg') }}" alt="R">
                                    {{ $deal->userData->name }}
                                </div>
                            </div>
                            <div class="col-md-2 npcommontableBodytext">
                                <div class="dTContactName">
                                    <img src="{{ URL::asset('/images/account_box.svg') }}" alt="R">
                                    {{ $deal->contactName->first_name ?? '' }} {{ $deal->contactName->last_name ?? '' }}
                                </div>
                            </div>
                            <div class="col-md-2 commonTextEllipsis npcommontableBodytext">
                                <div class="dTContactName">
                                    <img src="{{ URL::asset('/images/phoneb.svg') }}"
                                        alt="P">{{ $deal->contactName->phone ?? '9999999999' }}
                                </div>
                            </div>
                            <div class="col-md-2 commonTextEllipsis npcommontableBodytext ">
                                <div class="dTContactName"> <img src="{{ URL::asset('/images/mailb.svg') }}"
                                        alt="M">{{ $deal->contactName->email ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="col-md-2 npcommontableBodytext ">
                                <div class="dTContactName"><img src="{{ URL::asset('/images/event_busy.svg') }}"
                                        alt="E">
                                    {{ date('m/d/Y', strtotime($deal['closing_date'])) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="dtransactionsCardDiv">
                    @foreach ($closedDeals as $deal)
                        <div class="dtCardMainDiv">
                            <div class="dtCardDateDiv">
                                <div class="dTCardName">
                                    {{ $deal['deal_name'] }}
                                </div>
                                <div class="dTCardDate"><img src="{{ URL::asset('/images/event_busy.svg') }}"
                                        alt="E">
                                    {{ date('M d', strtotime($deal['closing_date'])) }}
                                </div>
                            </div>
                            <div class="dTCardName">
                                <img src="{{ URL::asset('/images/account_box.svg') }}" alt="R">
                                {{ $deal->userData->name ?? 'N/A' }}
                            </div>
                            <div class="dTCardName">
                                <img src="{{ URL::asset('/images/account_box.svg') }}" alt="R">
                                {{ $deal->contactName->first_name ?? '' }} {{ $deal->contactName->last_name ?? '' }}
                            </div>
                            <div class="dTCardName">
                                <img src="{{ URL::asset('/images/phoneb.svg') }}"
                                    alt="P">{{ $deal->contactName->phone ?? 'N/A' }}
                            </div>
                            <div class="dTCardmail"> <img src="{{ URL::asset('/images/mailb.svg') }}"
                                    alt="M">{{ $deal->contactName->email ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- @endif --}}

            </div>
        </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropforNote">
        <div class="tooltip-wrapper">
            <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
            <span class="tooltiptext">Add Notes</span>
        </div>
    </div>
    {{-- Modals --}}
    {{-- Create New Task Modal --}}
    @include('common.tasks.create')
    {{-- Note Modal --}}
    @include('common.notes.create')

    {{-- save Modal --}}
    {{-- <div class="modal fade" id="saveModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content">
                <div class="modal-header border-0">
                    {{-- <h5 class="modal-title">Modal title</h5> --}}
    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="saveModalBodyText">Changes have been saved</p>
                </div>
                <div class="modal-footer justify-content-evenly border-0">
                    <div class="d-grid col-12">
                        <button type="button" class="btn btn-secondary saveModalBtn" data-bs-dismiss="modal">
                            <i class="fas fa-check trashIcon"></i>
                            Understood
                        </button>
                    </div>

                </div>

            </div>
        </div> --}}
    {{-- </div>` --}}
    {{-- save Modal --}}
    {{-- @vite(['resources/js/dashboard.js']) --}}
    <!-- Include Date Range Picker -->

@section('dashboardScript')
@endsection
@endsection

<script>
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
    document.addEventListener('DOMContentLoaded', function() {
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
        tabs.forEach(function(tab) {
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

        // console.log("yes tist woring", @json($allMonths), )
        var ctx = document.getElementById('chart').getContext('2d');
        window.myGauge = new Chart(ctx, config);


    });
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

    window.createTransaction = function() {
        document.getElementById("loaderOverlay").style.display = "block";
        document.getElementById('loaderfor').style.display = "block";
        var formData = {
            "data": [{
                "Deal_Name": "{{ config('variables.dealName') }}",
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}"
                },
                "Stage": "Potential"
            }],
            "_token": '{{ csrf_token() }}'
        };
        $.ajax({
            url: '{{ url('/pipeline/create') }}',
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
                window.location.href = `{{ url('/pipeline-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById("loaderfor").style.display = "none";
                console.error('Error:', error);
            }
        });
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
@vite(['resources/js/dashboard.js'])
