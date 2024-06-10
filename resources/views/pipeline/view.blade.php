@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline Create')

@section('content')
@vite(['resources/css/pipeline.css'])
@vite(['resources/js/toast.js'])
<script>
    function editText(zohoID, name, value) {
        event.preventDefault();
        let dealNameElement = document.getElementById(name + zohoID);
        var text = dealNameElement.textContent.trim();
        text === "" ? text = value : text;
        dealNameElement.innerHTML =
            '<input type="text" class="inputDesign" id="edit' + name + zohoID +
            '" value="' + value + '" >';
        let inputElementmake = document.getElementById('edit' + name + zohoID);
        inputElementmake.focus();
        inputElementmake.selectionStart = dealNameElement.selectionEnd = text.length;
        inputElementmake.addEventListener('change', function() {
            dealNameElement.innerHTML = '<h5 class="card-title" id="' + name + zohoID + '">' + inputElementmake
                .value + '</h5>';
            updateContact(zohoID, name);

        });
        // Prevent default action when clicking on container
        let container = document.getElementById("contactlist");
        container?.addEventListener("click", function(event) {
            event.preventDefault();
        });
    }

</script>
<div class="container-fluid">
    <div class="commonFlex ppipeDiv">
        <p class="pText">{{ $deal['deal_name'] }}</p>
        <div class="npbtnsDiv">
            {{--<div class="input-group-text text-white justify-content-center npdeleteBtn" id="btnGroupAddon"
                data-bs-toggle="modal" data-bs-target="#">
                <img src="{{ URL::asset('/images/delete.svg') }}" alt="Delete">
                Delete
            </div>--}}
            <a onclick="updateDataDeal('{{$deal['zoho_deal_id']}}','{{$deal['id']}}')">
                <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton"
                    data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-edit">
                    </i>
                    Update
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-sm-12 dtasksection">
            <div class="d-flex justify-content-between">
                <p class="dFont800 dFont15">Tasks</p>
                <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                    id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId{{$deal['id']}}"><i
                        class="fas fa-plus plusicon">
                    </i>
                    New Task
                </div>

            </div>
            <div class="row">
                <nav class="dtabs">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a href="/pipeline-view/{{ $deal['id'] }}?tab=In Progress"> <button
                                class="nav-link dtabsbtn active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" data-tab='In Progress' type="button" role="tab"
                                aria-controls="nav-home" aria-selected="true">In
                                Progress</button></a>
                        <a href="/pipeline-view/{{ $deal['id'] }}?tab=Not Started"> <button class="nav-link dtabsbtn"
                                data-tab='Not Started' id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Upcoming</button></a>
                        <a href="/pipeline-view/{{ $deal['id'] }}?tab=Completed"><button class="nav-link dtabsbtn"
                                data-tab='Overdue' id="nav-contact-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false">Overdue</button></a>
                    </div>
                </nav>
                @include('common.tasks', [
                'tasks' => $tasks,
                'retrieveModuleData' => $retrieveModuleData,
                ])
            </div>
            @include('common.notes.view', [
            'notesInfo' => $notesInfo,
            'retrieveModuleData' => $retrieveModuleData,
            'module' => 'Deals',
            ])
        </div>
    </div>
    {{-- information form --}}
    <div class="detailedPipeline">
    </div>

    {{-- contact roles --}}
    @include('contactRole.index',['dealContacts'=>$dealContacts])

    {{-- Add New Submittal --}}
    <div class="showsubmittal">
    </div>


    @if($deal['tm_preference'] == "Non-TM")
    {{-- Non-TM Check request --}}
    <div class="table-responsive dtranstiontable mt-3">
        <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
            <p class="nproletext">Non-TM Check request</p>
            <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#">
                <i class="fas fa-plus plusicon">
                </i>
                Add Non-TM Check request
            </div>

        </div>
        <div class="row npNom-TM-Table">
            <div class="col-md-4 ">Number</div>
            <div class="col-md-4 ">Close Date</div>
            <div class="col-md-4 ">Created Time</div>
        </div>
        @if ($nontms->isEmpty())

        <div>
            <p class="text-center notesAsignedText">No Non-TM assigned</p>

        </div>
        @else

        @foreach ($nontms as $nontm)
        <div class="row npNom-TM-Body">
            <div class="col-md-4 ">{{ $nontm['name'] }}</div>
            <div class="col-md-4 ">{{ $nontm['closed_date'] }}</div>
            <div class="col-md-4 commonTextEllipsis">{{ $nontm['created_at'] }}</div>
        </div>
        @endforeach
        @endif

        @foreach ($nontms as $nontm)
        <div class="npNom-TM-Card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="npcommonheaderText">Number</p>
                    <p class="npcommontableBodytext">{{ $nontm['name'] }}</p>
                </div>
                <div>
                    <p class="npcommonheaderText">Close Date</p>
                    <p class="npcommontableBodyDatetext">{{ $nontm['closed_date'] }}</p>
                </div>
            </div>
            <div class="npCardPhoneDiv">
                <p class="npcommonheaderText">Created Time</p>
                <p class="npcommontableBodyDatetext">{{ $nontm['created_at'] }}</p>
            </div>
        </div>
        @endforeach
        <div class="dpagination">
            <nav aria-label="..." class="dpaginationNav">
                <ul class="pagination ppipelinepage d-flex justify-content-end">
                    <li class="page-item disabled">
                        <a class="page-link">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>


    </div>
    @endif
    {{-- Non-TM Check request --}}
    <div class="table-responsive dtranstiontable mt-3">
        <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
            <p class="nproletext">Non-TM Check request</p>
            <div class="dropdown">
                <button class="btn btn-secondary btn-bg dropdown-toggle" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus plusicon"></i>
                    Add Non-TM Check request
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="/non-tm">New</a></li>
                    <li><a class="dropdown-item" href="#">Assign</a></li>
                </ul>
            </div>


        </div>
        <div class="row npNom-TM-Table">
            <div class="col-md-4 ">Number</div>
            <div class="col-md-4 ">Close Date</div>
            <div class="col-md-4 ">Created Time</div>
        </div>
        @if ($nontms->isEmpty())

        <div>
            <p class="text-center notesAsignedText">No Non-TM assigned</p>

        </div>
        @else

        @foreach ($nontms as $nontm)
        <div class="row npNom-TM-Body">
            <div class="col-md-4 ">{{ $nontm['name'] }}</div>
            <div class="col-md-4 ">{{ $nontm['closed_date'] }}</div>
            <div class="col-md-4 commonTextEllipsis">{{ $nontm['created_at'] }}</div>
        </div>
        @endforeach
        @endif

        @foreach ($nontms as $nontm)
        <div class="npNom-TM-Card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="npcommonheaderText">Number</p>
                    <p class="npcommontableBodytext">{{ $nontm['name'] }}</p>
                </div>
                <div>
                    <p class="npcommonheaderText">Close Date</p>
                    <p class="npcommontableBodyDatetext">{{ $nontm['closed_date'] }}</p>
                </div>
            </div>
            <div class="npCardPhoneDiv">
                <p class="npcommonheaderText">Created Time</p>
                <p class="npcommontableBodyDatetext">{{ $nontm['created_at'] }}</p>
            </div>
        </div>
        @endforeach
        <div class="dpagination">
            <nav aria-label="..." class="dpaginationNav">
                <ul class="pagination ppipelinepage d-flex justify-content-end">
                    <li class="page-item disabled">
                        <a class="page-link">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>


    </div>
    {{-- Agent’s Commissions --}}
    <div class="table-responsive dtranstiontable mt-3">
        <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
            <p class="nproletext">Agent’s Commissions</p>
        </div>

        <div class="row npAgentTable">
            <div class="col-md-3 ">Agent’s Name</div>
            <div class="col-md-3 ">IRS 1099 Income for this Transaction</div>
            <div class="col-md-3 ">Less Split to CHR</div>
            <div class="col-md-3 ">Modified Time</div>
        </div>
        @if ($dealaci->isEmpty())

        <div>
            <p class="text-center notesAsignedText">No ACI assigned</p>

        </div>
        @else

        @foreach ($dealaci as $aci)
        <div class="row npAgentBody">
            <div class="col-md-3 ">{{ $aci['agentName'] }}</div>
            <div class="col-md-3 ">${{ $aci['irs_reported_1099_income_for_this_transaction'] }}</div>
            <div class="col-md-3 ">${{ $aci['less_split_to_chr'] }}</div>
            <div class="col-md-3 commonTextEllipsis">{{ $aci['closing_date'] }}</div>
        </div>
        @endforeach
        @endif


        @foreach ($dealaci as $aci)
        <div class="npAgentCard">
            <div>
                <p class="npcommonheaderText">Agent’s Name</p>
                <p class="npcommontableBodytext">{{ $aci['agentName'] }}</p>
            </div>
            <div class="npCardPhoneDiv">
                <p class="npcommonheaderText">IRS 1099 Income for this Transaction</p>
                <p class="npcommontableBodytext">${{ $aci['irs_reported_1099_income_for_this_transaction'] }}</p>
            </div>
            <div>
                <p class="npcommonheaderText">Less Split to CHR</p>
                <p class="npcommontableBodytext">${{ $aci['less_split_to_chr'] }}</p>
            </div>
            <div class="npCardPhoneDiv">
                <p class="npcommonheaderText">Modified Time</p>
                <p class="npcommontableBodyDatetext">{{ $aci['closing_date'] }}</p>
            </div>
        </div>
        @endforeach

        <div class="dpagination">
            <nav aria-label="..." class="dpaginationNav">
                <ul class="pagination ppipelinepage d-flex justify-content-end">
                    <li class="page-item disabled">
                        <a class="page-link">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>


    </div>

