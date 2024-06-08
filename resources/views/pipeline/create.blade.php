@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline Create')

@section('content')
@vite(['resources/css/pipeline.css'])
@vite(['resources/js/toast.js'])
<script>
    function updateText(newText) {
        //  textElement = document.getElementById('editableText');
        console.log("newText", newText);
        textElement.innerHTML = newText;
    }

    function makeEditable(id) {
        textElement = document.getElementById('editableText' + id);
        textElementCard = document.getElementById('editableTextCard' + id);
        //For Table data                
        var text = textElement.textContent.trim();
        textElement.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

        //For card data
        var text = textElementCard.textContent.trim();
        textElementCard.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

        var inputElement = document.getElementById('editableInput' + id);
        inputElement.focus();
        inputElement.addEventListener('blur', function () {
            updateText(inputElement.value);
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
                checkbox.addEventListener('change', function() {
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
        function moduleSelected(selectedModule, accessToken) {
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
                url: '/task/get-' + selectedText+'?dealId={{$deal['zoho_deal_id']}}',
                method: "GET",
                dataType: "json",

                success: function(response) {
                    // Handle successful response
                    var tasks = response;
                    // Assuming you have another select element with id 'taskSelect'
                    var taskSelect = $('#taskSelect');
                    // Clear existing options
                    taskSelect.empty();
                    // Populate select options with tasks
                    $.each(tasks, function(index, task) {
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
                                value: task?.contactData?.zoho_contact_id,
                                text: task?.contactData?.first_name + ' ' + task?.contactData?.last_name
                            }));
                        }
                    });
                    taskSelect.show();
                    // Do whatever you want with the response data here
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error("Ajax Error:", error);
                }
            });

        }
