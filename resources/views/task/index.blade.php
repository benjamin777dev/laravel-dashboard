@extends('layouts.master')

@section('title')
    @lang('Task_List')
@endsection

@section('content')

    <div class="row">
        <div class="d-flex justify-content-between">
            <p class="dFont800 dFont15">Tasks</p>
            <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13" id="btnGroupAddon"
                data-bs-toggle="modal" data-bs-target="#staticBackdropforTask">
                <i class="fas fa-plus plusicon"></i> New Task
            </div>
        </div>
        <div class="col-lg-8">
            <!-- Upcoming Tasks -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Upcoming</h4>
                    <div class="table-responsive upcommingcard">
                        @include('common.tasks.upcommingcard')
                    </div>
                </div>
                @if (!empty($upcomingTasks->nextPageUrl()) && $upcomingTasks->count() >= 10)
                <div class="p-2 text-primary cursor-auto" id="see_moree_upcomming">
                    <p style="cursor: pointer">See More...</p>
                </div>
                @endif
            </div>

            <!-- In Progress Tasks -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Due Today</h4>
                    <div class="table-responsive see_moree_overdue_today">
                        @include('common.tasks.overduecard')
                    </div>
                </div>
                @if (!empty($inProgressTasks->nextPageUrl()) && $inProgressTasks->count() >= 10)
                <div class="p-2 text-primary cursor-auto" id="see_moree_overdue_today">
                    <p style="cursor: pointer">See More...</p>
                </div>
                @endif
            </div>

            <!-- Overdue Tasks -->
            <div class="card ">
                <div class="card-body">
                    <h4 class="card-title mb-4">Overdue</h4>
                    <div class="table-responsive overdue_section">
                        @include('common.tasks.taskcard')
                    </div>
                </div>
                @if (!empty($overdueTasks->nextPageUrl()) && $overdueTasks->count() >= 10)
                    <div class="p-2 text-primary cursor-auto" id="see_moree_overdue">
                        <p style="cursor: pointer">See More...</p>
                    </div>
                @endif

                <div class="datapagination d-none">
                    @include('common.pagination', ['module' => $overdueTasks])
                </div>

            </div>


            <!-- Completed Tasks -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Completed</h4>
                    <div class="table-responsive completed_section">
                        @include('common.tasks.completecard')
                    </div>
                </div>
                @if (!empty($completedTasks->nextPageUrl()) && $completedTasks->count() >= 10)
                <div class="p-2 text-primary" style="cursor: pointer" id="see_moree_complete">
                    <p>See More...</p>
                </div>
            @endif
            
            </div>
        </div>
        <!-- end col -->

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Tasks</h4>

                    <div id="task-chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Recent Tasks</h4>

                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <tbody>
                                @if (count($upcomingTasks->take(5)) > 0)
                                    @foreach ($upcomingTasks->take(5) as $task)
                                        <tr>
                                            <td>
                                                <h5 class="text-truncate font-size-14 m-0">
                                                    <a href="#" class="text-dark">{{ $task['subject'] ?? 'N/A' }}</a>
                                                </h5>
                                            </td>
                                            <td>
                                                @if ($task['related_to'] == 'Contacts')
                                                   <span>Related to:<a href="/contacts-view/{{$task->contactData->id ?? ""}}"> {{ $task->contactData->first_name ?? '' }}
                                                        {{ $task->contactData->last_name ?? 'General' }}</a> </span>
                                                @elseif ($task['related_to'] == 'Deals')
                                               <span>Related to: <a href="/pipeline-view/{{$task->dealData->id ?? ""}}"> {{ $task->dealData->deal_name ?? 'General' }}</a></span>
                                                @else
                                                    <span>Related to: General</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="2">No recent tasks found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>
    @include('common.tasks.create')

@endsection