</div>
<div class="dnotesBottomIcon" type="button" data-bs-toggle="modal"
    data-bs-target="#staticBackdropforNote_{{ $deal['id'] }}">
    <div class="tooltip-wrapper">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
        <span class="tooltiptext">Add Notes</span>
    </div>
</div>
{{-- Create New Task Modal --}}
@include('common.tasks.create', ['deal' => $deal, 'type' => 'Deals'])
{{-- Notes Model --}}
@include('common.notes.create', ['deal' => $deal, 'type' => 'Deals'])

@vite(['resources/js/pipeline.js'])

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dealId = @json($dealId);
        getPipeline(dealId);
        getSubmittals(dealId);
       
    })
    // Function to populate client information
    function getPipeline(dealId) {
        $.ajax({
        url: '{{ route("pipeline.view", ":dealId") }}'.replace(':dealId', dealId),
        type: 'GET',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: 'application/json',
        
        success: function (response) {
        /* if (response?.data && response.data[0]?.message) {
        // Convert message to uppercase and then display
        const upperCaseMessage = response.data[0].message.toUpperCase();
        showToast(upperCaseMessage);
        // window.location.reload();
        } */
        $('.detailedPipeline').html(response)
        },
        error: function (xhr, status, error) {
        // Handle error response
        console.error(xhr.responseText);
        }
        })
    }
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
    window.addTask = function (deal) {
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
                "What_Id": {
                    "id": deal
                },
                "$se_module": "Deals"
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

    window.updateDeal = function (dealId) {
        console.log(dealId);
        // Retrieve values from form fields
        var client_name_primary = $('#validationDefault01').val();
        var representing = $('#validationDefault02').val();
        var deal_name = $('#validationDefault03').val();
        var stage = $('#validationDefault04').val();
        var sale_price = $('#validationDefault05').val();
        var closing_date = $('#validationDefault06').val();
        var address = $('#validationDefault07').val();
        var city = $('#validationDefault08').val();
        var state = $('#validationDefault09').val();
        var zip = $('#validationDefault10').val();
        var commission = $('#validationDefault11').val();
        var property_type = $('#validationDefault12').val();
        var ownership_type = $('#validationDefault13').val();
        var potential_gci = $('#validationDefault14').val();
        var pipeline_probability = $('#validationDefault15').val();
        var probable_gci = $('#validationDefault16').val();
        var personal_transaction = $('#flexCheckChecked01').prop('checked');
        var double_ended = $('#flexCheckChecked02').prop('checked');
       
        // Create formData object
        var formData = {
            "data": [{
                "Client_Name_Primary": (client_name_primary.first_name || "") + " " + (client_name_primary.last_name || ""),
                        "Client_Name_Only": (client_name_primary.first_name || "") + " " + (client_name_primary.last_name || "") + " || " + client_name_primary.zoho_contact_id,
                "Representing": representing,
                "Deal_Name": deal_name,
                "Stage": stage,
                "Sale_Price": sale_price,
                "Closing_Date": closing_date,
                "Address": address,
                "City": city,
                "State": state,
                "Zip": zip,
                "Commission": commission,
                "Property_Type": property_type,
                "Ownership_Type": ownership_type,
                "Potential_GCI": potential_gci,
                "Pipeline_Probability": pipeline_probability,
                "Pipeline1": probable_gci,
                "Personal_Transaction": personal_transaction,
                "Double_Ended": double_ended,
                "Contact":{
                            "Name":(client_name_primary.first_name || "") + " " + (client_name_primary.last_name || ""),
                            "id":client_name_primary.zoho_contact_id
                        }
            }],
            "_token": '{{ csrf_token() }}'
        };
        console.log("formData", formData, dealId);

        // Send AJAX request
        $.ajax({
            url: "{{ route('pipeline.update', ['dealId' => ':id']) }}".replace(':id', dealId),
            type: 'PUT',
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
</script>
@section('pipelineScript')

@endsection
@endsection