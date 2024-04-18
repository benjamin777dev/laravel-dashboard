@extends('layouts.master')

@section('title', 'Agent Commander | Contact Details')

@section('content')
    @vite(['resources/css/custom.css'])
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
            {{-- <div class="col-md-4 col-sm-12">
                <h4 class="text-start dFont600 mb-4">Notes</h4>
                 @if ($notesInfo->isEmpty())
                 <div class="noNotesFound">
                        <p class="text-center notesAsignedText">No notes assigned</p>
                        <img src="{{ URL::asset('/images/news.svg') }}" alt="News">

                    </div> 
                @else 
                <ul class="list-group dnotesUl">
                    @foreach ($notesInfo as $note)
                    <li
                        class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                        <div class="text-start">
                            @if ($note !== null && $note['related_to_type'] === 'Deal')
                            <span class="dFont800 dFont13">Related to:</span>
                            {{ $note->dealData->deal_name }}<br />
                          @endif
                            @if ($note !== null && $note['related_to_type'] === 'Contact')
                                <span class="dFont800 dFont13">Related to:</span>
                                {{ $note->contactData->first_name }} {{ $note->contactData->last_name }}<br />
                            @endif
                            <p class="dFont400 fs-4 mb-0">
                                {{ $note['note_content'] }}
                            </p>
                        </div>
                        <div class="d-flex align-items-center gx-2">
                            <input type="checkbox" onclick="handleDeleteCheckbox('{{ $note['id'] }}')"
                                class="form-check-input checkbox{{ $note['id'] }}"
                                id="editButton{{ $note['id'] }}" class="btn btn-primary dnotesBottomIcon"
                                type="button" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropnoteupdatecontact{{ $note['id'] }}" />
                        </div>
                         {{-- note update modal --}}
                         {{-- <div class="modal fade" id="staticBackdropnoteupdatecontact{{ $note['id'] }}" data-bs-backdrop="static" data-bs-keyboard="false"
                         tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                         <div class="modal-dialog modal-dialog-centered deleteModal">
                             <div class="modal-content noteModal">
                                 <div class="modal-header border-0">
                                     <p class="modal-title dHeaderText">Note</p>
                                     <button type="button" class="btn-close" data-bs-dismiss="modal"
                                         aria-label="Close" onclick="document.getElementById('editButton{{ $note['id'] }}').checked=false;"></button>
                                 </div>
                                 <form  action="{{ route('update.note', ['id' => $note['zoho_note_id']]) }}" method="post">
                                     @csrf
                                     @method('POST')
                                     <div class="modal-body dtaskbody">
                                         <p class="ddetailsText">Details</p>
                                         <textarea name="note_text" rows="4" class="dtextarea">{{ $note['note_content'] }}</textarea>
                                         @error('note_text')
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
                    </li>
                    @endforeach
                    <button id="deleteButton{{ $note['id'] }}" onclick="deleteNote('{{ $note['id'] }}')"
                            class="btn btn-danger" style="display: none;">Delete</button>
                </ul>
                @endif --}}
            {{-- </div>  --}}

        </div>
        <form class="row" action="{{ route('create.contact') }}" method="POST">
            @csrf
            @method('POST')
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Internal Information</p>
                <div class="row g-3">
                    <div>
                        <label for="validationDefault01" class="form-label nplabelText">Contact Owner</label>
                        <select name="contactOwner" class="form-select npinputinfo" id="validationDefault04" >
                            {{-- <option selected disabled value=""></option> --}}
                            <option value="{{ json_encode(['id'=> $user_id,'Full_Name'=> $name])}}" selected>{{ 'CHR Technology' }}</option>

                        </select>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Last Called</label>
                        <input type="date"  name="last_called" class="form-control npinputinfo" id="datetimeInput" >
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Last Emailed</label>
                        <input type="date" name="last_emailed" class="form-control npinputinfo" id="validationDefault02" >
                    </div>

                </div>
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
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label nplabelText">First Name</label>
                        <input type="text" name="first_name" value="{{$contact['first_name']}}" placeholder="Enter First name" class="form-control npinputinfo"
                            id="validationDefault01" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Last Name</label>
                        <input type="text" value="{{$contact['last_name']}}" name="last_name" onkeyup="showValidation(this)" placeholder="Enter Last name" class="form-control npinputinfo"
                            id="last_name">
                            <div id="last_name_error_message" class="text-danger"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Mobile</label>
                        <input type="text" name="mobile" class="form-control npinputinfo" placeholder="Enter Mobile Number"
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Phone</label>
                        <input type="text" name="phone" class="form-control npinputinfo" placeholder="Enter Phone Number"
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Email</label>
                        <input type="text" name="email" class="form-control npinputinfo" placeholder="Enter Email"
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Market Area</label>
                        <input type="text" name="market_area" class="form-control npinputinfo" placeholder="Downtown Chicago"
                            id="validationDefault03" >
                    </div>
                </div>
            </div>
            {{-- Contact Preferences --}}
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Contact Preferences</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label nplabelText">Relationship Type</label>
                        <select name="relationship_type" class="form-select npinputinfo" id="validationDefault04" >
                            <option selected disabled value="">-None-</option>
                            <option value="Primary">Primary</option>
                            <option value="Secondary">Secondory</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Referred By</label>
                        <select name="reffered_by" type="text" placeholder="Louis Rinmbaud" class="form-select npinputinfo"
                            id="validationDefault02" >
                            <option value="">-None-</option>
                            @if (!empty($contacts))
                            @foreach ($contacts as $contact)
                            <option value="{{ json_encode(['id'=> $contact['zoho_contact_id'],'Full_Name'=> $contact['first_name'] . ' ' . $contact['last_name']])}}">{{$contact['first_name']}} {{$contact['last_name']}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Lead Source</label>
                        <select name="lead_source" type="text" class="form-select npinputinfo" placeholder="Peter Hunt"
                            id="validationDefault03" >
                            <option value="">-None-</option>
                            <option value="Activity">Activity</option>
                            <option value="CHR Lead">CHR Lead</option>
                            <option value="Class">Class</option>
                            <option value="Client Reviews">Client Reviews</option>
                            <option value="Event">Event</option>
                            <option value="Family">Family</option>
                            <option value="Farm">Farm</option>
                            <option value="Friend">Friend</option>
                            <option value="Networking Group">Networking Group</option>
                            <option value="Office Walk In">Office Walk In</option>
                            <option value="Online Lead">Online Lead</option>
                            <option value="Open House">Open House</option>
                            <option value="Past Client">Past Client</option>
                            <option value="Raferral Agent">Raferral Agent</option>
                            <option value="Raferral Business Partner">Raferral Business Partner</option>
                            <option value="Raferral Client">Raferral Client</option>
                            <option value="Raferral Client">Raferral Client</option>
                            <option value="Referral - Family/Friend">Referral - Family/Friend</option>
                            <option value="Sign Call">Sign Call</option>
                            <option value="Social Media">Social Media</option>
                            <option value="Sphere">Sphere</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Lead source details</label>
                        <input type="text" name="lead_source_detail" class="form-control npinputinfo" placeholder="Raoul P Associate"
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Envelope Salutation</label>
                        <input type="text" name="envelope_salutation" class="form-control npinputinfo" placeholder="Mr."
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Spouse/Partner</label>
                        <select type="text" name="spouse_partner" class="form-select npinputinfo" placeholder="Mary Long"
                            id="validationDefault03" >
                            <option value="">-None-</option>
                            @if (!empty($contacts))
                            @foreach ($contacts as $contact)
                            <option value="{{ json_encode(['id'=> $contact['zoho_contact_id'],'Full_Name'=> $contact['first_name'] . ' ' . $contact['last_name']])}}">{{$contact['first_name']}} {{$contact['last_name']}}</option>
                            @endforeach
                            @endif
                          
                        </select>
                    </div>
                </div>
            </div>
            {{-- Business Information --}}
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Business Information</p>
                <div class="row g-3">
                    <div>
                        <label for="validationDefault01" class="form-label nplabelText">Business Name</label>
                        <input type="text" name="business_name" placeholder="Burn Co." class="form-control npinputinfo"
                            id="validationDefault02" >
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">ABCD Class</label>
                        <select name="abcd_class" class="form-select npinputinfo" id="validationDefault04" >
                            <option selected disabled value="">-None-</option>
                            <option value="A+">A+</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Business Information</label>
                        <textarea name="business_information" type="text" rows="4" class="form-control nctextarea" id="validationDefault02" ></textarea>
                    </div>

                </div>
            </div>

            {{-- Primary Contact’s Address --}}
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Primary Contact’s Address</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label nplabelText">Address line 1</label>
                        <input type="text" name="address_line1" class="form-control npinputinfo" placeholder="22 Smith St."
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Address line 2</label>
                        <input type="text" name="address_line2"  placeholder="Dane Sq." class="form-control npinputinfo"
                            id="validationDefault02" >
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">City</label>
                        <input type="text" name="city"  class="form-control npinputinfo" placeholder="Enter City"
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">State</label>
                        <input type="text" name="city"  class="form-control npinputinfo" placeholder="Enter State"
                            id="validationDefault04" >
                        {{-- <select name="state" class="form-select npinputinfo" id="validationDefault04" >
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">ZIP code</label>
                        <input type="text" name="zip_code"  class="form-control npinputinfo" placeholder="Mr."
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Email</label>
                        <input type="text" name="email_primary"  class="form-control npinputinfo" placeholder="Mary Long"
                            id="validationDefault03" >
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" name="primary_address" type="checkbox" value="false" id="primary_address">
                        <label class="form-check-label nplabelText" for="flexCheckChecked">
                            Primary Address
                        </label>
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" name="secondry_address" id="secondry_address" type="checkbox" value="false" id="flexCheckChecked">
                        <label class="form-check-label nplabelText" for="flexCheckChecked">
                            Secondary Address
                        </label>
                    </div>
                </div>
            </div>
            <div>
                <button class="submit_button btn btn-primary" id="submit_button" type="button" onclick="validateContactForm()">Submit</button>
            </div>
        </form>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropContact">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
    </div>


      {{-- Note Modal --}}
      <div class="modal fade" id="staticBackdropContact" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                              @if (!empty($retrieveModuleData))
                              @foreach ($retrieveModuleData as $item)
                                  @if (in_array($item['api_name'], ['Contacts']))
                                      <option value="{{ $item }}">{{ $item['api_name'] }}</option>
                                  @endif
                              @endforeach
                              @endif
                          </select>
                          <select class="form-select dmodaltaskSelect" id="taskSelect" name="related_to_parent"
                              aria-label="Select Transaction" style="display: none;">
                              <option value="">Please Select one</option>
                          </select>
                      </div>
                      <div id="related_to_error" class="text-danger"></div>
                  </div>
                  <div class="modal-footer dNoteFooter border-0">
                      <button type="button" id="validate-button" onclick="validateFormc()"
                          class="btn btn-secondary dNoteModalmarkBtn">
                          <i class="fas fa-save saveIcon"></i> Add Note
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
   
@endsection
<script>

document.addEventListener('DOMContentLoaded', function() {
        $('#primary_address').change(function() {
    if($(this).is(':checked')) {
        $(this).val(true);
    } else {
        $(this).val(false);
    }
});

// Secondary Address Checkbox
$('#secondry_address').change(function() {
    if($(this).is(':checked')) {
        $(this).val(true);
    } else {
        $(this).val(false);
    }
});

        document.getElementById("note_text").addEventListener("keyup", validateFormc);
        document.getElementById("related_to").addEventListener("change", validateFormc);

    })

    function showValidation(e){
    let lastName = e.value;
    let regex = /^[a-zA-Z ]{1,20}$/; // Regular expression to match only letters and spaces up to 20 characters

    if (lastName.trim() === "" || !regex.test(lastName)) {
        $("#last_name_error_message").text("Last name must be between 1 and 20 characters long and contain only letters and spaces").show();
    } else {
        $("#last_name_error_message").hide(); // Hide the error message if the last name is valid
    }
    }
  function validateContactForm(){
    let last_name = $("#last_name").val(); 
    // let regex = /^[a-zA-Z ]{1,20}$/;
    if (last_name.trim() === "") {
    $("#last_name_error_message").text("Last name cannot be empty").show();
    return false;
    } else {
        $("#last_name_error_message").hide(); // Hide the error message if the last name is not empty
    }
    let submitbtn = $("#submit_button");
    submitbtn.attr("type", "submit");

  }

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

    function resetFormAndHideSelect() {
        document.getElementById('noteForm').reset();
        document.getElementById('taskSelect').style.display = 'none';
        clearValidationMessages();
    }

    function clearValidationMessages() {
        document.getElementById("note_text_error").innerText = "";
        document.getElementById("related_to_error").innerText = "";
    }

    function validateFormc() {
        let noteText = document.getElementById("note_text").value;
        let relatedTo = document.getElementById("related_to").value;
        let isValid = true;

        // Reset errors
        document.getElementById("note_text_error").innerText = "";
        document.getElementById("related_to_error").innerText = "";

        // Validate note text length
        if (noteText.trim().length > 50) {
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

</script>
