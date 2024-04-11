@extends('layouts.master')

@section('title', 'Agent Commander | Contact Group Details')

@section('content')
    @vite(['resources/css/custom.css'])
    <div class="container">
        <div class="commonFlex">
            <p class="ncText">Create new contact</p>
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
                            <a href="/pipeline-create?tab=In Progress"> <button class="nav-link dtabsbtn" id="nav-home-tab"
                                    data-bs-toggle="tab" data-bs-target="#nav-home" data-tab='In Progress' type="button"
                                    role="tab" aria-controls="nav-home" aria-selected="true">In
                                    Progress</button></a>
                            <a href="/pipeline-create?tab=Not Started"> <button class="nav-link dtabsbtn"
                                    data-tab='Not Started' id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Upcoming</button></a>
                            <a href="/pipeline-create?tab=Completed"><button class="nav-link dtabsbtn" data-tab='Overdue'
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
                                    <th scope="col">Task Date</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- @if (count($tasks) > 0) --}}
                                {{-- @foreach ($tasks as $task) --}}
                                <tr class="dresponsivetableTr">
                                    <td><input type="checkbox" /></td>
                                    <td>
                                        <p class="dFont900 dFont14 d-flex justify-content-between dMt16 dSubjectText"
                                            {{-- id="editableText{{ $task['id'] }}" --}}>
                                            {{ $task['subject'] ?? 'N/A' }}
                                            <i class="fas fa-pencil-alt pencilIcon" {{-- onclick="makeEditable('{{ $task['id'] }}')" --}}></i>
                                        </p>
                                    </td>
                                    <td>
                                        <input type="datetime-local" {{-- value="{{ \Carbon\Carbon::parse($task['created_time'])->format('Y-m-d\TH:i') }}" --}} />
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                                id="btnGroupAddon" data-bs-toggle="modal" {{-- onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')" --}}>
                                                <i class="fas fa-hdd plusicon"></i>
                                                Save
                                            </div>
                                            <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                                                id="btnGroupAddon" data-bs-toggle="modal" {{-- data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}" --}}>
                                                <i class="fas fa-trash-alt plusicon"></i>
                                                Delete
                                            </div>
                                        </div>

                                        {{-- delete Modal --}}
                                        <div class="modal fade" {{-- id="deleteModalId{{ $task['zoho_task_id'] }}" --}} tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0 deleteModalHeaderDiv">
                                                        {{-- <h5 class="modal-title">Modal title</h5> --}}
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body deletemodalBodyDiv">
                                                        <p class="deleteModalBodyText">Please confirm you’d like
                                                            to<br />
                                                            delete this item.</p>
                                                    </div>
                                                    <div
                                                        class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                                        <div class="d-grid gap-2 col-5">
                                                            <button type="button" {{-- onclick="deleteTask('{{ $task['zoho_task_id'] }}')" --}}
                                                                class="btn btn-secondary deleteModalBtn"
                                                                data-bs-dismiss="modal">
                                                                <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                                                delete
                                                            </button>
                                                        </div>
                                                        <div class="d-grid gap-2 col-5">
                                                            <button type="button" class="btn btn-primary goBackModalBtn">
                                                                <img src="{{ URL::asset('/images/reply.svg') }}"
                                                                    alt="R">No, go back
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- @endforeach --}}
                                {{-- @else
                                    <tr>
                                        <td class="text-center" colspan="12">No records found</td>
                                    </tr>
                                @endif --}}

                            </tbody>

                        </table>
                        {{-- @if (count($tasks) > 0) --}}
                        {{-- @foreach ($tasks as $task) --}}
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
                                    <input type="datetime-local" {{-- value="{{ \Carbon\Carbon::parse($task['created_time'])->format('Y-m-d\TH:i') }}" --}} />
                                </div>
                            </div>
                            <div class="dcardsbtnsDiv">
                                <div id="update_changes" class="input-group-text dcardssavebtn" id="btnGroupAddon"
                                    data-bs-toggle="modal" data-bs-target="#saveModalId">
                                    <i class="fas fa-hdd plusicon"></i>
                                    Save
                                </div>
                                <div class="input-group-text dcardsdeletebtn" {{-- onclick="deleteTask('{{ $task['zoho_task_id'] }}')"  --}} id="btnGroupAddon"
                                    data-bs-toggle="modal" data-bs-target="#deleteModalId">
                                    <i class="fas fa-trash-alt plusicon"></i>

                                    Delete
                                </div>
                            </div>
                        </div>
                        {{-- @endforeach --}}

                        <div class="dpagination">
                            <div {{-- onclick="removeAllSelected()" --}}
                                class="input-group-text text-white justify-content-center removebtn dFont400 dFont13">
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

                    </div>

                </div>

            </div>
            <div class="col-md-4 col-sm-12">
                <h4 class="text-start dFont600 mb-4">Notes</h4>
                {{-- @if ($notesInfo->isEmpty()) --}}
                {{-- <div class="noNotesFound">
                        <p class="text-center notesAsignedText">No notes assigned</p>
                        <img src="{{ URL::asset('/images/news.svg') }}" alt="News">

                    </div> --}}
                {{-- @else --}}
                <ul class="list-group dnotesUl">
                    {{-- @foreach ($notesInfo as $note) --}}
                    <li
                        class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                        <div class="text-start">
                            {{-- @if ($note['related_to_type'] === 'Deal')
                                <span class="dFont800 dFont13">Related to:</span>
                                {{ $note->dealData->deal_name }}<br />
                            @endif
                            @if ($note['related_to_type'] === 'Contact')
                                <span class="dFont800 dFont13">Related to:</span>
                                {{ $note->contactData->first_name }} {{ $note->contactData->last_name }}<br />
                            @endif
                            <p class="dFont400 fs-4 mb-0">
                                {{ $note['note_content'] }}
                            </p> --}}
                        </div>

                        {{-- dynamic edit modal --}}
                        {{-- note update modal --}}
                        <div class="modal fade" {{-- id="staticBackdropnoteupdate{{ $note['id'] }}" --}} data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered deleteModal">
                                <div class="modal-content noteModal">
                                    <div class="modal-header border-0">
                                        <p class="modal-title dHeaderText">Note</p>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form {{-- action="{{ route('update.note', ['id' => $note['id']]) }}" --}} method="post">
                                        @csrf
                                        @method('POST')
                                        <div class="modal-body dtaskbody">
                                            <p class="ddetailsText">Details</p>
                                            <textarea name="note_text" rows="4" class="dtextarea">
                                                {{-- {{ $note['note_text'] }} --}}
                                            </textarea>
                                            @error('note_text')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <p class="dRelatedText">Related to...</p>
                                            <div class="btn-group dmodalTaskDiv">
                                                <select class="form-select dmodaltaskSelect" name="related_to"
                                                    aria-label="Select Transaction">
                                                    <option value="">Please select one</option>
                                                    {{-- @foreach ($getdealsTransaction as $item)
                                                        <option value="{{ $item['Deal_Name'] }}"
                                                            {{ $note['related_to'] == $item['Deal_Name'] ? 'selected' : '' }}>
                                                            {{ $item['Deal_Name'] }}
                                                        </option>
                                                    @endforeach --}}
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
                        <input type="checkbox" {{-- onclick="handleDeleteCheckbox('{{ $note['id'] }}')" --}} {{-- class="form-check-input checkbox{{ $note['id'] }}" --}} {{-- id="checkbox{{ $loop->index + 1 }}" --}}>
                    </li>
                    {{-- @endforeach --}}
                    {{-- <button id="deleteButton{{ $note['id'] }}" onclick="deleteNote('{{ $note['id'] }}')"
                            class="btn btn-danger" style="display: none;">Delete</button> --}}
                </ul>
                {{-- @endif --}}
            </div>

        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Internal Information</p>
                <form class="row g-3">
                    <div>
                        <label for="validationDefault01" class="form-label nplabelText">Contact Owner</label>
                        <select class="form-select npinputinfo" id="validationDefault04" required>
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Last Called</label>
                        <input type="date" class="form-control npinputinfo" id="validationDefault02" required>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Last Emailed</label>
                        <input type="date" class="form-control npinputinfo" id="validationDefault02" required>
                    </div>

                </form>
            </div>
            <div class="col-md-6 col-sm-12"
                style="border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <div class="commonFlex ncgroupdiv">
                    <p class="npinfoText">Groups</p>

                    <div class="npbtnsDiv">
                        <div class="input-group-text text-white justify-content-center ncEditBtn" id="btnGroupAddon"
                            data-bs-toggle="modal" data-bs-target="#newTaskModalId">
                            <i class="fas fa-pencil-alt ncpencilIcon"></i>
                            Edit
                        </div>
                        <div class="input-group-text text-white justify-content-center ncAssignBtn" id="btnGroupAddon"
                            data-bs-toggle="modal" data-bs-target="#newTaskModalId">
                            <i class="fas fa-plus plusicon">
                            </i>
                            Assign
                        </div>
                    </div>

                </div>
                <div class="row ncGroupTable">



                    <div class="col-md-3 col-sm-3 col-3">
                        <div class="commonFlex">
                            <p class="mb-0">Group Name </p><img src="{{ URL::asset('/images/swap_vert.svg') }}"
                                alt="Close icon" class="ppiplineSwapIcon" id="pipelineSort">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-3">Is Public?</div>
                    <div class="col-md-3 col-sm-3 col-3 ">
                        <div class="commonFlex">
                            <p class="mb-0">Class </p><img src="{{ URL::asset('/images/swap_vert.svg') }}"
                                alt="Close icon" class="ppiplineSwapIcon" id="pipelineSort">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-3 ">
                        <div class="commonFlex">
                            <p class="mb-0">ID No. </p><img src="{{ URL::asset('/images/swap_vert.svg') }}"
                                alt="Close icon" class="ppiplineSwapIcon" id="pipelineSort">
                        </div>
                    </div>
                </div>

                <div class="row ncGroupBody">
                    <div class="col-md-3 col-sm-3 col-3">Pop-by</div>
                    <div class="col-md-3 col-sm-3 col-3">---</div>
                    <div class="col-md-3 col-sm-3 col-3">A</div>
                    <div class="col-md-3 col-sm-3 col-3">4254353</div>
                </div>
            </div>
            {{-- Contact Details --}}
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Contact Details</p>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label nplabelText">First Name</label>
                        <input type="text" placeholder="Enter First name" class="form-control npinputinfo"
                            id="validationDefault01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Last Name</label>
                        <input type="text" placeholder="Enter Last name" class="form-control npinputinfo"
                            id="validationDefault02" required>
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Mobile</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Enter Mobile Number"
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Phone</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Enter Phone Number"
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Email</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Enter Email"
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Market Area</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Downtown Chicago"
                            id="validationDefault03" required>
                    </div>
                </form>
            </div>
            {{-- Contact Preferences --}}
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Contact Preferences</p>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label nplabelText">Relationship Type</label>
                        <select class="form-select npinputinfo" id="validationDefault04" required>
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Referred By</label>
                        <input type="text" placeholder="Louis Rinmbaud" class="form-control npinputinfo"
                            id="validationDefault02" required>
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Lead Source</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Peter Hunt"
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Lead source details</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Raoul P Associate"
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Envelope Salutation</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Mr."
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Spouse/Partner</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Mary Long"
                            id="validationDefault03" required>
                    </div>
                </form>
            </div>
            {{-- Business Information --}}
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Business Information</p>
                <form class="row g-3">
                    <div>
                        <label for="validationDefault01" class="form-label nplabelText">Business Name</label>
                        <input type="text" placeholder="Burn Co." class="form-control npinputinfo"
                            id="validationDefault02" required>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">ABCD Class</label>
                        <select class="form-select npinputinfo" id="validationDefault04" required>
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Business Information</label>
                        <textarea type="text" rows="4" class="form-control nctextarea" id="validationDefault02" required></textarea>
                    </div>

                </form>
            </div>

            {{-- Primary Contact’s Address --}}
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Primary Contact’s Address</p>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label nplabelText">Address line 1</label>
                        <input type="text" class="form-control npinputinfo" placeholder="22 Smith St."
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Address line 2</label>
                        <input type="text" placeholder="Dane Sq." class="form-control npinputinfo"
                            id="validationDefault02" required>
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">City</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Enter City"
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">State</label>
                        <select class="form-select npinputinfo" id="validationDefault04" required>
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">ZIP code</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Mr."
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Email</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Mary Long"
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                        <label class="form-check-label nplabelText" for="flexCheckChecked">
                            Primary Address
                        </label>
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                        <label class="form-check-label nplabelText" for="flexCheckChecked">
                            Secondary Address
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="container">
        <h1>Contact Details: {{ $contactDetails['Full_Name'] ?? 'N/A' }}</h1>
        <div>
            <p>Email: {{ $contactDetails['Email'] ?? 'N/A' }}</p>
            <p>Phone: {{ $contactDetails['Phone'] ?? 'N/A' }}</p>
            <p>Mobile: {{ $contactDetails['Mobile'] ?? 'N/A' }}</p>
            <p>Secondary Email: {{ $contactDetails['Secondary_Email'] ?? 'N/A' }}</p>
            <p>Envelope Salutation: {{ $contactDetails['Salutation_s'] ?? 'N/A' }}</p>
            <p>Relationship Type: {{ $contactDetails['Relationship_Type'] ?? 'N/A' }}</p>
            <p>Referred By: {{ print_r($contactDetails['Referred_By'], true) ?? 'N/A' }}</p>
            <p>Lead Source: {{ print_r($contactDetails['Lead_Source'], true) ?? 'N/A' }}</p>
            <p>Lead Source Detail: {{ $contactDetails['Lead_Source_Detail'] ?? 'N/A' }}</p>
            <p>Market Area: {{ $contactDetails['Market_Area'] ?? 'N/A' }}</p>
            <p>Business Info: {{ $contactDetails['Business_Info'] ?? 'N/A' }}</p>
            <p>Spouse/Partner: {{ print_r($contactDetails['Spouse_Partner'], true) ?? 'N/A' }}</p>
            <p>Address: {{ $contactDetails['Mailing_Street'] ?? '' }}, {{ $contactDetails['Mailing_City'] ?? '' }},
                {{ $contactDetails['Mailing_State'] ?? '' }}, {{ $contactDetails['Mailing_Zip'] ?? '' }}</p>
        </div>
    </div> --}}
@endsection
