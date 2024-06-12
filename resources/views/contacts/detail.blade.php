@extends('layouts.master')

@section('title', 'Agent Commander | Contact Details')

@section('content')
    @vite(['resources/css/custom.css'])
    @vite(['resources/js/toast.js'])

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="container">
        <div class="commonFlex ppipeDiv">
            <p class="pText"></p>
            <a onclick="createTransaction('{{ $contact }}','{{ $userContact }}');">
                <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    New Transaction
                </div>
            </a>
        </div>
        <div class="row">
            <div class="col-md-8 col-sm-12 dtasksection">
                <div class="d-flex justify-content-between">
                    <p class="dFont800 dFont15">Tasks</p>
                    <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId{{ $contact['id'] }}"><i
                            class="fas fa-plus plusicon">
                        </i>
                        New Task
                    </div>

                </div>
                <div class="row">
                    <nav class="dtabs">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                             <button class="nav-link dtabsbtn" onclick="fetchContactTasks('In Progress','{{ $contact['id'] }}')"
                                    id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" data-tab='In Progress'
                                    type="button" role="tab" aria-controls="nav-home" aria-selected="true">In
                                    Progress</button>
                             <button class="nav-link dtabsbtn"  onclick="fetchContactTasks('Not Started','{{ $contact['id'] }}')"
                                    data-tab='Not Started' id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Upcoming</button>
                            <button class="nav-link dtabsbtn"  onclick="fetchContactTasks('Completed','{{ $contact['id'] }}')"
                                    data-tab='Completed' id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Overdue</button>
                        </div>
                    </nav>
                   <div class="contact-task-container">

                       @include('common.tasks', [
                           'tasks' => $tasks,
                           'retrieveModuleData' => $retrieveModuleData,
                           'module' => 'Contacts',
                           ])
                           </div>

                </div>

                @include('common.notes.view', [
                    'notesInfo' => $notes,
                    'retrieveModuleData' => $retrieveModuleData,
                    'module' => 'Contacts',
                ])
            </div>
        </div>
        <div class="row">
            <form class="row" id="contact_detail_form" action="{{ route('update.contact', ['id' => $contact->id]) }}"
                method="POST" onsubmit="enableContactSelect({{ $contact['id'] }})">
                @csrf
                @method('PUT')
                {{-- Contact Details --}}
                <div class="col-md-6 col-sm-12"
                    style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                    <p class="npinfoText">Internal Information</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="validationDefault01" class="form-label nplabelText">First Name</label>
                            <input type="text" name="first_name" value="{{ $contact['first_name'] }}"
                                placeholder="Enter First name" class="form-control npinputinfo" id="validationDefault01">
                        </div>
                        <div class="col-md-6">
                            <label for="validationDefault02" class="form-label nplabelText">Last Name</label>
                            <input type="text" value="{{ $contact['last_name'] }}" name="last_name"
                                onkeyup="showValidation(this)" placeholder="Enter Last name"
                                class="form-control npinputinfo" id="last_name">
                            <div id="last_name_error_message" class="text-danger"></div>
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
                            <input type="text" value="{{ $contact['email'] }}" name="email"
                                class="form-control npinputinfo" placeholder="Enter Email" id="validationDefault05">
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
                                <select id="choices-multiple-remove-button_test" placeholder="Select up to 5 Groups"
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
                {{-- Contact Preferences --}}
                <div class="col-md-6 col-sm-12"
                    style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                    <p class="npinfoText">Contact Preferences</p>
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
                                id="validationDefault13" style="display:none">
                                @if (!empty($spouseContact) && is_array($spouseContact))
                                    <option
                                        value="{{ json_encode(['id' => $spouseContact['zoho_contact_id'], 'Full_Name' => $spouseContact['first_name'] . ' ' . $spouseContact['last_name']]) }}"
                                        selected>
                                        {{ $spouseContact['first_name'] }} {{ $spouseContact['last_name'] }}
                                    </option>
                                @endif
                                @if (!empty($contacts))
                                    @foreach ($contacts as $contactrefs)
                                        <option
                                            value="{{ json_encode(['id' => $contactrefs['zoho_contact_id'], 'Full_Name' => $contactrefs['first_name'] . ' ' . $contactrefs['last_name']]) }}">
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
                            <label for="validationDefault13" class="form-label nplabelText">Address line 1</label>
                            <input type="text" value="{{ $contact['mailing_address'] }}" name="address_line1"
                                class="form-control npinputinfo" id="validationDefault13">
                        </div>
                        <div class="col-md-6">
                            <label for="validationDefault14" class="form-label nplabelText">Address line 2</label>
                            <input type="text" name="address_line2" class="form-control npinputinfo"
                                id="validationDefault14">
                        </div>
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
                        <div class="col-md-6">
                            <label for="validationDefault18" class="form-label nplabelText">Email</label>
                            <input type="text" value="{{ $contact['secondory_email'] }}" name="email_primary"
                                class="form-control npinputinfo" id="validationDefault18">
                        </div>

                    </div>
                </div>

                {{-- Business Information --}}
                <div class="col-md-6 col-sm-12"
                    style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                    <p class="npinfoText">Business Information</p>
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

                        <div>
                            <label for="validationDefault21" class="form-label nplabelText">Contact Owner</label>

                            <select name="contactOwner" class="form-select npinputinfo" id="validationDefault21"
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
                    <button class="submit_button btn btn-primary" id="submit_button" type="button"
                        onclick="validateContactForm()">Update Contact</button>
                </div>
            </form>
        </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal"
        data-bs-target="#staticBackdropforNote_{{ $contact['id'] }}">
        <div class="tooltip-wrapper">
            <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
            <span class="tooltiptext">Add Notes</span>
        </div>
    </div>


    {{-- view group secton --}}
    <div class="modal fade" id="staticBackdropforViewGroupforDetails" data-bs-backdrop="static" data-bs-keyboard="false"
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
    {{-- Note Modal --}}
    @include('common.notes.create', [
        'contact' => $contact,
        'retrieveModuleData' => $retrieveModuleData,
        'type' => 'Contacts',
    ])
    {{-- task modal --}}
    @include('common.tasks.create', [
        'contact' => $contact,
        'retrieveModuleData' => $retrieveModuleData,
        'type' => 'Contacts',
    ])

