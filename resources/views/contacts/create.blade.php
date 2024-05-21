@extends('layouts.master')

@section('title', 'Agent Commander | Contact Create')

@section('content')
@vite(['resources/css/custom.css'])
<script src="{{ URL::asset('http://[::1]:5173/resources/js/toast.js') }}"></script>
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
        <p class="ncText">Create new contact</p>
        <div class="commonFlex ppipeDiv">
            <p class="pText"></p>
            <a onclick="createTransaction({{$contact}});">
                <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    New Transaction
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <form class="row" action="{{ route('update.contact', ['id' => $contact->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="col-md-6 col-sm-12"
                style="display: none;padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Internal Information</p>
                <div class="row g-3">
                    <div>
                        <label for="validationDefault01" class="form-label nplabelText">Contact Owner</label>
                        <select name="contactOwner" class="form-select npinputinfo" id="validationDefault04">
                            {{-- <option selected disabled value=""></option> --}}
                            <option value="{{ json_encode(['id' => $user_id, 'Full_Name' => $name]) }}" selected>
                                {{ 'CHR Technology' }}
                            </option>

                        </select>
                    </div>
                    <div>
                        @php
                            // $date = "2024-04-11 00:00:00";
                            $lastcalled = \Carbon\Carbon::parse($contact['last_called'])->format('Y-m-d');
                            $lastemailed = \Carbon\Carbon::parse($contact['last_emailed'])->format('Y-m-d');
                        @endphp
                        <label for="validationDefault02" class="form-label nplabelText">Last Called</label>
                        <input type="date" value="{{ $lastcalled }}" name="last_called" class="form-control npinputinfo"
                            id="datetimeInput">
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Last Emailed</label>
                        <input type="date" value="{{ $lastemailed }}" name="last_emailed"
                            class="form-control npinputinfo" id="validationDefault02">
                    </div>

                </div>
            </div>

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
                        <input type="text" value="{{ $contact['last_name'] == 'CHR' ? '' : $contact['last_name'] }}" name="last_name"
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
                        <label for="validationDefault03" class="form-label nplabelText">Phone</label>
                        <input type="text" value="{{ $contact['phone'] }}" name="phone" class="form-control npinputinfo"
                            placeholder="Enter Phone Number" id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Email</label>
                        <input type="text" value="{{ $contact['email'] }}" name="email" class="form-control npinputinfo"
                            placeholder="Enter Email" id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Market Area</label>
                        <select name="market_area" class="form-select npinputinfo" id="validationDefault03">
                            <option disabled value="">-None-</option>
                            <option value="Denver" {{ $contact->market_area === 'Denver' ? 'selected' : '' }}>
                                Denver</option>
                            <option value="Colorado Springs" {{ $contact->market_area === 'Colorado Springs' ? 'selected' : '' }}>
                                Colorado Springs</option>
                        </select>
                                               
                    </div>
                    <div>
                        @php
                            $abcd = ['A+', 'A', 'B', 'C', 'D'];
                        @endphp
                        <label for="validationDefault02" class="form-label nplabelText">ABCD Status</label>
                        <select name="abcd_class" class="form-select npinputinfo" id="validationDefault04">
                            <option selected disabled value="">-None-</option>
                            @foreach ($abcd as $abcdIndex)
                                <option value="{{ $abcdIndex }}" {{ $contact['abcd'] == $abcdIndex ? 'selected' : '' }}>
                                    {{ $abcdIndex }}
                                </option>
                            @endforeach
                        </select>
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
                            <option value="Secondary" {{ $contact->relationship_type === 'Secondary' ? 'selected' : '' }}>
                                Secondary</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Referred By</label>
                        <select name="reffered_by" type="text" 
                            class="form-select npinputinfo" id="validationDefault02">
                            @php
                                $referred_id = $contact['referred_id'];
                            @endphp
                            <option value="">-None-</option>
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
                        <label for="validationDefault03" class="form-label nplabelText">Lead Source</label>
                        <select name="lead_source" type="text" class="form-select npinputinfo" 
                            id="validationDefault03">
                            <option value="">-None-</option>
                            @foreach ($leadSources as $leadSource)
                                <option value="{{ $leadSource }}" {{ $contact['Lead_Source'] == $leadSource ? 'selected' : '' }}>{{ $leadSource }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Lead source details</label>
                        <input type="text" value="{{ $contact['lead_source_detail'] }}" name="lead_source_detail"
                            class="form-control npinputinfo"  id="validationDefault03">
                    </div>
                    {{--<div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Envelope Salutation</label>
                        <input type="text" value="{{ $contact['envelope_salutation'] }}" name="envelope_salutation"
                            class="form-control npinputinfo"  id="validationDefault03">
                    </div>--}}
                    <div class="col-md-6">

                        <label for="validationDefault03" class="form-label nplabelText">Spouse/Partner</label>
                        <select type="text" name="spouse_partner" class="form-select npinputinfo"
                             id="validationDefault03">
                            @php
                                $spause_partner = $contact['spouse_partner'];
                            @endphp
                            <option value="">-None-</option>
                            @if (!empty($contacts))
                                @foreach ($contacts as $contactrefs)
                                    <option
                                        value="{{ json_encode(['id' => $contactrefs['zoho_contact_id'], 'Full_Name' => $contactrefs['first_name'] . ' ' . $contactrefs['last_name']]) }}"
                                        {{ $contactrefs['zoho_contact_id'] == $spause_partner ? 'selected' : '' }}>
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
                            class="form-control npinputinfo"  id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Address line 2</label>
                        <input type="text" name="address_line2"  class="form-control npinputinfo"
                            id="validationDefault02">
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">City</label>
                        <input type="text" value="{{ $contact['mailing_city'] }}" placeholder="Enter City" name="city"
                            class="form-control npinputinfo"  id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">State</label>
                        <input type="text" value="{{ $contact['mailing_state'] }}" placeholder="Enter State" name="state"
                            class="form-control npinputinfo"  id="validationDefault04">
                        {{-- <select name="state" class="form-select npinputinfo" id="validationDefault04">
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">ZIP code</label>
                        <input type="text" value="{{ $contact['mailing_zip'] }}" name="zip_code"
                            class="form-control npinputinfo"  id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Email</label>
                        <input type="text" value="{{ $contact['secondory_email'] }}" name="email_primary"
                            class="form-control npinputinfo"  id="validationDefault03">
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

                    <div>
                        <label for="validationDefault01" class="form-label nplabelText">Contact Owner</label>

                        <select name="contactOwner" class="form-select npinputinfo" id="validationDefault04">
                            
                                <option
                                    value="{{ json_encode(['id' => $users['root_user_id'], 'Full_Name' => $users['name']]) }}"
                                    selected>
                                    {{ $users['name']}}
                                </option>
                        </select>
                    </div>

                </div>
            </div>

            
            <div>
                <button class="submit_button btn btn-primary" id="submit_button" type="button"
                    onclick="validateContactForm()">Submit</button>
            </div>
        </form>
    </div>
</div>
<div class="dnotesBottomIcon" type="button" data-bs-toggle="modal"
    data-bs-target="#staticBackdropforNote_{{$contact['id']}}">
    <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon" title = "Add Notes">
</div>

{{-- view group secton --}}
<div class="modal fade" id="staticBackdropforViewGroup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
{{-- Note Modal --}}
@include('common.notes.create', ['contact' => $contact, 'retrieveModuleData' => $retrieveModuleData, 'type' => 'Contacts'])
{{-- task modal --}}
@include('common.tasks.create', ['contact' => $contact, 'retrieveModuleData' => $retrieveModuleData, 'type' => 'Contacts'])

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#primary_address').change(function () {
            if ($(this).is(':checked')) {
                $(this).val(true);
            } else {
                $(this).val(false);
            }
        });

        // Secondary Address Checkbox
        $('#secondry_address').change(function () {
            if ($(this).is(':checked')) {
                $(this).val(true);
            } else {
                $(this).val(false);
            }
        });

        document.getElementById("note_text").addEventListener("keyup", validateFormc);
        document.getElementById("related_to").addEventListener("change", validateFormc);

    })

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

    function validateForm() {
        let noteText = document.getElementById("note_text").value;
        let relatedTo = document.getElementById("related_to").value;
        let isValid = true;

        // Reset errors
        document.getElementById("note_text_error").innerText = "";
        document.getElementById("related_to_error").innerText = "";

        // Validate note text length
        if (noteText.trim().length > 100) {
            document.getElementById("note_text_error").innerText = "Note text must be 100 characters or less";
            isValid = false;
        }
        // Validate note text
        if (noteText.trim() === "") {
            document.getElementById("note_text_error").innerText = "Note text is required";
            isValid = false;
        }

        // Validate related to
        if (relatedTo === "") {
            document.getElementById("related_to_error").innerText = "Related to is required";
            document.getElementById("taskSelect").style.display = "none";
            isValid = false;
        }
        if (isValid) {
            let changeButton = document.getElementById('validate-button');
            changeButton.type = "submit";
        }
        return isValid;
    }


    function handleDeleteCheckbox(id) {
        // Get all checkboxes
        const checkboxes = document.querySelectorAll('.checkbox' + id);
        // Get delete button
        const deleteButton = document.getElementById('deleteButton' + id);
        const editButton = document.getElementById('editButton' + id);
        console.log(checkboxes, 'checkboxes')
        // Add event listener to checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                // Check if any checkbox is checked
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                // Toggle delete button visibility
                editButton.style.display = anyChecked ? 'block' : 'none';
                // if (deleteButton.style.display === 'block') {
                //     selectedNoteIds.push(id)
                // }
            });
        });

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

    function addTaskforContact(conID) {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML = "please enter details";
            return;
        }
        var whoSelectoneid = document.getElementsByName("who_id")[0].value;
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
                "Status": "In Progress",
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
        console.log("formData", formData);
        $.ajax({
            url: '{{ route('create.task') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function (response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    showToast(upperCaseMessage);
                    // window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }

    function moduleSelectedforContact(selectedModule) {
        // console.log(accessToken,'accessToken')
        var selectedOption = selectedModule.options[selectedModule.selectedIndex];
        var selectedText = selectedOption.text;
        //    var id = '{{ request()->route('id') }}'; 
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/task/get-' + selectedText + '?contactId={{ $contact['zoho_contact_id'] }}',
            method: "GET",
            dataType: "json",

            success: function (response) {
                // Handle successful response
                var tasks = response;
                // Assuming you have another select element with id 'taskSelect'
                var taskSelect = $('#taskSelect');
                // Clear existing options
                taskSelect.empty();
                // Populate select options with tasks
                $.each(tasks, function (index, task) {
                    console.log(task, 'task');
                    if (selectedText === "Tasks") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_task_id,
                            text: task?.subject
                        }));
                    }
                    if (selectedText === "Deals") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_deal_id,
                            text: task?.deal_name
                        }));
                    }
                    if (selectedText === "Contacts") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_contact_id,
                            text: task?.first_name ?? "" + ' ' + task?.last_name ?? "",
                        }));
                    }
                });
                taskSelect.show();
                // Do whatever you want with the response data here
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });

    }

    function getContact() {


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/task/get-Contacts',
            method: "GET",
            dataType: "json",

            success: function (response) {
                // Handle successful response
                console.log(response);

            },
            error: function (xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });


    }

    function resetFormAndHideSelect() {
        document.getElementById('noteForm').reset();
        document.getElementById('taskSelect').style.display = 'none';
        clearValidationMessages();
    }

    function clearValidationMessages() {
        document.getElementById("note_text_error").innerText = "";
        document.getElementById("related_to_error").innerText = "";
    }

    function markAsDone(noteId) {
        // Send an AJAX request to the route using jQuery
        $.ajax({
            type: 'POST',
            url: '{{ route('mark.done') }}',
            data: {
                // Pass the note ID to the server
                note_id: noteId,
                // Add CSRF token for Laravel security
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response?.mark_as_done === 1) {
                    window.location.reload();
                }
                // Handle success response if needed
            },
            error: function (xhr, status, error) {
                // Handle error if needed
            }
        });

    }

    function validateFormc() {
        let noteText = document.getElementById("note_text").value;
        let relatedTo = document.getElementById("related_to").value;
        let isValid = true;

        // Reset errors
        document.getElementById("note_text_error").innerText = "";
        document.getElementById("related_to_error").innerText = "";

        // Validate note text length
        /* if (noteText.trim().length > 50) {
            document.getElementById("note_text_error").innerText = "Note text must be 10 characters or less";
            isValid = false;
        } */
        // Validate note text
        if (noteText.trim() === "") {
            document.getElementById("note_text_error").innerText = "Note text is required";
            isValid = false;
        }

        // Validate related to
        if (relatedTo === "") {
            document.getElementById("related_to_error").innerText = "Related to is required";
            document.getElementById("taskSelect").style.display = "none";
            isValid = false;
        }
        if (isValid) {
            let changeButton = document.getElementById('validate-button');
            changeButton.type = "submit";
        }
        return isValid;
    }

    function createTransaction(contact) {
        console.log("Onclick");
        var formData = {
            "data": [{
                "Deal_Name": "{{ config('variables.dealName') }}",
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}"
                },
                "Stage": "Potential",
                "Client_Name_Primary":contact.first_name+" "+contact.last_name,
                "Client_Name_Only":contact.first_name+" "+contact.last_name+" || "+contact.zoho_contact_id,
                "Contact":{
                    "Name":contact.first_name+" "+contact.last_name,
                    "id":contact.zoho_contact_id
                }
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