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
                        onchange="">
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
    </div>

    <script>
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
                success: function(data) {
                    $('.group-container').html(data)
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });

        }
    </script>

@endsection