</script>
<div class="container-fluid">
    <div class="commonFlex ppipeDiv">
        <p class="pText"></p>
        <a onclick="updateDataDeal('{{$deal['zoho_deal_id']}}')">
            <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton"
                data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-save">
                </i>
                Save
            </div>
        </a>
    </div>

    {{-- information form --}}
    <div class="row">
        <div class="col-md-6 col-sm-12"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
            <p class="npinfoText">Client Information</p>
            <form class="row g-3" id="additionalFields">
                <div class="col-md-6 selectSearch">
                    <label for="leadAgent" class="form-label nplabelText">Lead Agent</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select id="leadAgent" style="display:none;">
                        <option value="" disabled {{ empty($deal['leadAgent']) ? 'selected' : '' }}>Please select
                        </option>
                        @foreach($users as $user)
                        <option value="{{ json_encode($user) }}" {{ isset($deal['leadAgent']) &&
                            $deal['leadAgent']['id']==$user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-6 selectSearch">
                    <label for="validationDefault01" class="form-label nplabelText">Client Name</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="validationDefault01" required value="{{ $deal['contactId'] }}">--}}
                    <select style="display:none;" id="validationDefault01" required>
                        @foreach($contacts as $contact)
                        <option value="{{$contact}}" {{ $deal['client_name_primary']==$contact['first_name']
                            .' '.$contact[' last_name']? 'selected' : '' }}>
                            {{$contact['first_name']}} {{$contact['last_name']}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault02" class="form-label nplabelText">Representing</label>
                    <select class="form-select npinputinfo validate" id="validationDefault02" required
                        onchange="checkValidate('{{$deal}}')">
                        <option value="" {{ empty($deal['representing']) ? 'selected' : '' }}>--None--</option>
                        <option value="Buyer" {{ $deal['representing']=='Buyer' ? 'selected' : '' }}>Buyer</option>
                        <option value="Seller" {{ $deal['representing']=='Seller' ? 'selected' : '' }}>Seller
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Transaction Name</label>
                    <input type="text" class="form-control npinputinfo validate" placeholder="Transaction Name"
                        id="validationDefault03" required
                        value="{{$deal['deal_name']=='Untitled'?'':$deal['deal_name']}}">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault04" class="form-label nplabelText">Stage</label>
                    <select class="form-select npinputinfo validate" id="validationDefault04" required
                        onchange="checkValidate('{{$deal}}')">
                        <option value="" disabled {{ empty($deal['stage']) ? 'selected' : '' }}>Please select</option>
                        @foreach ($allStages as $stage)
                        <option value="{{ $stage }}" {{ $deal['stage']==$stage ? 'selected' : '' }}>
                            {{ $stage }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault05" class="form-label nplabelText">Sale Price</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault05" required
                        value="{{ $deal['sale_price'] }}">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault06" class="form-label nplabelText">Closing Date</label>
                    <input type="date" class="form-control npinputinfo validate" id="validationDefault06" required
                        value="{{ $deal['closing_date'] ? \Carbon\Carbon::parse($deal['closing_date'])->format('Y-m-d') : '' }}">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault07" class="form-label nplabelText">Address</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault07" required
                        value="{{ $deal['address'] }}">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault08" class="form-label nplabelText">City</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault08" required
                        value="{{ $deal['city'] }}">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">State</label>
                    {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value=""></option>
                        <option>...</option>
                    </select> --}}
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required
                        value="{{ $deal['state'] }}">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault10" class="form-label nplabelText">ZIP</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault10" required
                        value="{{ $deal['zip'] }}">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault12" class="form-label nplabelText">Property Type</label>
                    <select class="form-select npinputinfo" id="validationDefault12" required>
                        <option selected disabled value="">--None--</option>
                        <option value="Residential" {{$deal['property_type']=='Residential' ? 'selected' : '' }}>
                            Residential</option>
                        <option value="Land" {{$deal['property_type']=='Land' ? 'selected' : '' }}>Land</option>
                        <option value="Farm" {{$deal['property_type']=='Farm' ? 'selected' : '' }}>Farm</option>
                        <option value="Commercial" {{$deal['property_type']=='Commercial' ? 'selected' : '' }}>
                            Commercial
                        </option>
                        <option value="Lease" {{$deal['property_type']=='Lease' ? 'selected' : '' }}>Lease</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="validationDefault13" class="form-label nplabelText">Ownership Type</label>
                    <select class="form-select npinputinfo" id="validationDefault13" required>
                        <option selected disabled value="">--None--</option>
                        <option value="Primary Residence" {{$deal['ownership_type']=='Primary Residence' ? 'selected'
                            : '' }}>Primary Residence</option>
                        <option value="Second Home" {{$deal['ownership_type']=='Second Home' ? 'selected' : '' }}>Second
                            Home</option>
                        <option value="Investment Property" {{$deal['ownership_type']=='Investment Property'
                            ? 'selected' : '' }}>Investment Property</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-sm-12"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">Earnings Information</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="validationDefault11" class="form-label nplabelText">Commission %</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault11" required
                        value="{{$deal['commission']}}">
                </div>
                <div class="col-md-6">
                    <label for="commissionflat" class="form-label nplabelText">Commission Flat Fee</label>
                    <input type="text" class="form-control npinputinfo" id="commissionflat" required
                        value="{{ $deal['commission_flat_free'] }}">
                </div>

                <div class="col-md-6">
                    <label for="validationDefault15" class="form-label nplabelText">Pipeline Probability (%)</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault15" required
                        value="{{$deal['pipeline_probability']}}">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault11" class="form-label nplabelText"></label>

                </div>
                <div class="col-md-6">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked01" <?php if
                        ($deal['personal_transaction']) echo 'checked' ; ?>>
                    <label class="form-check-label nplabelText" for="flexCheckChecked01">
                        Personal Transaction
                    </label>
                </div>
                <div class="col-md-6">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked02" <?php if
                        ($deal['double_ended']) echo 'checked' ; ?>>
                    <label class="form-check-label nplabelText" for="flexCheckChecked02">
                        Double ended
                    </label>
                </div>

                <p class="npinfoText">Settings</p>
                <div class="col-md-6">
                    <label for="transactionOwner" class="form-label nplabelText">Transaction Owner</label>
                    <input type="text" class="form-control npinputinfo" id="transactionOwner" required
                        value="{{$deal['userData']['name']}}">
                </div>
                <div class="col-md-6">
                    <label for="tmPreference" class="form-label nplabelText">Tm Preference</label>
                    <select class="form-select npinputinfo" id="tmPreference" required>
                        <option selected value="">--None--</option>
                        <option value="CHR TM" {{$deal['tm_preference']=='CHR TM' ? 'selected' : '' }}>CHR TM</option>
                        <option value="Non TM" {{$deal['tm_preference']=='Non TM' ? 'selected' : '' }}>Non TM</option>
                    </select>

                </div>
                <div class="col-md-6">
                    <label for="tmName" class="form-label nplabelText">TM Name</label>
                    <select class="form-select npinputinfo" id="tmName" required disabled>
                        @foreach($users as $user)
                        <option value="{{$user}}" {{ $deal['tm_name']==$user['root_user_id']? 'selected' : '' }}>
                            {{$user['name']}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="contactName" class="form-label nplabelText">Contact Name</label>
                    <input type="hidden" name="contactName" id="contactNameObject"
                        value="{{ json_encode($deal['contactName']) }}">
                    <input type="text" class="form-control npinputinfo" id="contactName"
                        value="{{ isset($deal['contactName']) ? $deal['contactName']['first_name'] . ' ' . $deal['contactName']['last_name'] : '' }}"
                        disabled />

                </div>

                <div class="col-md-6">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked03" <?php if
                        ($deal['review_gen_opt_out']) { echo 'checked' ; } ?>>
                    <label class="form-check-label nplabelText" for="flexCheckChecked03">
                        Review Gen Opt Out
                    </label>
                </div>
                <div class="col-md-6">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked04" <?php if
                        ($deal['status_rpt_opt_out']) { echo 'checked' ; } ?>>
                    <label class="form-check-label nplabelText" for="flexCheckChecked04">
                        Status Rpt Opt out
                    </label>
                </div>
                <div class="col-md-6">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked05" <?php if
                        ($deal['deadline_em_opt_out']) { echo 'checked' ; } ?>>
                    <label class="form-check-label nplabelText" for="flexCheckChecked05">
                        Deadline EM Opt Out
                    </label>
                </div>
            </form>
        </div>
    </div>
    {{-- contact roles --}}
    @include('contactRole.index',['dealContacts'=>$dealContacts])

</div>
<div class="dnotesBottomIcon" type="button" data-bs-toggle="modal"
    data-bs-target="#staticBackdropforNote_{{$deal['id']}}">
    <div class="tooltip-wrapper">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
        <span class="tooltiptext">Add Notes</span>
    </div>
</div>
@include('common.notes.create',["deal"=>$deal, 'type' => 'Deals'])

@vite(['resources/js/pipeline.js'])
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
        tabs.forEach(function (tab) {
            tab.classList.remove('active');
        });

        // Set active class to the tab corresponding to the status
        console.log(status, 'status');
        var activeTab = document.querySelector('.nav-link[data-tab="' + status + '"]');
        if (activeTab) {
            activeTab.classList.add('active');
            activeTab.style.backgroundColor = "#253C5B"
            activeTab.style.color = "#fff";
            activeTab.style.borderRadius = "4px";
        }
       var getLeadAgent = $('#leadAgent');
        getLeadAgent.select2({
            placeholder: 'Search...',
        });
        var getClientName = $('#validationDefault01');
        getClientName.select2({
            placeholder: 'Search...',
        });

    var representing = document.getElementById('validationDefault02');
    var stage = document.getElementById('validationDefault04');
    if (representing.value == 'Buyer' && stage.value == "Under Contract") {
        $('#additionalFields').append(`
                    <div class="col-md-6 additional-field ">
                        <label for="finance" class="form-label nplabelText">Financing</label>
                        <select class="form-select npinputinfo" id="finance" required onchange='checkAdditionalValidation(${deal})'>
                            <option value="" ${!(deal['financing']) ? 'selected' : ''}>--None--</option>
                            <option value="Cash" ${deal['financing'] == 'Cash' ? 'selected' : ''}>Cash</option>
                            <option value="Loan" ${deal['financing'] == 'Loan' ? 'selected' : ''}>Loan
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 additional-field">
                        <label for="lender_company" class="form-label nplabelText">Lender Company</label>
                        <select class="form-select npinputinfo" id="lender_company" required onchange='checkAdditionalValidation(${deal})'>
                            <option value="" ${!(deal['lender_company']) ? 'selected' : ''}>--None--</option>
                            <option value="Modern Mortgage" ${deal['lender_company'] == 'Modern Mortgage' ? 'selected' : ''}>Modern Mortgage</option>
                            <option value="Other" ${deal['lender_company'] == 'Other' ? 'selected' : ''}>Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 additional-field">
                        <label for="modern_mortgage_lender" class="form-label nplabelText">Modern Mortgage Lender</label>
                        <select class="form-select npinputinfo" id="modern_mortgage_lender" required >
                            <option value="" ${!(deal['modern_mortgage_lender']) ? 'selected' : ''}>--None--</option>
                            <option value="Joe Biniasz" ${deal['modern_mortgage_lender'] == 'Joe Biniasz' ? 'selected' : ''}>Joe Biniasz</option>
                            <option value="Laura Berry" ${deal['modern_mortgage_lender'] == 'Laura Berry' ? 'selected' : ''}>Laura Berry
                            </option>
                            <option value="Virginia Shank" ${deal['modern_mortgage_lender'] == 'Virginia Shank' ? 'selected' : ''}>Virginia Shank
                            </option>
                        </select>
                    </div>
                `);
    } else {
        // If representing is not buyer, remove the additional fields
        $('#additionalFields').find('.additional-field').remove();
    }


    var probability = document.getElementById('validationDefault15');
    if (stage.value == 'Active') {
        probability.value = "40";
    } else if (stage.value == 'Potential') {
        probability.value = "5";
    } else if (stage.value == 'Pre-Active') {
        probability.value = "20";
    } else if (stage.value == 'Under Contract') {
        probability.value = "60";
    } else if (stage.value == 'Dead-Lost To Competition') {
        probability.value = "100";
    }
    var address = document.getElementById('validationDefault07');
    var city = document.getElementById('validationDefault08');
    var state = document.getElementById('validationDefault09');
    var zip = document.getElementById('validationDefault10');
    var property_type = document.getElementById('validationDefault12');
    var tm_preference = document.getElementById('tmPreference');
    var finance = document.getElementById('finance');
    console.log("FINANCE", finance);
    var contact_name = document.getElementById('contactName');



    // Check representing value
    if (stage.value === 'Under Contract' && representing.value === 'Seller') {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(tm_preference, true);
        toggleValidation(contact_name, true);
        toggleValidation(property_type, true);
    } else if (stage.value === 'Under Contract' && representing.value === 'Buyer') {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(tm_preference, true);
        toggleValidation(contact_name, true);
        toggleValidation(property_type, true);
        if (finance) {
            toggleValidation(finance, true);
        }
    } else if (stage.value === 'Under Contract') {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(property_type, true);
    } else {
        toggleValidation(address, false);
        toggleValidation(city, false);
        toggleValidation(state, false);
        toggleValidation(zip, false);
        toggleValidation(tm_preference, false);
        toggleValidation(contact_name, false);
        toggleValidation(property_type, false);
        if (finance) {
            toggleValidation(finance, false);
        }
    }

    });
            // Function to populate client information
            window.addTask= function(deal) {
                var subject = document.getElementsByName("subject")[0].value;
                if (subject.trim() === "") {
                    document.getElementById("subject_error").innerHTML = "please enter details";
                }
                // var whoSelectoneid = document.getElementsByName("who_id")[0].value;
                // var whoId = window.selectedTransation
                // if (whoId === undefined) {
                //     whoId = whoSelectoneid
                // }
                var dueDate = document.getElementsByName("due_date")[0].value;
                
                var formData = {
                    "data": [{
                        "Subject": subject,
                        // "Who_Id": {
                        //     "id": whoId
                        // },
                        "Status": "Not Started",
                        "Due_Date": dueDate,
                        // "Created_Time":new Date()
                        "Priority": "High",
                        "What_Id":{
                            "id":deal
                        },
                        "$se_module":"Deals"
                    }],
                    "_token": '{{ csrf_token() }}'
                };
                console.log("formData",formData);
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

            {{-- window.updateNote= function(noteId) {
                var subject = document.getElementsByName("subject")[0].value;
                if (subject.trim() === "") {
                    document.getElementById("subject_error").innerHTML = "please enter details";
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
                        "Priority": "High",
                        "What_Id":{
                            "id":deal
                        },
                        "$se_module":"Deals"
                    }],
                    "_token": '{{ csrf_token() }}'
                };
                console.log("formData",formData);
                $.ajax({
                    url: '{{ route('update.note') }}',
                    type: 'PUT',
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
            } --}}

       


        
        


</script>
@section('pipelineScript')

@endsection
@endsection