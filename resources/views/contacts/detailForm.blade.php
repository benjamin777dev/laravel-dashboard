
<div class="row">
    <div class='card'>
    <form class="row" id="contact_detail_form" action="{{ route('update.contact', ['id' => $contact->id]) }}"
        method="POST" onsubmit="return validateContactForm();">
        @csrf
        @method('PUT')
        {{-- Contact Details --}}
        <div class="col-md-6 col-sm-12"
            >
            <div id="popup" class="text-danger"></div>
            <p class="npinfoText p-2">Contact Details</p>
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
                    <label for="validationDefault04" class="form-label nplabelText">Phone</label>
                    <input type="text" value="{{ $contact['phone'] }}" name="phone"
                        class="form-control npinputinfo" placeholder="Enter Phone Number"
                        id="validationDefault04">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault05" class="form-label nplabelText">Email</label>
                    <div class="input-group">
                        <input type="text" value="{{ $contact['email'] }} " name="email"
                        class="form-control npinputinfo" placeholder="Enter Email" id="validationDefault05"> 
                        <div class="input-group-text" onclick="openEmail()">
                            <i class="mdi mdi-send ms-1"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault06" class="form-label nplabelText">Market Area</label>
                    <select name="market_area" class="form-select npinputinfo" id="validationDefault06">
                        <option disabled value="">-None-</option>
                        <option value="Denver" {{ $contact->market_area === 'Denver' ? 'selected' : '' }}>
                            Denver</option>
                        <option value="Colorado Springs"
                            {{ $contact->market_area === 'Colorado Springs' ? 'selected' : '' }}>
                            Colorado Springs</option>
                    </select>

                </div>
                <div>
                    @php
                        $abcd = ['A+', 'A', 'B', 'C', 'D'];
                    @endphp
                    <label for="validationDefault07" class="form-label nplabelText">ABCD Status</label>
                    <select name="abcd_class" class="form-select npinputinfo" id="validationDefault07">
                        <option selected disabled value="">-None-</option>
                        @foreach ($abcd as $abcdIndex)
                            <option value="{{ $abcdIndex }}"
                                {{ $contact['abcd'] == $abcdIndex ? 'selected' : '' }}>{{ $abcdIndex }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row d-flex justify-content-center mt-100">
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText mt-2">Groups</label>
                        <select id="choices-multiple-remove-button_test" placeholder="Select Groups" multiple>
                            @foreach ($groups as $group)
                                @php
                                    $selected = ''; // Initialize variable to hold 'selected' attribute
                                    $contactGroupData = null; // Initialize variable to hold group data
                                    if (isset($contact['groupsData'])) {
                                        foreach ($contact['groupsData'] as $contactGroup) {
                                            if ($group['id'] === $contactGroup['groupId']) {
                                                $selected = 'selected'; // If IDs match, mark the option as selected
                                                $contactGroupData = $contactGroup;
                                                break; // Exit loop once a match is found
                                            }
                                        }
                                    }
                                @endphp
                                <option value="{{ $contactGroupData['zoho_contact_group_id'] ?? $group['zoho_group_id'] }}" {{ $selected }}>
                                    {{ $group['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        {{-- Contact Preferences --}}
        <div class="col-md-6 col-sm-12"
            >
            <p class="npinfoText p-2">Contact Preferences</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="validationDefault08" class="form-label nplabelText">Relationship Type</label>
                    <select name="relationship_type" class="form-select npinputinfo" id="validationDefault08">
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
                        <option value="" disabled {{ empty( $referred_id) ? 'selected' : '' }}>Please select
                        </option>
                        @if (!empty($contacts))
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
                    <label for="validationDefault10" class="form-label nplabelText">Lead Source</label>
                    <select name="lead_source" type="text" class="form-select npinputinfo"
                        id="validationDefault10">
                        <option value="">-None-</option>
                        @foreach ($leadSources as $leadSource)
                            <option value="{{ $leadSource }}"
                                {{ $contact['Lead_Source'] == $leadSource ? 'selected' : '' }}>
                                {{ $leadSource }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault11" class="form-label nplabelText">Lead source
                        details</label>
                    <input type="text" value="{{ $contact['lead_source_detail'] }}" name="lead_source_detail"
                        class="form-control npinputinfo" id="validationDefault11">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault13" class="form-label nplabelText">Spouse/Partner</label>
                    <select type="text" name="spouse_partner" class="form-select npinputinfo"
                        id="validationDefault13" >
                        @if (!empty($contacts))
                        @foreach ($contacts as $contactref)
                            @php
                                $zoho_contact_id = isset($contactref['zoho_contact_id']) ? (string) $contactref['zoho_contact_id'] : '';
                                $spouse_partner = isset($contact['spouse_partner']) ? (string) $contact['spouse_partner'] : '';
                            @endphp
                            <option value="{{ json_encode(['id' => $zoho_contact_id, 'Full_Name' => $contactref['first_name'] . ' ' . $contactref['last_name']]) }}" 
                                    {{ $zoho_contact_id === $spouse_partner ? 'selected' : '' }}
                                    data-id="{{ $contactref['id'] }}"
                                    data-icon="fas fa-external-link-alt">
                                {{ $contactref['first_name'] }} {{ $contactref['last_name'] }}
                            </option>
                        @endforeach
                    @endif
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="envelope_salutation" class="form-label nplabelText">Envelope Salutation</label>
                    <input type="text" value="{{ $contact['salutation_s'] }}" name="envelope_salutation"
                        class="form-control npinputinfo" id="envelope_salutation">
                </div>
            </div>
        </div>
        {{-- Primary Contact’s Address --}}
        <div class="col-md-6 col-sm-12"
            >
            <p class="npinfoText p-2">Mailing Address</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="address" class="form-label nplabelText">Address</label>
                    <input type="text" value="{{ $contact['mailing_address'] }}" name="address_line1"
                        class="form-control npinputinfo" id="address">
                </div>
                {{-- <div class="col-md-6">
                    <label for="validationDefault14" class="form-label nplabelText">Address line 2</label>
                    <input type="text" name="address_line2" class="form-control npinputinfo"
                        id="validationDefault14">
                </div> --}}
                <div class="col-md-6">
                    <label for="validationDefault15" class="form-label nplabelText">City</label>
                    <input type="text" value="{{ $contact['mailing_city'] }}" name="city"
                        class="form-control npinputinfo" placeholder="Enter City" id="validationDefault15">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault16" class="form-label nplabelText">State</label>
                    <input type="text" value="{{ $contact['mailing_state'] }}" name="state"
                        class="form-control npinputinfo" placeholder="Enter State" id="validationDefault16">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault17" class="form-label nplabelText">ZIP code</label>
                    <input type="text" value="{{ $contact['mailing_zip'] }}" name="zip_code"
                        class="form-control npinputinfo" id="validationDefault17">
                </div>
                <!-- <div class="col-md-6">
                    <label for="validationDefault18" class="form-label nplabelText">Email</label>
                    <input type="text" value="{{ $contact['secondory_email'] }}" name="email_primary"
                        class="form-control npinputinfo" id="validationDefault18">
                </div> -->

            </div>
        </div>

        {{-- Business Information --}}
        <div class="col-md-6 col-sm-12"
            >
            <p class="npinfoText p-2">Business Information</p>
            <div class="row g-3">
                <div>
                    <label for="validationDefault19" class="form-label nplabelText">Business Name</label>
                    <input type="text" value="{{ $contact['business_name'] }}" name="business_name"
                        class="form-control npinputinfo" id="validationDefault19">
                </div>

                <div>
                    <label for="validationDefault20" class="form-label nplabelText">Business
                        Information</label>
                    <textarea name="business_information" type="text" rows="4" class="form-control nctextarea"
                        id="validationDefault20">{{ $contact['business_information'] }}</textarea>
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
            <button type = "submit" class="submit_button btn btn-primary mt-3" id="submit_button" type="button">Update Contact</button>
        </div>
    </form>
    </div>
</div>
@include('pipeline.transaction',['deals'=>$deals,'allstages'=>$allstages,'contactId'=>$contact['zoho_contact_id']])
{{-- view group secton --}}
<div class="modal fade p-5" id="staticBackdropforViewGroupforDetails" data-bs-backdrop="static" data-bs-keyboard="false"
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
<!-- Modal -->
<div class="modal fade p-5" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modalValues">
            @include('emails.email-create',['selectedContacts'=>$selectedContacts,'contacts'=>$contacts])
        </div>
    </div>
</div>
<div class="modal fade p-5" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @include('emails.email_templates.email-template-create',['contact'=>$contact])
        </div>
    </div>
</div>
{{-- Emails--}}
<div class="p-4 d-flex justify-content-between ">
    <div class="">
        <h2 class='pText mt-3 text-center'> Emails </h2>
    </div>
    <div class=" text-end">
        <div class="input-group-text npcontactbtn" onclick="openEmail()">
            Compose Email
            <i class="mdi mdi-send ms-1"></i>
        </div>
    </div>
</div>

<div class="contactEmailList">
    @component('components.common-table', [
        'id' => 'contact-email-table',
    ])
    @endcomponent
</div>
@vite(['resources/js/pipeline.js'])
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>   
<script>
    contact=@json($contact);
    groups = @json($groups);
    $(document).ready(function() {
        var multipleCancelButton = new Choices('#choices-multiple-remove-button_test', {
            removeItemButton: true,
            maxItemCount: null,
            searchResultLimit: 500,
            renderChoiceLimit: -1,
        });

        let selectedGroupsArr = [];         
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'selectedGroups';

        document.getElementById('choices-multiple-remove-button_test')?.addEventListener('change', function(event) {
            var selectedGroups = event.detail.value;
            if (!selectedGroupsArr.includes(selectedGroups)) {
                selectedGroupsArr.push(selectedGroups);
            } else {
                selectedGroupsArr = selectedGroupsArr.filter(item => item !== selectedGroups);
            }
            hiddenInput.value = JSON.stringify(selectedGroupsArr);
        });

        let selectedGroupsDefault = [];
        $("#choices-multiple-remove-button_test option:selected").each(function() {
            selectedGroupsDefault.push($(this).val());
        });

        let removeGroupsArr = [];
        multipleCancelButton.passedElement.element.addEventListener('removeItem', function(event) {
            var removedGroup = event.detail.value;
            if (selectedGroupsDefault.includes(removedGroup)) {
                deleteAssignGroup(removedGroup);
            }
        });

        document.getElementById('contact_detail_form')?.appendChild(hiddenInput);
        
        var getReffered = $('#validationDefault14');
        getReffered.select2({
            placeholder: 'Search...',
        }).on('select2:open', () => {
            $(document).on('scroll.select2', function() {
                getReffered.select2('close');
            });
        }).on('select2:close', () => {
            $(document).off('scroll.select2');
        });
    });

    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var contactId = $(state.element).data('id');

        var contactUrl = "{{ url('/contacts-view/') }}"+"/"+ contactId;
        var $state = $(
            '<span style="display: flex; justify-content: space-between; align-items: center;">' +
                '<span style="flex-grow: 1;">' + state.text + '</span>' +
                '<a href="' + contactUrl + '" target="_blank" style="margin-left: 8px; color: inherit;">' +
                '<i class="' + $(state.element).data('icon') + '"></i>' +
            '</a>' +
            '</span>'
        );
        return $state;
    }

    var getSpouse = $('#validationDefault13');
    getSpouse.select2({
        placeholder: 'Search...',
        templateResult: formatState,
        templateSelection: formatState
    }).on('select2:open', () => {
        $('.select2-results .new-contact-btn').remove();
        $(".select2-results").prepend(
            '<div class="new-contact-btn" onclick="openContactModalAndCloseSelect()" style="padding: 6px; height: 20px; display: inline-table; color: black; cursor: pointer; background-color: lightgray; width: 100%"><i class="fas fa-plus plusicon"></i> New Spouse</div>'
        );

        // Add scroll event listener to close Select2 on scroll
        $(document).on('scroll.select2', function() {
            getSpouse.select2('close');
        });
    }).on('select2:close', () => {
        // Remove scroll event listener when Select2 is closed
        $(document).off('scroll.select2');
    });
    window.openContactModalAndCloseSelect = function() {
        $("#createContactModal").modal('show');
        getSpouse.select2('close'); // Close the select2 dropdown
    }

    $('#contact_detail_form').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: 'json',
            success: function(response) {
                showToast("Contact update successfully");
                updateContactform();
            },
            error: function(xhr, status, error) {
                getCreateForm();
                // console.error('Error in contact creation:', error, xhr.responseJSON, status);
                showToastError(xhr.responseJSON?.message);
            }
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

    function openEmail(){
        console.log("jfjfjgfjfjgf");
        
        $("#composemodal").modal('show');
    }


    

    

</script>
