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
            <div class="col-lg-3 col-md-3 col-sm-6 text-start">
                <p class="dFont900 dFont15 dMb10">Welcome Back, {{ $user['name'] }} <br />
                    <span class="dFont400 dFont13">{{ date('l, F j, Y') }}</span>
                </p>
                <p class="dFont800 dFont13 dMb5">Pipeline stats date ranges</p>
                <div class="d-flex justify-content-between align-items-center dCalander">
                    {{-- <p class="dFont400 dFont13 mb-0">{{ $startDate }} - {{ $endDate }}</p> --}}
                    <input class="dFont400 dFont13 mb-0 ddaterangepicker" type="text"
                        name="daterange" value="{{ $startDate }} - {{ $endDate }}" />
                    {{-- <i class="fa fa-calendar calendar-icon cursor-pointer" id="calendar-icon" onclick="triggerDateRangePicker()"></i> --}}
                    <img class="celendar_icon" src="{{ URL::asset('/images/calendar.svg') }}" alt=""
                        onclick="triggerDateRangePicker()">

                </div>

            </div>

            <div class="col-ld-9 col-md-9 col-sm-12">
                <div class="row dashboard-cards-resp">
                    @foreach ($stageData as $stage => $data)
                        <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols" data-stage="{{ $stage }}">
                            <div class="card dash-card">
                                <div class="card-body dash-front-cards">
                                    <h5 class="card-title dFont400 dFont13 dTitle mb-0">{{ $stage }}</h5>


                                    <div class="d-flex justify-content-center align-items-center dCenterText">

                                        <span class="dFont800 dFont18">${{ $data['sum'] }}</span>
                                        <i class = "{{ $data['stageProgressIcon'] }}" style = "font-size:25px"></i>
                                        <p class="mb-0 dpercentage {{ $data['stageProgressClass'] }}">
                                            {{ $data['stageProgressExpr'] }}{{ $data['stageProgress'] }}%</p>

                                    </div>
                                    <p class="card-text dFont800 dFont13">{{ $data['count'] }} Transactions
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-sm-12 dtasksection">
                <div class="d-flex justify-content-between">
                    <p class="dFont800 dFont15">Tasks</p>
                    <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId"><i
                            class="fas fa-plus plusicon">
                        </i>
                        New Task
                    </div>

                </div>
                <div class="row">
                    <nav class="dtabs">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a href="/dashboard?tab=In Progress"> <button class="nav-link dtabsbtn active" id="nav-home-tab"
                                    data-bs-toggle="tab" data-bs-target="#nav-home" data-tab='In Progress' type="button"
                                    role="tab" aria-controls="nav-home" aria-selected="true">In
                                    Progress</button></a>
                            <a href="/dashboard?tab=Not Started"> <button class="nav-link dtabsbtn" data-tab='Not Started'
                                    id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button"
                                    role="tab" aria-controls="nav-profile" aria-selected="false">Upcoming</button></a>
                            <a href="/dashboard?tab=Completed"><button class="nav-link dtabsbtn" data-tab='Overdue'
                                    id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button"
                                    role="tab" aria-controls="nav-contact" aria-selected="false">Overdue</button></a>
                        </div>
                    </nav>

                    <div class="table-responsive dresponsivetable">
                        <table class="table dtableresp">
                            <thead>
                                <tr class="dFont700 dFont10">
                                    <th scope="col"><input type="checkbox" onclick="toggleAllCheckboxes()"
                                            id="checkbox_all" id="checkbox_task" /></th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Transaction Related</th>
                                    <th scope="col">Task Date</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($tasks) > 0)
                                    @foreach ($tasks as $task)
                                        <tr class="dresponsivetableTr">
                                            <td><input onchange="triggerCheckbox('{{ $task['zoho_task_id'] }}')"
                                                    type="checkbox" class="task_checkbox"
                                                    id="{{ $task['zoho_task_id'] }}" /></td>
                                            <td>
                                                <p class="dFont900 dFont14 d-flex justify-content-between dMt16 dSubjectText"
                                                    id="editableText{{ $task['id'] }}">
                                                    {{ $task['subject'] ?? 'N/A' }}
                                                    <i class="fas fa-pencil-alt pencilIcon"
                                                        onclick="makeEditable('{{ $task['id'] }}')"></i>
                                                </p>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <select class="form-select dselect" aria-label="Transaction test"
                                                        id="dropdownMenuButton">
                                                        <option value="{{ $task['Who_Id']['id'] ?? '' }}">
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="datetime-local" id="date_val{{ $task['zoho_task_id'] }}"
                                                    value="{{ \Carbon\Carbon::parse($task['due_date'])->format('Y-m-d\TH:i') }}" />
                                            </td>
                                            <td>
                                                <div class="d-flex ">
                                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                                        id="btnGroupAddon" data-bs-toggle="modal"
                                                        onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')">
                                                        <i class="fas fa-hdd plusicon"></i>
                                                        Save
                                                    </div>
                                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                                                        id="btnGroupAddon" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
                                                        <i class="fas fa-trash-alt plusicon"></i>
                                                        Delete
                                                    </div>
                                                </div>
                                                {{-- delete Modal --}}
                                                {{-- <div class="modal fade" id="deleteModalId{{$task['zoho_task_id']}}" tabindex="-1">
                                                        <div class="modal-dialog modal-dialog-centered deleteModal">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0">
                                                                    {{-- <h5 class="modal-title">Modal title</h5> --}}
                                                {{-- <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="deleteModalBodyText">Please confirm you’d
                                                                        like to<br />
                                                                        delete this item.</p>
                                                                </div>
                                                                <div class="modal-footer justify-content-evenly border-0">
                                                                    <div class="d-grid gap-2 col-5">
                                                                        <button onclick="deleteTask('{{$task['zoho_task_id']}}')" type="button"
                                                                            class="btn btn-secondary deleteModalBtn"
                                                                            data-bs-dismiss="">
                                                                            <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                                                            delete
                                                                        </button>
                                                                    </div>
                                                                    <div class="d-grid gap-2 col-5">
                                                                        <button type="button"
                                                                            class="btn btn-primary goBackModalBtn">
                                                                            <i class="fas fa-arrow-left goBackIcon"></i>
                                                                            No, go back
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                {{-- </div>  --}}
                                                {{-- delete Modal --}}
                                                <div class="modal fade" id="deleteModalId{{ $task['zoho_task_id'] }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered deleteModal">
                                                        <div class="modal-content">
                                                            <div class="modal-header border-0 deleteModalHeaderDiv">
                                                                {{-- <h5 class="modal-title">Modal title</h5> --}}
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body deletemodalBodyDiv">
                                                                <p class="deleteModalBodyText">Please confirm you’d like
                                                                    to<br />
                                                                    delete this item.</p>
                                                            </div>
                                                            <div
                                                                class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                                                <div class="d-grid gap-2 col-5">
                                                                    <button type="button"
                                                                        onclick="deleteTask('{{ $task['zoho_task_id'] }}')"
                                                                        class="btn btn-secondary deleteModalBtn"
                                                                        data-bs-dismiss="modal">
                                                                        <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                                                        delete
                                                                    </button>
                                                                </div>
                                                                <div class="d-grid gap-2 col-5">
                                                                    <button type="button"
                                                                    data-bs-dismiss="modal"
                                                                        class="btn btn-primary goBackModalBtn">
                                                                        <img src="{{ URL::asset('/images/reply.svg') }}"
                                                                            data-bs-dismiss="modal" alt="R">No, go
                                                                        back
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="12">No records found</td>
                                    </tr>
                                @endif

                            </tbody>

                        </table>
                        <div class="dprogressCards">
                            @if (count($tasks) > 0)
                                @foreach ($tasks as $task)
                                    <div class="dcardscheckbox">
                                        <input type="checkbox" />
                                    </div>
                                    <div class="dcardssubjectdiv">
                                        <p class="dcardSubject" id="editableTextCard{{ $task['id'] }}"
                                            onclick="makeEditable('{{ $task['id'] }}')">
                                            {{ $task['subject'] ?? 'N/A' }}
                                            {{-- <i class="fas fa-pencil-alt pencilIcon "></i> --}}
                                        </p>
                                        <div class="btn-group dcardsselectdiv">
                                            <p class="dcardsTransactionText">Transaction Related</p>
                                            <select class="form-select dselect" aria-label="Transaction test"
                                                id="dropdownMenuButton">
                                                <option value="{{ $task['Who_Id']['id'] ?? '' }}">{{ $task }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="dcardsdateinput">
                                            <p class="dcardsTaskText">Task Date</p>
                                            <input type="datetime-local"
                                                value="{{ \Carbon\Carbon::parse($task['created_time'])->format('Y-m-d\TH:i') }}" />
                                        </div>
                                    </div>
                                    <div class="dcardsbtnsDiv">
                                        <div id="update_changes" class="input-group-text dcardssavebtn"
                                            id="btnGroupAddon" data-bs-toggle="modal"
                                            onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')"
                                            data-bs-target="#saveModalId">
                                            <i class="fas fa-hdd plusicon"></i>
                                            Save
                                        </div>
                                        <div class="input-group-text dcardsdeletebtn"
                                            onclick="deleteTask('{{ $task['zoho_task_id'] }}')" id="btnGroupAddon"
                                            data-bs-toggle="modal" data-bs-target="#deleteModalId">
                                            <i class="fas fa-trash-alt plusicon"></i>

                                            Delete
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div>
                                    <div class="text-center">No records found</div>
                                </div>
                            @endif
                        </div>
                        @if (count($tasks) > 0)
                            <div class="dpagination">
                                <div onclick="deleteTask('{{$task['zoho_task_id']}}')"
                                    class="input-group-text text-white justify-content-center removebtn dFont400 dFont13"
                                    id="removeBtn">
                                    <i class="fas fa-trash-alt plusicon"></i>
                                    Remove Selected
                                </div>
                                <nav aria-label="..." class="dpaginationNav">
                                    <ul class="pagination ppipelinepage d-flex justify-content-end">
                                        <!-- Previous Page Link -->
                                        @if ($tasks->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">Previous</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $tasks->previousPageUrl() }}&tab={{ request()->query('tab') }}"
                                                    rel="prev">Previous</a>
                                            </li>
                                        @endif

                                        <!-- Pagination Elements -->
                                        @php
                                            $currentPage = $tasks->currentPage();
                                            $lastPage = $tasks->lastPage();
                                            $startPage = max($currentPage - 1, 1);
                                            $endPage = min($currentPage + 1, $lastPage);
                                        @endphp

                                        {{-- @if ($startPage > 1)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif --}}

                                        @for ($page = $startPage; $page <= $endPage; $page++)
                                            <li class="page-item {{ $tasks->currentPage() == $page ? 'active' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $tasks->url($page) }}&tab={{ request()->query('tab') }}">{{ $page }}</a>
                                            </li>
                                        @endfor

                                        {{-- @if ($endPage < $lastPage)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif --}}

                                        <!-- Next Page Link -->
                                        @if ($tasks->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $tasks->nextPageUrl() }}&tab={{ request()->query('tab') }}"
                                                    rel="next">Next</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">Next</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>


                            </div>
                        @endif

                        {{-- <div class="dpagination">
                            <div onclick="removeAllSelected()"
                                class="input-group-text text-white justify-content-center removebtn dFont400 dFont13"> <i
                                    class="fas fa-trash-alt plusicon"></i>
                                Remove Selected
                            </div>
                            <nav aria-label="..." class="dpaginationNav">
                                <ul class="pagination d-flex justify-content-end">
                                    <li class="page-item disabled">
                                        <a class="page-link">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item active" aria-current="page">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div> --}}
                    </div>

                </div>

            </div>
            <div class="col-md-4 col-sm-12">
                <h4 class="text-start dFont600 mb-4">Notes</h4>
                @if ($notesInfo->isEmpty())
                    <p class="text-center">No notes found.</p>
                @else
                    <ul class="list-group dnotesUl">
                        @foreach ($notesInfo as $note)
                            <li
                                class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                                <div class="text-start">
                                    @if ($note['related_to_type'] === 'Deal')
                                        <span class="dFont800 dFont13">Related to:</span>
                                        {{ $note->dealData->deal_name ?? "" }}<br />
                                    @endif
                                    @if ($note['related_to_type'] === 'Contact')
                                        <span class="dFont800 dFont13">Related to:</span>
                                        {{ $note->contactData->first_name ?? "" }} {{ $note->contactData->last_name ?? "" }}<br />
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
                                        <div class="modal-content noteModal">
                                            <div class="modal-header border-0">
                                                <p class="modal-title dHeaderText">Note</p>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"
                                                    onclick="document.getElementById('editButton{{ $note['id'] }}').checked=false;"></button>
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
                                                <div class="modal-footer dNoteFooter border-0">
                                                    <button type="submit" class="btn btn-secondary dNoteModalmarkBtn">
                                                        <i class="fas fa-save saveIcon"></i> Mark as Done
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gx-2">
                                    <input type="checkbox" onclick="handleDeleteCheckbox('{{ $note['id'] }}')"
                                        class="form-check-input checkbox{{ $note['id'] }}"
                                        id="editButton{{ $note['id'] }}" class="btn btn-primary dnotesBottomIcon"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdropnoteupdate{{ $note['id'] }}" />
                                </div>
                            </li>
                        @endforeach
                        {{-- <button id="deleteButton{{ $note['id'] }}" onclick="deleteNote('{{ $note['id'] }}')"
                            class="btn btn-danger" style="display: none;">Delete</button> --}}
                    </ul>
                @endif
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
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
    </div>
    {{-- Modals --}}
    {{-- Create New Task Modal --}}
    <div class="modal fade" id="newTaskModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Tasks</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetValidation()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="subject" onkeyup="validateTextarea();" id="darea" rows="4" class="dtextarea"></textarea>
                    <div id="subject_error" class="text-danger"></div>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" onchange="selectedElement(this)" id="who_id"
                            name="who_id" aria-label="Select Transaction">
                            @php
                                $encounteredIds = []; // Array to store encountered IDs
                            @endphp
                          
                            @foreach ($getdealsTransaction as $item)
                                        @php
                                            $contactId = $item['userData']['zoho_id'];
                                        @endphp

                                        {{-- Check if the current ID has been encountered before --}}
                                        @if (!in_array($contactId, $encounteredIds))
                                            {{-- Add the current ID to the encountered IDs array --}}
                                            @php
                                                $encounteredIds[] = $contactId;
                                            @endphp

                                            <option value="{{ $contactId }}"
                                                @if (old('related_to') == $item['userData']['name']) selected @endif>
                                                {{ $item['userData']['name'] }}</option>
                                        @endif
                                    @endforeach
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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content noteModal">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Note</p>
                    <button type="button" onclick="resetFormAndHideSelect();" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="noteForm" action="{{ route('save.note') }}" method="post">
                    @csrf
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
                                    @if (in_array($item['api_name'], ['Deals', 'Tasks', 'Contacts']))
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
                        <button type="button" id="validate-button" onclick="validateForm()"
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
    <div class="modal fade" id="saveModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content">
                <div class="modal-header saveModalHeaderDiv border-0">
                    {{-- <h5 class="modal-title">Modal title</h5> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body saveModalBodyDiv">
                    <p class="saveModalBodyText" id="updated_message">Changes have been saved</p>
                </div>
                <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                    <div class="d-grid col-12">
                        <button type="button" class="btn btn-secondary saveModalBtn" data-bs-dismiss="modal">
                            <i class="fas fa-check trashIcon"></i>
                            Understood
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>`



    @vite(['resources/js/dashboard.js'])
    <!-- Include Date Range Picker -->

@section('dashboardScript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var canvas = document.getElementById('customGaugeChart');
            var ctx = canvas.getContext('2d');

            // Resize the canvas and draw the gauge accordingly
            function resizeCanvas() {
                var container = document.querySelector('.widget-thermometer');
                canvas.width = container.offsetWidth / 1.1; // Set the canvas width to the width of the container
                canvas.height = container.offsetWidth / 2; // Keep the canvas height half of the width
                drawGauge();
            }

            function drawGauge() {
                ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas before redrawing

                var centerX = canvas.width / 2;
                var centerY = canvas.height * 0.95; // Lower the center to give more space at the top
                var radius = canvas.width * 0.45; // Reduce the radius to ensure it fits in the canvas

                // Draw the red segment
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, Math.PI, Math.PI * 1.25, false);
                ctx.strokeStyle = 'red';
                ctx.lineWidth = radius * 0.2;
                ctx.stroke();

                // Draw the yellow segment
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, Math.PI * 1.25, Math.PI * 1.5, false);
                ctx.strokeStyle = 'yellow';
                ctx.lineWidth = radius * 0.2;
                ctx.stroke();

                // Draw the green segment
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, Math.PI * 1.5, 2 * Math.PI, false);
                ctx.strokeStyle = 'green';
                ctx.lineWidth = radius * 0.2;
                ctx.stroke();

                // Draw the needle
                var needleAngle = Math.PI + (progress / 100) * Math.PI;
                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.lineTo(centerX + radius * Math.cos(needleAngle), centerY + radius * Math.sin(needleAngle));
                ctx.strokeStyle = '#333';
                ctx.lineWidth = 5;
                ctx.stroke();

                // Draw the center circle for the needle
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius * 0.05, 0, Math.PI * 2, false);
                ctx.fillStyle = '#333';
                ctx.fill();

                // Draw the progress text
                ctx.fillStyle = '#000';
                ctx.font = 'bold 20px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(progress + '%', centerX, centerY - radius / 2);
            }

            function drawSegment(x, y, r, startAngle, endAngle, color, lineWidth) {
                ctx.beginPath();
                ctx.arc(x, y, r, startAngle, endAngle, false);
                ctx.strokeStyle = color;
                ctx.lineWidth = lineWidth;
                ctx.stroke();
            }

            function drawNeedle(x, y, angle, length) {
                ctx.beginPath();
                ctx.moveTo(x, y);
                ctx.lineTo(x + length * Math.cos(angle), y + length * Math.sin(angle));
                ctx.strokeStyle = '#333';
                ctx.lineWidth = 5;
                ctx.stroke();
            }

            function drawProgressText(x, y, text) {
                ctx.fillStyle = '#000';
                ctx.font = 'bold 20px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(text, x, y);
            }

            resizeCanvas();
            window.addEventListener('resize', resizeCanvas); // Redraw the gauge on window resize

            // for drawing the monthly chart
            var monthlyCtx = document.getElementById('monthlyComparisonChart').getContext('2d');
            var monthlyComparisonChart = new Chart(monthlyCtx, {
                type: 'bar', // This specifies a vertical bar chart
                data: {
                    labels: @json($allMonths->keys()), // Laravel Blade directive
                    datasets: [{
                        label: 'Monthly GCI',
                        data: @json($allMonths->values()), // Laravel Blade directive
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return 'GCI: $' + tooltipItem.yLabel.toLocaleString();
                            }
                        }
                    },
                    indexAxis: 'y', // 'x' for vertical chart and 'y' for horizontal
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        x: {
                            beginAtZero: true, // Ensure this is set to have the bars start at the base
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 12 // Adjust as needed for the number of months
                                // Include the following if the labels are still overlapping:
                                // callback: function(value, index, values) {
                                //   return index % 2 === 0 ? value : '';
                                // },
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            color: '#444',
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value, context) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    maintainAspectRatio: false // Add this to prevent the chart from taking the default aspect ratio
                }
            });
        });
    </script>


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

        document.getElementById("note_text").addEventListener("keyup", validateForm);
        document.getElementById("related_to").addEventListener("change", validateForm);
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
        document.getElementById("subject_error").innerHTML = "";
        document.getElementById('darea').value = "";
    }

    function validateTextarea() {
        var textarea = document.getElementById('darea');
        var textareaValue = textarea.value.trim();
        // Check if textarea value is empty
        if (textareaValue === '') {
            // Show error message or perform validation logic
            document.getElementById("subject_error").innerHTML = "please enter details";
        } else {
            document.getElementById("subject_error").innerHTML = "";
        }
    }

    function addTask() {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML = "please enter details";
            return;
        }
        var whoSelectoneid = document.getElementsByName("who_id")[0].value;
        var whoId = window.selectedTransation
        if (whoId === undefined) {
            whoId = whoSelectoneid
        }
        var dueDate = document.getElementsByName("due_date")[0].value;
        var formData = {
            "data": [{
                "Subject": subject,
                "Who_Id": {
                    "id": whoId
                },
                "Status": "In Progress",
                "Due_Date": dueDate,
                // "Priority": "High",
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

    function makeEditable(id) {
        textElement = document.getElementById('editableText' + id);
        textElementCard = document.getElementById('editableTextCard' + id);
        //For Table data                
        var text = textElement.textContent.trim();
        textElement.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

        //For card data
        var text = textElementCard.textContent.trim();
        textElementCard.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

        var inputElement = document.getElementById('editableInput' + id);
        inputElement.focus();
        inputElement.addEventListener('blur', function() {
            updateText(inputElement.value);
        });
    }

    function updateText(newText) {
        //  textElement = document.getElementById('editableText');
        textElement.innerHTML = newText;
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

    function updateTask(id, indexid) {
        // console.log(id, indexid, 'chekcdhfsjkdh')
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var inputElement = document.getElementById('editableText' + indexid);
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

    function deleteTask(id) {
        let updateids = removeAllSelected();
        if (updateids === "" && id === undefined) {
            return;
        }
        if(updateids!==""){
            if (confirm("Are you sure you want to delete selected task?")) {
                  
            }else{
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

    function resetFormAndHideSelect() {
        document.getElementById('noteForm').reset();
        document.getElementById('taskSelect').style.display = 'none';
        clearValidationMessages();
    }
    // validation function onsubmit
    function validateForm() {
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



    function moduleSelected(selectedModule) {
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
                var taskSelect = $('#taskSelect');
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
                console.log(response,'response is here');
                Object.keys(response).forEach(function(stage) {
                    if (response.hasOwnProperty(stage)) {
                        // Find the corresponding card element using data-stage attribute
                        var cardElement = $('.dCardsCols[data-stage="' + stage + '"]');
                        // Update data in the card
                        var data = response[stage];
                        cardElement.find('.dFont800.dFont18').text('$' + data.sum);
                        cardElement.find('.dpercentage').text(data.stageProgressExpr + data
                            .stageProgress + '%');
                        cardElement.find('.dpercentage').removeClass().addClass('dpercentage ' +
                            data.stageProgressClass);
                        cardElement.find('.mdi').removeClass().addClass(data.stageProgressIcon);
                        cardElement.find('.dFont800.dFont13').text(data.count + ' Transactions');
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
