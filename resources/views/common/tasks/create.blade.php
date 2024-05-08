@if(isset($deal) && is_object($deal))
 <div class="modal fade" id="newTaskModalId{{ $deal->id }}" tabindex="-1">
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
@elseif(isset($contact))

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
@else
<div class="modal fade" id="staticBackdropforTask" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Tasks</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidation()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="subject" onkeyup="validateTextarea()" id="subject" rows="4" class="dtextarea"></textarea>
                    <div id="task_error" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" id="related_to_task"
                            onchange="taskModuleSelected(this)" name="related_to_task" aria-label="Select Transaction">
                            <option value="">Please select one</option>
                            @foreach ($retrieveModuleData as $item)
                                @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                    <option value="{{ $item['api_name'] }}">{{ $item['api_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-select dmodaltaskSelect" id="taskSelect" name="related_to_parent"
                            aria-label="Select Transaction" style="display: none;">
                            <option value="">Please Select one</option>
                        </select>
                    </div>
                    <p class="dDueText">Date due</p>
                    <input type="date" name="due_date" class="dmodalInput" />
                </div>
                <div class="modal-footer ">
                    <button type="button" onclick="addTask()" class="btn btn-secondary taskModalSaveBtn">
                        <i class="fas fa-save saveIcon"></i> Save Changes
                    </button>

                </div>

            </div>
        </div>
    </div>
@endif
