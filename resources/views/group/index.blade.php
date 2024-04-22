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

                <select class="form-select dbgSelectinfo" id="validationDefault04" required>
                    <option selected value="Select columns to display">Select columns to display</option>
                    <option>...</option>
                </select>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="row dbgSortDiv">
                    <div class="col-md-6 col-sm-12 dbgGroupDiv">
                        <select class="form-select dbgSelectinfo" id="validationDefault04" required>
                            <option selected disabled value="Sort Groups by...">Sort Groups by...</option>
                            <option>...</option>
                        </select>
                    </div>
                    <div class="input-group-text dbgfilterBtn col-md-6 col-sm-12" id="btnGroupAddon">
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
                            <div class="dbgcommonFlex">
                                <input type="checkbox" />
                            </div>
                        </th>
                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">Name</p>
                                <img src="{{ URL::asset('/images/swap_vert.svg') }}" class="ppiplineSwapIcon"
                                    alt="Transaction icon">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">A+</p>
                                <input type="checkbox" />
                            </div>
                        </th>
                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">A</p>
                                <input type="checkbox" />
                            </div>
                        </th>
                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">B</p>
                                <input type="checkbox" />
                            </div>
                        </th>
                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">C</p>
                                <input type="checkbox" />
                            </div>
                        </th>
                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">D</p>
                                <input type="checkbox" />
                            </div>
                        </th>

                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">Email Blast</p>
                                <input type="checkbox" />
                            </div>
                        </th>

                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">Market Mailer</p>
                                <input type="checkbox" />
                            </div>
                        </th>

                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">Notepad Mailer</p>
                                <input type="checkbox" />
                            </div>
                        </th>

                        <th scope="col">
                            <div class="dbgcommonFlex">
                                <p class="mb-0">Client Event</p>
                                <input type="checkbox" />
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-center dbgBodyTable">
                    @foreach ($contacts as $contact)
                    <tr>
                        <td> <input type="checkbox" /></td>
                        <td class="text-start"> {{$contact->contactData['first_name']??'N/A'}} {{$contact->contactData['last_name']??'N/A'}}</td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'A+' ? 'checked' : '' }}/></td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'A' ? 'checked' : '' }}/></td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'B' ? 'checked' : '' }}/></td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'C' ? 'checked' : '' }}/></td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'D' ? 'checked' : '' }}/></td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'Email Blast' ? 'checked' : '' }}/></td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'Market Mailer' ? 'checked' : '' }}/></td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'Notepad Mailer' ? 'checked' : '' }}/></td>
                        <td> <input type="checkbox" {{ $contact->groupData['name'] == 'Client Event' ? 'checked' : '' }}/></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection