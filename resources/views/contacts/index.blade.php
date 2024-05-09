@extends('layouts.master')
@section('title', 'Agent Commander | Contacts')

@section('content')
    @vite(['resources/css/custom.css'])
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
            <p class="pText">Database</p>
            <a onclick="createContact();">
                <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    New Contact
                </div>
            </a>
        </div>
        {{-- <div class="pfilterDiv">
            <div class="pcommonFilterDiv">
                <input placeholder="Search" class="psearchInput" id="pipelineSearch" />
                <i class="fas fa-search search-icon"></i>
            </div>
            <p class="porText">or</p>
            <div class="pcommonFilterDiv">
                <input placeholder="Sort contacts by..." id="pipelineSort" class="psearchInput" />
                <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon" class="ppipelinesorticon">
            </div>
            <div class="input-group-text pfilterBtn" id="btnGroupAddon"> <i class="fas fa-filter"></i>
                Filter
            </div>
        </div> --}}

        <div class="row g-4 database-filter-div">
            <div class="col-md-4">
                <div class="row align-items-center" style="gap:12px">
                    <div class="col-md-10 pcommonFilterDiv">
                        <input placeholder="Search" class="psearchInput" id="contactSearch" />
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    <p class="col-md-1 porText">or</p>
                </div>
            </div>
            <div class="col-md-5">
                @php
                    $abcd = ['A+', 'A', 'B', 'C', 'D'];
                @endphp
                <div class="row" style="gap:24px">
                    <div class="psortFilterDiv col-md-6">
                        <select name="abcd_class" class="psearchInput" id="contactSort">
                            <option selected value="">-None-</option>
                            @foreach ($abcd as $abcdIndex)
                                <option value="{{ $abcdIndex }}">{{ $abcdIndex }}</option>
                            @endforeach
                        </select>
                        {{-- <input placeholder="Sort contacts by..." id="pipelineSort" class="psearchInput" /> --}}
                        {{-- <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon"
                            class="ppipelinesorticon"> --}}
                    </div>

                    <div class="input-group-text pfilterBtn col-md-6" onclick="fetchContact()" id="btnGroupAddon"> <i
                            class="fas fa-filter"></i>
                        Filter
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-3 cardsTab">
                <div class="viewCards">
                    <img src="{{ URL::asset('/images/person_pin.svg') }}" class="viewCardsImg" alt="">

                    <p class="viewCardsP">View as Cards</p>
                </div>
                <div class="viewMap">
                    <img src="{{ URL::asset('/images/universal_local.svg') }}" class="viewMapImg" alt="">
                    <p class="viewMapP">View on Map

                    </p>
                </div>
            </div> --}}
        </div>

        <div class="contactlist" id="contactlist">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3 ">

                @foreach ($contacts as $contact)
                    <a id="taskRoute" href="{{ route('contacts.show', $contact['id']) }}">
                        <div class="col">
                            <div class="card dataCardDiv">
                                <div class="card-body dacBodyDiv">
                                    <div class="d-flex justify-content-between align-items-center dacHeaderDiv">
                                        <div class="d-flex gap-2" style="max-width: 83%;"
                                            onclick="editText('{{ $contact['zoho_contact_id'] }}','first_name')">
                                            <h5 class="card-title" id="first_name{{ $contact['zoho_contact_id'] }}">
                                                {{ $contact['first_name'] . ' ' . $contact['last_name'] ?? 'N/A' }}</h5>
                                        </div>

                                        <p class="databaseCardWord">
                                            {{ $contact['abcd'] ?? '-' }}</p>
                                    </div>
                                    <div class="dataPhoneDiv">
                                        <img src="{{ URL::asset('/images/phone.svg') }}" alt=""
                                            class="dataphoneicon">

                                        <div class="d-flex gap-2"
                                            onclick="editText('{{ $contact['zoho_contact_id'] }}','mobile')">
                                            <p id="mobile{{ $contact['zoho_contact_id'] }}" class="card-text">
                                                {{ $contact['mobile'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="datamailDiv">
                                        <img src="{{ URL::asset('/images/mail.svg') }}" alt=""
                                            class="datamailicon">
                                        <div class="d-flex gap-2 overflow-hidden"
                                            onclick="editText('{{ $contact['zoho_contact_id'] }}','email')">
                                            <p id="email{{ $contact['zoho_contact_id'] }}" class="dataEmailtext">
                                                {{ $contact['email'] ?? 'N/A' }}</p>

                                        </div>
                                    </div>
                                    <div class="datadiversityDiv">
                                        <img src="{{ URL::asset('/images/diversity.svg') }}" alt=""
                                            class="datadiversityicon">
                                        <p class="datadiversitytext">2nd</p>
                                    </div>
                                </div>
                                <div class="card-footer dataCardFooter">
                                    <div class="datafootericondiv" onclick="event.preventDefault();">

                                        <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                                            class="datadiversityicon" data-bs-toggle="modal"
                                            data-bs-target="#newTaskContactModalId{{ $contact['zoho_contact_id'] }}">
                                        <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                                            class="datadiversityicon"
                                            onclick="fetchNotesForContact('{{ $contact['id'] }}','{{ $contact['zoho_contact_id'] }}')">
                                    </div>
                                    <div onclick="event.preventDefault();" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdropforNote_{{ $contact['id'] }}">
                                        <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                                            class="datadiversityicon">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- task create model --}}
                        <div class="modal fade" onclick="event.preventDefault();"
                            id="newTaskContactModalId{{ $contact['zoho_contact_id'] }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content dtaskmodalContent">
                                    <div class="modal-header border-0">
                                        <p class="modal-title dHeaderText">Create New Tasks</p>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            onclick="resetValidation()" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body dtaskbody">
                                        <p class="ddetailsText">Details</p>
                                        <textarea name="subject" onkeyup="validateTextarea();" id="darea" rows="4" class="dtextarea"></textarea>
                                        <div id="subject_error" class="text-danger"></div>
                                        <p class="dRelatedText">Related to...</p>
                                        <div class="btn-group dmodalTaskDiv">
                                            <select class="form-select dmodaltaskSelect" onchange="selectedElement(this)"
                                                id="who_id" name="who_id" aria-label="Select Transaction">
                                                <option value="{{ $contact['zoho_contact_id'] }}"
                                                    @if (old($contact['zoho_contact_id']) == $contact['zoho_contact_id']) selected @endif>
                                                    {{ $contact['first_name'] . ' ' . $contact['last_name'] }}</option>


                                            </select>
                                        </div>
                                        <p class="dDueText">Date due</p>
                                        <input type="date" name="due_date" class="dmodalInput" />
                                    </div>
                                    <div class="modal-footer ">
                                        <button type="button"
                                            onclick="addTaskforContact('{{ $contact['zoho_contact_id'] }}')"
                                            class="btn btn-secondary taskModalSaveBtn">
                                            <i class="fas fa-save saveIcon"></i> Save Changes
                                        </button>

                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- fetch details notes related 0 --}}
                        <div class="modal fade testing" onclick="event.preventDefault();"
                            id="notefetchrelatedContact{{ $contact['zoho_contact_id'] }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content dtaskmodalContent">
                                    <div class="modal-header border-0">
                                        <p class="modal-title dHeaderText">Notes</p>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            onclick="resetValidation()" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body dtaskbody" id="notesContainer">

                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="savemakeModalId{{ $contact['zoho_contact_id'] }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content">
                                    <div class="modal-header saveModalHeaderDiv border-0">
                                        {{-- <h5 class="modal-title">Modal title</h5> --}}
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body saveModalBodyDiv">
                                        <p class="saveModalBodyText" id="updated_message_make">
                                            Changes have been saved</p>
                                    </div>
                                    <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                                        <div class="d-grid col-12">
                                            <button type="button" class="btn btn-secondary saveModalBtn"
                                                data-bs-dismiss="modal">
                                                <i class="fas fa-check trashIcon"></i>
                                                Understood
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        @include('common.notes.create', ['contact' => $contact])
                    </a>
                @endforeach
            </div>
            <div class="datapagination">
                @include('common.pagination', ['module' => $contacts])
            </div>
        </div>
    </div>

