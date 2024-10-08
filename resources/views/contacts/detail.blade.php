@extends('layouts.master')
<!DOCTYPE html>
@section('title', 'zPortal | Contact Details')
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
        <div class="row mt-2">
            <div class="col-md-8 col-sm-12 dtasksection">
                <div class="d-flex justify-content-between mb-1">
                    <p class="dFont800 dFont15">Tasks</p>
                    <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId{{ $contact['id'] }}"><i
                            class="fas fa-plus plusicon">
                        </i>
                        New Task
                    </div>

                </div>
                @include("common.confirm-delete-modal")
                <div class="row">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                             <button class="nav-link dtabsbtn"
                                    id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" data-tab='In Progress'
                                    type="button" role="tab" aria-controls="nav-home" aria-selected="true">In
                                    Progress</button>
                             <button class="nav-link dtabsbtn"
                                    data-tab='Upcoming' id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Upcoming</button>
                            <button class="nav-link dtabsbtn"
                                    data-tab='Overdue' id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Overdue</button>
                            <button class="nav-link dtabsbtn"
                                    data-tab='Completed' id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Completed</button>
                        </div>
                    </nav>
                   @component('components.common-table', [
                    'id'=>'datatable_tasks',
                    'commonArr' =>$tasks,
                    'retrieveModuleData'=>$retrieveModuleData,
                    "type" =>"contact",
                ])
                @endcomponent
                <div onclick="deleteTask('remove_selected',true)" class="input-group-text text-white justify-content-center removebtn dFont400 dFont13 col-lg-3" id="removeBtn">
                    <i class="fas fa-trash-alt plusicon"></i>
                    Delete Selected
                </div>
                </div>
                
            </div>
            @include('common.notes.view', [
                'notesInfo' => $notes,
                'retrieveModuleData' => $retrieveModuleData,
                'module' => 'Contacts',
            ])
        </div>
        @include('contacts.detail-form',[
            'contact'=>$contact,
            'userContact'=>$userContact,
            'deals'=>$deals,
            'allstages'=>$allstages,
            'user_id'=>$user_id,
            'tab'=>$tab,
            'name'=>$name,
            'contacts'=>$contacts,
            'contactsWithEmails'=>$contactsWithEmails,
            'tasks'=>$tasks,
            'notes'=>$notes, 
            'getdealsTransaction'=>$getdealsTransaction,
            'retrieveModuleData'=>$retrieveModuleData,
            'dealContacts'=>$dealContacts, 'contactId', 'users', 'groups', 'contactsGroups','spouseContact'])
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
  window.onload = function() {
        fetchContactTasks('In Progress',contactId)

        const ui = {
            confirm: async (message) => createConfirm(message)
        };
        const createConfirm = (message) => {
            console.log("message", message);
            return new Promise((complete, failed) => {
                $('#confirmMessage').text(message);
    
                $('#confirmYes').off('click').on('click', () => {
                    $('#confirmModal').modal('hide');
                    complete(true);
                });
    
                $('#confirmNo').off('click').on('click', () => {
                    $('#confirmModal').modal('hide');
                    complete(false);
                });
    
                $('#confirmModal').modal('show');
            });
        };

        window.saveForm = async function (){
            console.log(ui);
            const confirm = await ui.confirm('Are you sure you want to do this?');
    
            if (confirm) {
                return true;
            } else {
                return false;
            }
        };
        
    };

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
            document.getElementById("subject_error").innerHTML = "please enter subject";
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
