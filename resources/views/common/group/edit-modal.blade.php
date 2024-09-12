<div class="modal fade p-5" id="editGroupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg"
    role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Groups</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach ($groups as $group)
                    <div class="mb-3 row">
                        <div class="col-sm-8">
                            <input type="text" name="edit_group_name" placeholder="Enter Group Name" class="form-control npinputinfo" data-id="{{ $group->id }}" value="{{ $group->name }}"/>
                        </div>
                        <div class="col-sm-2">
                            <i class="fas fa-trash-alt deleteIcon" onclick="openConfirmModal({{ $group->id }})"></i>
                        </div>
                    </div>
                    <div id="confirmModal{{$group->id}}" class="modal">
                        <!-- Modal content -->
                        <div class="modal-dialog modal-dialog-centered deleteModal">
                            <div class="modal-content">
                                <div class="modal-header border-0 deleteModalHeaderDiv">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close" onclick="closeConfirmationModal('{{ $group->id }}')"></button>
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
                                        onclick="deleteGroup(this, {{ $group->id }}, '{{ $group->name }}')"
                                            class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                                            <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                            delete
                                        </button>
                                    </div>
                                    <div class="d-grid gap-2 col-5">
                                        <button type="button" onclick="closeConfirmationModal('{{ $group->id }}')">No,
                                            go
                                            back
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="modal-footer">
                    <div>
                        <button type="button" class="btn btn-secondary taskModalSaveBtn" data-bs-dismiss="modal">
                            <i class="fas fa-save saveIcon"></i> Done
                        </button>
                    </div>
                </div>
            </div>

             
        </div>
    </div>
</div>

<script>
    var changesmade = false;
    document.addEventListener('DOMContentLoaded', function () {
        $('input[name="edit_group_name"]').on('change', function () {
            let id = $(this).data('id');
            let group_name = $(this).val().trim();
            let url = "{{ route('group.edit', ['groupId' => 'groupId']) }}";
            url = url.replace('groupId', id);

            if (group_name === "") {
                showToastError('Please enter group name')
                return false;
            }

            if (validateEditGroupForm(id)) {
                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "group_name": group_name,
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            showToast(response.message);
                            changesmade = true;
                            $('#editGroupModal').modal('hide');
                            if (changesmade) {
                                window.location.reload();
                            }
                        } else {
                            showToastError(response.message);
                        }
                    },
                    error: function (response) {
                        showToastError('An error occurred. Please try again later.');
                    }
                });
            }
        });
    });

    window.openConfirmModal = function(id) {
        $('#confirmModal'+id).modal('show');
        console.log("DELETEGROUPID",id);
    }
    window.closeConfirmationModal = function(id) {
        $('#confirmModal'+id).modal('hide');
        console.log("DELETEGROUPID",id);
    }

    function validateEditGroupForm(id, group_name) {
        // let regex = /^[a-zA-Z ]{1,20}$/;
        if (group_name === "") {
            showToastError('Please enter group name')
            return false;
        }

        // check if the group name already exists
        let groupNames = @json($groups);
        for (let i = 0; i < groupNames.length; i++) {
            if (groupNames[i].name === group_name && groupNames[i].id !== id) {
                showToastError('Group name already exists')
                return false;
            }
        }

        return true;
    }

    function deleteGroup(t, group_id, group_name) {
        let url = "{{ route('group.delete', ['groupId' => 'groupId']) }}";
        url = url.replace('groupId', group_id);

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.status === 'success') {
                    showToast(response.message);
                    // remove parent 'row; class div
                    $(t).closest('.row').remove();
                    changesmade = true;
                } else {
                    showToastError(response.message);
                }
            },
            error: function (response) {
                showToastError('An error occurred. Please try again later.');
            }
        });
    }

    // close modal on click taskModalSaveBtn
    $('.taskModalSaveBtn').on('click', function () {
        $('#editGroupModal').modal('hide');
        if (changesmade) {
            window.location.reload();
        }
    });

    //reload page on modal close if changes were made
    $('#editGroupModal').on('hidden.bs.modal', function () {
        console.log(changesmade);
        if (changesmade) {
            window.location.reload();
        }
    });

</script>
