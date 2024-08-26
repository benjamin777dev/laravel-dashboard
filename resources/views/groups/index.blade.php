@extends('layouts.master')

@section('title', 'zPortal | Groups')

@section('content')
    @vite(['resources/css/custom.css'])
    @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="container full-width-container">
            <div class="dbgroupsFlex">
                <p class="ngText">Database Groups</p>
                <div class="dbgroupsFlex">
                    <div class="input-group-text dbNewGroups" onclick="addGroup()"><i class="fas fa-plus plusicon">
                        </i>
                        Add Group
                    </div>
                    <div class="input-group-text dbEditGroups" onclick="EditGroup()"><i class="fas fa-edit plusicon">
                        </i>
                        Edit Group
                    </div>
                    <div class="input-group-text dbEditGroups" onclick="selectGroup()">
                        Compose Email <i class="mdi mdi-send ms-1"></i>
                    </div>
                </div>
            </div>
            <div class="row" style="gap: 24px">
                <div class="col-md-6 col-sm-12 dbgSelectDiv">
                    <div class="dropdown gdropdown-div dbgSelectinfo">
                        <div class="dropdown-toggle gdropdown-select " type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <input class="gdropdown-btn" value="Select columns to display" readonly />
                            <div>
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </div>

                        <ul class="dropdown-menu gdropdown-ul gdropdownMax" aria-labelledby="dropdownMenuButton1"
                            onchange="refetchData()">
                            @foreach ($groups as $group)
                                <li class="gdropdown" value="{{ $group['id'] }}">{{ $group['name'] }} <input
                                        type="checkbox" {{ $group->isShow == true ? 'checked' : '' }} /></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="dbgSortDiv">
                        <div class="dbgGroupDiv">
                            <select class="form-select dbgSelectinfo" placeholder="Sort groups by" id="validationDefault05"
                                onchange="refetchData()" required>
                                <option selected value = "">-None-</option>
                                <option value = "has_address">Has Address</option>
                                <option value = "has_email">Has Email</option>
                                @foreach ($groups as $group)
                                    <option value = "{{ $group['id'] }}">{{ $group['name'] }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group-text dbgfilterBtn " id="btnGroupAddon" onclick ="refetchData()">
                            <i class="fas fa-filter"></i>
                            Filter
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row" style="gap: 24px">
            <div class="col-md-6 col-sm-12 dbgSelectDiv">
                <div class="dropdown gdropdown-div dbgSelectinfo">
                    <div class="dropdown-toggle gdropdown-select " type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <input class="gdropdown-btn" value="Select columns to show" readonly />
                    </div>

                    <ul class="dropdown-menu gdropdown-ul" aria-labelledby="dropdownMenuButton1" onchange="">
                        @foreach ($groups as $group)
                            <li class="gdropdown" value="{{ $group['id'] }}">{{ $group['name'] }} <input type="checkbox"
                                    {{ $group->isShow == true ? 'checked' : '' }} /></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="row dbgSortDiv">
                    <div class="col-md-6 col-sm-12 dbgGroupDiv">
                        <select class="form-select dbgSelectinfo" id="validationDefault05" onchange="refetchData()" required>
                            <option selected value = "">--None--</option>
                            @foreach ($groups as $group)
                                <option value = "{{ $group['id'] }}">{{ $group['name'] }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group-text dbgfilterBtn col-md-6 col-sm-12" id="btnGroupAddon" onclick ="refetchData()">
                        <i class="fas fa-filter"></i>
                        Filter
                    </div>
                </div>
            </div>
        </div> --}}
            <div class = "group-container">
                @include('groups.group')
            </div>
            <div class="datapagination">
                @include('common.pagination', ['module' => $contacts])
            </div>
        </div>
        @include('common.group.createModal', ['groups' => $groups])
        @include('common.group.editModal', ['groups' => $ownerGroups])
        <div class="modal fade p-5" id="selectGroupModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Select Group</h5>
                        <button type="button" class="btn-close" id="selectGroupModalClose" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="selectGroupForm">
                            <div class="table-container">
                                @if (count($groups))
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Group Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groups as $group)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="group_{{ $group['id'] }}"
                                                            id="{{ $group['id'] }}">
                                                    </td>
                                                    <td>
                                                        <label
                                                            for="group_{{ $group['id'] }}">{{ $group['name'] }}</label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Group Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3">No Groups Available</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary taskModalSaveBtn"
                            onclick="sendMultipleGroupMail();">
                            <i class="fas fa-save saveIcon"></i> Send Mail
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade p-5" id="composemodal" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="composemodalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="modalValues">
                </div>
            </div>
        </div>
        <div class="modal fade p-5" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    @include('emails.email_templates.email-template-create', ['contact' => null])
                </div>
            </div>
        </div>
    <script>
        let nextPageUrl = '{{ $contacts->nextPageUrl() ? str_replace('/', '', $contacts->nextPageUrl()) : null }}';

        // Get selected filter value
        var filterSelect = document.getElementById('validationDefault05');
        var filterValue = filterSelect.options[filterSelect.selectedIndex].value;
        var sortField = sortDescending ? 'desc' : 'asc';
        nextPageUrl = nextPageUrl + '&filter=' + filterValue + '&sort=' + sortField;

        let moreData = true;
        var contactList = @json($contactsList ?? '');
        window.onload = function() {
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadMorePosts(page);
            });
            let isLoading = false;
            function loadMorePosts(page) {
                isLoading = true; // Prevent multiple AJAX calls
                filterSelect = document.getElementById('validationDefault05');
                filterValue = filterSelect.options[filterSelect.selectedIndex].value;
                sortField = sortDescending ? 'desc' : 'asc';
                nextPageUrl = nextPageUrl.replace(/&filter=([^&]*)/, function(match, filter) {
                    return '&filter=' + filterValue;
                });
                nextPageUrl = nextPageUrl.replace(/&sort=([^&]*)/, function(match, sort) {
                    return '&sort=' + sortField;
                });

                $.ajax({
                    url: '/group?filter=' + filterValue + '&sort=' + sortField + '&page=' + page,
                    type: 'get',
                    beforeSend: function() {
                       
                    },
                    success: function(data) {
                        console.log(data,'data is here')
                        if (data.trim() === "") {
                            moreData = false; // No more data to load
                            $('.datapagination').hide();
                        }

                        $('.groupTable').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading more posts:", error);
                        isLoading = false; // Allow next AJAX call even if there is an error
                    }
                });
            }
        }

        window.refetchData = function(sortField = null) {
            // Get selected filter value
            const filterSelect = document.getElementById('validationDefault05');
            const filterValue = filterSelect.options[filterSelect.selectedIndex].value;
            // reset next page url first page
            nextPageUrl = nextPageUrl.replace(/page=(\d+)/, function(match, pageNumber) {
                return 'page=2';
            });
            moreData = true;
            // Make AJAX call
            $.ajax({
                url: "{{ url('/contact/groups') }}",
                method: 'GET',
                data: {
                    columnShow: JSON.stringify(selectedValues),
                    filter: filterValue,
                    sort: sortField
                },
                success: function(data) {
                    $('.group-container').html(data)
                    checkAllCheckboxes()
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });

        }
        window.checkAllCheckboxes = function() {
            // Loop through each header checkbox to handle them individually
            document.querySelectorAll('.headerCheckbox').forEach(function(headerCheckbox) {
                var groupId = headerCheckbox.getAttribute('data-group-id');
                var columnIndex = headerCheckbox.getAttribute('data-index');
                var allChecked = true;

                // Select all checkboxes in the current column
                var checkboxes = document.querySelectorAll('.groupCheckbox[data-index="' + columnIndex + '"]');

                checkboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });

                headerCheckbox.checked = allChecked;
            });
        };

        window.addGroup = function() {
            $('#createGroupModal').modal('show');
        }

        window.EditGroup = function() {
            $('#editGroupModal').modal('show');
        }

        window.selectGroup = function() {
            $('#selectGroupModal').modal('show');
        }

        window.sendMultipleGroupMail = function() {
            let selectedGroups = [];
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(function(checkbox) {
                if (checkbox.id && checkbox.name.startsWith('group_')) {
                    selectedGroups.push(checkbox.id);
                }
            });

            console.log(selectedGroups);
            getGroupContacts(selectedGroups, function(groupContacts) {
                console.log(groupContacts);
                openGroupComposeModal(groupContacts);
            });

        }

        window.getGroupContacts = function(groupIds, callback) {
            $.ajax({
                url: '/get/group/contacts', // Your endpoint here
                type: 'GET',
                data: {
                    groups: JSON.stringify(groupIds),
                },
                success: function(response) {
                    if (callback && typeof callback === 'function') {
                        callback(response.map(val => val.contactId));
                        $("#selectGroupModalClose").click();
                    }
                },
                error: function(xhr, status, error) {
                    showToastError('An error occurred');
                }
            });
        }

        window.openGroupComposeModal = function(Ids) {

            var intIds = Ids.map(id => parseInt(id));
            var selectedContacts = contactList.filter(contact => intIds.includes(contact.id));
            console.log("Open modal ids", selectedContacts);
            var data = {
                contacts: contactList,
                selectedContacts: selectedContacts,
                emailType: "multiple"
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
                success: function(response) {
                    // Update the modal content with the response
                    $('#modalValues').html(response);

                    // Re-initialize Select2
                    $('#toSelect').select2({
                        placeholder: "To",

                    });

                    // Open the modal
                    $("#composemodal").modal("show");
                    // selectedContacts.forEach(element => {
                    //     $('#email-checkbox'+element.id).prop('checked', false);
                    // });
                },
                error: function() {
                    // Handle any errors
                    alert('Failed to load modal content');
                }
            });
        }
    </script>

@endsection