@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

 


    <script>
        let nextPageUrloverdue = '{{ $overdueTasks->nextPageUrl() }}';
        let nextPageUrlComplete = '{{ $completedTasks->nextPageUrl() }}';
        let nextinProgressTasks = '{{ $inProgressTasks->nextPageUrl() }}';
        let nextUpcommingTasks = '{{ $upcomingTasks->nextPageUrl() }}';
        window.onload = function() {
            let dddddd= @json($taskcal);
            $("#see_moree_overdue").click(function() {
                if (nextPageUrloverdue !== "") {
                    console.log("yes hittt")
                    $('.spinner').show();

                    $.ajax({
                        url: nextPageUrloverdue + "&status=Overdue",
                        type: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            // Optionally you can set nextPageUrloverdue to empty or reset it here
                            // nextPageUrloverdue = ''; 
                        },
                        success: function(data) {
                            $('.spinner').hide();
                            data?.nextPageUrl===null?$('#see_moree_overdue').hide():data?.nextPageUrl;
                            // Update nextPageUrloverdue with the URL for the next page, 
                            nextPageUrloverdue = data?.nextPageUrl || '';
                            // Append the new content to the overdue_section
                            $('.overdue_section').append(data?.html ||
                            ''); // Assuming `data.html` contains the HTML content
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading more posts:", error);
                            $('.spinner').hide();
                        }
                    });
                }
            });

            $("#see_moree_complete").click(function() {
                if (nextPageUrlComplete !== "") {
                    $('.spinner').show();
                    $.ajax({
                        url: nextPageUrlComplete + "&status=Completed",
                        type: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            // Optionally you can set nextPageUrlComplete to empty or reset it here
                            // nextPageUrlComplete = ''; 
                        },
                        success: function(data) {
                            $('.spinner').hide();
                            data?.nextPageUrl===null?$('#see_moree_complete').hide():data?.nextPageUrl;
                            // Update nextPageUrlComplete with the URL for the next page, 
                            nextPageUrlComplete = data?.nextPageUrl || '';
                            // Append the new content to the overdue_section
                            $('.completed_section').append(data?.html ||
                            ''); // Assuming `data.html` contains the HTML content
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading more posts:", error);
                            $('.spinner').hide();
                        }
                    });
                }
            });
            $("#see_moree_overdue_today").click(function() {
                if (nextinProgressTasks !== "") {
                    $('.spinner').show();
                    $.ajax({
                        url: nextinProgressTasks + "&status=Overdue_today",
                        type: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            // Optionally you can set nextPageUrlComplete to empty or reset it here
                            // nextPageUrlComplete = ''; 
                        },
                        success: function(data) {
                            $('.spinner').hide();
                            data?.nextPageUrl===null?$('#see_moree_overdue_today').hide():data?.nextPageUrl;
                            // Update nextPageUrlComplete with the URL for the next page, 
                            nextinProgressTasks = data?.nextPageUrl || '';

                            // Append the new content to the overdue_section
                            $('.see_moree_overdue_today').append(data?.html ||
                            ''); // Assuming `data.html` contains the HTML content
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading more posts:", error);
                            $('.spinner').hide();
                        }
                    });
                }
            });
            $("#see_moree_upcomming").click(function() {
                if (nextUpcommingTasks !== "") {
                    $('.spinner').show();
                    $.ajax({
                        url: nextUpcommingTasks + "&status=Upcomming",
                        type: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            // Optionally you can set nextPageUrlComplete to empty or reset it here
                            // nextPageUrlComplete = ''; 
                        },
                        success: function(data) {
                            $('.spinner').hide();
                            data?.nextPageUrl===null?$('#see_moree_upcomming').hide():data?.nextPageUrl;
                            // Update nextPageUrlComplete with the URL for the next page, 
                            nextUpcommingTasks = data?.nextPageUrl || '';
                            // Append the new content to the overdue_section
                            $('.upcommingcard').append(data?.html ||
                            ''); // Assuming `data.html` contains the HTML content
                        },
                        error: function(xhr, status, error) {
                            console.error("Error loading more posts:", error);
                            $('.spinner').hide();
                        }
                    });
                }
            });


            var completedTasksData = [];
            var allTasksData = [];
            let taskCounts = @json($taskcal);
            for (var month = 1; month <= 12; month++) {
                completedTasksData.push(taskCounts.monthlyCounts[month] ? taskCounts.monthlyCounts[month].completed : 0);
                allTasksData.push(taskCounts.monthlyCounts[month] ? taskCounts.monthlyCounts[month].total : 0);
            }
            var options = {
                chart: {
                    height: 280,
                    type: 'line',
                    stacked: false,
                    toolbar: {
                        show: false,
                    }
                },
                stroke: {
                    width: [0, 2, 5],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                    columnWidth: '20%',
                    endingShape: 'rounded'
                    }
                },
                colors: ['#556ee6', '#34c38f'],
                series: [{
                    name: 'Complete Tasks',
                    type: 'column',
                    data: completedTasksData,
                },
                {
                    name: 'All Tasks',
                    type: 'line',
                    data: allTasksData,
                }],
                fill: {
                        gradient: {
                            inverseColors: false,
                            shade: 'light',
                            type: "vertical",
                            opacityFrom: 0.85,
                            opacityTo: 0.55,
                            stops: [0, 100, 100, 100]
                        }
                },
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                markers: {
                    size: 0
                },

                yaxis: {
                    min: 0
                },
                }

                var chart = new ApexCharts(
                document.querySelector("#task-chart"),
                options
                );

                chart.render();
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
                    "Status": "Completed"
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

        $(document).on('click', '.dpagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetchTasks(page);
        });

        function fetchTasks(page = 1) {
            $.ajax({
                url: `/tasks?page=${page}`,
                success: function(data) {
                    $('.task-container').html(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        async function updateTask(indexid, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const taskDate = document.getElementById('date_val' + indexid);
            const formattedDateTime = convertDateTime(taskDate.value);

            const formData = {
                "data": [{
                    "Status": "Completed",
                    "Due_Date": formattedDateTime
                }]
            };

            $.ajax({
                url: "{{ route('update.task', ['id' => ':id']) }}".replace(':id', id),
                method: 'PUT',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.data && response.data[0] && response.data[0].status === "success") {
                        const button = document.getElementById('update_changes');
                        const update_message = document.getElementById('updated_message');
                        const modalTarget = document.getElementById('saveModalId' + indexid);

                        button.setAttribute('data-bs-target', `#${modalTarget.id}`);
                        update_message.textContent = response.data[0].message;
                        button.click();
                        window.location.reload();
                    } else {
                        showToastError("Update failed");
                    }
                },
                error: function(xhr, status, error) {
                    showToastError(xhr.responseJSON.error);
                    console.error(xhr.responseText);
                }
            });
        }


        async function deleteTask(id = "", isremoveselected = false) {
            let updateids = removeAllSelected();

            if (updateids === "" && id === 'remove_selected') {
                return;
            }
            if (isremoveselected) {
                id = undefined;
            }

            if (updateids !== "") {
                const shouldDelete = await saveForm();
                if (!shouldDelete) {
                    return;
                }
            }
            if (id === undefined) {
                id = updateids;
            }

            ids = id.replace(/(\b\w+\b)(?=.*\b\1\b)/g, '').replace(/^,|,$/g, '');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if (id) {
                $.ajax({
                    url: "{{ route('delete.task', ['id' => ':id']) }}".replace(':id', ids),
                    method: 'DELETE',
                    contentType: 'application/json',
                    dataType: 'JSON',
                    data: {
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        showToast("Deleted successfully");
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        showToastError(xhr.responseText);
                    }
                });
            }
        }

        function makeEditable(id, textfield, zohoID, textid) {
            if (textfield === "date") {
                let dateLocal = document.getElementById(textid);
                var text = dateLocal.value.trim();
                updateText(text, textfield, zohoID);
            }
        }

        function convertDateTime(dateTimeString) {
            var date = new Date(dateTimeString);
            var formattedDateTime = date.toISOString().slice(0, 19).replace('T', ' ');
            return formattedDateTime;
        }

        function updateText(newText, textfield, id, WhatSelectoneid = "", whoID = "") {
            let dateLocal;
            if (textfield === "date") {
                dateLocal = document.getElementById('date_local' + id);
                newText = newText?.substring(0, 10);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = {
                "data": [{
                    "Subject": textfield === "subject" ? newText : undefined,
                    "Due_Date": textfield === "date" ? newText : undefined,
                    "What_Id": WhatSelectoneid ? {
                        "id": WhatSelectoneid
                    } : undefined,
                    "Who_Id": whoID ? {
                        "id": whoID
                    } : undefined,
                    "$se_module": textfield === "Deals" || textfield === "Contacts" ? textfield : undefined,
                }]
            };

            formData.data[0] = Object.fromEntries(
                Object.entries(formData.data[0]).filter(([_, value]) => value !== undefined)
            );

            $.ajax({
                url: "{{ route('update.task', ['id' => ':id']) }}".replace(':id', id),
                method: 'PUT',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function(response) {
                    showToast(response?.data[0]?.message.toUpperCase());
                },
                error: function(xhr, status, error) {
                    showToastError(xhr.responseJSON.error);
                    console.error(xhr.responseText);
                }
            });
        }

        window.saveForm = function() {
            return new Promise((complete, failed) => {
                $('#confirmMessage').text('Are you sure you want to do this?');

                $('#confirmYes').off('click').on('click', () => {
                    $('#confirmModal').modal('hide');
                    complete(true);
                });

                $('#confirmNo').off('click').on('click', () => {
                    $('#confirmModal').modal('hide');
                    complete(false);
                });

                $('#confirmModal').modal('show');
            });
        }

        function removeAllSelected() {
            var checkboxes = document.querySelectorAll('input[class="task_checkbox"]');
            var ids = "";
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    if (checkbox.id !== "light-mode-switch" && checkbox.id !== "dark-rtl-mode-switch" && checkbox
                        .id !== "rtl-mode-switch" && checkbox.id !== "dark-mode-switch" && checkbox.id !==
                        "checkbox_all") {
                        ids += checkbox.id + ",";
                    }
                }
            });

            if (ids !== "") {
                ids = ids.replace(/,+(?=,|$)/g, "");
            }

            return ids;
        }
    </script>
@endsection
