
 {{-- Note Modal --}}
  <div class="modal fade" onclick="event.preventDefault();"
  id="{{$targetId}}" data-bs-backdrop="static"
  data-bs-keyboard="false" data-custom="noteModal" tabindex="-1"
  aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered deleteModal">
      <div class="modal-content noteModal">
          <div class="modal-header border-0">
              <p class="modal-title dHeaderText">Note</p>
              <button type="button"
                  onclick="resetFormAndHideSelect('{{ $module['zoho_contact_id'] }}');"
                  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="noteForm{{ $module['zoho_contact_id'] }}"
              action="{{ route('save.note') . '?conID=' . $module['id'] }}" method="post">
              @csrf
              <div class="modal-body dtaskbody">
                  <p class="ddetailsText">Details</p>
                  <textarea name="note_text" id="note_text{{ $module['zoho_contact_id'] }}" rows="4" class="dtextarea"></textarea>
                  <div id="note_text_error{{ $module['zoho_contact_id'] }}"
                      class="text-danger"></div>
                  <p class="dRelatedText">Related to...</p>
                  <div class="btn-group dmodalTaskDiv">
                      <select class="form-select dmodaltaskSelect"
                          id="related_to{{ $module['zoho_contact_id'] }}"
                          onchange="moduleSelectedforContact(this,'{{ $module['zoho_contact_id'] }}')"
                          name="related_to" aria-label="Select Transaction">
                          <option value="">Please select one</option>
                          @foreach ($retrieveModuleData as $item)
                              @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                  <option value="{{ $item }}">{{ $item['api_name'] }}
                                  </option>
                              @endif
                          @endforeach
                      </select>
                      <select class="form-select dmodaltaskSelect"
                          id="taskSelect{{ $module['zoho_contact_id'] }}"
                          name="related_to_parent" aria-label="Select Transaction"
                          style="display: none;">
                          <option value="">Please Select one</option>
                      </select>
                  </div>
                  <div id="related_to_error{{ $module['zoho_contact_id'] }}"
                      class="text-danger"></div>
              </div>
              <div class="modal-footer dNoteFooter border-0">
                  <button type="button" id="validate-button{{ $module['zoho_contact_id'] }}"
                      onclick="validateFormc('submit','{{ $module['zoho_contact_id'] }}')"
                      class="btn btn-secondary dNoteModalmarkBtn">
                      <i class="fas fa-save saveIcon"></i> Add Note
                  </button>
              </div>
          </form>
      </div>
  </div>
</div>