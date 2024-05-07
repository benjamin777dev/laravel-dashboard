<div id="{{$targetId}}" class="modal">
    <!-- Modal content -->
    
        {{-- <span class="close"
            onclick="closeConfirmationModal('{{ $targetId }}')">&times;</span> --}}
        {{-- <p>Are you sure you want to delete?</p> --}}
        <!-- Add buttons for confirmation -->
        {{-- <button onclick="deleteNoteItem('{{ $targetId}}')">Yes</button>
        <button
            onclick="closeConfirmationModal('confirmModal{{ $targetId }}')">No</button> --}}
            <div class="modal-dialog modal-dialog-centered deleteModal">
                <div class="modal-content">
                    <div class="modal-header border-0 deleteModalHeaderDiv">
                        {{-- <h5 class="modal-title">Modal title</h5> --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" onclick="closeConfirmationModal('{{ $targetId }}')"></button>
                    </div>
                    <div class="modal-body deletemodalBodyDiv">
                        <p class="deleteModalBodyText">Please confirm you’d
                            like
                            to<br />
                            delete this item.</p>
                    </div>
                    <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                        <div class="d-grid gap-2 col-5">
                            <button type="button"
                            onclick="deleteNoteItem('{{ $targetId}}')"
                                class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                                <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                delete
                            </button>
                        </div>
                        <div class="d-grid gap-2 col-5">
                            <button type="button" onclick="closeConfirmationModal('{{ $targetId }}')">No,
                                go
                                back
                            </button>
                        </div>
                    </div>

                </div>
            </div>
</div>