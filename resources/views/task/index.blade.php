@extends('layouts.master')

@section('title') @lang('Task_List') @endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Tasks @endslot
        @slot('title') Task List @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <!-- Upcoming Tasks -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Upcoming</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <tbody>
                                @if (count($upcomingTasks) > 0)
                                    @foreach ($upcomingTasks as $task)
                                        @include('task.partials.task_row', ['task' => $task])
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">No upcoming tasks found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- In Progress Tasks -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Due Today</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <tbody>
                                @if (count($inProgressTasks) > 0)
                                    @foreach ($inProgressTasks as $task)
                                        @include('task.partials.task_row', ['task' => $task])
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">No tasks in progress found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Overdue Tasks -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Overdue</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <tbody>
                                @if (count($overdueTasks) > 0)
                                    @foreach ($overdueTasks as $task)
                                        @include('task.partials.task_row', ['task' => $task])
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">No overdue tasks found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Completed</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <tbody>
                                @if (count($completedTasks) > 0)
                                    @foreach ($completedTasks as $task)
                                        @include('task.partials.task_row', ['task' => $task])
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">No completed tasks found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
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
                                                    <span>Related to: {{ $task->contactData->first_name ?? '' }} {{ $task->contactData->last_name ?? 'General' }}</span>
                                                @elseif ($task['related_to'] == 'Deals')
                                                    <span>Related to: {{ $task->dealData->deal_name ?? 'General' }}</span>
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
    <!-- end row -->

@endsection

@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- dashboard init -->
    <script src="{{ URL::asset('build/js/pages/tasklist.init.js') }}"></script>


    <script>

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
            url: "https://zportal.coloradohomerealty.com/update-task/:id".replace(':id', id),
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
                showToastError("Something went wrong");
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
                url: `https://zportal.coloradohomerealty.com/update-task/${id}`,
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
                    showToastError("Something went wrong");
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
                    "What_Id": WhatSelectoneid ? { "id": WhatSelectoneid } : undefined,
                    "Who_Id": whoID ? { "id": whoID } : undefined,
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
                    showToastError(error);
                    console.error(xhr.responseText);
                }
            });
        }

        function saveForm() {
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

