@extends('layouts.master')

@section('title', 'Agent Commander | Groups')

@section('content')
    @vite(['resources/css/custom.css'])
    <div class="container">
        <div class="dbgroupsFlex">
            <p class="ngText">Database Groups</p>
            <div class="input-group-text dbNewGroups"><i class="fas fa-plus plusicon">
                </i>
                Add Group
            </div>
        </div>
        {{-- <div class="row" style="gap: 24px">
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
                        onchange="">
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
                        <select class="form-select dbgSelectinfo" placeholder="Sort groups by" id="validationDefault05"
                            onchange="fetchData()" required>
                            <option selected value = "">--</option>
                            @foreach ($groups as $group)
                                <option value = "{{ $group['id'] }}">{{ $group['name'] }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group-text dbgfilterBtn col-md-6 col-sm-12" id="btnGroupAddon" onclick ="fetchData()">
                        <i class="fas fa-filter"></i>
                        Filter
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="table-responsive dbgTable">
            <table class="table dbgHeaderTable">
                <thead>
                    <tr class="dFont700 dFont10">
                        <th scope="col">

                        </th>

                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">Name</p>
                                <img src="{{ URL::asset('/images/swap_vert.svg') }}" class="ppiplineSwapIcon"
                                    alt="Transaction icon" onclick="toggleSort()">
                            </div>
                        </th>
                        @foreach ($shownGroups as $shownGroup)
                            <th scope="col">
                                <div class="dbgheaderFlex">
                                    <p id="selectedCountHeader{{ $loop->index }}" class="mb-0">0</p>
                                    <div class="checkboxText">
                                        <p class="mb-0 text-end">{{ $shownGroup['name'] }}</p>
                                        <input type="checkbox" class="headerCheckbox"
                                            data-target="#confirmModel{{ $shownGroup['id'] }}"
                                            data-index="{{ $loop->index }}" />
                                    </div>
                                </div>
                            </th>
                            <div id="confirmModel{{ $shownGroup['id'] }}" class="modal">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 deleteModalHeaderDiv">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body deletemodalBodyDiv">
                                            <p class="deleteModalBodyText">Are you Sure?
                                                <br>
                                                This will add ALL your contacts to this group.
                                            </p>
                                        </div>
                                        <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                            <div class="d-grid gap-2 col-5">
                                                <button type="button" class="btn btn-secondary deleteModalBtn"
                                                    data-bs-dismiss="modal"
                                                    onclick="selectAllCheckboxes('{{ $loop->index }}','{{ $shownGroup['zoho_group_id'] }}','confirmModel{{ $shownGroup['id'] }}')">
                                                    Select All
                                                </button>
                                            </div>
                                            <div class="d-grid gap-2 col-5">
                                                <button type="button" data-bs-dismiss="modal" aria-label="Close">Cancel
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="text-center dbgBodyTable">
                    @foreach ($contacts as $contact)
                        <tr>
                            <td> <input type="checkbox" class="rowCheckbox" /></td>
                            <td class="text-start"> {{ $contact->first_name ?? '' }} {{ $contact->last_name ?? '' }}
                            </td>
                            @foreach ($shownGroups as $index => $shownGroup)
                                @php
                                    $group = $contact->groups->firstWhere('groupId', $shownGroup['id']);
                                @endphp
                                <td>
                                    <input type="checkbox"
                                        data-id="{{ $contact['zoho_contact_id'] }}"onclick="contactGroupUpdate('{{ $contact ? $contact : 'null' }}', '{{ $shownGroup }}', this.checked,'{{ $group }}')"
                                        class="groupCheckbox checkbox-black-bg" {{ $group ? 'checked' : '' }}
                                        data-index="{{ $index }}" />

                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- <div class="datapagination">
            @include('common.pagination', ['module' => $deals])
        </div> --}}
    </div>

    <script>
        function closeConfirmationModal(id) {
            // var modal = document.getElementById(id);
            // modal.style.display = 'none';
        }
        // Define an empty array to store selected checkbox values
        let selectedValues = [];

        // Add event listener to checkboxes
        document.querySelectorAll('.gdropdown-ul input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const value = this.parentNode.getAttribute('value');
                const isChecked = this.checked;
                const existingIndex = selectedValues.findIndex(item => item.id === value);

                const checkboxData = {
                    id: value,
                    isChecked: isChecked
                };

                // If the ID exists in the array, update the corresponding object
                if (existingIndex !== -1) {
                    selectedValues[existingIndex] = checkboxData;
                } else {
                    // If the ID doesn't exist in the array, push the new object
                    selectedValues.push(checkboxData);
                }
                // Log the array to see the selected values
                console.log(selectedValues);
            });
        });
        let headerCheckboxes = document.querySelectorAll('.headerCheckbox');
        document.addEventListener('DOMContentLoaded', function() {
            const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
            const groupCheckboxes = document.querySelectorAll('.groupCheckbox');

            function updateSelectedCount() {
                headerCheckboxes.forEach((checkbox, index) => {
                    const selectedCount = document.querySelectorAll(
                        `.groupCheckbox[data-index="${index}"]:checked`).length;
                    document.getElementById(`selectedCountHeader${index}`).textContent = selectedCount;
                });
            }

            headerCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    // Get the target modal id from data attribute
                    const targetModalId = this.getAttribute('data-target');
                    // Get the elements to update
                    const modal = document.querySelector(targetModalId);
                    const targetModalElement = modal.querySelector('.deleteModalBodyText');
                    const buttonElement = modal.querySelector('.deleteModalBtn');

                    if (checkbox.checked) {
                        // Checkbox is checked, update modal content and button text for "Select All"
                        targetModalElement.innerHTML =
                            `Are you Sure?<br>This will add ALL your contacts to this group.`;
                        buttonElement.innerText = `Select All`;
                    } else {
                        // Checkbox is not checked, update modal content and button text for "Deselect All"
                        targetModalElement.innerHTML =
                            `Are you Sure?<br>This will remove ALL your contacts from this group.`;
                        buttonElement.innerText = `Deselect All`;
                    }

                    if (modal) {
                        const bsModal = new bootstrap.Modal(modal);
                        bsModal.show();
                    }
                });
            });


            headerCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            groupCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            updateSelectedCount(); // Initial count update
        });
        // Function to fetch data based on selected values and filter
        // Function to fetch data based on selected values and filter
        window.fetchData = function(sortField = null) {
            // Get selected filter value
            const filterSelect = document.getElementById('validationDefault05');
            const filterValue = filterSelect.options[filterSelect.selectedIndex].value;
            // Make AJAX call
            $.ajax({
                url: '{{ url('/contact/groups') }}',
                method: 'GET',
                data: {
                    columnShow: JSON.stringify(selectedValues),
                    filter: filterValue,
                    sort: sortField
                },
                dataType: 'json',
                success: function(data) {
                    // Handle successful API response
                    console.log("Response", data);
                    const tbody = $('.dbgBodyTable'); // Cache tbody selector
                    const thead = $('.dbgHeaderTable thead tr'); // Cache thead selector
                    thead.empty();

                    // Append checkbox column header
                    thead.append(`<th scope="col">
                            
                        </th>`);
                    // Append Name column header
                    thead.append(`<th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">Name</p>
                                <img src="{{ URL::asset('/images/swap_vert.svg') }}" class="ppiplineSwapIcon" alt="Transaction icon" onclick="toggleSort()">
                            </div>
                        </th>`);

                    // Append headers for shownGroups
                    $.each(data.shownGroups, function(index, item) {
                        thead.append(`<th scope="col">
                                <div class="dbgcommonFlex">
                                    <p class="mb-0">${item.name}</p>
                                    <input type="checkbox" />
                                </div>
                            </th>`);
                    });

                    tbody.empty(); // Clear existing table body content

                    // Append rows for contacts
                    $.each(data.contacts, function(index, contact) {
                        const row = $('<tr>'); // Create a new row
                        // Append checkbox cell
                        row.append(`<td><input type="checkbox" /></td>`);
                        // Append Name cell
                        row.append(
                            `<td class="text-start">${contact.first_name ?? ''} ${contact.last_name ?? ''}</td>`
                        );
                        // Append cells for shownGroups
                        $.each(data.shownGroups, function(index, item) {
                            let groupFound = false;
                            $.each(contact.groups, function(index, group) {
                                if (group.groupId === item.id) {
                                    groupFound = true;
                                    return false; // exit the loop
                                }
                            });
                            row.append(
                                `<td><input type="checkbox" ${groupFound ? 'checked' : ''}></td>`
                            );
                        });
                        $('tbody').append(row); // Append row to tbody
                    });
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });

        }
        let sortDirection = 'desc'
        window.toggleSort = function() {
            // Toggle the sort direction
            sortDirection = (sortDirection === 'desc') ? 'asc' : 'desc';
            // Call fetchDeal with the sortField parameter
            fetchData(sortDirection);
        };
        window.contactGroupUpdate = function(contact, group, isChecked, contactGroup) {
            contact = JSON.parse(contact);
            group = JSON.parse(group);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            console.log(contact, group, isChecked);
            if (isChecked) {
                var formData = {
                    "data": [{
                        "Contacts": {
                            'id': contact.zoho_contact_id
                        },
                        "Groups": {
                            'id': `${group.zoho_group_id}`
                        },
                    }],
                };
                console.log(formData);
                $.ajax({
                    url: '/contact/group/update',
                    method: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(formData),
                    success: function(response) {
                        // Handle successful API response
                        // if (response?.status == "success") {
                        window.location.href = '/group';
                        // }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error('Error:', error);
                    }
                });
            } else {
                contactGroup = JSON.parse(contactGroup);
                console.log("contactGroup", contactGroup);
                console.log(formData);
                $.ajax({
                    url: '/contact/group/delete/' + contactGroup.zoho_contact_group_id,
                    method: 'DELETE',
                    contentType: 'application/json',

                    success: function(response) {
                        // Handle successful API response
                        // if (response?.status == "success") {
                        window.location.href = '/group';
                        // }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error('Error:', error);
                    }
                });
            }
        }

        function selectAllCheckboxes(columnIndex, groupId, targetModalId) {
            console.log("Select ALl Inbox", targetModalId);
            let checkedGroup = []
            var checkboxes = document.querySelectorAll('.groupCheckbox[data-index="' + columnIndex + '"]');
            // Get the elements to update
            const buttonElement = document.querySelector('.deleteModalBtn');
            const elementInnerText = buttonElement.innerText; // Retrieves visible text content, ignoring hidden elements


            checkboxes.forEach(function(checkbox) {
                var contactId = checkbox.getAttribute('data-id');
                if (elementInnerText == "Select All" && !(checkbox.checked)) {
                    checkedGroup.push({
                        groupId: groupId,
                        contactId: contactId
                    })
                    checkbox.checked = true;
                }
                if (elementInnerText == "Deselect All" && checkbox.checked) {
                    checkedGroup.push({
                        groupId: groupId,
                        contactId: contactId
                    })
                    checkbox.checked = false;
                }
            })
            console.log(checkedGroup);
            var jsonString = JSON.stringify(checkedGroup);

            var formData = {
                "data": jsonString,
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/contact/group/create/CSVfile',
                method: 'GET',
                contentType: 'application/json',
                dataType: 'json',
                data: {
                    "laravelData": jsonString,
                },
                success: function(response) {
                    // Handle successful API response
                    // if (response?.status == "success") {
                    window.location.href = '/group';
                    // }
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });

        }
    </script>

@endsection
