@extends('layouts.master')

@section('title', 'Agent Commander | Contact Details')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <div class="container-fluid">
        <div class="loader" id="loaderfor" style="display: none;"></div>
        <div class="loader-overlay" id="loaderOverlay" style="display: none;"></div>
        <div class="commonFlex ppipeDiv">
            <p class="pText"></p>
            <a onclick="createTransaction({{ $userContact }},{{ $contact }});">
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
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                             <button class="nav-link dtabsbtn" onclick="fetchContactTasks('In Progress','{{ $contact['id'] }}')"
                                    id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" data-tab='In Progress'
                                    type="button" role="tab" aria-controls="nav-home" aria-selected="true">In
                                    Progress</button>
                             <button class="nav-link dtabsbtn"  onclick="fetchContactTasks('Upcoming','{{ $contact['id'] }}')"
                                    data-tab='Upcoming' id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Upcoming</button>
                            <button class="nav-link dtabsbtn"  onclick="fetchContactTasks('Overdue','{{ $contact['id'] }}')"
                                    data-tab='Overdue' id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Overdue</button>
                            <button class="nav-link dtabsbtn"  onclick="fetchContactTasks('Completed','{{ $contact['id'] }}')"
                                    data-tab='Completed' id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Completed</button>
                        </div>
                    </nav>
                    <div class="contact-task-container">
                    </div>

                </div>
                
            </div>
            @include('common.notes.view', [
                'notesInfo' => $notes,
                'retrieveModuleData' => $retrieveModuleData,
                'module' => 'Contacts',
            ])
        </div>
        <div class="updateContactform">
           
        </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal"
        data-bs-target="#staticBackdropforNote_{{ $contact['id'] }}">
        <div class="tooltip-wrapper">
            <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
            <span class="tooltiptext">Add Notes</span>
        </div>
    </div>



    {{-- task modal --}}
    @include('common.tasks.create', [
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
  

@endsection
<script>
    var contactId = @json($contactId);
    $(document).ready(function() {
        updateContactform();
        fetchContactTasks('In Progress',contactId)
        
    });

    function updateContactform() {
        $.ajax({
            url: `{{ url('/contact/detail/form/') }}/${contactId}`,
            method: 'GET',
            success: function(data) {
                 if (data.redirect) {
                    window.location.href = data.redirect;
                }else{
                    $('.updateContactform').html(data);
                }                 
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    function fetchContactTasks(tab, contactId) {
        // Make AJAX call
        $.ajax({
            url: '/task/for/contact/'+contactId,
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
            'Upcoming': false,
            'Completed': false,
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
            maxItemCount: null,
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
            $(".select2-results").prepend(
                '<div class="new-contact-btn" onclick="openContactModal()" style="padding: 6px; height: 20px; display: inline-table; color: black; cursor: pointer; background-color: lightgray; width: 100%"><i class="fas fa-plus plusicon"></i> New Spouse</div>'
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

   

</script>
