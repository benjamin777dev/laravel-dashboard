
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
                                        {{  $task->contactData->first_name ?? '' }} {{  $task->contactData->last_name ?? '' }}
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
            <div class="modal fade p-5" id="deleteModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered deleteModal">
                    <div class="modal-content">
                        <div class="modal-header border-0 deleteModalHeaderDiv">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close_modal_task_dash"></button>
                        </div>
                        <div class="modal-body deletemodalBodyDiv">
                            <p class="deleteModalBodyText">Please confirm you’d like to<br />delete this item.</p>
                        </div>
                        <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                            <div class="d-grid gap-2 col-5">
                                <button type="button" onclick="deleteTaskdash('{{ $task['zoho_task_id'] }}')" class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
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

    <script>
        window.onload = function(){
          window.deleteTaskdash = function(id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                try {
                    if (id) {
                        $.ajax({
                            url: "/delete-task/"+id,
                            method: 'DELETE', // Change to DELETE method
                            contentType: 'application/json',
                            dataType: 'JSON',
                            data: {
                                'id': id,
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                // Handle success response
                                window.fetchData();
                                showToast("deleted successfully");
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

    window.fetchData = function() {
        $('#spinner').show();
       let loadtask = true;
        // Make AJAX call
        $.ajax({
            url: '/upcomming-task',
            method: 'GET',
            dataType: 'html',
            success: function(data) {
                $('#spinner').hide();
                loadtask = false;
                $('.upcomming-task').html(data);

            },
            error: function(xhr, status, error) {
                // Handle errors
                loadtask = false;
                console.error('Error:', error);
            }
        });

    }

}
    </script>