@endsection
<script>
    window.onload = function() {
        @foreach ($contacts as $contact)
            var noteTextElement = document.getElementById("note_text{{ $contact['zoho_contact_id'] }}");
            var relatedToElement = document.getElementById("related_to{{ $contact['zoho_contact_id'] }}");
            if (noteTextElement && relatedToElement) {
                noteTextElement.addEventListener("keyup", function() {
                    validateFormc("", "{{ $contact['zoho_contact_id'] }}");
                });
                relatedToElement.addEventListener("change", function() {
                    validateFormc("", "{{ $contact['zoho_contact_id'] }}");
                });
            } else {
                console.log("One or both elements not found for contact ID {{ $contact['zoho_contact_id'] }}");
            }
        @endforeach
    }

    function createContact() {
        console.log("Onclick");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let name = "CHR";
        var formData = {
            "data": [{
                "Relationship_Type": "Primary",
                "Missing_ABCD": true,
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}",
                    "full_name": "{{ auth()->user()->name }}",
                },
                "Unsubscribe_From_Reviews": false,
                "Currency": "USD",
                "Market_Area": "-None-",
                "Lead_Source": "-None-",
                "ABCD": "-None-",
                "Last_Name": name,
                "zia_suggested_users": {}
            }],
            "_token": '{{ csrf_token() }}'
        };
        $.ajax({
            url: '{{ url('/contact/create') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(data) {
                console.log(data);
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/contacts-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }


    function getColorByAbcd(abcd) {
        return '#b5b5b5';
    }

    function validateFormc(submitClick = '', modId = "") {
        let noteText = document.getElementById("note_text" + modId).value;
        let relatedTo = document.getElementById("related_to" + modId).value;
        let isValid = true;
        console.log(noteText, 'text')
        // Reset errors
        document.getElementById("note_text_error" + modId).innerText = "";
        document.getElementById("related_to_error" + modId).innerText = "";
        // Validate note text length
        if (noteText.trim().length > 50) {
            document.getElementById("note_text_error" + modId).innerText = "Note text must be 10 characters or less";
            isValid = false;
        }
        // Validate note text
        if (noteText.trim() === "") {
            console.log("yes here sdklfhsdf");
            document.getElementById("note_text_error" + modId).innerText = "Note text is required";
            isValid = false;
        }

        // Validate related to
        if (relatedTo === "") {
            document.getElementById("related_to_error" + modId).innerText = "Related to is required";
            document.getElementById("taskSelect" + modId).style.display = "none";
            isValid = false;
        }
        if (isValid) {
            let changeButton = document.getElementById('validate-button' + modId);
            changeButton.type = "submit";
            if (submitClick === "submit") $('[data-custom="noteModal"]').removeAttr("onclick");

        }
        return isValid;
    }


    function fetchNotesForContact(id, conId) {
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('notes.fetch', ['contactId' => ':contactId']) }}".replace(':contactId', id),
            method: "GET",
            dataType: "json",

            success: function(response) {
                // $('#notesContainer').append('<p>New Note Content</p>');
                let noteContainer = $("#notesContainer");
                console.log(noteContainer, 'noteContainer')
                // Clear previous contents of note containe
                // noteContainer.empty();
                // Loop through each note in the response array
                response?.forEach(function(note) {
                    console.log(note,'note')
                    // Create HTML to display note content and creation time
                    let data = `<div class="note">
                        <h1 id="${note.id}">Note ID: ${note.id}</h1>
                        <p>Note Content: ${note.note_content}</p>
                        <p>Created Time: ${note.created_time}</p>
                    </div>`;
                    // Append the HTML to noteContainer
                    noteContainer.append(data);
                    console.log("testing", noteContainer)
                });
                // Show the modal after appending notes
                $("#notefetchrelatedContact" + conId).modal('show');


            },
            error: function(xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });

    }


    function filterContactData(sortField, sortDirection, searchInput, filterVal) {
        const searchValue = searchInput.val().trim();
        $.ajax({
            url: '{{ url('/contacts/fetch-contact') }}',
            method: 'GET',
            data: {
                search: encodeURIComponent(searchValue),
                filter: filterVal,

            },
            dataType: 'json',
            success: function(data) {
                // Select the contact list container
                const contactList = $('.contactlist .row');

                // Clear existing contact cards
                contactList.empty();
                if (data?.data?.length === 0) {
                    contactList.append('<p class="text-center">No records found.</p>');
                } else {
                    // Iterate over each contact
                    data?.data?.forEach(function(contact) {
                        // Generate HTML for the contact card using the template
                        const cardHtml = `
            <a href="{{ route('contacts.show', $contact['id']) }}">
                <div class="col">
                    <div class="card dataCardDiv">
                        <div class="card-body dacBodyDiv">
                        <div class="d-flex justify-content-between align-items-center dacHeaderDiv">
                            <h5 class="card-title" id="first_name${contact.zoho_contact_id}" onclick="editText('${contact.zoho_contact_id}','first_name')">${contact.first_name ?? 'N/A'}</h5>
                            <p class="databaseCardWord" style="background-color: ${getColorByAbcd(contact.abcd)};">${contact.abcd ?? '-'}</p>
                        </div>
                        <div class="dataPhoneDiv">
                            <img src="{{ URL::asset('/images/phone.svg') }}" alt="" class="dataphoneicon">
                            <p id="mobile${contact.zoho_contact_id}" onclick="editText('${contact.zoho_contact_id}','mobile')" class="card-text">${contact.mobile ?? 'N/A'}</p>
                        </div>
                        <div class="datamailDiv">
                            <img src="{{ URL::asset('/images/mail.svg') }}" alt="" class="datamailicon">
                            <p id="email${contact.zoho_contact_id}" onclick="editText('${contact.zoho_contact_id}','email')"  class="dataEmailtext">${contact.email ?? 'N/A'}</p>
                        </div>
                        <div class="datadiversityDiv">
                            <img src="{{ URL::asset('/images/diversity.svg') }}" alt="" class="datadiversityicon">
                            <p class="datadiversitytext">2nd</p>
                        </div>
                    </div>
                    <div class="card-footer dataCardFooter">
                        <div class="datafootericondiv">
                            <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt="" class="datadiversityicon">
                            <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="" class="datadiversityicon">
                        </div>
                        <div>
                            <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="" class="datadiversityicon" onclick="event.preventDefault(),showPopup(${contact?.id});" data-bs-target=#newTaskNoteModalId${contact?.id}>
                        </div>

                        <div class="modal fade" id="savemakeModalId${contact.zoho_contact_id}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content">
                                    <div class="modal-header saveModalHeaderDiv border-0">
                                        {{-- <h5 class="modal-title">Modal title</h5> --}}
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body saveModalBodyDiv">
                                        <p class="saveModalBodyText" id="updated_message_make">
                                            Changes have been saved</p>
                                    </div>
                                    <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                                        <div class="d-grid col-12">
                                            <button type="button" class="btn btn-secondary saveModalBtn"
                                                data-bs-dismiss="modal">
                                                <i class="fas fa-check trashIcon"></i>
                                                Understood
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div> 
        <div class="modal fade" onclick="event.preventDefault();"
                            id=newTaskNoteModalId${contact?.id} data-bs-backdrop="static"
                            data-bs-keyboard="false" data-custom="noteModal" tabindex="-1"
                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content noteModal">
                                    <div class="modal-header border-0">
                                        <p class="modal-title dHeaderText">Note</p>
                                        <button type="button"
                                            onclick="resetFormAndHideSelect(${contact.zoho_contact_id});"
                                            class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="noteForm${contact.zoho_contact_id}"
                                    action="{{ route('save.note') }}?conId=${Number(contact?.id)}" method="post">
                                        @csrf
                                        <div class="modal-body dtaskbody">
                                            <p class="ddetailsText">Details</p>
                                            <textarea name="note_text" id="note_text${contact.zoho_contact_id}" rows="4" class="dtextarea"></textarea>
                                            <div id="note_text_error${contact.zoho_contact_id}"
                                                class="text-danger"></div>
                                            <p class="dRelatedText">Related to...</p>
                                            <div class="btn-group dmodalTaskDiv">
                                                <select class="form-select dmodaltaskSelect"
                                                    id="related_to${contact.zoho_contact_id}"
                                                    onchange="moduleSelectedforContact(this,'${contact.zoho_contact_id}')"
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
                                                    id="taskSelect${contact.zoho_contact_id}"
                                                    name="related_to_parent" aria-label="Select Transaction"
                                                    style="display: none;">
                                                    <option value="">Please Select one</option>
                                                </select>
                                            </div>
                                            <div id="related_to_error${contact.zoho_contact_id}"
                                                class="text-danger"></div>
                                        </div>
                                        <div class="modal-footer dNoteFooter border-0">
                                            <button type="button" id="validate-button${contact.zoho_contact_id}"
                                                onclick="validateFormc('submit','${contact.zoho_contact_id}')"
                                                class="btn btn-secondary dNoteModalmarkBtn">
                                                <i class="fas fa-save saveIcon"></i> Add Note
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

            </a>
            
        `;
                        // Append the contact card HTML to the contact list container
                        contactList.append(cardHtml);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function moduleSelectedforContact(selectedModule, conId) {
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
            url: '/task/get-' + selectedText + '?contactId=' + conId,
            method: "GET",
            dataType: "json",

            success: function(response) {
                // Handle successful response
                var tasks = response;
                // Assuming you have another select element with id 'taskSelect'
                var taskSelect = $('#taskSelect' + conId);
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
                        console.log(task);
                        taskSelect.append($('<option>', {
                            value: task?.zoho_contact_id,
                            text: task?.first_name ?? "" + ' ' + task?.last_name ?? "",
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

    function showPopup(contact) {
        new bootstrap.Modal(document.getElementById('newTaskNoteModalId' + contact)).show();
    }

    function fetchContact(sortField, sortDirection) {
        const searchInput = $('#contactSearch');
        var csearch = $('#contactSort');
        var filterVal = csearch.val();
        // var filterVal = selectedModule.val();
        // Call fetchData with the updated parameters
        filterContactData(sortField, sortDirection, searchInput, filterVal);
    }

    function resetValidation() {
        document.getElementById("subject_error").innerHTML = "";
        document.getElementById('darea').value = "";
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

    // function taskCreate(event,conId){
    //     event.preventDefault(); // Prevent the default action  
    // }
    function addTaskforContact(conID) {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML = "please enter details";
            return;
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
            success: function(response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    alert(upperCaseMessage);
                    window.location.reload();
                } else {
                    alert("Response or message not found");
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }

    function editText(zohoID, name) {
        event.preventDefault();
        let firstNameElement = document.getElementById(name + zohoID);
        console.log(firstNameElement, name, zohoID, 'testing it');
        var text = firstNameElement.textContent.trim();
        firstNameElement.innerHTML =
            '<input type="text" class="inputDesign" onclick="event.preventDefault();" id="edit' + name + zohoID +
            '" value="' + text + '" />';
        let inputElementmake = document.getElementById('edit' + name + zohoID);
        inputElementmake.focus();
        inputElementmake.addEventListener('blur', function() {
            firstNameElement.innerHTML = '<h5 class="card-title" id="' + name + zohoID + '">' + inputElementmake
                .value + '</h5>';
            updateContact(zohoID, name);

        });
        // Prevent default action when clicking on container
        let container = document.getElementById("contactlist");
        container?.addEventListener("click", function(event) {
            event.preventDefault();
        });
    }

    function formatSentence(sentence) {
        // Convert the first character to uppercase and the rest to lowercase
        return sentence.charAt(0).toUpperCase() + sentence.slice(1).toLowerCase();
    }

    function updateContact(zohoID, name) {
        let elementId = document.getElementById(name + zohoID);
        console.log(name, 'eleme');
        let formData = {
            "data": [{
                "Missing_ABCD": true,
                "Owner": {
                    "id": '{{ auth()->user()->root_user_id }}',
                    "full_name": '{{ auth()->user()->name }}'
                },
                "Unsubscribe_From_Reviews": false,
                "Currency": "USD",
                "First_Name": name == "first_name" ? elementId.textContent : undefined,
                "Mobile": name == "mobile" ? elementId.textContent : undefined,
                // "ABCD": "",
                "Email": name == "email" ? elementId.textContent : undefined,
                "zia_suggested_users": []
            }],
            "skip_mandatory": true
        }
        // Iterate through the data array
        formData?.data?.forEach(obj => {
            // Iterate through the keys of each object
            Object.keys(obj).forEach(key => {
                // Check if the value is undefined and delete the key
                if (obj[key] === undefined) {
                    delete obj[key];
                }
            });
        });
        //ajax call hitting here
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('update.contact', ['id' => ':id']) }}".replace(':id', zohoID),
            method: 'PUT',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                console.log(response)
                // Handle success response
                if (response?.data[0]?.status == "success") {
                    if (!document.getElementById('savemakeModalId' + zohoID).classList.contains('show')) {
                        var modalTarget = document.getElementById('savemakeModalId' + zohoID);
                        var update_message = document.getElementById('updated_message_make');
                        update_message.textContent = formatSentence(response?.data[0]?.message);
                        // Show the modal
                        $(modalTarget).modal('show');
                        window.location.reload();
                    }

                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText, 'errrorroororooro');


            }
        })



    }
</script>
