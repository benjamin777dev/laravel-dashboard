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
            <div class="commonFlex cpbutton">
            <a onclick="createContact();">
                <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    New Contact
                </div>
            </a>
            <a onclick="createTransaction();">
                <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-plus plusicon">
                    </i>
                    New Transaction
                </div>
            </a>
            </div>
        </div>
        <div class="pfilterDiv">
            <div class="pcommonFilterDiv">
                <input placeholder="Search" class="psearchInput" id="contactSearch" oninput="fetchContact(event)"/>
                <i class="fas fa-search search-icon"></i>
            </div>
            <p class="porText">or</p>
            <div class="col-md-5">
                @php
                    $abcd = ['A+', 'A', 'B', 'C', 'D'];
                @endphp
                <div class="row" style="gap:24px;flex-wrap:nowrap;">
                    <div class="psortFilterDiv col-md-6">
                        <select name="abcd_class" onchange="fetchContact(event)" class="psearchInput" id="contactSort">
                            <option selected value="">-None-</option>
                            @foreach ($abcd as $abcdIndex)
                                <option value="{{ $abcdIndex }}">{{ $abcdIndex }}</option>
                            @endforeach
                        </select>
                        {{-- <input placeholder="Sort contacts by..." id="pipelineSort" class="psearchInput" /> --}}
                        {{-- <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon"
                            class="ppipelinesorticon"> --}}
                    </div>
                </div>
                
            </div>
            <div class="input-group-text cursor-pointer pfilterBtn col-md-6" id="btnGroupAddon"
                        data-bs-toggle="modal" data-bs-target="#filterModal"> <i class="fas fa-filter"></i>
                Filter
            </div>
        </div>

        <div class="contactlist" id="contactlist">
            @include('contacts.contact', ['contacts' => $contacts])
            <!-- Filter Modal -->
            <div class="modal fade" id="filterModal" id="staticBackdrop" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Missing Fields</h1>
                            <button type="button" onclick="resetFilters()"
                                class="btn btn-secondary w-auto filterClosebtn m-4">Reset</button>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>

                        </div>
                        <div class="modal-body filter_model">
                            <div class="fil_email d-con">
                                <input type="checkbox" value="Email" id="filterEmail" />Email
                            </div>
                            <div class="fil_mobile d-con">
                                <input type="checkbox" value="Mobile" id="filterMobile" />Mobile
                            </div>
                            <div class="fil_ABCD d-con">
                                <input type="checkbox" value="ABCD" id="filterABCD" />ABCD
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary w-auto filterClosebtn"
                                data-bs-dismiss="modal">Close</button>
                            <button type="button" class="pfilterBtn w-auto" onclick="applyFilter()">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="datapagination">
                @include('common.pagination', ['module' => $contacts])
            </div>
        </div>
    </div>

@endsection
<script src="{{ URL::asset('http://[::1]:5173/resources/js/toast.js') }}"></script>
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

    function applyFilter() {
        let email = document.getElementById('filterEmail').checked;
        let mobile = document.getElementById('filterMobile').checked;
        let abcd = document.getElementById('filterABCD').checked;
        console.log("Filter applied with Email: " + email + ", Mobile: " + mobile + ", ABCD: " + abcd);
        let filter = {
            email: email,
            mobile: mobile,
            abcd: abcd
        }


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
                noteContainer.empty();
                // Loop through each note in the response array
                response?.forEach(function(note) {
                    // console.log(note, 'note')
                    // Create HTML to display note content and creation time
                    let data = `<div class="noteCardForContact">
                        <p>Note Content: ${note?.contact_data?.first_name} ${note?.contact_data?.last_name}</p>
                        <p>Note Content: ${note?.note_content}</p>
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
                showToastError(error);
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
                const contactList = $('.contactlist');

                // Clear existing contact cards
                contactList.empty();
                if (data?.data?.length === 0) {
                    contactList.append('<p class="text-center">No records found.</p>');
                } else {
                    // Iterate over each contact
                    data?.data?.forEach(function(contact) {
                        contactList.html(data);
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

    function fetchContact(e, sortField, sortDirection) {
        const searchInput = $('#contactSearch');
        var csearch = $('#contactSort');
        var filterVal = csearch.val();
        if (e.target.id === "contactSearch") {
            if (searchInput.val().trim() !== "") {
                csearch.val("");
            }
        }
        if (e.target.id === "contactSort") {
            if (csearch.val().trim() !== "") {
                searchInput.val("");
            }
        }


        // var filterVal = selectedModule.val();
        // Call fetchData with the updated parameters
        filterContactData(sortField, sortDirection, searchInput, filterVal);
    }

    function resetValidation() {
        document.getElementById("subject_error").innerHTML = "";
        document.getElementById('darea').value = "";
    }

    function resetFilters() {
        document.getElementById('filterEmail').checked = false;
        document.getElementById('filterMobile').checked = false;
        document.getElementById('filterABCD').checked = false;
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

    function editText(zohoID, name, value) {
        event.preventDefault();
        let firstNameElement = document.getElementById(name + zohoID);
        var text = firstNameElement.textContent.trim();
        text === "" ? text = value : text;
        firstNameElement.innerHTML =
            '<input type="text" class="inputDesign" id="edit' + name + zohoID +
            '" value="' + value + '" >';
        let inputElementmake = document.getElementById('edit' + name + zohoID);
        inputElementmake.focus();
        inputElementmake.selectionStart = firstNameElement.selectionEnd = text.length;
        inputElementmake.addEventListener('change', function() {
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
                if (xhr?.responseJSON?.status === 401) {
                    showToastError(xhr?.responseJSON?.error);

                }
                // Handle error response


            }
        })



    }

     function createTransaction() {
        console.log("Onclick");
        var formData = {
            "data": [{
                "Deal_Name": "{{ config('variables.dealName') }}",
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}"
                },
                "Stage": "Potential",
                // "Client_Primary_Name":,
                // "Client_Name_Only":
                // "Contact":{
                //     "Name":,
                //     "id"
                // }
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
