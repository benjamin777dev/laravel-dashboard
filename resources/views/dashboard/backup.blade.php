{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.master')

@section('title', 'zPortal | Dashboard')
@section('content')
    @vite(['resources/css/dashboard.css'])
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <div class="container-fluid">
        <div class="row mt-4 text-center">
            <div class="col-lg-3 col-md-3 col-sm-6 text-start">
                <p class="dFont900 dFont15 dMb10">Welcome Back, {{ $user['name'] }} <br />
                    <span class="dFont400 dFont13">{{ date('l, F j, Y') }}</span>
                </p>
                <p class="dFont800 dFont13 dMb5">Pipeline stats date ranges</p>
                <div class="d-flex justify-content-between align-items-baseline dCalander">
                    <p class="dFont400 dFont13 mb-0">{{ $startDate }} - {{ $endDate }}</p>
                    <i class="fa fa-calendar calendar-icon" onclick="toggleDatePicker();"></i>
                    <!-- <input type="text" id="dateRangePicker" onclick="datePickerRange();" value="{{ $startDate }} - {{ $endDate }}" name="daterange"> -->
                </div>

            </div>

            <div class="col-ld-9 col-md-9 col-sm-12">
                <div class="row dashboard-cards-resp">
                    @foreach ($stageData as $stage => $data)
                        {{-- {{ dd($data) }} --}}
                        <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols">
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
                                    <th scope="col"><input type="checkbox" /></th>
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
                                            <td><input type="checkbox" /></td>
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
                                                <input type="datetime-local"
                                                    value="{{ \Carbon\Carbon::parse($task['created_time'])->format('Y-m-d\TH:i') }}" />
                                            </td>
                                            <td>
                                                <div class="d-flex ">
                                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                                        id="btnGroupAddon" data-bs-toggle="modal"
                                                        {{-- onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')" --}}
                                                        data-bs-target="#saveModalId">
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
                                                {{-- <div class="modal fade p-5" id="deleteModalId{{ $task['zoho_task_id'] }}"
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
                                                                        class="btn btn-primary goBackModalBtn">
                                                                        <img src="{{ URL::asset('/images/reply.svg') }}"
                                                                            alt="R">No, go back
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                {{-- </div> --}}
                                                <form action="/delete.task/{{ $task['zoho_task_id'] }}" method="POST" id="deleteTaskForm">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal fade p-5" id="deleteModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
                                                        <div class="modal-dialog modal-dialog-centered deleteModal">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0 deleteModalHeaderDiv">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body deletemodalBodyDiv">
                                                                    <p class="deleteModalBodyText">Please confirm you’d like to delete this item.</p>
                                                                </div>
                                                                <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                                                    <div class="d-grid gap-2 col-5">
                                                                        <button type="submit" class="btn btn-secondary deleteModalBtn">
                                                                            <i class="fas fa-trash-alt trashIcon"></i> Yes, delete
                                                                        </button>
                                                                    </div>
                                                                    <div class="d-grid gap-2 col-5">
                                                                        <button type="button" class="btn btn-primary goBackModalBtn" data-bs-dismiss="modal">
                                                                            <img src="{{ URL::asset('/images/reply.svg') }}" alt="R"> No, go back
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
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
                        @if (count($tasks) > 0)
                        @foreach ($tasks as $task)
                            <div class="dprogressCards">
                                <div class="dcardscheckbox">
                                    <input type="checkbox" />
                                </div>
                                <div class="dcardssubjectdiv">
                                    <p class="dcardSubject">
                                        {{ $task['subject'] ?? 'N/A' }}
                                        {{-- <i class="fas fa-pencil-alt pencilIcon "></i> --}}
                                    </p>
                                    <div class="btn-group dcardsselectdiv">
                                        <p class="dcardsTransactionText">Transaction Related</p>
                                        <select class="form-select dselect" aria-label="Transaction test"
                                            id="dropdownMenuButton">
                                            <option value="{{ $task['Who_Id']['id'] ?? '' }}">
                                        </select>
                                    </div>
                                    <div class="dcardsdateinput">
                                        <p class="dcardsTaskText">Task Date</p>
                                        <input type="datetime-local"
                                            value="{{ \Carbon\Carbon::parse($task['created_time'])->format('Y-m-d\TH:i') }}" />
                                    </div>
                                </div>
                                <div class="dcardsbtnsDiv">
                                    <div class="input-group-text dcardssavebtn" id="btnGroupAddon"
                                        data-bs-toggle="modal" data-bs-target="#saveModalId">
                                        <i class="fas fa-hdd plusicon"></i>
                                        Save
                                    </div>
                                    <div class="input-group-text dcardsdeletebtn"
                                    onclick="deleteTask('{{ $task['zoho_task_id'] }}')"
                                    id="btnGroupAddon"
                                        data-bs-toggle="modal" data-bs-target="#deleteModalId">
                                        <i class="fas fa-trash-alt plusicon"></i>

                                        Delete
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="12">No records found</td>
                        </tr>
                    @endif
                    @if (count($tasks) > 0)
                        <div class="dpagination">
                            <div onclick="removeAllSelected()" class="input-group-text text-white justify-content-center removebtn dFont400 dFont13">
                                <i class="fas fa-trash-alt plusicon"></i>
                                Remove Selected
                            </div>
                            <nav aria-label="..." class="dpaginationNav">
                                <ul class="pagination ppipelinepage d-flex justify-content-end">
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
                @if ($notes->isEmpty())
                    <p class="text-center">No notes found.</p>
                @else
                    <ul class="list-group">
                        @foreach ($notes as $note)
                            <li
                                class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                                <div class="text-start">
                                    <span class="dFont800 dFont13">Related to:</span> {{ $note['related_to'] }}<br />
                                    <p class="dFont400 fs-4 mb-0">
                                        {{ $note['note_text'] }}
                                    </p>
                                </div>
                                <button id="editButton{{ $note['id'] }}" type="button" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdropnoteupdate{{ $note['id'] }}"
                                    class="btn btn-primary dnotesBottomIcon" style="display: none;">Edit</button>

                                {{-- delete button --}}
                                {{-- <i class="fa-solid fa-minus" id="deleteButton{{ $note['id'] }}" onclick="deleteNote('{{ $note['id'] }}')"
                                    style="display: none;"></i>  --}}


                                {{-- dynamic edit modal --}}
                                {{-- note update modal --}}
                                <div class="modal fade p-5" id="staticBackdropnoteupdate{{ $note['id'] }}"
                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered deleteModal">
                                        <div class="modal-content noteModal">
                                            <div class="modal-header border-0">
                                                <p class="modal-title dHeaderText">Note</p>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('update.note', ['id' => $note['id']]) }}"
                                                method="post">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-body dtaskbody">
                                                    <p class="ddetailsText">Details</p>
                                                    <textarea name="note_text" rows="4" class="dtextarea">{{ $note['note_text'] }}</textarea>
                                                    @error('note_text')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <p class="dRelatedText">Related to...</p>
                                                    <div class="btn-group dmodalTaskDiv">
                                                        <select class="form-select dmodaltaskSelect" name="related_to"
                                                            aria-label="Select Transaction">
                                                            <option value="">Please select one</option>
                                                            @foreach ($getdealsTransaction as $item)
                                                                <option value="{{ $item['Deal_Name'] }}"
                                                                    {{ $note['related_to'] == $item['Deal_Name'] ? 'selected' : '' }}>
                                                                    {{ $item['Deal_Name'] }}
                                                                </option>
                                                            @endforeach
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
                                <input type="checkbox" onclick="handleDeleteCheckbox('{{ $note['id'] }}')"
                                    class="form-check-input checkbox{{ $note['id'] }}"
                                    id="checkbox{{ $loop->index + 1 }}">
                            </li>
                        @endforeach
                        {{-- <button id="deleteButton{{ $note['id'] }}" onclick="deleteNote('{{ $note['id'] }}')"
                            class="btn btn-danger" style="display: none;">Delete</button> --}}
                    </ul>
                @endif
            </div>
            <div class="table-responsive dtranstiontable mt-3">
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
                        @if (count($deals) === 0)
                            <tr>
                                <td class="text-center" colspan="5">No records found</td>
                            </tr>
                        @else
                            @foreach ($deals as $deal)
                                <tr>
                                    <td>{{ $deal['deal_name'] }}</td>
                                    <td>{{ $deal->userData->name }}</td>
                                    <td>---</td>
                                    <td>{{ $deal->userData->email }}</td>
                                    <td>{{ $deal['closing_date'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon" >
    </div>
    {{-- Modals --}}
    {{-- Create New Task Modal --}}
    <div class="modal fade p-5" id="newTaskModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content dtaskmodalContent">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Create New Task</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body dtaskbody">
                    <p class="ddetailsText">Details</p>
                    <textarea name="subject" id="darea" rows="4" class="dtextarea">
                    </textarea>
                    <p class="dRelatedText">Related to...</p>
                    <div class="btn-group dmodalTaskDiv">
                        <select class="form-select dmodaltaskSelect" name="who_id" aria-label="Select Transaction">
                            @foreach ($getdealsTransaction as $item)
                                <option value="{{ $item['Owner']['id'] }}"
                                    @if (old('related_to') == $item['Deal_Name']) selected @endif>{{ $item['Deal_Name'] }}</option>
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
    <div class="modal fade p-5" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content noteModal">
                <div class="modal-header border-0">
                    <p class="modal-title dHeaderText">Note</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('save.note') }}" method="post">
                    @csrf
                    <div class="modal-body dtaskbody">
                        <p class="ddetailsText">Details</p>
                        <textarea name="note_text" rows="4" class="dtextarea"></textarea>
                        @error('note_text')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <p class="dRelatedText">Related to...</p>
                        <div class="btn-group dmodalTaskDiv">
                            <select class="form-select dmodaltaskSelect" name="related_to"
                                aria-label="Select Transaction">
                                <option value="">Please select one</option>
                                @foreach ($getdealsTransaction as $item)
                                    <option value="{{ $item['Deal_Name'] }}"
                                        @if (old('related_to') == $item['Deal_Name']) selected @endif>{{ $item['Deal_Name'] }}</option>
                                @endforeach
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

    {{-- save Modal --}}
    {{-- <div class="modal fade p-5" id="saveModalId" tabindex="-1">
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
    <div class="modal fade p-5" id="saveModalId" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered deleteModal">
            <div class="modal-content">
                <div class="modal-header saveModalHeaderDiv border-0">
                    {{-- <h5 class="modal-title">Modal title</h5> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body saveModalBodyDiv">
                    <p class="saveModalBodyText">Changes have been saved</p>
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
        }
    });
    // var selectedNoteIds = [];

    function handleDeleteCheckbox(id) {
        //checkobox notes showing delete btn functionlity
        //  console.log(id,'id is hereeeee'
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
                console.log(anyChecked, 'checkoeddd')
                // Toggle delete button visibility
                editButton.style.display = anyChecked ? 'block' : 'none';
                // if (deleteButton.style.display === 'block') {
                //     selectedNoteIds.push(id)
                // }
            });
        });

    }

    function addTask() {
        var subject = document.getElementsByName("subject")[0].value;
        var whoId = document.getElementsByName("who_id")[0].value;
        var dueDate = document.getElementsByName("due_date")[0].value;
        console.log(subject, whoId, dueDate, 'checkot');
        // return;
        var formData = {
            "data": [{
                "Subject": subject
                // "Who_Id": {
                //     "name": document.querySelector('select[name="who_id"] option:checked').text,
                //     "id": whoId
                // },
                // "Due_Date": dueDate
            }],
            _token: '{{ csrf_token() }}',
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
                // Handle success response
                console.log(response);
                // Optionally, update the UI or close the modal
                $('#newTaskModalId').modal('hide');
                // window.location.reload();
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
        var text = textElement.textContent.trim();
        textElement.innerHTML = '<input type="text" id="editableInput' + id + '" value="' + text + '" />';

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

    // function updateTask(id, indexid) {
    //     var inputElement = document.getElementById('editableText' + indexid);
    //     if (!inputElement) {
    //         console.error("Input element not found for indexid:", indexid);
    //         return;
    //     }
    //     var elementValue = inputElement.textContent;
    //     // console.log("inputElementval",elementValue!==undefined,elementValue)
    //     if (elementValue !== undefined) { // return;
    //         console.log(textElement.value, 'valueeee')
    //         var formData = {
    //             "data": [{
    //                 "Subject": elementValue,
    //                 // "Who_Id": {
    //                 //     "name": document.querySelector('select[name="who_id"] option:checked').text,
    //                 //     "id": whoId
    //                 // },
    //                 // "Due_Date": dueDate
    //             }],
    //             "_token": $('meta[name="csrf-token"]').attr('content'),
    //         };
    //         // console.log("ys check ot")
    //         $.ajax({
    //             url: '/update.task/' + id,
    //             type: 'PUT',
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             contentType: 'application/json',
    //             dataType: 'json',
    //             data: JSON.stringify(formData),
    //             success: function(response) {
    //                 // Handle success response
    //                 alert("updated success")
    //                 // console.log(response);
    //                 // Optionally, update the UI or close the modal
    //                 $('#newTaskModalId').modal('hide');
    //                 window.location.reload();
    //             },
    //             error: function(xhr, status, error) {
    //                 // Handle error response
    //                 console.error(xhr.responseText);
    //                 alert(xhr.responseText)
    //             }
    //         })
    //     }
    // }

    function deleteTask(id) {
        console.log(id, 'checkot')
        try {
            if (id) {
                $.ajax({
                    url: '/delete.task/' + id,
                    type: 'DELETE', // Change to DELETE method
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: 'application/json',
                    success: function(response) {
                        // Handle success response
                        showToast("deleted successfully")
                        // console.log(response);
                        // Optionally, update the UI or close the modal
                        // $('#newTaskModalId').modal('hide');
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        showToast(xhr.responseText)
                    }
                })
            }
        } catch (err) {
            console.error("error", err);
        }
    }

    function removeAllSelected() {
        // Select all checkboxes
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');

        // Iterate through each checkbox
        checkboxes.forEach(function(checkbox) {
            // Check if the checkbox is checked
            if (checkbox.checked) {
                // Uncheck the checkbox
                checkbox.checked = false;
            }
        });
    }
</script>
@vite(['resources/js/dashboard.js'])
