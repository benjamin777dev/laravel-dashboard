@extends('layouts.master')

@section('title', 'zPortal | Groups')

@section('content')
    @vite(['resources/css/custom.css'])
    @if(session('message'))
    <div class="alert alert-info">
        {{ session('message') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
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
                        onchange="fetchData()">
                        @foreach ($groups as $group)
                            <li class="gdropdown" value="{{ $group['id'] }}">{{ $group['name'] }} <input type="checkbox"
                                    {{ $group->isShow == true ? 'checked' : '' }} /></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="dbgSortDiv">
                    <div class="dbgGroupDiv">
                        <select class="form-select dbgSelectinfo" placeholder="Sort groups by" id="validationDefault05"
                            onchange="fetchData()" required>
                            <option selected value = "">-None-</option>
                            <option value = "has_address">Has Address</option>
                            <option value = "has_email">Has Email</option>
                            @foreach ($groups as $group)
                                <option value = "{{ $group['id'] }}">{{ $group['name'] }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group-text dbgfilterBtn " id="btnGroupAddon" onclick ="fetchData()">
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
                        <select class="form-select dbgSelectinfo" id="validationDefault05" onchange="fetchData()" required>
                            <option selected value = "">--None--</option>
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
        <div class = "group-container">
            @include('groups.group')
        </div>
        <div class="datapagination d-none">
            @include('common.pagination', ['module' => $contacts])
        </div>
    </div>
    @include('common.group.createModal', ['groups' => $groups])
    @include('common.group.editModal', ['groups' => $ownerGroups])

    <script>
        let nextPageUrl = '{{ $contacts->nextPageUrl() ? str_replace('/', '', $contacts->nextPageUrl()) : null }}';

        // Get selected filter value
        var filterSelect = document.getElementById('validationDefault05');
        var filterValue = filterSelect.options[filterSelect.selectedIndex].value;
        var sortField = sortDescending ? 'desc' : 'asc';
        nextPageUrl = nextPageUrl + '&filter=' + filterValue + '&sort=' + sortField;

        let moreData = true;
        window.onload = function() {
            let isLoading = false;

            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100 && nextPageUrl && !isLoading && moreData) {
                    loadMorePosts();
                }
            });

            function loadMorePosts() {
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
                    url: nextPageUrl,
                    type: 'get',
                    beforeSend: function() {
                        $('.spinner').show();
                    },
                    success: function(data) {
                        $('.spinner').hide();
                        if (data.trim() === "") {
                            moreData = false; // No more data to load
                            $('.datapagination').hide();
                        }

                        $('.dbgBodyTable').append(data);
                        $('.ptableCardDiv').append(data);

                        // Increment page number from next page url query string value and append it to the next page url
                        nextPageUrl = nextPageUrl.replace(/page=(\d+)/, function(match, pageNumber) {
                            return 'page=' + (parseInt(pageNumber) + 1);
                        });
                        isLoading = false; // Allow next AJAX call
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading more posts:", error);
                        $('.spinner').hide();
                        isLoading = false; // Allow next AJAX call even if there is an error
                    }
                });
            }
        }

        window.fetchData = function(sortField = null) {
            // Get selected filter value
            const filterSelect = document.getElementById('validationDefault05');
            const filterValue = filterSelect.options[filterSelect.selectedIndex].value;
            // reset next page url first page
            nextPageUrl = nextPageUrl.replace(/page=(\d+)/, function(match, pageNumber) {
                            return 'page=' + 1;
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
    </script>

@endsection
