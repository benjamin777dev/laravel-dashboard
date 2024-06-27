<div class="row">
    <form class="row" id="contact_create_form" action="{{ route('update.contact', ['id' => $contact->id]) }}"
        method="POST" onsubmit="return validateContactForm();">
        @csrf
        @method('PUT')
        {{-- Contact Details --}}
        <div class="col-md-6 col-sm-12"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
            <p class="npinfoText">Contact Details</p>
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="validationDefault01" class="form-label nplabelText">First Name</label>
                    <input type="text" name="first_name" value="{{ $contact['first_name'] }}"
                        placeholder="Enter First name" class="form-control npinputinfo" id="validationDefault01">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault02" class="form-label nplabelText">Last Name</label>
                    <input type="text" value="{{ $contact['last_name'] == 'CHR' ? '' : $contact['last_name'] }}"
                        name="last_name" placeholder="Enter Last name" class="form-control npinputinfo validate"
                        id="last_name">
                </div>

                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Mobile</label>
                    <input type="text" value="{{ $contact['mobile'] }}" name="mobile"
                        class="form-control npinputinfo" placeholder="Enter Mobile Number" id="validationDefault03">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Phone</label>
                    <input type="text" value="{{ $contact['phone'] }}" name="phone"
                        class="form-control npinputinfo" placeholder="Enter Phone Number" id="validationDefault03">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Email</label>
                    <input type="text" value="{{ $contact['email'] }}" name="email"
                        class="form-control npinputinfo" placeholder="Enter Email" id="validationDefault03">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Market Area</label>
                    <select name="market_area" class="form-select npinputinfo" id="validationDefault03">
                        <option disabled value="">-None-</option>
                        <option value="Denver" {{ $contact->market_area === 'Denver' ? 'selected' : '' }}>
                            Denver</option>
                        <option value="Colorado Springs"
                            {{ $contact->market_area === 'Colorado Springs' ? 'selected' : '' }}>
                            Colorado Springs</option>
                    </select>

                </div>
                <div>
                    <div>
                        @php
                            $abcd = ['A+', 'A', 'B', 'C', 'D'];
                        @endphp
                        <label for="validationDefault02" class="form-label nplabelText">ABCD Status</label>
                        <select name="abcd_class" class="form-select npinputinfo" id="validationDefault04">
                            <option selected disabled value="">-None-</option>
                            @foreach ($abcd as $abcdIndex)
                                <option value="{{ $abcdIndex }}"
                                    {{ $contact['abcd'] == $abcdIndex ? 'selected' : '' }}>
                                    {{ $abcdIndex }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row d-flex justify-content-center mt-100">
                        <div>
                            <label for="validationDefault02" class="form-label nplabelText mt-2">Groups</label>
                            <select id="choices-multiple-remove-button" placeholder="Select Groups"
                                multiple>
                                @foreach ($groups as $group)
                                    @php
                                        $selected = ''; // Initialize variable to hold 'selected' attribute
                                        if (isset($contactsGroups[0]['groups'])) {
                                            foreach ($contactsGroups[0]['groups'] as $contactGroup) {
                                                if (
                                                    $group['zoho_group_id'] ===
                                                    $contactGroup['zoho_contact_group_id']
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

                </div>
            </div>
        </div>
        {{-- Contact Preferences --}}
        <div class="col-md-6 col-sm-12"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
            <p class="npinfoText">Contact Preferences</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="validationDefault01" class="form-label nplabelText">Relationship Type</label>
                    <select name="relationship_type" class="form-select npinputinfo" id="validationDefault04">
                        <option disabled value="">-None-</option>
                        <option value="Primary" {{ $contact->relationship_type === 'Primary' ? 'selected' : '' }}>
                            Primary</option>
                        <option value="Secondary"
                            {{ $contact->relationship_type === 'Secondary' ? 'selected' : '' }}>
                            Secondary</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault14" class="form-label nplabelText">Referred By</label>
                    <select name="reffered_by" type="text" class="form-select npinputinfo"
                        id="validationDefault14" style="display:none">
                        @php
                            $referred_id = $contact['referred_id'];
                        @endphp
                        @if (!empty($contacts))
                        <option value="" disabled {{ empty( $referred_id) ? 'selected' : '' }}>Please select
                        </option>
                            @foreach ($contacts as $contactRef)
                                <option
                                    value="{{ json_encode(['id' => $contactRef['zoho_contact_id'], 'Full_Name' => $contactRef['first_name'] . ' ' . $contactRef['last_name']]) }}"
                                    {{ $contactRef['zoho_contact_id'] == $referred_id ? 'selected' : '' }}>
                                    {{ $contactRef['first_name'] }} {{ $contactRef['last_name'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-6">
                    @php
                        $leadSources = [
                            'Activity',
                            'CHR Lead',
                            'Class',
                            'Client Reviews',
                            'Event',
                            'Family',
                            'Farm',
                            'Friend',
                            'Networking Group',
                            'Office Walk In',
                            'Online Lead',
                            'Open House',
                            'Past Client',
                            'Referral Agent',
                            'Referral Business Partner',
                            'Referral Client',
                            'Referral - Family/Friend',
                            'Sign Call',
                            'Social Media',
                            'Sphere',
                        ];
                    @endphp
                    <label for="validationDefault03" class="form-label nplabelText">Lead Source</label>
                    <select name="lead_source" type="text" class="form-select npinputinfo"
                        id="validationDefault03">
                        <option value="">-None-</option>
                        @foreach ($leadSources as $leadSource)
                            <option value="{{ $leadSource }}"
                                {{ $contact['Lead_Source'] == $leadSource ? 'selected' : '' }}>{{ $leadSource }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Lead source details</label>
                    <input type="text" value="{{ $contact['lead_source_detail'] }}" name="lead_source_detail"
                        class="form-control npinputinfo" id="validationDefault03">
                </div>
                {{-- <div class="col-md-6">
                <label for="validationDefault03" class="form-label nplabelText">Envelope Salutation</label>
                <input type="text" value="{{ $contact['envelope_salutation'] }}" name="envelope_salutation"
                    class="form-control npinputinfo"  id="validationDefault03">
            </div> --}}
                <div class="col-md-6">

                    <label for="validationDefault13" class="form-label nplabelText">Spouse/Partner</label>
                    <select type="text" name="spouse_partner" class="form-select npinputinfo"
                        id="validationDefault13" >
                        <option value="" disabled {{ empty( $spouseContact) ? 'selected' : '' }}>Please select
                        </option>
                    @if (!empty($spouseContact) && is_array($spouseContact))
                        <option value="{{ json_encode(['id' => $spouseContact['zoho_contact_id'], 'Full_Name' => $spouseContact['first_name'] . ' ' . $spouseContact['last_name']]) }}" selected>
                            {{ $spouseContact['first_name'] }} {{ $spouseContact['last_name'] }}
                        </option>
                    @endif
                    @if (!empty($contacts))
                        @foreach ($contacts as $contactrefs)
                            <option
                                value="{{ json_encode(['id' => $contactrefs['zoho_contact_id'], 'Full_Name' => $contactrefs['first_name'] . ' ' . $contactrefs['last_name']]) }}"
                                >
                                {{ $contactrefs['first_name'] }} {{ $contactrefs['last_name'] }}
                            </option>
                        @endforeach
                    @endif

                    </select>
                </div>
            </div>
        </div>
        {{-- Primary Contact’s Address --}}
        <div class="col-md-6 col-sm-12"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
            <p class="npinfoText">Mailing Address</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="validationDefault01" class="form-label nplabelText">Address line 1</label>
                    <input type="text" value="{{ $contact['mailing_address'] }}" name="address_line1"
                        class="form-control npinputinfo" id="validationDefault03">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault02" class="form-label nplabelText">Address line 2</label>
                    <input type="text" name="address_line2" class="form-control npinputinfo"
                        id="validationDefault02">
                </div>

                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">City</label>
                    <input type="text" value="{{ $contact['mailing_city'] }}" placeholder="Enter City"
                        name="city" class="form-control npinputinfo" id="validationDefault03">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">State</label>
                    <input type="text" value="{{ $contact['mailing_state'] }}" placeholder="Enter State"
                        name="state" class="form-control npinputinfo" id="validationDefault04">
                    {{-- <select name="state" class="form-select npinputinfo" id="validationDefault04">
                    <option selected disabled value=""></option>
                    <option>...</option>
                </select> --}}
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">ZIP code</label>
                    <input type="text" value="{{ $contact['mailing_zip'] }}" name="zip_code"
                        class="form-control npinputinfo" id="validationDefault03">
                </div>
                
                {{-- <div class="col-md-6">
                <input class="form-check-input" name="primary_address" type="checkbox" value="false"
                    id="primary_address">
                <label class="form-check-label nplabelText" for="flexCheckChecked">
                    Primary Address
                </label>
            </div>
            <div class="col-md-6">
                <input class="form-check-input" name="secondry_address" id="secondry_address" type="checkbox"
                    value="false" id="flexCheckChecked">
                <label class="form-check-label nplabelText" for="flexCheckChecked">
                    Secondary Address
                </label>
            </div> --}}
            </div>
        </div>

        {{-- Business Information --}}
        <div class="col-md-6 col-sm-12"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
            <p class="npinfoText">Business Information</p>
            <div class="row g-3">
                <div>
                    <label for="validationDefault01" class="form-label nplabelText">Business Name</label>
                    <input type="text" value="{{ $contact['business_name'] }}" name="business_name"
                        class="form-control npinputinfo" id="validationDefault02">
                </div>

                <div>
                    <label for="validationDefault02" class="form-label nplabelText">Business
                        Information</label>
                    <textarea name="business_information" type="text" rows="4" class="form-control nctextarea"
                        id="validationDefault02">{{ $contact['business_information'] }}</textarea>
                </div>

                <div style="display:none">
                    <label for="contactOwner" class="form-label nplabelText">Contact Owner</label>

                    <select name="contactOwner" class="form-select npinputinfo" id="contactOwner"
                        disabled>

                        <option
                            value="{{ json_encode(['id' => $users['root_user_id'], 'Full_Name' => $users['name']]) }}"
                            selected>
                            {{ $users['name'] }}
                        </option>
                    </select>
                </div>

            </div>
        </div>


        <div>
            <button class="submit_button btn btn-primary" id="submit_button" type="submit">Create</button>
        </div>
    </form>
</div>
{{-- view group secton --}}
<div class="modal fade" id="staticBackdropforViewGroup" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered deleteModal">
        <div class="modal-content noteModal">
            <div class="modal-header border-0">
                <p class="modal-title dHeaderText">Groups</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body dtaskbody">
                <p class="ddetailsText">Assign the Groups...</p>
                <div class="checkBox-Design">
                    <input type="checkbox" />
                    <p class="mb-0">GroupOne</p>


                </div>
                <div id="related_to_error" class="text-danger"></div>
            </div>
            <div class="modal-footer dNoteFooter border-0">
                <button type="button" id="validate-button" onclick="validateFormc()"
                    class="btn btn-secondary dNoteModalmarkBtn">
                    <i class="fas fa-save saveIcon"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>
@include('common.contact.createModal', [
    'contact' => $contact,
    'retrieveModuleData' => $retrieveModuleData,
    'type' => 'Contacts',
])
<script>
    $(document).ready(function() {
        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
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
       
        document.getElementById('contact_create_form').appendChild(hiddenInput);
        var getReffered = $('#validationDefault14')
        getReffered.select2({
            placeholder: 'Search...',
        })
        var getSpouse = $('#validationDefault13');
        getSpouse.select2({
            placeholder: 'Search...',
        }).on('select2:open', () => {
            // Remove existing button to avoid duplicates
            $('.select2-results .new-contact-btn').remove();

            // Append the button
            $(".select2-results").prepend(
                '<div class="new-contact-btn" onclick="openContactModal()" style="padding: 6px; height: 20px; display: inline-table; color: black; cursor: pointer; background-color: lightgray; width: 100%"><i class="fas fa-plus plusicon"></i> New Spouse</div>'
            );
        });

        window.openContactModal = function() {
            $("#createContactModal").modal('show');
        }
        $('#contact_create_form').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            // Serialize form data
            var formData = $(this).serialize();
            // console.log(JSON.parse(formData));
            // return;
            // AJAX post request
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    showToast("Contact create successfully")
                    window.location.href = "{{route('contacts.show', ['contactId' => $contact->id])}}"
                },
                error: function(xhr, status, error) {
                    console.error('Error in contact creation:', xhr.responseJSON);
                    showToastError(xhr.responseJSON?.message)
                    getCreateForm();
                }
            });
        });
    });
    function validateContactForm() {
        let last_name = $("#last_name").val();
        if (last_name.trim() === "") {
            showToastError('Please enter last name');
            return false;
        }
        $('#contactOwner').removeAttr('disabled');
        return true;
    }

</script>