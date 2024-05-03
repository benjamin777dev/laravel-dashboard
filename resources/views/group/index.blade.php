@extends('layouts.master')

@section('title', 'Agent Commander | Groups')

@section('content')
    @vite(['resources/css/custom.css'])
    <div class="container">
        <div class="commonFlex">
            <p class="ncText">Database Groups</p>
        </div>
        <div class="row" style="gap: 24px">
            <div class="col-md-6 col-sm-12 dbgSelectDiv">
                <div class="dropdown gdropdown-div dbgSelectinfo">
                    <div class="dropdown-toggle gdropdown-select " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <input class="gdropdown-btn" value="Select columns to show" readonly />
                    </div>
    
                    <ul class="dropdown-menu gdropdown-ul" aria-labelledby="dropdownMenuButton1" onchange="">
                        @foreach($groups as $group)
                        <li class="gdropdown" value="{{$group['id']}}">{{$group['name']}}  <input type="checkbox" {{ $group->isShow == true ? 'checked' : '' }}/></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="row dbgSortDiv">
                    <div class="col-md-6 col-sm-12 dbgGroupDiv">
                        <select class="form-select dbgSelectinfo" id="validationDefault05" onchange="fetchData()" required>
                            <option selected value = "">--None--</option>
                            @foreach ($groups as $group)
                                <option value = "{{$group['id']}}">{{ $group['name'] }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group-text dbgfilterBtn col-md-6 col-sm-12" id="btnGroupAddon" onclick ="fetchData()">
                        <i class="fas fa-filter"></i>
                        Filter
                    </div>
                </div>
            </div>
        </div>
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
                        @foreach($shownGroups as $shownGroup)
                            <th scope="col">
                                <div class="dbgcommonFlex">
                                    <p id="selectedCountHeader{{$loop->index}}">0</p><br><br>
                                    <p class="mb-0">{{$shownGroup['name']}}</p>
                                    <input type="checkbox" class="headerCheckbox" data-index="{{$loop->index}}" />
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="text-center dbgBodyTable">
                    @foreach ($contacts as $contact)
                    <tr>
                        <td> <input type="checkbox" class="rowCheckbox" /></td>
                        <td class="text-start"> {{$contact->first_name ?? ''}} {{$contact->last_name ?? ''}}</td>
                        @foreach ($shownGroups as $index => $shownGroup)
                        @php
                        $group = $contact->groups->firstWhere('groupId', $shownGroup['id']);
                        @endphp
                        <td>
                                <input type="checkbox" onclick="contactGroupUpdate('{{ $contact ? $contact : 'null' }}', '{{ $shownGroup }}', this.checked, '{{ $group ? $group->zoho_contact_group_id : 'null' }}')" class="groupCheckbox" {{ $group ? 'checked' : '' }} data-index="{{ $index }}" />
                            
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

   <script>
    // Define an empty array to store selected checkbox values
    let selectedValues = [];

    // Add event listener to checkboxes
    document.querySelectorAll('.gdropdown-ul input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const value =  this.parentNode.getAttribute('value');
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
    document.addEventListener('DOMContentLoaded', function () {
        const headerCheckboxes = document.querySelectorAll('.headerCheckbox');
        const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
        const groupCheckboxes = document.querySelectorAll('.groupCheckbox');

        function updateSelectedCount() {
            headerCheckboxes.forEach((checkbox, index) => {
                const selectedCount = document.querySelectorAll(`.groupCheckbox[data-index="${index}"]:checked`).length;
                document.getElementById(`selectedCountHeader${index}`).textContent = selectedCount;
            });
        }

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
            sort:sortField
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
           $.each(data.contacts, function (index, contact) {
            const row = $('<tr>'); // Create a new row
            // Append checkbox cell
            row.append(`<td><input type="checkbox" /></td>`);
            // Append Name cell
            row.append(`<td class="text-start">${contact.first_name ?? ''} ${contact.last_name ?? ''}</td>`);
            // Append cells for shownGroups
            $.each(data.shownGroups, function (index, item) {
                let groupFound = false;
                $.each(contact.groups, function (index, group) {
                    if (group.groupId === item.id) {
                        groupFound = true;
                        return false; // exit the loop
                    }
                });
                row.append(`<td><input type="checkbox" ${groupFound ? 'checked' : ''}></td>`);
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

</script>

@endsection
