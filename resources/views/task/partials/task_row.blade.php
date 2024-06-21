<tr>
    <td>
        <h5 class="text-truncate font-size-14 m-0">
            <a href="#" class="text-dark">{{ $task['subject'] ?? 'N/A' }}</a>
        </h5>
    </td>
    <td>
        @if ($task['related_to'] == 'Contacts' && isset($task->contactData->zoho_contact_id))
            <a href="https://zportal.coloradohomerealty.com/contacts-view/{{ $task->contactData->zoho_contact_id ?? '' }}">
                {{ $task->contactData->first_name ?? '' }} {{ $task->contactData->last_name ?? 'General' }}
            </a>
        @elseif ($task['related_to'] == 'Deals' && isset($task->dealData->zoho_deal_id))
            <a href="https://zportal.coloradohomerealty.com/pipeline-view/{{ $task->dealData->zoho_deal_id ?? '' }}">
                {{ $task->dealData->deal_name ?? 'General' }}
            </a>
        @else
            General
        @endif
    </td>
    <td>
        <input type="datetime-local" id="date_val{{ $task['zoho_task_id'] }}" onchange="makeEditable('{{ $task['id'] }}','date','{{ $task['zoho_task_id'] }}','date_val{{ $task['zoho_task_id'] }}')" value="{{ \Carbon\Carbon::parse($task['due_date'])->format('Y-m-d\TH:i') }}" />
    </td>
    <td>
        <div class="d-flex btn-save-del">
            <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                id="update_changes" data-bs-toggle="modal" onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')">
                <i class="fas fa-hdd plusicon"></i>
                Done
            </div>
            <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
                <i class="fas fa-trash-alt plusicon"></i>
                Delete
            </div>
        </div>
    </td>
</tr>

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
