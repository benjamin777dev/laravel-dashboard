<div class="modal fade custom-confirm-modal" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">Please confirm you’d like to delete this item.</p>
            </div>
            <div class="modal-footer justify-content-evenly border-0">
                <div class="d-grid gap-2 col-5">
                    <button type="button" id="confirmYes" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-trash-alt"></i> Yes, delete
                    </button>
                </div>
                <div class="d-grid gap-2 col-5">
                    <button type="button" id="confirmNo" class="btn btn-primary" data-bs-dismiss="modal">
                        <img src="{{ URL::asset('/images/reply.svg') }}" alt="R"> No, go back
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>