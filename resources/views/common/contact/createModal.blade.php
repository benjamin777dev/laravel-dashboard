<div class="modal fade" id="createContactModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Quick Create: Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contact.create') }}" method="POST" onsubmit="enableCreateContactSelect()">
                    @csrf
                    @method('PUT')
                    {{-- First Name --}}
                    <div class="mb-3 row">
                            <label for="validationDefault01"
                            class="col-sm-2 col-form-label nplabelText text-end"><strong>Layout</strong></label>
                            <div class="col-sm-2 col-6 min-width-120" >
                            <select name="layout" class="form-select text-center" id="layout_design">
                                <option value="" readonly>None</option>
                                <option value="Agent" selected>Agent</option>
                                <option value="Standard">Standard</option>
                            </select>
                    </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="validationDefault01"
                            class="col-sm-3 col-form-label nplabelText text-end"><strong>First Name</strong></label>
                        <div class="col-sm-9">
                            <input type="text" name="first_name" value="{{ $contact['first_name'] }}"
                                placeholder="Enter First name" class="form-control npinputinfo"
                                id="validationDefault01">
                        </div>
                    </div>
                    {{-- Last Name --}}
                    <div class="mb-3 row">
                        <label for="validationDefault02"
                            class="col-sm-3 col-form-label nplabelText text-end"><strong>Last Name</strong></label>
                        <div class="col-sm-9">
                            <input type="text"
                                value="{{ $contact['last_name'] == 'CHR' ? '' : $contact['last_name'] }}"
                                name="last_name" placeholder="Enter Last name" class="form-control npinputinfo validate"
                                id="last_name">
                        </div>
                    </div>
                    {{-- Mobile --}}
                    <div class="mb-3 row">
                        <label for="validationDefault03"
                            class="col-sm-3 col-form-label nplabelText text-end"><strong>Mobile</strong></label>
                        <div class="col-sm-9">
                            <input type="text" value="{{ $contact['mobile'] }}" name="mobile"
                                class="form-control npinputinfo" placeholder="Enter Mobile Number"
                                id="validationDefault03">
                        </div>
                    </div>
                    {{-- Phone --}}
                    <div class="mb-3 row">
                        <label for="validationDefault04"
                            class="col-sm-3 col-form-label nplabelText text-end"><strong>Phone</strong></label>
                        <div class="col-sm-9">
                            <input type="text" value="{{ $contact['phone'] }}" name="phone"
                                class="form-control npinputinfo" placeholder="Enter Phone Number"
                                id="validationDefault04">
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="mb-3 row">
                        <label for="validationDefault05"
                            class="col-sm-3 col-form-label nplabelText text-end"><strong>Email</strong></label>
                        <div class="col-sm-9">
                            <input type="text" value="{{ $contact['email'] }}" name="email"
                                class="form-control npinputinfo" placeholder="Enter Email" id="validationDefault05">
                        </div>
                    </div>
                    {{-- Relationship Type --}}
                    <div class="mb-3 row">
                        <label for="validationDefault08"
                            class="col-sm-3 col-form-label nplabelText text-end"><strong>Relationship
                                Type</strong></label>
                        <div class="col-sm-9">
                            <select name="relationship_type" class="form-select npinputinfo" id="validationDefault08">
                                <option disabled value="">-None-</option>
                                <option value="Primary"
                                    {{ $contact->relationship_type === 'Primary' ? 'selected' : '' }}>Primary</option>
                                <option value="Secondary"
                                    {{ $contact->relationship_type === 'Secondary' ? 'selected' : '' }}>Secondary
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="validationDefault05"
                            class="col-sm-3 text-end col-form-label nplabelText"><strong>Groups</strong></label>
                        <div class="col-sm-9">
                            <select id="choices-multiple-remove-button" placeholder="Select up to 5 Groups" multiple>
                                @foreach ($groups as $group)
                                    @php
                                        $selected = ''; // Initialize variable to hold 'selected' attribute
                                        if (isset($contactsGroups[0]['groups'])) {
                                            foreach ($contactsGroups[0]['groups'] as $contactGroup) {
                                                if (
                                                    $group['zoho_group_id'] === $contactGroup['zoho_contact_group_id']
                                                ) {
                                                    $selected = 'selected'; // If IDs match, mark the option as selected
                                                    break; // Exit loop once a match is found
                                                }
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $group['zoho_group_id'] }}" {{ $selected }}>
                                        {{ $group['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <button type="button" class="btn btn-secondary taskModalSaveBtn"
                                onclick="submitContactRoles('')">
                                <i class="fas fa-save saveIcon"></i> Save Changes
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
