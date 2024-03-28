{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.master')

@section('title', 'Agent Commander | Dashboard')
@section('content')
    @vite(['resources/css/dashboard.css'])
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <div class="container-fluid">
        <div class="row mt-4 text-center">
            <div class="col-lg-3 col-md-3 col-sm-6 text-start">
                <p class="dFont900 dFont15 dMb10">Welcome Back, Mark <br />
                    <span class="dFont400 dFont13">Thursday, March 22, 2024</span>
                </p>
                <p class="dFont800 dFont13 dMb5">Pipeline stats date ranges</p>
                <div class="d-flex justify-content-between align-items-baseline dCalander">
                    {{-- <p class="dFont400 dFont13 mb-0">19.12.2020 - 25.12.2020</p> --}}
                    {{-- <i class="fa fa-calendar calendar-icon" onclick="toggleDatePicker();"></i> --}}
                    <input type="text" id="dateRangePicker" onclick="datePickerRange();" value="19.12.2020 - 25.12.2020"
                        name="daterange">
                </div>

            </div>

            <div class="col-ld-9 col-md-9 col-sm-12">
                <div class="row dashboard-cards">
                    @foreach ($stageData as $stage => $data)
                        {{-- {{ dd($data) }} --}}
                        <div class="col-lg-3 col-md-3 col-sm-6 col-6 text-center">
                            <div class="card dash-card">
                                <div class="card-body dash-front-cards">
                                    <h5 class="card-title dFont400 dFont13 dTitle mb-0">{{ $stage }}</h5>


                                    <div class="d-flex justify-content-center align-items-center dCenterText">
                                        {{-- <div class="col"> --}}
                                        <span class="dFont800 dFont18">${{ $data['asum'] }}</span>
                                        {{-- </div> --}}
                                        <div class="dimgdiv">
                                            <img src="{{ url('/images/customImages/arrow_outward.svg') }}" alt=""
                                                height="13" class="auth-logo-dark" />
                                        </div>
                                        {{-- </div> --}}
                                        {{-- <div class="col"> --}}
                                        <p class="mb-0 dpercentage">+0.2%</p>
                                        {{-- </div> --}}
                                    </div>
                                    <p class="card-text dFont800 dFont13">{{ $data['count'] }} Transactions
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-sm-12 dtasksection">
                <div class="d-flex justify-content-between">
                    <p class="dFont800 dFont15">Tasks</p>
                    <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                        id="btnGroupAddon" data-bs-toggle="modal"
                        data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
                        </i>
                        New Task
                    </div>

                </div>
                <div class="row">
                    <nav class="dtabs">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a href="/dashboard?tab=In Progress"> <button class="nav-link dtabsbtn active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" data-tab='In Progress' type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">In
                                Progress</button></a>
                           <a href="/dashboard?tab=Not Started"> <button class="nav-link dtabsbtn" data-tab='Not Started' id="nav-profile-tab" data-bs-toggle="tab" 
                                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Upcoming</button></a>
                            <a href="/dashboard?tab=Overdue"><button class="nav-link dtabsbtn" data-tab='Overdue' id="nav-contact-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false">Overdue</button></a>
                        </div>
                    </nav>

                    <div class="table-responsive dresponsivetable">
                        <table class="table dtableresp">
                            <thead>
                                <tr class="dFont700 dFont10">
                                    <th scope="col"><input type="checkbox" /></th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Transaction Related</th>
                                    <th scope="col">Task Date</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                @if (count($tasks['tasks']) > 0)
                                @foreach ($tasks['tasks'] as $task)
                <tr class="dresponsivetableTr">
                    <td><input type="checkbox" /></td>
                    <td>
                        <p class="dFont900 dFont14 d-flex justify-content-between dMt16">{{ $task['Subject'] ?? "N/A" }}  <i class="fas fa-pencil-alt pencilIcon "></i></p>
                    </td>
                    <td>
                        <div class="btn-group">
                            <select class="form-select" aria-label="Transaction test" id="dropdownMenuButton">
                                <option value="{{ $task['Who_Id']['id'] ?? '' }}">{{ $task['Who_Id']['name'] ?? '' }}</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <input type="datetime-local" value="{{ \Carbon\Carbon::parse($task['Due_Date'])->format('Y-m-d\TH:i') }}" />
                    </td>
                    <td>
                        <div class="row ">
                            <div class="input-group-text dFont800 dFont11 text-white col-md-5 col-sm-5 justify-content-center align-items-baseline savebtn"
                                id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#saveModalId">
                                <i class="fas fa-hdd plusicon"></i>
                                Save
                            </div>
                            <div class="input-group-text dFont800 dFont11 text-white col-md- col-sm-5 justify-content-center align-items-baseline deletebtn"
                                id="btnGroupAddon" data-bs-toggle="modal"
                                data-bs-target="#deleteModalId">
                                <i class="fas fa-trash-alt plusicon"></i>

                                Delete
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
                    </div>
                </div>

            </div>
            <div class="col-md-4 col-sm-12">
                <h4 class="text-start dFont600 mb-4">Notes</h4>
                <ul class="list-group">
                    <li
                        class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                        <div class="text-start">
                            <span class="dFont800 dFont13">Related to:</span> Global<br />
                            <p class="dFont400 fs-4 mb-0">
                                Add items to contract
                            </p>
                        </div>
                        <input type="checkbox" class="form-check-input" id="checkbox1">
                    </li>
                    <li
                        class="list-group-item border-0 rounded-1 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                        <div class="text-start">
                            <span class="fw-bold">Related to:</span> Global<br />
                            <p class="fs-4">
                                Add items to contract
                            </p>
                        </div>
                        <input type="checkbox" class="form-check-input" id="checkbox1">
                    </li>
                    <li
                        class="list-group-item border-0 rounded-1 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                        <div class="text-start">
                            <span class="fw-bold">Related to:</span> Global<br />
                            <p class="fs-4">
                                Add items to contract
                            </p>
                        </div>
                        <input type="checkbox" class="form-check-input" id="checkbox1">
                    </li>
                    <li
                        class="list-group-item border-0 rounded-1 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                        <div class="text-start">
                            <span class="fw-bold">Related to:</span> Global<br />
                            <p class="fs-4">
                                Add items to contract
                            </p>
                        </div>
                        <input type="checkbox" class="form-check-input" id="checkbox1">
                    </li>


                </ul>


            </div>
            <div class="table-responsive dtranstiontable mt-3">
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
                        <tr>
                            <td>debit</td>
                            <td>Mark</td>
                            <td>45455454</td>
                            <td>mark@gmail.com</td>
                            <td>24/4/2024</td>
                        </tr>
                        <tr>
                            <td>debit</td>
                            <td>Mark</td>
                            <td>45455454</td>
                            <td>mark@gmail.com</td>
                            <td>24/4/2024</td>
                        </tr>
                        <tr>
                            <td>debit</td>
                            <td>Mark</td>
                            <td>45455454</td>
                            <td>mark@gmail.com</td>
                            <td>24/4/2024</td>
                        </tr>
                        <tr>
                            <td>debit</td>
                            <td>Mark</td>
                            <td>45455454</td>
                            <td>mark@gmail.com</td>
                            <td>24/4/2024</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
        {{-- Modals --}}
        {{-- new task modal --}}
        <div class="modal fade" id="newTaskModalId" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered deleteModal">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        {{-- <h5 class="modal-title">Modal title</h5> --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="deleteModalBodyText">In progress</p>
                    </div>
                    <div class="modal-footer justify-content-evenly border-0">
                        <div class="d-grid gap-2 col-5">
                            {{-- <button type="button" class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                                <i class="fas fa-trash-alt trashIcon"></i> Yes, delete
                            </button> --}}
                        </div>
                        <div class="d-grid gap-2 col-5">
                            {{-- <button type="button" class="btn btn-primary goBackModalBtn">
                                <i class="fas fa-arrow-left goBackIcon"></i> No, go back
                            </button> --}}
                        </div>
                    </div>
    
                </div>
            </div>
        </div>`
    {{-- save Modal --}}
    <div class="modal fade" id="saveModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content">
                <div class="modal-header border-0">
                    {{-- <h5 class="modal-title">Modal title</h5> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        </div>
    </div>`
    {{-- delete Modal --}}
    <div class="modal fade" id="deleteModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content">
                <div class="modal-header border-0">
                    {{-- <h5 class="modal-title">Modal title</h5> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="deleteModalBodyText">Please confirm you’d like to<br />
                        delete this item.</p>
                </div>
                <div class="modal-footer justify-content-evenly border-0">
                    <div class="d-grid gap-2 col-5">
                        <button type="button" class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                            <i class="fas fa-trash-alt trashIcon"></i> Yes, delete
                        </button>
                    </div>
                    <div class="d-grid gap-2 col-5">
                        <button type="button" class="btn btn-primary goBackModalBtn">
                            <i class="fas fa-arrow-left goBackIcon"></i> No, go back
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>`
    {{-- <div class="row mt-4">
            <div class="col-md-4 widget-thermometer">
                <div class="card">
                    <div class="card-header">
                        My Pipeline - Next 12 Months
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="customGaugeChart"></canvas>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">Stage</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Deals</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stageData as $stage => $data)
                                        <tr>
                                            <td>{{ $stage }}</td>
                                            <td>${{ $data['sum'] }}</td>
                                            <td>{{ $data['count'] }} Deals</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th scope="row">Current Pipeline Value</th>
                                        <td colspan="2">${{ $currentPipelineValue }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">My Projected Income</th>
                                        <td colspan="2">${{ $projectedIncome }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">My Income Goal</th>
                                        <td colspan="2">${{ $goal }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">Not in Pipeline</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Beyond 12 Months</td>
                                        <td>${{ $beyond12MonthsData['sum'] }}</td>
                                        <td>{{ $beyond12MonthsData['count'] }} Deals</td>
                                    </tr>
                                    <tr>
                                        <td>Needs New Date</td>
                                        <td>${{ $needsNewDateData['sum'] }}</td>
                                        <td>{{ $needsNewDateData['count'] }} Deals</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ url('/manage-pipeline') }}" class="btn btn-primary">Manage Pipeline</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card widget-monthly-comparison" style="height: 400px;width=100%;">
                    <div class="card-header">
                        My Pipeline - Monthly Comparison
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyComparisonChart"></canvas>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Database Maintenance</div>
                    <div class="card-body">
                        <p>ABC Contacts: {{ $contactData['abcContacts'] }}</p>
                        <p>Needs Email: {{ $contactData['needsEmail'] }}</p>
                        <p>Needs Address: {{ $contactData['needsAddress'] }}</p>
                        <p>Needs Phone: {{ $contactData['needsPhone'] }}</p>
                        <p>Missing ABCD: {{ $contactData['missingAbcd'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-info">
                    <h3>Average Pipeline Probability: {{ number_format($averagePipelineProbability, 0) }}%</h3>
                </div>
                <div class="alert alert-success">
                    <h3>Transactions Last 30 Days: {{ number_format($newContactsLast30Days, 0) }}</h3>
                </div>
                <div class="alert alert-secondary">
                    <h3>Contacts Last 30 Days: {{ number_format($newDealsLast30Days, 0) }}</h3>
                </div>
                <div class="card">
                    <div class="card-header">Cap data</div>
                    <div class="card-body">
                        <p>Cap Paid YTD: {{ $aciData['totalaci'] }}</p>
                        <p>My Initial Cap</p>
                        <p>My Residual Cap</p>
                        <p>Checks Received YTD: {{ $aciData['totalAgentCheck'] }}</p>
                        <p>1099 Amount YTD: {{ $aciData['totalIRS1099'] }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Performance Metrics</div>
                    <div class="card-body">
                        <p><a
                                href="https://analytics.zoho.com/open-view/2487682000008614470/3c546af6361400d5afd39fa034e3f1b9">CHR
                                Rankings Report</a></p>
                        <p><a
                                href="https://analytics.zoho.com/open-view/2487682000008657377/8b86fc2667f41985c4de6ebf80d00ba7">CHR
                                Company Production</a></p>
                        <p><a href="#">Strategy Group Production (Coming Soon)</a></p>
                        <p><a
                                href="https://analytics.zoho.com/open-view/2487682000008655113/74f218cdf16cc52f2a54e19c1f5fdc83">Co-Op
                                Agent Analysis</a></p>
                    </div>
                </div>
            </div>
        </div> --}}


    {{-- <div class="row mt-3"> --}}
    {{-- <div class="row mt-4">
            {{-- Task Section --}}
    {{-- <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Action to Take</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th> <!-- For checkbox -->
                                        <th>Subject</th>
                                        <th>Due Date</th>
                                        <th>Related To</th>
                                        <th>Assigned To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks['tasks'] as $task)
                                        <tr>
                                            <td><input type="checkbox" name="taskCompleted[]" value="{{ $task['id'] }}">
                                            </td>
                                            <td>{{ $task['Subject'] ?? 'N/A' }}</td>
                                            <td>{{ $task['Due_Date'] ? Carbon\Carbon::parse($task['Due_Date'])->format('m/d/Y') : 'N/A' }}
                                            </td>
                                            <td>{{ $task['Who_Id']['name'] ?? 'N/A' }}</td>
                                            <td>{{ $task['Owner']['name'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}


    @vite(['resources/js/dashboard.js'])
    <!-- Include Date Range Picker -->

@section('dashboardScript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var canvas = document.getElementById('customGaugeChart');
            var ctx = canvas.getContext('2d');
            var progress = @json($progress); // Your progress value from the server

            // Resize the canvas and draw the gauge accordingly
            function resizeCanvas() {
                var container = document.querySelector('.widget-thermometer');
                canvas.width = container.offsetWidth / 1.1; // Set the canvas width to the width of the container
                canvas.height = container.offsetWidth / 2; // Keep the canvas height half of the width
                drawGauge();
            }

            function drawGauge() {
                ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas before redrawing

                var centerX = canvas.width / 2;
                var centerY = canvas.height * 0.95; // Lower the center to give more space at the top
                var radius = canvas.width * 0.45; // Reduce the radius to ensure it fits in the canvas

                // Draw the red segment
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, Math.PI, Math.PI * 1.25, false);
                ctx.strokeStyle = 'red';
                ctx.lineWidth = radius * 0.2;
                ctx.stroke();

                // Draw the yellow segment
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, Math.PI * 1.25, Math.PI * 1.5, false);
                ctx.strokeStyle = 'yellow';
                ctx.lineWidth = radius * 0.2;
                ctx.stroke();

                // Draw the green segment
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, Math.PI * 1.5, 2 * Math.PI, false);
                ctx.strokeStyle = 'green';
                ctx.lineWidth = radius * 0.2;
                ctx.stroke();

                // Draw the needle
                var needleAngle = Math.PI + (progress / 100) * Math.PI;
                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.lineTo(centerX + radius * Math.cos(needleAngle), centerY + radius * Math.sin(needleAngle));
                ctx.strokeStyle = '#333';
                ctx.lineWidth = 5;
                ctx.stroke();

                // Draw the center circle for the needle
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius * 0.05, 0, Math.PI * 2, false);
                ctx.fillStyle = '#333';
                ctx.fill();

                // Draw the progress text
                ctx.fillStyle = '#000';
                ctx.font = 'bold 20px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(progress + '%', centerX, centerY - radius / 2);
            }

            function drawSegment(x, y, r, startAngle, endAngle, color, lineWidth) {
                ctx.beginPath();
                ctx.arc(x, y, r, startAngle, endAngle, false);
                ctx.strokeStyle = color;
                ctx.lineWidth = lineWidth;
                ctx.stroke();
            }

            function drawNeedle(x, y, angle, length) {
                ctx.beginPath();
                ctx.moveTo(x, y);
                ctx.lineTo(x + length * Math.cos(angle), y + length * Math.sin(angle));
                ctx.strokeStyle = '#333';
                ctx.lineWidth = 5;
                ctx.stroke();
            }

            function drawProgressText(x, y, text) {
                ctx.fillStyle = '#000';
                ctx.font = 'bold 20px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(text, x, y);
            }

            resizeCanvas();
            window.addEventListener('resize', resizeCanvas); // Redraw the gauge on window resize

            // for drawing the monthly chart
            var monthlyCtx = document.getElementById('monthlyComparisonChart').getContext('2d');
            var monthlyComparisonChart = new Chart(monthlyCtx, {
                type: 'bar', // This specifies a vertical bar chart
                data: {
                    labels: @json($allMonths->keys()), // Laravel Blade directive
                    datasets: [{
                        label: 'Monthly GCI',
                        data: @json($allMonths->values()), // Laravel Blade directive
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return 'GCI: $' + tooltipItem.yLabel.toLocaleString();
                            }
                        }
                    },
                    indexAxis: 'y', // 'x' for vertical chart and 'y' for horizontal
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        x: {
                            beginAtZero: true, // Ensure this is set to have the bars start at the base
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 12 // Adjust as needed for the number of months
                                // Include the following if the labels are still overlapping:
                                // callback: function(value, index, values) {
                                //   return index % 2 === 0 ? value : '';
                                // },
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            color: '#444',
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value, context) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    maintainAspectRatio: false // Add this to prevent the chart from taking the default aspect ratio
                }
            });
        });
    </script>


@endsection
@endsection
<script>
  document.addEventListener('DOMContentLoaded', function() {
     var defaultTab = "{{ $tab }}";
     console.log(defaultTab,'tab is here')
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
    console.log(tabs,'tabssss')
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
});

</script>
<script src="{{ URL::asset('http://[::1]:5173/resources/js/dashboard.js') }}"></script>


