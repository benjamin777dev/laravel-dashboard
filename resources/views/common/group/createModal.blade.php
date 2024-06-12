<div class="modal fade" id="createGroupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg"
    role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('group.create') }}" id ="group_create_form" method="POST" onsubmit="return validateAddGroupForm()">
                    @csrf

                    {{-- Group Name: --}}
                    <div class="mb-3 row">
                        <label for="groupName" class="col-sm-3 col-form-label nplabelText text-end">
                            <strong>Enter Group Name:</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="group_name" value="" placeholder="Enter Group Name" class="form-control npinputinfo" id="groupName">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div>
                            <button type="submit" class="btn btn-secondary taskModalSaveBtn" id="group_submit_button">
                                <i class="fas fa-save saveIcon"></i> Save Changes
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>

    function validateAddGroupForm() {
        let group_name = ($("#groupName").val()).trim();

        // let regex = /^[a-zA-Z ]{1,20}$/;
        if (group_name === "") {
            showToastError('Please enter group name')
            return false;
        }

        // check if the group name already exists
        let groupNames = @json($groups);
        for (let i = 0; i < groupNames.length; i++) {
            if (groupNames[i].name === group_name) {
                showToastError('Group name already exists')
                return false;
            }
        }

        return true;
    }
    </script>
