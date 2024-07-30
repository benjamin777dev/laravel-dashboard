
<div class="card mb-2 shadow-sm border-0">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
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

            <div class="d-flex">
               {{-- // if completed, don't show buttons
                // but do show the completed date --}}
                @php
                    $taskzId = $task['zoho_task_id'];
                    $taskId = $task['id'];
                    $subject = $task['subject'];
                @endphp
                @if($task['status']!="Completed")
                <button class="btn btn-dark btn-sm me-2" onclick="closeTask('{{ $taskzId }}', '{{$taskId}}', '{{$subject}}')">
                    <i class="fas fa-check"></i> Done
                </button>
                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content">
            <div class="modal-header border-0 deleteModalHeaderDiv">
                <button type="button" id="task_modal_" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

<!-- Save Modal -->
<div class="modal fade" id="saveModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content">
            <div class="modal-header saveModalHeaderDiv border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body saveModalBodyDiv">
                <p class="saveModalBodyText" id="updated_message">Changes
                    have been saved</p>
            </div>
            <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                <div class="d-grid col-12">
                    <button type="button" class="btn btn-secondary saveModalBtn"
                        data-bs-dismiss="modal">
                        <i class="fas fa-check trashIcon"></i>
                        Understood
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Save Make Modal -->
<div class="modal fade" id="savemakeModalId{{ $task['id'] }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content">
            <div class="modal-header saveModalHeaderDiv border-0">
                {{-- <h5 class="modal-title">Modal title</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body saveModalBodyDiv">
                <p class="saveModalBodyText" id="updated_message_make">
                    Changes have been saved</p>
            </div>
            <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                <div class="d-grid col-12">
                    <button type="button" class="btn btn-secondary saveModalBtn"
                        data-bs-dismiss="modal">
                        <i class="fas fa-check trashIcon"></i>
                        Understood
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
