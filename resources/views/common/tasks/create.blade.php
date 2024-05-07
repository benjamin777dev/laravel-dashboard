 <div class="modal fade" id="newTaskModalId{{ $deal['id'] }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content dtaskmodalContent">
            <div class="modal-header border-0">
                <p class="modal-title dHeaderText">Create New Tasks</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidation()"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body dtaskbody">
                <p class="ddetailsText">Details</p>
                <textarea name="subject" onkeyup="validateTextarea();" id="darea" rows="4" class="dtextarea"></textarea>
                <div id="subject_error" class="text-danger"></div>
                <p class="dRelatedText">Related to...</p>
                <div class="btn-group dmodalTaskDiv">
                    <select class="form-select dmodaltaskSelect" name="related_to"
                        aria-label="Select Transaction">
                        <option value="{{ $deal['zoho_deal_id'] }}" selected>
                            {{ $deal['deal_name'] }}
                        </option>
                    </select>
                </div>
                <p class="dDueText">Date due</p>
                <input type="date" name="due_date" class="dmodalInput" />
            </div>
            <div class="modal-footer ">
                <button type="button" onclick="addTask('{{ $deal['zoho_deal_id'] }}')" class="btn btn-secondary taskModalSaveBtn">
                    <i class="fas fa-save saveIcon"></i> Save Changes
                </button>

            </div>

        </div>
    </div>
</div>