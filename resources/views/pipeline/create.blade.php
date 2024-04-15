@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline Create')

@section('content')
    @vite(['resources/css/pipeline.css'])

    <div class="container-fluid">
        <div class="commonFlex ppipeDiv">
            <input type="text" value="{{$deal['deal_name']}}" class="cpinput">
            <div>
                <div class="input-group-text text-white justify-content-center npeditBtn" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#newTaskModalId">
                    <img src="{{ URL::asset('/images/edit.svg') }}" alt="Edit">
                    Save
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
                            <a href="/pipeline-create/{{$deal['id']}}?tab=In Progress"> <button class="nav-link dtabsbtn" id="nav-home-tab"
                                    data-bs-toggle="tab" data-bs-target="#nav-home" data-tab='In Progress' type="button"
                                    role="tab" aria-controls="nav-home" aria-selected="true">In
                                    Progress</button></a>
                            <a href="/pipeline-create/{{$deal['id']}}?tab=Not Started"> <button class="nav-link dtabsbtn" data-tab='Not Started'
                                    id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button"
                                    role="tab" aria-controls="nav-profile" aria-selected="false">Upcoming</button></a>
                            <a href="/pipeline-create/{{$deal['id']}}?tab=Completed"><button class="nav-link dtabsbtn" data-tab='Overdue'
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

                                @if (count($tasks) > 0)
                                @foreach ($tasks as $task)
                                    <tr class="dresponsivetableTr">
                                        <td><input type="checkbox" /></td>
                                        <td>
                                            <p class="dFont900 dFont14 d-flex justify-content-between dMt16 dSubjectText"
                                                id="editableText{{ $task['id'] }}">
                                                {{ $task['subject'] ?? 'N/A' }}
                                                <i class="fas fa-pencil-alt pencilIcon" {{-- onclick="makeEditable('{{ $task['id'] }}')" --}}></i>
                                            </p>
                                        </td>
                                        <td>
                                            <input type="datetime-local"
                                                value="{{ \Carbon\Carbon::parse($task['created_time'])->format('Y-m-d\TH:i') }}" />
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
                                            <div class="modal fade" id="deleteModalId{{ $task['zoho_task_id'] }}"
                                                tabindex="-1">
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
                                                                <button type="button"
                                                                    class="btn btn-primary goBackModalBtn">
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
                                    <div id="update_changes" class="input-group-text dcardssavebtn" id="btnGroupAddon"
                                        data-bs-toggle="modal" data-bs-target="#saveModalId">
                                        <i class="fas fa-hdd plusicon"></i>
                                        Save
                                    </div>
                                    <div class="input-group-text dcardsdeletebtn" {{-- onclick="deleteTask('{{ $task['zoho_task_id'] }}')"  --}}
                                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#deleteModalId">
                                        <i class="fas fa-trash-alt plusicon"></i>

                                        Delete
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @else
                            <div class="dprogressCardselse">
                                <p class="text-center" colspan="12">No records found</p>
                            </div>
                        @endif
                        {{-- @if (count($tasks) > 0) --}}
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
                        {{-- @endif --}}

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
                    <div class="noNotesFound">
                        <p class="text-center notesAsignedText">No notes assigned</p>
                    </div>
                @else
                    <ul class="list-group dnotesUl">
                        @foreach ($notesInfo as $note)
                            <li
                                class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                                <div class="text-start">
                                    @if ($note['related_to_type'] === 'Deal')
                                        <span class="dFont800 dFont13">Related to:</span>
                                        {{ $note->dealData->deal_name }}<br />
                                    @endif
                                    @if ($note['related_to_type'] === 'Contact')
                                        <span class="dFont800 dFont13">Related to:</span>
                                        {{ $note->contactData->first_name }} {{ $note->contactData->last_name }}<br />
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
                                <input type="checkbox" {{-- onclick="handleDeleteCheckbox('{{ $note['id'] }}')" --}}
                                    class="form-check-input checkbox{{ $note['id'] }}" {{-- id="checkbox{{ $loop->index + 1 }}" --}}>
                            </li>
                        @endforeach
                        {{-- <button id="deleteButton{{ $note['id'] }}" onclick="deleteNote('{{ $note['id'] }}')"
                            class="btn btn-danger" style="display: none;">Delete</button> --}}
                    </ul>
                @endif
            </div>

        </div>
        {{-- information form --}}
        <div class="row">
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Client Information</p>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label nplabelText">Client Name</label>
                        <input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                            id="validationDefault01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Representing</label>
                        <input type="text" placeholder="Representing" class="form-control npinputinfo"
                            id="validationDefault02" required>
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Transaction Name</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Transaction Name"
                            id="validationDefault03" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault04" class="form-label nplabelText">Stage</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Potential"
                            id="validationDefault04" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault05" class="form-label nplabelText">Sale Price</label>
                        <input type="text" class="form-control npinputinfo" placeholder="$ 725,000.00"
                            id="validationDefault05" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault06" class="form-label nplabelText">Closing Date</label>
                        <input type="date" class="form-control npinputinfo" id="validationDefault06" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault07" class="form-label nplabelText">Address</label>
                        <input type="text" class="form-control npinputinfo" placeholder="52 Realand Road"
                            id="validationDefault07" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault08" class="form-label nplabelText">City</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Highlands Ranch"
                            id="validationDefault08" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault09" class="form-label nplabelText">State</label>
                        {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                        <input type="text" class="form-control npinputinfo" placeholder="Highlands Ranch"
                            id="validationDefault09" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault10" class="form-label nplabelText">ZIP</label>
                        <input type="text" class="form-control npinputinfo" placeholder="80129"
                            id="validationDefault10" required>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

                <p class="npinfoText">Earnings Information</p>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault11" class="form-label nplabelText">Commission %</label>
                        <input type="text" class="form-control npinputinfo" id="validationDefault11" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault12" class="form-label nplabelText">Property Type</label>
                        {{-- <select class="form-select npinputinfo" id="validationDefault12" required>
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                        <input type="text" class="form-control npinputinfo" id="validationDefault12" required>
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault13" class="form-label nplabelText">Ownership Type</label>
                        {{-- <select class="form-select npinputinfo" id="validationDefault13" required>
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                        <input type="text" class="form-control npinputinfo" id="validationDefault13" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault14" class="form-label nplabelText">Potential GCI</label>
                        <input type="text" class="form-control npinputinfo" placeholder="Potential GCI"
                            id="validationDefault14" required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault15" class="form-label nplabelText">Pipeline Probability (%)</label>
                        <input type="text" class="form-control npinputinfo" placeholder="15" id="validationDefault15"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault16" class="form-label nplabelText">Probable GCI</label>
                        <input type="text" class="form-control npinputinfo" placeholder="$ 3,045.00"
                            id="validationDefault16" required>
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked01" checked>
                        <label class="form-check-label nplabelText" for="flexCheckChecked01">
                            Personal Transaction
                        </label>
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked02" checked>
                        <label class="form-check-label nplabelText" for="flexCheckChecked02">
                            Double ended
                        </label>
                    </div>
                </form>
            </div>
        </div>

        {{-- contact roles --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Contact Roles</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
                    </i>
                    Add Contact Role
                </div>

            </div>

            <div class="row npRoleTable">
                <div class="col-md-3 ">Role</div>
                <div class="col-md-2 ">Role Name</div>
                <div class="col-md-3 ">Phone</div>
                <div class="col-md-4 ">Email</div>
            </div>
            @if ($dealContacts->isEmpty())
                    <div>
                        <p class="text-center notesAsignedText">No contacts assigned</p>
                    </div>
                @else
                        @foreach ($dealContacts as $dealContact)
                             <div class="row npRoleBody">
                                <div class="col-md-3 ">{{$dealContact->contactData?$dealContact->contactData->first_name.' '.$dealContact->contactData->last_name:($dealContact->userData?$dealContact->userData->name:'N/A')}}</div>
                                <div class="col-md-2 ">{{$dealContact['contactRole']}}</div>
                                <div class="col-md-3 ">{{$dealContact->contactData?($dealContact->contactData->phone?$dealContact->contactData->phone:'N/A'):'N/A'}}</div>
                                <div class="col-md-4 commonTextEllipsis">{{$dealContact->contactData?$dealContact->contactData->email:($dealContact->userData?$dealContact->userData->email:'N/A')}}</div>
                            </div>
                        @endforeach
                @endif
           
                @foreach ($dealContacts as $dealContact)
                    <div class="npRoleCard vprolecard">
                        <div>
                            <p class="npcommonheaderText">Role</p>
                            <p class="npcommontableBodytext">{{$dealContact->contactData?$dealContact->contactData->first_name.' '.$dealContact->contactData->last_name:($dealContact->userData?$dealContact->userData->name:'N/A')}}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center npCardPhoneDiv">
                            <div>
                                <p class="npcommonheaderText">Role Name</p>
                                <p class="npcommontableBodyDatetext">{{$dealContact['contactRole']}}</p>
                            </div>
                            <div>
                                <p class="npcommonheaderText">Phone</p>
                                <p class="npcommontableBodyDatetext">{{$dealContact->contactData?($dealContact->contactData->phone?$dealContact->contactData->phone:'N/A'):'N/A'}}</p>
                            </div>
                        </div>
                        <div>
                            <p class="npcommonheaderText">Email</p>
                            <p class="npcommontableBodyDatetext">{{$dealContact->contactData?$dealContact->contactData->email:($dealContact->userData?$dealContact->userData->email:'N/A')}}</p>
                        </div>
                    </div>
                @endforeach
           
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

        {{-- Non-TM Check request --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Non-TM Check request</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
                    </i>
                    Add Non-TM Check request
                </div>

            </div>
            <div class="row npNom-TM-Table">
                <div class="col-md-4 ">Number</div>
                <div class="col-md-4 ">Close Date</div>
                <div class="col-md-4 ">Created Time</div>
            </div>

           {{-- <div class="row npNom-TM-Body">
                <div class="col-md-4 ">N654685</div>
                <div class="col-md-4 ">March 12, 2024</div>
                <div class="col-md-4 commonTextEllipsis">Mar 25, 2024 08:33 AM </div>
            </div> --}}


            <div class="npNom-TM-Card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="npcommonheaderText">Number</p>
                        {{--<p class="npcommontableBodytext">N654685</p>--}}
                    </div>
                    <div>
                        <p class="npcommonheaderText">Close Date</p>
                        {{--<p class="npcommontableBodyDatetext">March 12, 2024</p>--}}
                    </div>
                </div>
                <div class="npCardPhoneDiv">
                    <p class="npcommonheaderText">Created Time</p>
                    {{--<p class="npcommontableBodyDatetext">March 12, 2024</p>--}}
                </div>
            </div>
            <div class="dpagination">
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

        {{-- Agent’s Commissions --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Agent’s Commissions</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
                    </i>
                    Add Agent’s Commissions
                </div>

            </div>

            <div class="row npAgentTable">
                <div class="col-md-3 ">Agent’s Name</div>
                <div class="col-md-3 ">IRS 1099 Income for this Transaction</div>
                <div class="col-md-3 ">Less Split to CHR</div>
                <div class="col-md-3 ">Modified Time</div>
            </div>
            @if ($dealaci->isEmpty())
                <div>
                    <p class="text-center notesAsignedText">No ACI assigned</p>

                </div>
            @else
                @foreach ($dealaci as $aci)
                    <div class="row npAgentBody">
                        <div class="col-md-3 ">{{$aci['agentName']}}</div>
                        <div class="col-md-3 ">${{$aci['irs_reported_1099_income_for_this_transaction']}}</div>
                        <div class="col-md-3 ">${{$aci['less_split_to_chr']}}</div>
                        <div class="col-md-3 commonTextEllipsis">{{$aci['closing_date']}}</div>
                    </div>
                @endforeach
            @endif
             
                @foreach ($dealaci as $aci)
                    <div class="npAgentCard">
                        <div>
                            <p class="npcommonheaderText">Agent’s Name</p>
                            <p class="npcommontableBodytext">{{$aci['agentName']}}</p>
                        </div>
                        <div class="npCardPhoneDiv">
                            <p class="npcommonheaderText">IRS 1099 Income for this Transaction</p>
                            <p class="npcommontableBodytext">${{$aci['irs_reported_1099_income_for_this_transaction']}}</p>
                        </div>
                        <div>
                            <p class="npcommonheaderText">Less Split to CHR</p>
                            <p class="npcommontableBodytext">${{$aci['less_split_to_chr']}}</p>
                        </div>
                        <div class="npCardPhoneDiv">
                            <p class="npcommonheaderText">Modified Time</p>
                            <p class="npcommontableBodyDatetext">{{$aci['closing_date']}}</p>
                        </div>
                    </div>
                @endforeach
           
            <div class="dpagination">
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

        {{-- Add New Attachment --}}
        <div class="table-responsive dtranstiontable mt-3">
            <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
                <p class="nproletext">Attachments</p>
                <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
                    data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
                    </i>
                    Add New Attachment
                </div>

            </div>

            <div class="row npAttachmentTable">
                <div class="col-md-3">Attachment Name</div>
                <div class="col-md-3 ">Type</div>
                <div class="col-md-3 ">Owner</div>
                <div class="col-md-3 ">Uploaded On</div>
            </div>

            {{-- <div class="row npAttachmentBody">
                <div class="col-md-3 npcommontableBodytext">mycontract.pdf</div>
                <div class="col-md-3 npcommontableBodytext">PDF</div>
                <div class="col-md-3 npcommontableBodytext">Chad Seagal</div>
                <div class="col-md-3 commonTextEllipsis npcommontableBodyDatetext">Mar 25, 2024 08:33 AM</div>
            </div> --}}

            <div class="npContactCard">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="npcommonheaderText">Attachment Name</p>
                       {{-- <p class="npcommontableBodytext">mycontract.pdf</p> --}}
                    </div>
                    <div>
                        <p class="npcommonheaderText">Type</p>
                        {{-- <p class="npcommontableBodytext">PDF</p> --}}
                    </div>
                </div>
                <div class="npCardPhoneDiv">
                    <p class="npcommonheaderText">Owner</p>
                    {{--<p class="npcommontableBodytext">Chad Seagal</p>--}}
                </div>
                <div>
                    <p class="npcommonheaderText">Uploaded On</p>
                    {{--<p class="npcommontableBodyDatetext">Mar 25, 2024 08:33 AM</p>--}}
                </div>
            </div>
            <div class="dpagination">

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
                            <button type="button" onclick="addTask('{{ $deal['zoho_deal_id'] }}')" class="btn btn-secondary taskModalSaveBtn">
                                <i class="fas fa-save saveIcon"></i> Save Changes
                            </button>

                        </div>

                    </div>
                </div>
            </div>

    </div>
    @vite(['resources/js/pipeline.js'])

    <script>
        $(document).ready(function() {
                $.ajax({
                url: '{{ url('/pipeline-view') }}',
                method: 'GET',
                data: {},
                dataType: 'json',
                success: function(data) {
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

            // Function to populate client information
            window.addTask= function(deal) {
                var subject = document.getElementsByName("subject")[0].value;
                if (subject.trim() === "") {
                    document.getElementById("subject_error").innerHTML = "please enter details";
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
                        "Priority": "High",
                        "What_Id":{
                            "id":deal
                        }
                    }],
                    "_token": '{{ csrf_token() }}'
                };
                console.log("formData",formData);
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
        });

        
        


    </script>
@section('pipelineScript')

@endsection
@endsection
