<!-- Delete Modal -->
@foreach($openTasks as $task)
<div class="modal fade p-5" id="deleteModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
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
@endforeach

<!-- Save Modal -->
@foreach($openTasks as $task)
<div class="modal fade p-5" id="saveModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
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
@endforeach
