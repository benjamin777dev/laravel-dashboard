<div class="modal fade" id="{{$targetId}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content">
            <div class="modal-header saveModalHeaderDiv border-0">
                {{-- <h5 class="modal-title">Modal title</h5> --}}
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