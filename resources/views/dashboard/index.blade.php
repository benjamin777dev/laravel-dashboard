{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.master')

@section('title', 'Agent Commander | Dashboard')
@section('content')
    @vite(['resources/css/dashboard.css'])
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
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
        <div class="row mt-4 text-center">
            {{-- <div class="col-lg-3 col-md-3 col-sm-6 text-start">
                <p class="dFont900 dFont15 dMb10">Welcome Back, {{ $user['name'] }} <br />
                    <span class="dFont400 dFont13">{{ date('l, F j, Y') }}</span>
                </p>
                <p class="dFont800 dFont13 dMb5">Pipeline stats date ranges</p>
                <div class="d-flex justify-content-between align-items-center dCalander">
                    <input class="dFont400 dFont13 mb-0 ddaterangepicker" type="text" name="daterange"
                        value="{{ $startDate }} - {{ $endDate }}" />
                    <img class="celendar_icon" src="{{ URL::asset('/images/calendar.svg') }}" alt=""
                        onclick="triggerDateRangePicker()">
                </div>

            </div> --}}
            <div class="col-lg-3 col-md-3 text-start dcontactbtns-div">

                <div class="row g-1">
                    <div>
                        <div class="input-group-text dcontactBtns" id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#"
                            onclick="createContact();"><i class="fas fa-plus plusicon">
                            </i>
                            New Contact
                        </div>
                    </div>

                    <div>
                        <div class="input-group-text dcontactBtns" id="btnGroupAddon" data-bs-toggle="modal"
                            data-bs-target="#" onclick="createTransaction()"><i class="fas fa-plus plusicon">
                            </i>
                            New Transaction
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-ld-9 col-md-9 col-sm-12">
                <div class="row dashboard-cards-resp">
                    @foreach ($stageData as $stage => $data)
                        <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols" data-stage="{{ $stage }}">
                            <div class="card dash-card">
                                <div class="card-body dash-front-cards">
                                    <h5 class="card-title dTitle mb-0">{{ $stage }}</h5>


                                    {{-- <div class="d-flex justify-content-center align-items-center dCenterText"> --}}

                                    <p class="dSumValue">${{ $data['sum'] }}</p>
                                    {{-- <i class = "{{ $data['stageProgressIcon'] }}" style = "font-size:25px"></i>
                                        <p class="mb-0 dpercentage {{ $data['stageProgressClass'] }}">
                                            {{ $data['stageProgressExpr'] }}{{ $data['stageProgress'] }}%</p> --}}

                                    {{-- </div> --}}
                                    <p class="card-text dcountText">{{ $data['count'] }} Transactions
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
        <div class="row dmain-Container">
            <div class="row dgraphdiv">
                <div class="col-md-8">
                    <div class="row dspeedn-month-camaparison">
                        <div class="col-md-4 dspeedometersection">
                            <p class="dpipetext">My Pipeline</p>
                            <div id="canvas-holder" style="width:100%">
                                <canvas id="chart" width="400" height="400"></canvas>
                            </div>
                            {{-- <button id="randomizeData" onclick="randomDatassss();">Randomize Data</button> --}}
                            {{-- <div class="wrapper">
                                <div class="gauge">
                                    <div class="slice-colors">
                                        <div class="st slice-item"></div>
                                        <div class="st slice-item"></div>
                                        <div class="st slice-item"></div>
                                    </div>
                                    <div class="needle"></div>
                                    <div class="gauge-center"></div>
                                </div>
                            </div> --}}
                            <div>
                                <p class="dFont800 dFont13 dMb5">Pipeline range</p>
                                <div class="d-flex justify-content-between align-items-center dCalander">
                                    <input class="dFont400 dFont13 mb-0 ddaterangepicker" type="text" name="daterange"
                                        value="{{ $startDate }} - {{ $endDate }}" />
                                    <img class="celendar_icon" src="{{ URL::asset('/images/calendar.svg') }}" alt=""
                                        onclick="triggerDateRangePicker()">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 graphp-dash">
                            <div class="container  dgraphpstackContainer">
                                <p class="dcamptext">Monthly Pipeline Comparison</p>
                                <!-- Stacked Bar Chart -->
                                <div class="stacked-bar-chart w-100 stacked-contain">

                                    @php
                                        // Extracting the 'gci' values from the nested arrays
                                        $gcis = array_column($allMonths, 'gci');
                                        // Finding the maximum 'gci' value
                                        $maxGCI = max($gcis);
                                    @endphp

                                    @foreach ($allMonths as $month => $data)
                                        @php
                                            $widthPercentage = $maxGCI != 0 ? ($data['gci'] / $maxGCI) * 91 : 0;
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-1 align-self-center dmonth-design">
                                                {{ Carbon\Carbon::parse($month)->format('M') }}</div>
                                            <div class="col-md-11 dashchartImg">
                                                <div class="row dgraph-strip">
                                                    @php
                                                        // Remove the currency symbol ('$') and commas from the formatted value
                                                        $formattedGCI = str_replace(
                                                            ['$', ','],
                                                            '',
                                                            number_format($data['gci'], 0),
                                                        );
                                                    @endphp
                                                    <div class="col-md-10 text-end bar-a" {{-- style="width: {{ $widthPercentage }}%"
                                                  --}}
                                                        style="width: {{ $formattedGCI < 1000 ? 'auto' : $widthPercentage . '%' }}">
                                                        {{ '$' . number_format($data['gci'], 0) }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <p class="dtransactions-des">{{ $data['deal_count'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h4 class="text-start dFont600 mb-4">Notes</h4>
                    @if ($notesInfo->isEmpty())
                        <p class="text-center">No notes found.</p>
                    @else
                        <ul class="list-group dnotesUl">
                            @foreach ($notesInfo as $note)
                                <li
                                    class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                                    <div class="text-start" onclick="handleDeleteCheckbox('{{ $note['id'] }}')"
                                        class="form-check-input checkbox{{ $note['id'] }}"
                                        id="editButton{{ $note['id'] }}" class="btn btn-primary dnotesBottomIcon"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdropnoteview{{ $note['id'] }}">
                                        @if ($note['related_to_type'] === 'Deal')
                                            <span class="dFont800 dFont13">Related to:</span>
                                            {{ $note->dealData->deal_name ?? '' }}<br />
                                        @elseif ($note['related_to_type'] === 'Contact')
                                            <span class="dFont800 dFont13">Related to:</span>
                                            {{ $note->contactData->first_name ?? '' }}
                                            {{ $note->contactData->last_name ?? '' }}<br />
                                        @else
                                            <span class="dFont800 dFont13">Related to:</span>
                                            Global
                                        @endif
                                        <p class="dFont400 fs-4 mb-0">
                                            {{ $note['note_content'] }}
                                        </p>
                                    </div>

                                    {{-- dynamic edit modal --}}
                                    {{-- note update modal --}}
                                    <div class="modal fade" id="staticBackdropnoteupdate{{ $note['id'] }}"
                                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered deleteModal">
                                            <!-- Font Awesome delete icon -->
                                            <div class="modal-content noteModal">
                                                <div class="modal-header justify-content-between border-0">


                                                    <p class="modal-title dHeaderText">Note</p>


                                                    <div>
                                                        <i class="fa fa-trash trash-icon"
                                                            onclick="openConfirmationModal('confirmModal{{ $note['id'] }}')"></i>

                                                        <button type="button" class="btn-close closeIcon"
                                                            data-bs-dismiss="modal" aria-label="Close"
                                                            onclick="document.getElementById('editButton{{ $note['id'] }}').checked=false;"></button>
                                                    </div>
                                                    <!-- Your modal markup (assuming it has an id 'confirmModal') -->
                                                    <div id="confirmModal{{ $note['id'] }}" class="modal">
                                                        <!-- Modal content -->
                                                        <div class="modal-content">
                                                            <span class="close"
                                                                onclick="closeConfirmationModal('confirmModal{{ $note['id'] }}')">&times;</span>
                                                            <p>Are you sure you want to delete?</p>
                                                            <!-- Add buttons for confirmation -->
                                                            <button onclick="deleteNoteItem()">Yes</button>
                                                            <button
                                                                onclick="closeConfirmationModal('confirmModal{{ $note['id'] }}')">No</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form action="{{ route('update.note', ['id' => $note['zoho_note_id']]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="modal-body dtaskbody">
                                                        <p class="ddetailsText">Details</p>
                                                        <textarea name="note_text" rows="4" class="dtextarea">{{ $note['note_content'] }}</textarea>
                                                        @error('note_content')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        <p class="dRelatedText">Related to...</p>
                                                        <div class="btn-group dmodalTaskDiv">
                                                            <select class="form-select dmodaltaskSelect" name="related_to"
                                                                aria-label="Select Transaction">
                                                                <option value="{{ $note['zoho_note_id'] }}" selected>
                                                                    {{ $note['related_to_type'] }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                        @error('related_to')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 modal-footer dNoteFooters border-0">
                                                            <button type="submit"
                                                                class="btn btn-secondary dNoteModalmarkBtns">
                                                                <i class="fas fa-save saveIcon"></i> Update Note
                                                            </button>
                                                        </div>
                                                        {{-- <div class="col-md-6 modal-footer dNoteFooters border-0">
                                                            <button type="button"
                                                                onclick="markAsDone({{ $note['id'] }})"
                                                                class="btn btn-secondary dNoteModalmarkBtns">
                                                                <i class="fas fa-save saveIcon"></i> Mark as Done
                                                            </button>
                                                        </div> --}}
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- note view modal --}}
                                    <div class="modal fade" id="staticBackdropnoteview{{ $note['id'] }}"
                                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered deleteModal">
                                            <div class="modal-content noteModal">
                                                <div class="modal-header border-0">
                                                    <p class="modal-title dHeaderText">Note</p>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"
                                                        onclick="document.getElementById('editButton{{ $note['id'] }}').checked=false;"></button>
                                                </div>
                                                <div class="modal-body dtaskbody">
                                                    <p class="ddetailsText">Details</p>
                                                    <textarea name="note_text" rows="4" class="dtextarea" readonly>{{ $note['note_content'] }}</textarea>
                                                    @error('note_content')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <p class="dRelatedText">Related to...</p>
                                                    <div class="btn-group dmodalTaskDiv">
                                                        <select class="form-select dmodaltaskSelect" name="related_to"
                                                            aria-label="Select Transaction">
                                                            <option value="{{ $note['zoho_note_id'] }}" selected readonly>
                                                                {{ $note['related_to_type'] }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    @error('related_to')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gx-2"
                                        onclick="handleDeleteCheckbox('{{ $note['id'] }}')" data-bs-toggle="modal"
                                        id="editButton{{ $note['id'] }}"
                                        data-bs-target="#staticBackdropnoteupdate{{ $note['id'] }}">
                                        {{-- <input type="button" 
                                            class="checkboxupdate{{ $note['id'] }}"
                                             class="btn btn-primary dnotesBottomIcon"
                                            type="button" 
                                            {{-- {{ $note['mark_as_done'] == 1 ? 'checked' : '' }} --}}
                                        {{-- /> --}}
                                        <i class="fas fa-edit"></i>

                                    </div>
                                </li>
                            @endforeach
                            {{-- <button id="deleteButton{{ $note['id'] }}" onclick="deleteNote('{{ $note['id'] }}')"
                            class="btn btn-danger" style="display: none;">Delete</button> --}}
                        </ul>
                    @endif
                </div>
            </div>
            <div class="col-sm-12 dtasksection">
                <div class="d-flex justify-content-between">
                    <p class="dFont800 dFont15">Tasks</p>
                    <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#staticBackdropforTask"><i
                            class="fas fa-plus plusicon">
                        </i>
                        New Task
                    </div>

                </div>
                <div class="row">
                    <nav class="dtabs">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a href="/dashboard?tab=In Progress"> <button class="nav-link dtabsbtn active"
                                    id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                                    data-tab='In Progress' type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">In
                                    Progress</button></a>
                            <a href="/dashboard?tab=Not Started"> <button class="nav-link dtabsbtn"
                                    data-tab='Not Started' id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab"
                                    aria-controls="nav-profile" aria-selected="false">Upcoming</button></a>
                            <a href="/dashboard?tab=Completed"><button class="nav-link dtabsbtn" data-tab='Overdue'
                                    id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                                    type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Overdue</button></a>
                        </div>
                    </nav>
                    @include('task.tasks_table', ['tasks' => $tasks,'dealFordash'=>$dealFordash,'retrieveModuleData'=>$retrieveModuleData])
                </div>

            </div>
            <div class="table-responsive dtranstiontable mt-3">
                <p class="fw-bold">Transactions closing soon</p>
                <div class="row dtabletranstion">
                    <div class="col-md-3">Transaction Name</div>
                    <div class="col-md-2 ">Contact Name</div>
                    <div class="col-md-2 ">Phone</div>
                    <div class="col-md-3">Email</div>
                    <div class="col-md-2 ">Closing Date</div>
                </div>
                @if (count($closedDeals) === 0)
                    <div>
                        <p class="text-center" colspan="5">No records found</p>
                    </div>
                @else
                    @foreach ($closedDeals as $deal)
                        <div class="row npAttachmentBody">
                            <div class="col-md-3 npcommontableBodytext">
                                <div class="dTContactName">
                                    {{ $deal['deal_name'] }}
                                </div>
                            </div>
                            <div class="col-md-2 npcommontableBodytext">
                                <div class="dTContactName">
                                    <img src="{{ URL::asset('/images/account_box.svg') }}" alt="R">
                                    {{ $deal->userData->name }}
                                </div>
                            </div>
                            <div class="col-md-2 commonTextEllipsis npcommontableBodytext">
                                <div class="dTContactName">
                                    <img src="{{ URL::asset('/images/phoneb.svg') }}" alt="P">(720)
                                    765-4321
                                </div>
                            </div>
                            <div class="col-md-3 commonTextEllipsis npcommontableBodytext ">
                                <div class="dTContactName"> <img src="{{ URL::asset('/images/mailb.svg') }}"
                                        alt="M">{{ $deal->userData->email }}
                                </div>
                            </div>
                            <div class="col-md-2 npcommontableBodytext ">
                                <div class="dTContactName"><img src="{{ URL::asset('/images/event_busy.svg') }}"
                                        alt="E">
                                    {{ date('M d', strtotime($deal['closing_date'])) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif



                {{-- @if (count($closedDeals) === 0)
                    <div>
                        <p class="text-center" colspan="5">No records found</p>
                    </div>
                @else --}}
                <div class="dtransactionsCardDiv">
                    @foreach ($closedDeals as $deal)
                        <div class="dtCardMainDiv">
                            <div class="dtCardDateDiv">
                                <div class="dTCardName">
                                    {{ $deal['deal_name'] }}
                                </div>
                                <div class="dTCardDate"><img src="{{ URL::asset('/images/event_busy.svg') }}"
                                        alt="E">
                                    {{ date('M d', strtotime($deal['closing_date'])) }}
                                </div>
                            </div>
                            <div class="dTCardName">
                                <img src="{{ URL::asset('/images/account_box.svg') }}" alt="R">
                                {{ $deal->userData->name }}
                            </div>
                            <div class="dTCardName">
                                <img src="{{ URL::asset('/images/phoneb.svg') }}" alt="P">(720)
                                765-4321
                            </div>
                            <div class="dTCardmail"> <img src="{{ URL::asset('/images/mailb.svg') }}"
                                    alt="M">{{ $deal->userData->email }}
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- @endif --}}

            </div>
            {{-- <div class="table-responsive dtranstiontable mt-3">
                <p class="fw-bold">Transactions closing soon</p>
                <table class="table dtabletranstion">
                    <thead>
                        <tr>
                            <th scope="col">Transaction Name</th>
                            <th scope="col">Contact Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Email</th>
                            <th scope="col">Closing Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($closedDeals) === 0)
                            <tr>
                                <td class="text-center" colspan="5">No records found</td>
                            </tr>
                        @else
                            @foreach ($closedDeals as $deal)
                                <tr>
                                    <td>{{ $deal['deal_name'] ?? 'N/A' }}</td>
                                    <td>{{ $deal->contactName->first_name ?? 'N/A' }}
                                        {{ $deal->contactName->last_name ?? '' }}</td>
                                    <td>{{ $deal->contactName->phone ?? 'N/A' }}</td>
                                    <td>{{ $deal->contactName->email ?? 'N/A' }}</td>
                                    <td>{{ $deal['closing_date'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div> --}}
        </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropforNote">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
    </div>
    {{-- Modals --}}
    {{-- Create New Task Modal --}}
    <div class="modal fade" id="staticBackdropforTask" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Tasks</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidation()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="subject" onkeyup="validateTextarea()"  id="subject" rows="4" class="dtextarea"></textarea>
                    <div id="task_error" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" id="related_to" onchange="moduleSelected(this)"
                            name="related_to_task" aria-label="Select Transaction">
                            <option value="">Please select one</option>
                            @foreach ($retrieveModuleData as $item)
                                @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                    <option value="{{ $item['api_name'] }}">{{ $item['api_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                        <select class="form-select dmodaltaskSelect" id="taskSelect" name="related_to_parent"
                            aria-label="Select Transaction" style="display: none;">
                            <option value="">Please Select one</option>
                        </select>
                    </div>
                    <p class="dDueText">Date due</p>
                    <input type="date" name="due_date" class="dmodalInput" />
                </div>
                <div class="modal-footer ">
                    <button type="button" onclick="addTask()" class="btn btn-secondary taskModalSaveBtn">
                        <i class="fas fa-save saveIcon"></i> Save Changes
                    </button>

                </div>

            </div>
        </div>
    </div>
    {{-- Note Modal --}}
    <div class="modal fade" id="staticBackdropforNote" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content noteModal">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Note</p>
                    <button type="button" onclick="resetFormAndHideSelectDashboard();" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="noteForm_dash" action="{{ route('save.note') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="modal-body dtaskbody">
                        <p class="ddetailsText">Details</p>
                        <textarea name="note_text" id="note_text" rows="4" class="dtextarea"></textarea>
                        <div id="note_text_error" class="text-danger"></div>
                        <p class="dRelatedText">Related to...</p>
                        <div class="btn-group dmodalTaskDiv">
                            <select class="form-select dmodaltaskSelect" id="related_to" onchange="moduleSelected(this)"
                                name="related_to" aria-label="Select Transaction">
                                <option value="">Please select one</option>
                                @foreach ($retrieveModuleData as $item)
                                    @if (in_array($item['api_name'], ['Deals', 'Contacts']))
                                        <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="form-select dmodaltaskSelect" id="taskSelect" name="related_to_parent"
                                aria-label="Select Transaction" style="display: none;">
                                <option value="">Please Select one</option>
                            </select>
                        </div>
                        <div id="related_to_error" class="text-danger"></div>
                    </div>
                    <div class="modal-footer dNoteFooter border-0">
                        <button type="button" id="validate-button" onclick="validateNoteDash()"
                            class="btn btn-secondary dNoteModalmarkBtn">
                            <i class="fas fa-save saveIcon"></i> Add Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- save Modal --}}
    {{-- <div class="modal fade" id="saveModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content">
                <div class="modal-header border-0">
                    {{-- <h5 class="modal-title">Modal title</h5> --}}
    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="saveModalBodyText">Changes have been saved</p>
                </div>
                <div class="modal-footer justify-content-evenly border-0">
                    <div class="d-grid col-12">
                        <button type="button" class="btn btn-secondary saveModalBtn" data-bs-dismiss="modal">
                            <i class="fas fa-check trashIcon"></i>
                            Understood
                        </button>
                    </div>

                </div>

            </div>
        </div> --}}
    {{-- </div>` --}}
    {{-- save Modal --}}





    {{-- @vite(['resources/js/dashboard.js']) --}}
    <!-- Include Date Range Picker -->

@section('dashboardScript')



@endsection
@endsection

<script>
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
            'Not Started': false,
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
            activeTab.style.backgroundColor = "#253C5B"
            activeTab.style.color = "#fff";
            activeTab.style.borderRadius = "4px";
        }

        document.getElementById("note_text").addEventListener("keyup", validateNoteDash);
        document.getElementById("related_to").addEventListener("change", validateNoteDash);

        // console.log("yes tist woring", @json($allMonths), )
        var ctx = document.getElementById('chart').getContext('2d');
        window.myGauge = new Chart(ctx, config);


    });
    // var selectedNoteIds = [];

    function handleDeleteCheckbox(id) {
        // Get all checkboxes
        const checkboxes = document.querySelectorAll('.checkbox' + id);
        // Get delete button
        const deleteButton = document.getElementById('deleteButton' + id);
        const editButton = document.getElementById('editButton' + id);
        console.log(checkboxes, 'checkboxes')
        // Add event listener to checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Check if any checkbox is checked
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                // Toggle delete button visibility
                editButton.style.display = anyChecked ? 'block' : 'none';
                // if (deleteButton.style.display === 'block') {
                //     selectedNoteIds.push(id)
                // }
            });
        });

    }
    window.selectedTransation;
    // Get the select element

    function selectedElement(element) {
        var selectedValue = element.value;
        window.selectedTransation = selectedValue;
        //    console.log(selectedTransation);
    }

    function resetValidation() {
        document.getElementById("task_error").innerHTML = "";
        
    }

    function validateTextarea() {
        var textarea = document.getElementById('subject');
        var textareaValue = textarea.value;
        // Check if textarea value is empty
        if (textareaValue === '') {
            // Show error message or perform validation logic
            document.getElementById("task_error").innerHTML = "please enter details";
        } else {
            document.getElementById("task_error").innerHTML = "";
        }
    }

    function addTask() {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("task_error").innerHTML = "please enter details";
            return;
        }
        var seModule = document.getElementsByName("related_to_task")[0].value;
        var WhatSelectoneid = document.getElementsByName("related_to_parent")[0].value;
        // var whoId = window.selectedTransation
        // if (whoId === undefined) {
        //     whoId = whoSelectoneid
        // }
        var dueDate = document.getElementsByName("due_date")[0].value;
        var formData = {
            "data": [{
                "Subject": subject,
                "Status": "Not Started",
                "Due_Date": dueDate,
                "Priority": "High",
                "What_Id": {
                    "id": WhatSelectoneid
                },
                "$se_module": seModule
            }],
            "_token": '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route('create.task') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    alert(upperCaseMessage);
                    window.location.reload();
                } else {
                    alert("Response or message not found");
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }
    var textElement;

    function makeEditable(id, textfield, zohoID) {
         
        if (textfield === "subject") {
            textElement = document.getElementById('editableText' + id);
            textElementCard = document.getElementById('editableTextCard' + id);
            //For Table data                
            var text = textElement.textContent.trim();
            textElement.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

            //For card data
            var text = textElementCard.textContent.trim();
            textElementCard.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

            let inputElementmake = document.getElementById('editableInput' + id);
            inputElementmake.focus();                          
            inputElementmake.addEventListener('blur', function() {
                updateText(inputElementmake.value, textfield, zohoID);
            });
        }
        if (textfield === "date") {
            let dateLocal = document.getElementById('date_local' + id);
            var text = dateLocal.value.trim();
            updateText(text, textfield, zohoID);
        }
       

    }

    function updateText(newText, textfield, id,WhatSelectoneid="") {
        let inputElementtext;
        let dateLocal;
        if(textfield==="subject"){
             inputElementtext = document.getElementById('editableText' + id);
        }else if(textfield==="date"){
             dateLocal = document.getElementById('date_local' + id);
             console.log(dateLocal,newText,'checlout');
             dateLocal = dateLocal?.substring(0, 10);
             newText = newText?.substring(0, 10);
        }else{
            
        }
        if (newText == "") {
            alert("Empty text feild");
            return;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = {
            "data": [{
                "Subject": textfield === "subject" ? newText : inputElementtext?.value,
                "Due_Date": textfield === "date" ? newText : dateLocal?.value,
                "What_Id": WhatSelectoneid ? { "id": WhatSelectoneid } : undefined,
                 "$se_module": textfield === "deals" ? newText : undefined
            }]
        };
        // Filter out undefined values
            formData.data[0] = Object.fromEntries(
                Object.entries(formData.data[0]).filter(([_, value]) => value !== undefined)
            );
        // console.log("ys check ot")
        $.ajax({
            url: "{{ route('update.task', ['id' => ':id']) }}".replace(':id', id),
            method: 'PUT',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                // Handle success response
                if (response?.data[0]?.status == "success") {
                    if (!document.getElementById('savemakeModalId' + id).classList.contains('show')) {
                        var modalTarget = document.getElementById('savemakeModalId' + id);
                        var update_message = document.getElementById('updated_message_make');
                        update_message.textContent = response?.data[0]?.message;
                        // Show the modal
                        $(modalTarget).modal('show');
                        window.location.reload();
                    }

                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText, 'errrorroororooro');


            }
        })



    }

    function convertDateTime(inputDateTime) {

        // Parse the input date string
        let dateObj = new Date(inputDateTime);

        // Format the date components
        let year = dateObj.getFullYear();
        let month = (dateObj.getMonth() + 1).toString().padStart(2, '0'); // Month is 0-indexed, so we add 1
        let day = dateObj.getDate().toString().padStart(2, '0');
        let hours = dateObj.getHours().toString().padStart(2, '0');
        let minutes = dateObj.getMinutes().toString().padStart(2, '0');
        let seconds = dateObj.getSeconds().toString().padStart(2, '0');

        // Format the timezone offset
        let timezoneOffsetHours = Math.abs(dateObj.getTimezoneOffset() / 60).toString().padStart(2, '0');
        let timezoneOffsetMinutes = (dateObj.getTimezoneOffset() % 60).toString().padStart(2, '0');
        let timezoneOffsetSign = dateObj.getTimezoneOffset() > 0 ? '-' : '+';

        // Construct the formatted datetime string
        let formattedDateTime =
            `${year}-${month}-${day}T${hours}:${minutes}:${seconds}${timezoneOffsetSign}${timezoneOffsetHours}:${timezoneOffsetMinutes}`;

        return formattedDateTime;
    }
    // Function to open the confirmation modal
    function openConfirmationModal(id) {
        var modal = document.getElementById(id);
        modal.style.display = 'block';
    }
    // Function to close the confirmation modal
    function closeConfirmationModal(id) {
        var modal = document.getElementById(id);
        modal.style.display = 'none';
    }

    // Function to handle deletion
    function deleteNoteItem(ids) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        try {
            if (id) {
                $.ajax({
                    url: "{{ route('delete.note', ['id' => ':id']) }}".replace(':id', ids),
                    method: 'DELETE', // Change to DELETE method
                    contentType: 'application/json',
                    dataType: 'JSON',
                    data: {
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        // Handle success response
                        alert("deleted successfully", response);
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        alert(xhr.responseText)
                    }
                })

            }
        } catch (err) {
            console.error("error", err);
        }

    }

    function updateTask(id, indexid) {
        alert('update task');
        // console.log(id, indexid, 'chekcdhfsjkdh')
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let inputElement = document.getElementById('editableText' + indexid);
        var taskDate = document.getElementById('date_val' + id);
        let formattedDateTime = convertDateTime(taskDate.value);
        // console.log(formattedDateTime);
        //         alert(formattedDateTime);
        //         return;
        if (!inputElement) {
            console.error("Input element not found for indexid:", indexid);
            return;
        }
        var elementValue = inputElement.textContent;
        // return;
        if (elementValue.trim() === "") {
            // console.log("chkockdsjkfjksdh")
            return alert("Please enter subject value first");
        }
        // console.log("inputElementval",elementValue!==undefined,elementValue)
        if (elementValue !== undefined) { // return;
            var formData = {
                "data": [{
                    "Subject": elementValue,
                    // "Remind_At": {
                    //     "ALARM": `FREQ=NONE;ACTION=EMAIL;TRIGGER=DATE-TIME:${taskDate.value}`
                    // }
                }]
            };
            // console.log("ys check ot")
            $.ajax({
                url: "{{ route('update.task', ['id' => ':id']) }}".replace(':id', id),
                method: 'PUT',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function(response) {
                    // Handle success response

                    if (response?.data[0]?.status == "success") {
                        // console.log(response?.data[0], 'sdfjkshdjkfshd')
                        // Get the button element by its ID
                        if (!document.getElementById('saveModalId').classList.contains('show')) {
                            var button = document.getElementById('update_changes');
                            var update_message = document.getElementById('updated_message');
                            // Get the modal target element by its ID
                            var modalTarget = document.getElementById('saveModalId');
                            console.log(modalTarget, 'modalTarget')
                            // Set the data-bs-target attribute of the button to the ID of the modal
                            button.setAttribute('data-bs-target', '#' + modalTarget.id);
                            update_message.textContent = response?.data[0]?.message;
                            // Trigger a click event on the button to open the modal
                            button.click();
                            // alert("updated success", response)
                            // window.location.reload();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText, 'errrorroororooro');


                }
            })
        }
    }

    function deleteTask(id, isremoveselected = false) {
        let updateids = removeAllSelected();
        if (updateids === "" && id === undefined) {
            return;
        }
        if (isremoveselected) {
            id = undefined;
        }
        if (updateids !== "") {
            if (confirm("Are you sure you want to delete selected task?")) {

            } else {
                return;
            }
        }
        if (id === undefined) {
            id = updateids;
        }
        //remove duplicate ids
        ids = id.replace(/(\b\w+\b)(?=.*\b\1\b)/g, '').replace(/^,|,$/g, '');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        try {
            if (id) {
                $.ajax({
                    url: "{{ route('delete.task', ['id' => ':id']) }}".replace(':id', ids),
                    method: 'DELETE', // Change to DELETE method
                    contentType: 'application/json',
                    dataType: 'JSON',
                    data: {
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        // Handle success response
                        alert("deleted successfully", response);
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        alert(xhr.responseText)
                    }
                })

            }
        } catch (err) {
            console.error("error", err);
        }
    }

    function removeAllSelected() {
        // Select all checkboxes
        var checkboxes = document.querySelectorAll('input[class="task_checkbox"]');
        var ids = ""; // Initialize ids variable to store concatenated IDs
        // Iterate through each checkbox
        checkboxes.forEach(function(checkbox) {
            // console.log(checkboxes,'checkboxes')
            // Check if the checkbox is checked
            if (checkbox.checked) {
                if (checkbox.id !== "light-mode-switch" && checkbox.id !== "dark-rtl-mode-switch" && checkbox
                    .id !== "rtl-mode-switch" && checkbox.id !== "dark-mode-switch" && checkbox.id !==
                    "checkbox_all") {
                    // Concatenate the checkbox ID with a comma
                    ids += checkbox.id + ",";
                    document.getElementById("removeBtn").style.backgroundColor = "rgb(37, 60, 91);"
                }
            }
        });

        // Remove the trailing comma
        if (ids !== "") {
            ids = ids.replace(/,+(?=,|$)/g, "");
        }

        return ids;
    }

    function toggleAllCheckboxes() {
        // console.log("yes it")
        let state = false;
        let updateColor = document.getElementById("removeBtn");
        var allCheckbox = document.getElementById('checkbox_all');
        var checkboxes = document.querySelectorAll('input[class="task_checkbox"]');

        checkboxes.forEach(function(checkbox) {
            // Set the state of each checkbox based on the state of the "checkbox_all"
            checkbox.checked = allCheckbox.checked;
            if (checkbox.checked) {

                state = true;

            } else {
                state = false;
            }
        });
        if (state) {
            updateColor.style.backgroundColor = "rgb(37, 60, 91)";
        } else {

            updateColor.style.backgroundColor = "rgb(192 207 227)";
        }
    }

    function triggerCheckbox(checkboxid) {
        let updateColor = document.getElementById("removeBtn");
        var allCheckbox = document.getElementById('checkbox_all');
        var checkboxes = document.querySelectorAll('input[class="task_checkbox"]');
        var allChecked = true;
        var anyUnchecked = false; // Flag to track if any checkbox is unchecked
        var anyChecked = false;
        checkboxes.forEach(function(checkbox) {
            if (!checkbox.checked) {
                anyUnchecked = true; // Set flag to true if any checkbox is unchecked
                // updateColor.style.backgroundColor = "rgb(192 207 227)";
            } else {
                // updateColor.style.backgroundColor = "rgb(37, 60, 91)";
                anyChecked = true;
            }
        });

        if (anyChecked) {
            updateColor.style.backgroundColor = "rgb(37, 60, 91)"; // Checked color
        } else {
            updateColor.style.backgroundColor = "rgb(192, 207, 227)"; // Unchecked color
        }
        allCheckbox.checked = !anyUnchecked; // Update "Select All" checkbox based on the flag
    }

    function clearValidationMessages() {
        document.getElementById("note_text_error").innerText = "";
        document.getElementById("related_to_error").innerText = "";
    }

    function resetFormAndHideSelectDashboard() {
        document.getElementById('noteForm_dash')?.reset();
        document.getElementById('taskSelect').style.display = 'none';
        clearValidationMessages();
    }
    // validation function onsubmit
    function validateNoteDash() {
        let noteText = document.getElementById("note_text").value;
        let relatedTo = document.getElementById("related_to").value;
        let isValid = true;

        // Reset errors
        document.getElementById("note_text_error").innerText = "";
        document.getElementById("related_to_error").innerText = "";

        // Validate note text length
        if (noteText.trim().length > 10) {
            document.getElementById("note_text_error").innerText = "Note text must be 10 characters or less";
            isValid = false;
        }
        // Validate note text
        if (noteText.trim() === "") {
            document.getElementById("note_text_error").innerText = "Note text is required";
            isValid = false;
        }

        // Validate related to
        if (relatedTo === "") {
            document.getElementById("related_to_error").innerText = "Related to is required";
            document.getElementById("taskSelect").style.display = "none";
            isValid = false;
        }
        if (isValid) {
            let changeButton = document.getElementById('validate-button');
            changeButton.type = "submit";
        }
        return isValid;
    }



    function moduleSelected(selectedModule,id="") {
        // console.log(accessToken,'accessToken')
        var selectedOption = selectedModule.options[selectedModule.selectedIndex];
        var selectedText = selectedOption.text;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/task/get-' + selectedText,
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Handle successful response
                var tasks = response;
                // Assuming you have another select element with id 'taskSelect'
                var taskSelect = $('#taskSelect'+id);
                // Clear existing options
                taskSelect.empty();
                // Populate select options with tasks
                $.each(tasks, function(index, task) {
                    if (selectedText === "Tasks") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_task_id,
                            text: task?.subject
                        }));
                    }
                    if (selectedText === "Deals") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_deal_id,
                            text: task?.deal_name
                        }));
                    }
                    if (selectedText === "Contacts") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_contact_id,
                            text: task?.first_name + ' ' + task?.last_name
                        }));
                    }
                });
                taskSelect.show();
                // Do whatever you want with the response data here
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });

    }

    function triggerDateRangePicker() {
        // Trigger click event on the input element
        $('.ddaterangepicker').click();
    }

    function markAsDone(noteId) {
        // Send an AJAX request to the route using jQuery
        $.ajax({
            type: 'POST',
            url: '{{ route('mark.done') }}',
            data: {
                // Pass the note ID to the server
                note_id: noteId,
                // Add CSRF token for Laravel security
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response?.mark_as_done === 1) {
                    window.location.reload();
                }
                // Handle success response if needed
            },
            error: function(xhr, status, error) {
                // Handle error if needed
            }
        });

    }

    window.createTransaction = function() {
        console.log("Onclick");
        var formData = {
            "data": [{
                "Deal_Name": "{{ config('variables.dealName') }}",
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}"
                },
                "Stage": "Potential"
            }],
            "_token": '{{ csrf_token() }}'
        };
        $.ajax({
            url: '{{ url('/pipeline/create') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(data) {
                console.log(data);
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/pipeline-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function createContact() {
        console.log("Onclick");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let name = "CHR";
        var formData = {
            "data": [{
                "Relationship_Type": "Primary",
                "Missing_ABCD": true,
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}",
                    "full_name": "{{ auth()->user()->name }}",
                },
                "Unsubscribe_From_Reviews": false,
                "Currency": "USD",
                "Market_Area": "-None-",
                "Lead_Source": "-None-",
                "ABCD": "-None-",
                "Last_Name": name,
                "zia_suggested_users": {}
            }],
            "_token": '{{ csrf_token() }}'
        };
        $.ajax({
            url: '{{ url('/contact/create') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(data) {
                console.log(data);
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/contacts-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }


    var randomScalingFactor = function(progressCount = "") {
        // console.log(progressCount,'progressCount')
        return Math.round(Math.random() * 100);
        // return Math.round(progressCount!==""?progressCount:@json($progress) );
    };

    var randomData = function() {
        return [15, 45, 100];
    };
    var randomValue = function(data) {
        if (data) {
            console.log(data, 'data')
            return data;
        }
        return @json($progress);
    };

    var data = randomData();
    var value = randomValue();
    console.log(data, value, 'valueishereeee')
    var config = {
        type: 'gauge',
        data: {
            //labels: ['Success', 'Warning', 'Warning', 'Error'],
            datasets: [{
                data: data,
                value: value,
                backgroundColor: ['#FE5243', '#FADA05', '#21AC25'],
                //  [@json($progressClass)],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                //   text: 'Gauge chart'
            },
            layout: {
                padding: {
                    bottom: 30
                }
            },
            needle: {
                // Needle circle radius as the percentage of the chart area width
                radiusPercentage: 2,
                // Needle width as the percentage of the chart area width
                widthPercentage: 3.2,
                // Needle length as the percentage of the interval between inner radius (0%) and outer radius (100%) of the arc
                lengthPercentage: 80,
                // The color of the needle
                color: 'rgba(0, 0, 0, 1)'
            },
            valueLabel: {
                formatter: Math.round,
            },
            chartArea: {
                // Set the desired width and height of the chart area
                width: '80%',
                height: '80%'
            }
        }
    };

    function calculateStageData(e) {
        var dateRangeString = e.value; // Assuming e.value contains the date range string
        var dates = dateRangeString.split(' - ');
        var startDate = dates[0];
        var endDate = dates[1];

        // Convert start date to "year-month-day" format
        // var startDateComponents = startDate.split('-');
        // var endDateComponents = endDate.split('-');
        // var formattedStartDate = startDateComponents[2] + '-' + startDateComponents[0] + '-' + startDateComponents[1];
        // var formattedEndtDate = endDateComponents[2] + '-' + endDateComponents[0] + '-' + endDateComponents[1];
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: `get-stages?start_date=${startDate}&end_date=${endDate}`,
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Handle successful response
                randomScalingFactor(response?.calculateProgress);
                data = randomData();
                value = randomValue(response?.calculateProgress);
                console.log(data, value, 'datavalue')
                // Update gauge chart data and value
                config.data.datasets[0].data = data;
                config.data.datasets[0].value = value;
                // Update gauge chart with new data
                window.myGauge.update();
                var ctx = document.getElementById('chart').getContext('2d');
                window.myGauge = new Chart(ctx, config);
                Object.keys(response).forEach(function(stage) {
                    if (response.hasOwnProperty(stage)) {
                        console.log(stage, 'stage is here')
                        // Find the corresponding card element using data-stage attribute
                        var cardElement = $('.dCardsCols[data-stage="' + stage + '"]');
                        // Update data in the card
                        var data = response[stage];
                        cardElement.find('.dSumValue').text('$' + data.sum);
                        // cardElement.find('.dpercentage').text(data.stageProgressExpr + data
                        //     .stageProgress + '%');
                        // cardElement.find('.dpercentage').removeClass().addClass('dpercentage ' +
                        //     data.stageProgressClass);
                        // cardElement.find('.mdi').removeClass().addClass(data.stageProgressIcon);
                        cardElement.find('.dcountText').text(data.count + ' Transactions');
                    }
                });

            },
            error: function(xhr, status, error) {
                // Handle error
                console.error("Ajax Error:", error);
            }
        });


    }
</script>
<script src="{{ URL::asset('http://[::1]:5173/resources/js/dashboard.js') }}"></script>
