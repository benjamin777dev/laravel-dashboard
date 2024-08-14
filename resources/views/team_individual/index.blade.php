@extends('layouts.master')

@section('title', 'Team & Individual Information')

@section('content')

<!-- Start Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-weight-bold">Team & Individual Information</h4>
        </div>
    </div>
</div>
<!-- End Page Title -->

<!-- KPIs Section -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Average Pipeline Probability</h6>
                <h2 class="font-weight-bold">{{ number_format($averagePipelineProbability, 2) }}%</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Average Commission %</h6>
                <h2 class="font-weight-bold">{{ number_format($averageCommPercentage, 2) }}%</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Average Sale Price</h6>
                <h2 class="font-weight-bold">${{ number_format($averageSalePrice, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Pipeline Value</h6>
                <h2 class="font-weight-bold">${{ number_format($pipelineValue, 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Income Goal</h6>
                <h2 class="font-weight-bold">${{ number_format($incomeGoal, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Contacts and Needs Section -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">ABCD Contacts (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $abcdContacts['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $abcdContacts['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Missing ABCD (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $missingAbcd['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $missingAbcd['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Needs Address (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $needsAddress['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $needsAddress['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Needs Phone (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $needsPhone['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $needsPhone['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Needs Email (This Year vs Last Year)</h6>
                <p>Current Year: <span class="font-weight-bold">{{ $needsEmail['currentYearCount'] }}</span></p>
                <p>Previous Year: <span class="text-muted">{{ $needsEmail['previousYearCount'] }}</span></p>
            </div>
        </div>
    </div>
</div>

<!-- Open Tasks Section -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">My Open Tasks</h5>
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Task Name</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Related To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($openTasks as $task)
                                <tr>
                                    <td>
                                        <h5 class="text-truncate font-size-14 m-0">
                                            <a href="#" class="text-dark">{{ $task->subject ?? 'N/A' }}</a>
                                        </h5>
                                    </td>
                                    <td>{{ $task->description ?? 'No description provided' }}</td>
                                    <td>{{ isset($task->due_date) ? $task->due_date->format('Y-m-d') : '' }}</td>
                                    <td>
                                        @if ($task['related_to'] == 'Contacts' && isset($task->contactData->zoho_contact_id))
                                            <span>Related to: <a href="{{ url('/contacts-view/' . $task->contactData->id ?? '') }}" class="text-primary">
                                                {{ $task->contactData->first_name ?? '' }} {{ $task->contactData->last_name ?? 'General' }}
                                            </a></span>
                                        @elseif ($task['related_to'] == 'Deals' && isset($task->dealData->zoho_deal_id))
                                            <span>Related to: <a href="{{ url('/pipeline-view/' . $task->dealData->id ?? '') }}" class="text-primary">
                                                {{ $task->dealData->deal_name ?? 'General' }}
                                            </a></span>
                                        @else
                                            <span>Related to: General</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task['status'] != "Completed")
                                            <button class="btn btn-dark btn-sm me-2" onclick="closeTask('{{ $task['zoho_task_id'] }}', '{{ $task->id }}', '{{ $task->subject }}')">
                                                <i class="fas fa-check"></i> Done
                                            </button>
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        @else
                                            Completed on {{ \Carbon\Carbon::parse($task->completion_date)->format('M d, Y') ?? 'N/A' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @foreach($openTasks as $task)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="text-muted">Task Name</h6>
                                <p>{{ $task->subject }}</p>
                                <h6 class="text-muted">Description</h6>
                                <p>{{ $task->description ?? 'No description provided' }}</p>
                                <h6 class="text-muted">Due Date</h6>
                                <p>{{ isset($task->due_date) ? $task->due_date->format('Y-m-d') : '' }}</p>
                                <h6 class="text-muted">Related To</h6>
                                <p>
                                    @if ($task['related_to'] == 'Contacts' && isset($task->contactData->zoho_contact_id))
                                        <a href="{{ url('/contacts-view/' . $task->contactData->id ?? '') }}" class="text-primary">
                                            {{ $task->contactData->first_name ?? '' }} {{ $task->contactData->last_name ?? '' }}
                                        </a>
                                    @elseif ($task['related_to'] == 'Deals' && isset($task->dealData->zoho_deal_id))
                                        <a href="{{ url('/pipeline-view/' . $task->dealData->id ?? '') }}" class="text-primary">
                                            {{ $task->dealData->deal_name ?? 'General' }}
                                        </a>
                                    @else
                                        General
                                    @endif
                                </p>
                                <div class="d-flex">
                                    @if($task['status'] != "Completed")
                                        <button class="btn btn-dark btn-sm me-2" onclick="closeTask('{{ $task['zoho_task_id'] }}', '{{ $task->id }}', '{{ $task->subject }}')">
                                            <i class="fas fa-check"></i> Done
                                        </button>
                                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    @else
                                        Completed on {{ \Carbon\Carbon::parse($task->completion_date)->format('M d, Y') ?? 'N/A' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Transactions and Volume Section -->
<div class="row">
    <div class="col-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Transactions - Past 4 Quarters</h5>
                <canvas id="transactionsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Volume - Past 4 Quarters</h5>
                <canvas id="volumeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Pipeline by Month Section -->
<div class="row">
    <div class="col-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">Pipeline $ by Month</h5>
                <canvas id="pipelineChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-muted">My Groups</h5>
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Group</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myGroups as $group)
                                <tr>
                                    <td>{{ $group->abcd }}</td>
                                    <td>{{ $group->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @foreach($myGroups as $group)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="text-muted">Group</h6>
                                <p>{{ $group->abcd }}</p>
                                <h6 class="text-muted">Count</h6>
                                <p>{{ $group->count }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.modals')

<script>
    // Transactions - Past 4 Quarters Chart
    var ctx = document.getElementById('transactionsChart').getContext('2d');
    var transactionsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($transactionsPastFourQuarters->pluck('quarter')),
            datasets: [{
                label: 'Transactions',
                data: @json($transactionsPastFourQuarters->pluck('count')),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Volume - Past 4 Quarters Chart
    var ctx2 = document.getElementById('volumeChart').getContext('2d');
    var volumeChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($volumePastFourQuarters->pluck('quarter')),
            datasets: [{
                label: 'Volume',
                data: @json($volumePastFourQuarters->pluck('total')),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Pipeline $ by Month Chart
    var ctx3 = document.getElementById('pipelineChart').getContext('2d');
    var pipelineChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: @json($pipelineByMonth->pluck('month')),
            datasets: [{
                label: 'Pipeline $',
                data: @json($pipelineByMonth->pluck('total')),
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Close task function
    function closeTask(taskId, indexId, subject) {
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

        $.ajax({
            url: "{{ route('update.task', ['id' => ':id']) }}".replace(':id', taskId),
            method: 'PUT',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response?.data[0]?.status === "success") {
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

    // Delete task function
    async function deleteTask(taskId = "", isRemoveSelected = false) {
        let idsToDelete = taskId || removeAllSelected();

        if (!idsToDelete) return;

        const shouldDelete = await confirmDeletion();
        if (!shouldDelete) return;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('delete.task', ['id' => ':id']) }}".replace(':id', idsToDelete),
            method: 'DELETE',
            contentType: 'application/json',
            dataType: 'json',
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

    // Confirm deletion function
    function confirmDeletion() {
        return new Promise((resolve) => {
            $('#confirmModal').modal('show');

            $('#confirmYes').off('click').on('click', () => {
                $('#confirmModal').modal('hide');
                resolve(true);
            });

            $('#confirmNo').off('click').on('click', () => {
                $('#confirmModal').modal('hide');
                resolve(false);
            });
        });
    }

    // Remove all selected tasks (checkboxes)
    function removeAllSelected() {
        let ids = "";
        $('input.task_checkbox:checked').each(function() {
            if (!["light-mode-switch", "dark-rtl-mode-switch", "rtl-mode-switch", "dark-mode-switch", "checkbox_all"].includes(this.id)) {
                ids += `${this.id},`;
            }
        });
        return ids.replace(/,+$/, '');
    }
</script>

@endsection
