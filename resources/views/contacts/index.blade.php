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
    <div class="container-fluid">
        <div class="loader" id="loaderfor" style="display: none;"></div>
        <div class="loader-overlay" id="loaderOverlay" style="display: none;"></div>
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
                <input placeholder="Search" class="psearchInput" id="contactSearch" oninput="fetchContact(event)" />
                <i class="fas fa-search search-icon"></i>
            </div>
            <p class="porText">or</p>
            <div class="psortingFilterDiv">
                @php
                    $abcd = ['A+', 'A', 'B', 'C', 'D'];
                @endphp
                <div class="row" style="gap:24px;flex-wrap:nowrap;">
                    <div class="psortFilterDiv">
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
            <div class="d-flex gap-4">
            <div class="input-group-text cursor-pointer pfilterBtn col-md-6" id="btnGroupAddon" data-bs-toggle="modal"
                data-bs-target="#filterModal"> <i class="fas fa-filter"></i>
                Filter
            </div>
            <div class="input-group-text cursor-pointer pfilterBtn col-md-6" id="btnGroupAddon" data-bs-toggle="modal"
                data-bs-target="#" onclick="applyFilter('reset')"> <i class="fas fa-sync"></i>
                Reset All
            </div>
        </div>
        </div>

        <div class="contactlist" id="contactlist">
            @include('contacts.contact', ['contacts' => $contacts])
            <!-- Filter Modal -->
          
        </div>
        <div class="modal fade" id="filterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Missing Fields</h1>
                        <button type="button" onclick="resetFilters()"
                            class="btn btn-secondary w-auto filterClosebtn m-4">Reset</button>
                        <button id="close_btn" type="button" class="btn-close" data-bs-dismiss="modal"
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
    </div>

@endsection
@vite(['resources/js/toast.js'])
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

    function applyFilter(reset) {
        let email = document.getElementById('filterEmail').checked;
        let mobile = document.getElementById('filterMobile').checked;
        let abcd = document.getElementById('filterABCD').checked;
        var searchInput = $('#contactSearch');
        var csearch = $('#contactSort');
        let count = 1;
        if (!email && !mobile && !abcd && count > 2) return;

        let missingFeild = {
            email: email,
            mobile: mobile,
            abcd: abcd
        }
        if (!missingFeild.email && !missingFeild.mobile && !missingFeild.abcd) {
            missingFeild = "";
        }  
         if (reset) {
            if (searchInput.val().trim() !== "") {
                searchInput.val("");
            }
            if (csearch.val().trim() !== "") {
                csearch.val("");
            }
        }

        filterContactData("", "", "", "", missingFeild,reset);
        count++;


    }

    function createContact() {
        document.getElementById("loaderOverlay").style.display = "block";
        document.getElementById('loaderfor').style.display = "block";
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
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/contacts-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                console.error('Error:', error);
            }
        });
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





    function filterContactData(sortField = "", sortDirection = "", searchInput = "", filterVal = "", missingFeild =
        "",reset) {
        var searchValuetrim = "";
        if (searchInput) {
            searchValuetrim = searchInput?.val().trim();
        }
        if(reset){
          sortField = "", sortDirection = "", searchInput = "", filterVal = "", missingFeild =
        ""  
        }
        var load = true;
        
        $.ajax({
            url: '{{ url('/contacts/fetch-contact') }}',
            method: 'GET',
            data: {
                search: encodeURIComponent(searchValuetrim),
                sort: sortField || "",
                sortType: sortDirection || "",
                filter: filterVal,
                missingField: missingFeild,

            },
            success: function(data) {
                // Select the contact list container
                if (!load) {
                    document.getElementById("loaderOverlay").style.display = "none";
                    document.getElementById('loaderfor').style.display = "none";

                }
                document.getElementById('close_btn').click();
                const card = $('.contactlist').html(data);
            },
            error: function(xhr, status, error) {
                if (!load) {
                    document.getElementById("loaderOverlay").style.display = "none";
                    document.getElementById('loaderfor').style.display = "none";

                }
                console.error('Error:', error);
            }
        });
    }





    function fetchContact(e, sortField, sortDirection) {
        const searchInput = $('#contactSearch');
        var csearch = $('#contactSort');
        var filterVal = csearch.val();
        console.log(sortField, sortDirection, searchInput, filterVal, 'testignnnnnnnnn')
        // var filterVal = selectedModule.val();
        // Call fetchData with the updated parameters
        filterContactData(sortField, sortDirection, searchInput, filterVal);
    }



    function resetFilters() {
        document.getElementById('filterEmail').checked = false;
        document.getElementById('filterMobile').checked = false;
        document.getElementById('filterABCD').checked = false;
        applyFilter();
    }



    // function taskCreate(event,conId){
    //     event.preventDefault(); // Prevent the default action  
    // }




    function formatSentence(sentence) {
        // Convert the first character to uppercase and the rest to lowercase
        return sentence.charAt(0).toUpperCase() + sentence.slice(1).toLowerCase();
    }



    function createTransaction() {
        document.getElementById("loaderOverlay").style.display = "block";
        document.getElementById('loaderfor').style.display = "block";
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
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/pipeline-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                console.error('Error:', error);
            }
        });
    }
</script>
