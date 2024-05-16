<script src="{{ URL::asset('http://[::1]:5173/resources/js/toast.js') }}"></script>
<div class="table-responsive dbgTable">
    <table class="table dbgHeaderTable">
        <thead>
            <tr class="dFont700 dFont10">
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
                            <p class="mb-0">{{ count($shownGroup['contacts']) }}</p>
                            <div class="checkboxText">
                                <p class="mb-0 text-end">{{ $shownGroup['name'] }}</p>
                                <input type="checkbox" class="headerCheckbox" id="headerCheckbox{{ $loop->index }}"
                                    data-bs-toggle="modal" data-bs-target="#confirmModel{{ $shownGroup['id'] }}"
                                    data-index="{{ $loop->index }}" />
                            </div>
                        </div>
                        <div class="modal fade" id="confirmModel{{ $shownGroup['id'] }}" tabindex="-1"
                            aria-labelledby="confirmModelLabel{{ $shownGroup['id'] }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content">
                                    <div class="modal-header border-0 deleteModalHeaderDiv">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body deletemodalBodyDiv">
                                        <p class="deleteModalBodyText">Are you sure you want to add all your contacts to this group?</p>
                                    </div>
                                    <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                        <div class="d-grid gap-2 col-5">
                                            <button type="button" class="btn btn-secondary deleteModalBtn"
                                                    onclick="selectAllCheckboxes('{{ $loop->index }}','{{ $shownGroup['zoho_group_id'] }}','confirmModel{{ $shownGroup['id'] }}')">
                                                Select All
                                            </button>
                                        </div>
                                        <div class="d-grid gap-2 col-5">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </th>
                @endforeach

            </tr>
        </thead>
        <tbody class="text-center dbgBodyTable">
            @foreach ($contacts as $contact)
                <tr>
                    <td class="text-start">
                        @if($contact->relationship_type == 'Secondary')
                            <i class="fa fa-caret-right"></i>
                        @endif
                        {{$contact->first_name ?? ''}} {{$contact->last_name ?? ''}}
                    </td>

                    @foreach ($shownGroups as $index => $shownGroup)
                                @php
                                $group = $contact->groups->firstWhere('groupId', $shownGroup['id']);
                                @endphp
                                <td>
                                    <input type="checkbox" data-id="{{$contact['zoho_contact_id']}}" data-group-id="{{$group}}"
                                        onclick="contactGroupUpdate('{{ $contact ? $contact : 'null' }}', '{{ $shownGroup }}', this.checked,'{{$group}}')"
                                        class="groupCheckbox" {{ $group ? 'checked' : '' }} data-index="{{ $index }}" />
                                </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
  
    window.selectedValues = [];

    document.querySelectorAll('.gdropdown-ul input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const value = this.parentNode.getAttribute('value');
            const isChecked = this.checked;
            const existingIndex = selectedValues.findIndex(item => item.id === value);

            const checkboxData = {
                id: value,
                isChecked: isChecked
            };

            if (existingIndex !== -1) {
                selectedValues[existingIndex] = checkboxData;
            } else {
                selectedValues.push(checkboxData);
            }
            console.log(selectedValues);
        });
    });
    window.headerCheckboxes = document.querySelectorAll('.headerCheckbox');
    document.addEventListener('DOMContentLoaded', function() {
        headerCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const targetModalElement = document.querySelector('.deleteModalBodyText');
                const buttonElement = document.querySelector('.deleteModalBtn');
                console.log(targetModalElement,buttonElement);
                if (checkbox.checked) {
                    targetModalElement.innerHTML =
                        `Are you Sure?<br>This will add ALL your contacts to this group.`;
                    buttonElement.innerText = `Select All`;
                } else {
                    targetModalElement.innerHTML =
                        `Are you Sure?<br>This will remove ALL your contacts from this group.`;
                    buttonElement.innerText = `Deselect All`;
                }
            });
        });
    });
    window.fetchData = function (sortField = null) {
        const filterSelect = document.getElementById('validationDefault05');
        const filterValue = filterSelect.options[filterSelect.selectedIndex].value;
        $.ajax({
            url: '{{ url('/contact/groups') }}',
            method: 'GET',
            data: {
                columnShow: JSON.stringify(selectedValues),
                filter: filterValue,
                sort: sortField
            },
            success: function (data) {
                $('.dbgTable').html(data)
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error('Error:', error);
            }
        });

    }
    let sortDescending = true;

    window.toggleSort = function () {
        sortDescending = !sortDescending;
        const sortDirection = sortDescending ? 'desc' : 'asc';
        fetchData(sortDirection);
    };

    window.contactGroupUpdate = function (contact, group, isChecked, contactGroup) {
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
                    showToast('Contact add successfully')
                },
                error: function(xhr, status, error) {
                    showToastError(error)
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

                success: function (response) {
                   showToast('Contact remove successfully')
                },
                error: function(xhr, status, error) {
                    showToastError(error)
                }
            });
        }
    }
    window.selectAllCheckboxes = function (columnIndex, groupId, targetModalId) {
        console.log("Select ALl Inbox", targetModalId);
        let checkedGroup = []
        var checkboxes = document.querySelectorAll('.groupCheckbox[data-index="' + columnIndex + '"]');
        // Get the elements to update
        const buttonElement = document.querySelector('.deleteModalBtn');
        const elementInnerText = buttonElement
            .innerText; // Retrieves visible text content, ignoring hidden elements
        checkboxes.forEach(function (checkbox) {
            var contactId = checkbox.getAttribute('data-id');
            var contactgroupId = checkbox.getAttribute('data-group-id');
            if(contactgroupId){

                contactgroupId = JSON.parse(contactgroupId);
            }
            if (elementInnerText == "Select All" && !(checkbox.checked)) {
                checkedGroup.push({ groupId: groupId, contactId: contactId })
                checkbox.checked = true;
            }
            if (elementInnerText == "Deselect All" && checkbox.checked) {
                checkedGroup.push({ contactGroupId: contactgroupId.zoho_contact_group_id })
                checkbox.checked = false;
            }
        })
        if(elementInnerText == "Select All"){
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
                success: function (response) {
                    showToast('Contacts add successfully')
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });

        }else{
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
                url: '/contact/group/bulk/remove',
                method: 'GET',
                contentType: 'application/json',
                dataType: 'json',
                data: {
                    "laravelData": jsonString,
                },
                success: function (response) {
                    showToast('Contacts remove successfully')
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });
        }
        
    }
</script>