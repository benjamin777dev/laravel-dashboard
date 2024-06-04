<div class="modal fade " id="createContactModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content contact-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title">Quick Create: Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contact.create') }}" method="POST" onsubmit="enableCreateContactSelect()">
                    @csrf
                    @method('PUT')
                    <div >
                        <label for="validationDefault08" class="form-label nplabelText">Layout</label>
                        <select name="relationship_type" class="form-select npinputinfo" id="validationDefault08">
                            <option disabled value="">-None-</option>
                            <option value="Agent" {{ $contact->relationship_type === 'Agent' ? 'selected' : '' }}>
                                Agent</option>
                            <option value="Standard" {{ $contact->relationship_type === 'Standard' ? 'selected' : '' }}>
                                Standard</option>
                        </select>
                    </div>
                    {{-- Contact Details --}}
                    <div 
                        style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                            <div >
                                <label for="validationDefault01" class="form-label nplabelText">First Name</label>
                                <input type="text" name="first_name" value="{{ $contact['first_name'] }}"
                                    placeholder="Enter First name" class="form-control npinputinfo" id="validationDefault01">
                            </div>
                            <div >
                                <label for="validationDefault02" class="form-label nplabelText">Last Name</label>
                                <input type="text" value="{{ $contact['last_name'] == 'CHR' ? '' : $contact['last_name'] }}" name="last_name"
                                    placeholder="Enter Last name"
                                    class="form-control npinputinfo validate" id="last_name">
                            </div>

                            <div >
                                <label for="validationDefault03" class="form-label nplabelText">Mobile</label>
                                <input type="text" value="{{ $contact['mobile'] }}" name="mobile"
                                    class="form-control npinputinfo" placeholder="Enter Mobile Number" id="validationDefault03">
                            </div>
                            <div >
                                <label for="validationDefault04" class="form-label nplabelText">Phone</label>
                                <input type="text" value="{{ $contact['phone'] }}" name="phone" class="form-control npinputinfo"
                                    placeholder="Enter Phone Number" id="validationDefault04">
                            </div>
                            <div >
                                <label for="validationDefault05" class="form-label nplabelText">Email</label>
                                <input type="text" value="{{ $contact['email'] }}" name="email" class="form-control npinputinfo"
                                    placeholder="Enter Email" id="validationDefault05">
                            </div>
                            <div >
                                <label for="validationDefault08" class="form-label nplabelText">Relationship Type</label>
                                <select name="relationship_type" class="form-select npinputinfo" id="validationDefault08">
                                    <option disabled value="">-None-</option>
                                    <option value="Primary" {{ $contact->relationship_type === 'Primary' ? 'selected' : '' }}>
                                        Primary</option>
                                    <option value="Secondary" {{ $contact->relationship_type === 'Secondary' ? 'selected' : '' }}>
                                        Secondary</option>
                                </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary taskModalSaveBtn" onclick="submitContactRoles('')">
                            <i class="fas fa-save saveIcon"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>