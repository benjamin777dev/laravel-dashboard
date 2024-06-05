<div class="modal fade" id="createContactModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg"
    role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Quick Create: Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contact.spouse.create', ['contactId' => $contact->id]) }}" id ="contact_spouse_create_form" method="POST">
                    @csrf
                    {{-- Layout --}}
                    <div class="mb-3 row">
                        <label for="layout_design" class="col-sm-2 col-form-label nplabelText text-end">
                            <strong>Layout</strong>
                        </label>
                        <div class="col-sm-2 col-6 min-width-120">
                            <select name="layout" class="form-select text-center" id="layout_design">
                                <option value="" readonly>None</option>
                                <option value="Agent" selected>Agent</option>
                                <option value="Standard">Standard</option>
                            </select>
                        </div>
                    </div>

                    {{-- First Name --}}
                    <div class="mb-3 row">
                        <label for="spouseFirstName" class="col-sm-3 col-form-label nplabelText text-end">
                            <strong>First Name</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="first_name" value="" placeholder="Enter First name" class="form-control npinputinfo" id="spouseFirstName">
                        </div>
                    </div>

                    {{-- Last Name --}}
                    <div class="mb-3 row">
                        <label for="spouseLastName" class="col-sm-3 col-form-label nplabelText text-end">
                            <strong>Last Name</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" value="" name="last_name" placeholder="Enter Last name" class="form-control npinputinfo validate" id="spouseLastName">
                        </div>
                    </div>

                    {{-- Mobile --}}
                    <div class="mb-3 row">
                        <label for="spouseMobile" class="col-sm-3 col-form-label nplabelText text-end">
                            <strong>Mobile</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" value="" name="mobile" class="form-control npinputinfo" placeholder="Enter Mobile Number" id="spouseMobile">
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3 row">
                        <label for="spousePhone" class="col-sm-3 col-form-label nplabelText text-end">
                            <strong>Phone</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" value="" name="phone" class="form-control npinputinfo" placeholder="Enter Phone Number" id="spousePhone">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3 row">
                        <label for="spouseEmail" class="col-sm-3 col-form-label nplabelText text-end">
                            <strong>Email</strong>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" value="" name="email" class="form-control npinputinfo" placeholder="Enter Email" id="spouseEmail">
                        </div>
                    </div>

                    {{-- Relationship Type --}}
                    <div class="mb-3 row">
                        <label for="spouseRelationType" class="col-sm-3 col-form-label nplabelText text-end">
                            <strong>Relationship Type</strong>
                        </label>
                        <div class="col-sm-9">
                            <select name="relationship_type" class="form-select npinputinfo" id="spouseRelationType">
                                <option disabled value="">-None-</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                            </select>
                        </div>
                    </div>

                    {{-- Groups --}}
                    <div class="mb-3 row">
                        <label for="choices-multiple-remove-button" class="col-sm-3 col-form-label nplabelText text-end">
                            <strong>Groups</strong>
                        </label>
                        <div class="col-sm-9">
                            <select id="choices-multiple-remove-button" placeholder="Select up to 5 Groups" multiple>
                                @foreach ($groups as $group)
                                    @php
                                        $selected = '';
                                        if (isset($contactsGroups[0]['groups'])) {
                                            foreach ($contactsGroups[0]['groups'] as $contactGroup) {
                                                if ($group['zoho_group_id'] === $contactGroup['zoho_contact_group_id']) {
                                                    $selected = 'selected';
                                                    break;
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
                            <button type="button" class="btn btn-secondary taskModalSaveBtn" id="spouse_submit_button" onclick="validateSpouseContactForm()">
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
 document.addEventListener('DOMContentLoaded', function() {
     var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            maxItemCount: 5,
            searchResultLimit: 5,
            renderChoiceLimit: 5,
        });
        let selectedGroupsArr = [];
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'selectedGroups';
        document.getElementById('choices-multiple-remove-button').addEventListener('change', function(event) {
            var selectedGroups = event.detail.value;
            if (!selectedGroupsArr.includes(selectedGroups)) {
                selectedGroupsArr.push(selectedGroups);
            } else {
                // If the value already exists, remove it from the array
                selectedGroupsArr = selectedGroupsArr.filter(item => item !== selectedGroups);
            }
            hiddenInput.value = JSON.stringify(selectedGroupsArr);
            console.log(selectedGroupsArr);

        });
        document.getElementById('contact_spouse_create_form').appendChild(hiddenInput);
 })    

    function validateSpouseContactForm() {
        let last_name = $("#spouseLastName").val();
        
        // let regex = /^[a-zA-Z ]{1,20}$/;
        if (last_name.trim() === "") {
            showToastError('Please enter last name')
            return false;
        }
        let submitbtn = $("#spouse_submit_button");
        console.log(submitbtn);
        submitbtn.attr("type", "submit");

    }
    </script>
