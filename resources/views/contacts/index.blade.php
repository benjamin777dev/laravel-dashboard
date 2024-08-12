@extends('layouts.master')
<!DOCTYPE html>

@section('title', 'zPortal | Contacts')

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
        <div class="dbgroupsFlex">
            <p class="ngText text-center">Database</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <div class="w-control">
                        @component('components.button', [
                         'id' => 'create_contact',
                         'type' => 'database',
                         'label' => 'New Contact',
                         'icon' => 'fas fa-plus plusicon'
                     ])
                     @endcomponent
                </div>
                <div class="w-control">
                            @component('components.button', [
                               'id' => 'create_transaction',
                               'type' => 'database',
                               'label' => 'New Transaction',
                               'icon' => 'fas fa-plus plusicon'
                           ])
                           @endcomponent
                </div>
                <div class="w-control">
                            @component('components.button', [
                               'id' => 'compose_email',
                               'type' => 'database',
                               'label' => 'Compose Email',
                               'icon' => 'fab fa-telegram-plane ms-1'
                           ])
                           @endcomponent
                </div>
            </div>
        </div>

        <div class="pfilterDiv">
            <div class="pcommonFilterDiv">
                <input placeholder="Search" class="psearchInput" id="contactSearch" />
                <i class="fas fa-search search-icon"></i>
            </div>
            <p class="porText">or</p>
            <div class="psortingFilterDiv">
                @php
                    $abcd = ['A1', 'A', 'B', 'C', 'D'];
                @endphp
                <div class="row" style="gap:24px;flex-wrap:nowrap;">
                    <div class="psortFilterDiv">
                        <select name="abcd_class" class="psearchInput" id="contactSort">
                            <option selected value="">-None-</option>
                            @foreach ($abcd as $abcdIndex)
                                <option value="{{ $abcdIndex }}">{{ $abcdIndex==="A1" ? "A+" : $abcdIndex }}</option>
                            @endforeach
                        </select>
                        {{-- <input placeholder="Sort contacts by..." id="pipelineSort" class="psearchInput" /> --}}
                        {{-- <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon"
                            class="ppipelinesorticon"> --}}
                    </div>
                </div>

            </div>
            <div class="d-flex gap-4 cus-grid">
                <div class="w-control">
                    @component('components.button', [
                       'attributes' => 'id=btnGroupAddon data-bs-toggle=modal data-bs-target=#filterModal',
                       'label' => 'Filter',
                       'icon' => 'fas fa-filter'
                   ])
                   @endcomponent
               </div>
               <div class="w-control">
                      @component('components.button', [
                       'clickEvent' => 'applyFilter(\'reset\')',
                       'label' => 'Reset All',
                       'icon' => 'fas fa-sync'
                   ])
                   @endcomponent
               </div>
            </div>
        </div>

    

        <div class="contactlist overflow-auto" id="contactlist">
            @component('components.common-table', [
                'id'=>'datatable_contact',
                'commonArr' =>$contacts,
                'retrieveModuleData'=>$retrieveModuleData,
                "type" =>"contact",
            ])
            @endcomponent
            <!-- Filter Modal -->

        </div>
        <div class="modal fade pt-5" id="filterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Missing Fields</h1>
                        <button type="button"
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
                        <button type="button" class="pfilterBtn w-auto">Filter</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade p-5" id="composemodal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="composemodalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modalValues">
                </div>
            </div>
        </div>
        <div class="modal fade p-5" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    @include('emails.email_templates.email-template-create',['contact'=>null])
                </div>
            </div>
        </div>
    </div>

@endsection
@vite(['resources/js/toast.js'])
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var contacts = @json($contactsWithEmails).data;
    
    $(document).ready(function(){
        
        
    });
    window.openComposeModal = function (Ids) {
        console.log("Open modal ids",Ids);
        var intIds = Ids.map(id => parseInt(id));
        var selectedContacts = contacts.filter(contact => intIds.includes(contact.id));
        var data = {
            contacts: contacts,
            selectedContacts: selectedContacts,
            emailType:"multiple"
        };
        $.ajax({
            url: '/get/email-create', 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            dataType: 'html',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                // Update the modal content with the response
                $('#modalValues').html(response);

                // Re-initialize Select2
                $('#toSelect').select2({
                    placeholder: "To",
                    
                });

                // Open the modal
                $("#composemodal").modal("show");
                selectedContacts.forEach(element => {
                    $('#email-checkbox'+element.id).prop('checked', false);
                });
            },
            error: function () {
                // Handle any errors
                alert('Failed to load modal content');
            }
        });
    }
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
            }
        @endforeach

    }

    function applyFilter(reset="") {
        console.log('yesshdhfshdf');
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

        filterContactData("", "", "", "", missingFeild, reset);
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
    var prevSelectedColumn = null;
    var prevSortDirection = "";

    function filterContactData(sortField = "", sortDirection = "", searchInput = "", filterVal = "", missingFeild =
        "", reset, clickedColumn = "") {
        var searchValuetrim = "";
        if (searchInput) {
            searchValuetrim = searchInput?.val().trim();
        }
        if (reset) {
            sortField = "", sortDirection = "", searchInput = "", filterVal = "", missingFeild =
                ""
        }
        var load = true;

        $.ajax({
            url: '{{ url('/contacts') }}',
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
                // Update arrow colors for the previously selected column
                if (prevSelectedColumn !== null) {
                    if (prevSortDirection === "asc") {
                        $(prevSelectedColumn).find(".down-arrow").css("color", "#fff");
                        $(prevSelectedColumn).find(".up-arrow").css("color", "#fff");
                    } else {
                        $(prevSelectedColumn).find(".up-arrow").css("color", "#fff");
                        $(prevSelectedColumn).find(".down-arrow").css("color", "#fff");
                    }
                }
                if (sortDirection === "asc") {
                    $(clickedColumn).find(".down-arrow").css("color", "#D3D3D3");
                    $(clickedColumn).find(".up-arrow").css("color", "#fff");
                } else {
                    $(clickedColumn).find(".up-arrow").css("color", "#D3D3D3");
                    $(clickedColumn).find(".down-arrow").css("color", "#fff");
                }

                // Update the previously selected column and its sorting direction
                prevSelectedColumn = clickedColumn;
                prevSortDirection = sortDirection;
                document.getElementById('close_btn').click();
                const card = $('.table_apeend').html(data.view);
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





    function fetchContact(e, sortField, sortDirection, clickedColumn) {
        const searchInput = $('#contactSearch');
        var csearch = $('#contactSort');
        var filterVal = csearch.val();
        console.log(sortField, sortDirection, searchInput, filterVal, 'testignnnnnnnnn')
        // var filterVal = selectedModule.val();
        // Call fetchData with the updated parameters
        filterContactData(sortField, sortDirection, searchInput, filterVal, "", "", clickedColumn);
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
</script>
