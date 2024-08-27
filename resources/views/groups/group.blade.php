@vite(['resources/js/toast.js'])
<div class="table-responsive dbgTable groupTable">
    <table class="table dbgHeaderTable ">
        <thead class ="">
            <tr class="dFont700 dFont10 groups-view">
                <th class= "sticky-col sticky-head " scope="col">
                    <div class="dbgcommonFlex">
                        <p class="mb-0">Name</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" class="ppiplineSwapIcon"
                            alt="Transaction icon" onclick="toggleSort()">
                    </div>
                </th>
                @foreach ($shownGroups as $shownGroup)
                    <th scope="col" class="sticky-head">
                        <div class="dbgheaderFlex">
                            <p class="mb-0">{{ count($shownGroup['contacts']) }} <i class="mdi mdi-send ms-1" onclick="sendGroupMail('{{$shownGroup['id']}}')"></i></p>

                            <div class="checkboxText">
                                <p class="mb-0 text-end">{{ $shownGroup['name'] }}</p>
                                <input type="checkbox" class="headerCheckbox" data-group-id="{{ $shownGroup['id'] }}" id="headerCheckbox{{ $loop->index }}"
                                    data-bs-toggle="modal" data-bs-target="#confirmModel{{ $shownGroup['id'] }}"
                                    data-index="{{ $loop->index }}" />
                            </div>
                        </div>
                        <div class="modal fade p-5" id="confirmModel{{ $shownGroup['id'] }}" tabindex="-1"
                            aria-labelledby="confirmModelLabel{{ $shownGroup['id'] }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content">
                                    <div class="modal-header border-0 deleteModalHeaderDiv">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeModel{{ $shownGroup['zoho_group_id'] }}" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body deletemodalBodyDiv">
                                        <p class="deleteModalBodyText popuptext{{ $shownGroup['id'] }}">Are you sure you want to add all your contacts to this group?</p>
                                    </div>
                                    <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                        <div class="d-grid gap-2 col-5">
                                            <button type="button" class="btn btn-secondary deleteModalBtn popupButton{{ $shownGroup['id'] }}"
                                                    onclick="selectAllCheckboxes('{{ $loop->index }}','{{ $shownGroup['zoho_group_id'] }}','confirmModel{{ $shownGroup['id'] }}','{{ $shownGroup['id'] }}')">
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
            @include('groups.load')
        </tbody>
        <tfoot class="text-center">
            <tr class="spinner" style="display: none;">
                <td colspan="10">
                    <!-- Add your spinner HTML here -->
                    <!-- For example, you can use Font Awesome spinner -->
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </td>
            </tr>
        </tfoot>
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
                const shownGroupId = checkbox.dataset.groupId;
                const targetModalElement = document.querySelector(".popuptext"+shownGroupId);
                const buttonElement = document.querySelector(".popupButton"+shownGroupId);
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
        document.querySelectorAll('.groupCheckbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            checkAllCheckboxes();
        });
    });

    checkAllCheckboxes(); // Initial check on page load
    });
    window.refetchData = function (sortField = null) {
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
                checkAllCheckboxes();
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error('Error:', error);
            }
        });

    }
    var sortDescending = false;

    window.toggleSort = function () {
        sortDescending = !sortDescending;
        const sortDirection = sortDescending ? 'desc' : 'asc';
        refetchData(sortDirection);
    };

    window.contactGroupUpdate = function (t, zoho_contact_id, zoho_group_id, isChecked, zoho_contact_group_id) {
        zoho_contact_group_id = zoho_contact_group_id ? zoho_contact_group_id : $(t).attr('data-group-id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (isChecked) {
            var formData = {
                "data": [{
                    "Contacts": {
                        'id': zoho_contact_id
                    },
                    "Groups": {
                        'id': zoho_group_id
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
                    // set group id data attribute
                    $(t).attr('data-group-id', response.zoho_contact_group_id);
                    refetchData();

                },
                error: function(xhr, status, error) {
                    showToastError(xhr.responseText)
                }
            });
        } else {
            console.log(formData);
            $.ajax({
                url: '/contact/group/delete/' + zoho_contact_group_id,
                method: 'DELETE',
                contentType: 'application/json',

                success: function (response) {
                   showToast('Contact remove successfully');
                   refetchData();
                },
                error: function(xhr, status, error) {
                    showToastError(error)
                }
            });
        }
    }
    window.selectAllCheckboxes = function (columnIndex, groupId, targetModalId,dbGroupId = null) {
        console.log("Select ALL Inbox", targetModalId);
        let checkedGroup = []
        var checkboxes = document.querySelectorAll('.groupCheckbox[data-index="' + columnIndex + '"]');
        // Get the elements to update
        const buttonElement = document.querySelector(".popupButton"+dbGroupId);
        const closeId = document.getElementById('closeModel'+groupId);
        console.log(closeId,"closeId");
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
                if(contactgroupId.zoho_contact_group_id){
                checkedGroup.push(contactgroupId.zoho_contact_group_id)
            }
            checkbox.checked = false;
            }
        })
        if(elementInnerText == "Select All"){
            console.log(checkedGroup);
            var jsonString = checkedGroup;

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
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function (response) {
                    showToast('Contacts add successfully')
                    refetchData();
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });

        }else{
            var jsonString = JSON.stringify(checkedGroup);
            console.log(jsonString);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/contact/group/bulk/remove',
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: jsonString,
                success: function (response) {
                    showToast('Contacts remove successfully')
                    refetchData();
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });
        }
        closeId.click();
        checkAllCheckboxes();
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

    window.sendGroupMail = function(id){
        let selectedGroups = [id];
        console.log(selectedGroups);
        getGroupContacts(selectedGroups,function(groupContacts){
            console.log(groupContacts);
            openGroupComposeModal(groupContacts);
        });

    }


</script>