@endsection
<script>
    function enableContactSelect(id) {
        // Enable the select element before form submission
        document.getElementById('validationDefault21').removeAttribute('disabled');
        // Return true to allow form submission
        return true;
    }

        window.fetchContactTasks = function(tab, contactId) {
            // Make AJAX call
            $.ajax({
                url: '/contacts-view/'+contactId,
                method: 'GET',
                data: {
                    tab: tab,
                },
                dataType: 'html',
                success: function(data) {

                    $('.contact-task-container').html(data);

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    loading = false;
                    console.error('Error:', error);
                }
            });

        }
    document.addEventListener('DOMContentLoaded', function() {
        var defaultTab = "{{ $tab }}";
        console.log(defaultTab, 'tab is here')
        localStorage.setItem('status', defaultTab);
        // Retrieve the status from local storage
        var status = localStorage.getItem('status');

        // Object to store status information
        var statusInfo = {
            'In Progress': false,
            'Overdue': false,
            'Not Started': false,
        };

        // Update the status information based on the current status
        statusInfo[status] = true;

        // Loop through statusInfo to set other statuses to false
        for (var key in statusInfo) {
            if (key !== status) {
                statusInfo[key] = false;
            }
        }

        // Example of accessing status information
        console.log(statusInfo);

        // Remove active class from all tabs
        var tabs = document.querySelectorAll('.nav-link');
        console.log(tabs, 'tabssss')
        tabs.forEach(function(tab) {
            tab.classList.remove('active');
        });

        // Set active class to the tab corresponding to the status
        console.log(status, 'status');
        var activeTab = document.querySelector('.nav-link[data-tab="' + status + '"]');
        if (activeTab) {
            activeTab.classList.add('active');
        }
        $('#primary_address').change(function() {
            if ($(this).is(':checked')) {
                $(this).val(true);
            } else {
                $(this).val(false);
            }
        });

        // Secondary Address Checkbox
        $('#secondry_address').change(function() {
            if ($(this).is(':checked')) {
                $(this).val(true);
            } else {
                $(this).val(false);
            }
        });

        var multipleCancelButton = new Choices('#choices-multiple-remove-button_test', {
            removeItemButton: true,
            maxItemCount: 5,
            searchResultLimit: 500,
            renderChoiceLimit: -1,
        });
        let selectedGroupsArr = [];
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'selectedGroups';

        document.getElementById('choices-multiple-remove-button_test').addEventListener('change', function(
            event) {
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
        //selected groups default
        let selectedGroupsDefault = [];
        $("#choices-multiple-remove-button_test option:selected").each(function() {
            selectedGroupsDefault.push($(this).val());
        })
        // Add event listener for remove button
        let removeGroupsArr = [];
        multipleCancelButton.passedElement.element.addEventListener('removeItem', function(event) {
            var removedGroup = event.detail.value;
            if (selectedGroupsDefault.includes(removedGroup)) {
                // Perform your API hit here
                // console.log("API hit for removed group: " + removedGroup);
                deleteAssignGroup(removedGroup);
            }

        });


        // This will log an array of selected values
        document.getElementById('contact_detail_form').appendChild(hiddenInput);

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
            $(".select2-results").append(
                '<div class="new-contact-btn" onclick="openContactModal()" style="padding: 6px; height: 20px; display: inline-table; color: black; cursor: pointer;"><i class="fas fa-plus plusicon"></i> New Contact</div>'
            );
        });

        window.openContactModal = function() {
            $("#createContactModal").modal('show');
        }



    });


    function deleteAssignGroup(removeGroupId) {
        console.log("yes i am here", removeGroupId);
        $.ajax({
            url: '/contact/group/delete/'+removeGroupId, // Replace 'your_delete_endpoint_url' with the actual URL of your delete endpoint
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'DELETE',
            success: function(response) {
                if(response){
                    showToast("Group deleted successfully");

                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error('Error deleting group:', error);
            }
        });

    }

    function showValidation(e) {
        let lastName = e.value;
        let regex = /^[a-zA-Z ]{1,20}$/; // Regular expression to match only letters and spaces up to 20 characters

        if (lastName.trim() === "" || !regex.test(lastName)) {
            $("#last_name_error_message").text(
                "Last name must be between 1 and 20 characters long and contain only letters and spaces").show();
        } else {
            $("#last_name_error_message").hide(); // Hide the error message if the last name is valid
        }
    }

    function validateContactForm() {
        let last_name = $("#last_name").val();
        // let regex = /^[a-zA-Z ]{1,20}$/;
        if (last_name.trim() === "") {
            $("#last_name_error_message").text("Last name cannot be empty").show();
            return false;
        } else {
            $("#last_name_error_message").hide(); // Hide the error message if the last name is not empty
        }
        let submitbtn = $("#submit_button");
        submitbtn.attr("type", "submit");

    }



    function validateTextarea() {
        var textarea = document.getElementById('darea');
        var textareaValue = textarea.value.trim();
        // Check if textarea value is empty
        if (textareaValue === '') {
            // Show error message or perform validation logic
            document.getElementById("subject_error").innerHTML = "please enter details";
        } else {
            document.getElementById("subject_error").innerHTML = "";
        }
    }
    let sortDirection = 'desc';

    function toggleSortGroup(sortField) {

        // Toggle the sort direction
        sortDirection = (sortDirection === 'desc') ? 'asc' : 'desc';
        // Call fetchDeal with the sortField parameter
        fetchGroups(sortField, sortDirection);

    }

    function fetchGroups(sortField, sortDirection) {
        $.ajax({
            url: '{{ url('/get-groups') }}',
            method: 'GET',
            data: {
                sort: sortField || "",
                sortType: sortDirection || "",
            },
            dataType: 'json',
            success: function(data) {
                console.log(data, 'data is here')
                let arrObj = [];
                for (const key in data) {
                    if (data.hasOwnProperty(key)) {
                        const object = data[key];
                        object?.groups?.forEach(object => {
                            let contactId = "{{ $contactId }}";
                            console.log(object?.contactId, Number(contactId), 'contactId');
                            if (object?.contactId === Number(contactId)) {
                                arrObj.push(object);
                                // 
                            }
                        });
                    }
                }
                if (arrObj.length > 0) {
                    renderGroups(arrObj);
                }

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function renderGroups(groups) {
        // Clear existing groups
        $('.group_container').empty();

        // Render groups
        groups.forEach(function(group) {
            var isPublicIcon = (group?.group?.isPublic == 1) ?
                '<i class="fas fa-check" style="color: green;"></i>' : '<i class="fas fa-check"></i>';
            var isABCDIcon = (group?.group?.isABCD == 1) ?
                '<i class="fas fa-check" style="color: green;"></i>' :
                '<i class="fas fa-check"></i>';
            // Generate a unique ID for each group
            const row = $(`<div class="row ncGroupBody" id="groupContainer${group?.group?.id}">`).html(`
                
                                    <div class="col-md-3 col-sm-3 col-3">${group?.group?.name}</div>
                                    <div class="col-md-3 col-sm-3 col-3">
                                       ${isPublicIcon}
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-3">
                                       ${isABCDIcon}
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-3">${group?.group?.id}</div>
                                </div>
                            
                            `);

            // Append the group HTML to #groupContainer
            $('.group_container').append(row);
        });
    }




    window.selectedTransation;

    function selectedElement(element) {
        var selectedValue = element.value;
        window.selectedTransation = selectedValue;
        //    console.log(selectedTransation);
    }

    function addTaskforContact(conID) {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML = "please enter details";
            return;
        }
        var whoSelectoneid = document.getElementsByName("who_id")[0].value;
        // console.log(window.selectedTransation,'sdfjhsjkdfhjk')
        var whoId = window.selectedTransation
        if (whoId === undefined) {
            whoId = whoSelectoneid
        }
        var dueDate = document.getElementsByName("due_date")[0].value;

        var formData = {
            "data": [{
                "Subject": subject,
                "Who_Id": {
                    "id": whoId
                },
                "Status": "Not Started",
                "Due_Date": dueDate,
                // "Created_Time":new Date()
                // "Priority": "High",
                "What_Id": {
                    "id": conID
                },
                "$se_module": "Contacts"
            }],
            "_token": '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route('create.task') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    showToast(upperCaseMessage);
                    // window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }

    function createTransaction(contactData, userContactData) {
       let contact =  JSON.parse(contactData);
       let userContact = JSON.parse(userContactData);
        var formData = {
            "data": [{
                "Deal_Name": "{{ config('variables.dealName') }}",
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}"
                },
                "Stage": "Potential",
                "Client_Name_Primary": contact.first_name + " " + contact.last_name,
                "Client_Name_Only": contact.first_name + " " + contact.last_name + " || " + contact
                    .zoho_contact_id,
                "Contact": {
                    "Name": contact.first_name + " " + contact.last_name,
                    "id": contact.zoho_contact_id
                },
                "Contact_Name": {
                    "Name": userContact.first_name + " " + userContact.last_name,
                    "id": userContact.zoho_contact_id
                },
            }],
            "_token": '{{ csrf_token() }}'
        };
        $.ajax({
            url: '{{ url('/pipeline/create') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(data) {
                console.log(data);
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/pipeline-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
</script>
