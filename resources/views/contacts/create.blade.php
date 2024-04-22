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
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskContactModalId"><i
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
                                                <i class="fas fa-pencil-alt pencilIcon" onclick="makeEditable('{{ $task['id'] }}')"></i>
                                            </p>
                                        </td>
                                        <td>
                                            <input type="datetime-local" id="date_val{{ $task['zoho_task_id'] }}"
                                                value="{{ \Carbon\Carbon::parse($task['due_date'])->format('Y-m-d\TH:i') }}" />
                                        </td>
                                        <td>
                                            <div class="d-flex ">
                                                <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                                    id="btnGroupAddon" data-bs-toggle="modal" onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')">
                                                    <i class="fas fa-hdd plusicon"></i>
                                                    Save
                                                </div>
                                                <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                                                    id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
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
                                                                <button type="button" onclick="deleteTask('{{ $task['zoho_task_id'] }}')"
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
                 @if ($notes->isEmpty())
                 <div class="noNotesFound">
                        <p class="text-center notesAsignedText">No notes assigned</p>

                    </div> 
                @else 
                <ul class="list-group dnotesUl">
                    @foreach ($notes as $note)
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
             <div class="modal fade" id="staticBackdropnoteupdatecontact{{ $note['id'] }}" data-bs-backdrop="static" data-bs-keyboard="false"
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
                @endif 
            </div> 

        </div>

        <form class="row" action="{{ route('update.contact', ['id' => $contact->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="col-md-6 col-sm-12"
                style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                <p class="npinfoText">Internal Information</p>
                <div class="row g-3">
                    <div>
                        <label for="validationDefault01" class="form-label nplabelText">Contact Owner</label>
                        <select name="contactOwner" class="form-select npinputinfo" id="validationDefault04">
                            {{-- <option selected disabled value=""></option> --}}
                            <option value="{{ json_encode(['id' => $user_id, 'Full_Name' => $name]) }}" selected>
                                {{ 'CHR Technology' }}</option>

                        </select>
                    </div>
                    <div>
                        @php
                            // $date = "2024-04-11 00:00:00";
                            $lastcalled = \Carbon\Carbon::parse($contact['last_called'])->format('Y-m-d');
                            $lastemailed = \Carbon\Carbon::parse($contact['last_emailed'])->format('Y-m-d');
                        @endphp 
                        <label for="validationDefault02" class="form-label nplabelText">Last Called</label>
                        <input type="date" value="{{$lastcalled}}"  name="last_called" class="form-control npinputinfo" id="datetimeInput">
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Last Emailed</label>
                        <input type="date" value="{{$lastemailed}}"  name="last_emailed" class="form-control npinputinfo"
                            id="validationDefault02">
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
                        <input type="text" name="first_name" value="{{ $contact['first_name'] }}"
                            placeholder="Enter First name" class="form-control npinputinfo" id="validationDefault01">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Last Name</label>
                        <input type="text" value="{{ $contact['last_name'] }}" name="last_name"
                            onkeyup="showValidation(this)" placeholder="Enter Last name" class="form-control npinputinfo"
                            id="last_name">
                        <div id="last_name_error_message" class="text-danger"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Mobile</label>
                        <input type="text" value="{{ $contact['mobile'] }}" name="mobile"
                            class="form-control npinputinfo" placeholder="Enter Mobile Number" id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Phone</label>
                        <input type="text" value="{{ $contact['phone'] }}" name="phone"
                            class="form-control npinputinfo" placeholder="Enter Phone Number" id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Email</label>
                        <input type="text" value="{{ $contact['email'] }}" name="email"
                            class="form-control npinputinfo" placeholder="Enter Email" id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Market Area</label>
                        <input type="text" value="{{ $contact['market_area'] }}" name="market_area"
                            class="form-control npinputinfo" placeholder="Downtown Chicago" id="validationDefault03">
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
                        <select name="relationship_type" class="form-select npinputinfo" id="validationDefault04">
                            <option disabled value="">-None-</option>
                            <option value="Primary" {{ $contact->relationship_type === 'Primary' ? 'selected' : '' }}>
                                Primary</option>
                            <option value="Secondary" {{ $contact->relationship_type === 'Secondary' ? 'selected' : '' }}>
                                Secondary</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Referred By</label>
                        <select name="reffered_by" type="text" placeholder="Louis Rinmbaud"
                            class="form-select npinputinfo" id="validationDefault02">
                            @php
                                $referred_id = $contact['referred_id'];
                            @endphp
                            <option value="">-None-</option>
                            @if (!empty($contacts))
                                @foreach ($contacts as $contactRef)
                                    <option
                                        value="{{ json_encode(['id' => $contactRef['zoho_contact_id'], 'Full_Name' => $contactRef['first_name'] . ' ' . $contactRef['last_name']]) }}"
                                        {{ $contactRef['zoho_contact_id'] == $referred_id ? 'selected' : '' }}>
                                        {{ $contactRef['first_name'] }} {{ $contactRef['last_name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6">
                        @php
                            $leadSources = [
                                'Activity',
                                'CHR Lead',
                                'Class',
                                'Client Reviews',
                                'Event',
                                'Family',
                                'Farm',
                                'Friend',
                                'Networking Group',
                                'Office Walk In',
                                'Online Lead',
                                'Open House',
                                'Past Client',
                                'Referral Agent',
                                'Referral Business Partner',
                                'Referral Client',
                                'Referral - Family/Friend',
                                'Sign Call',
                                'Social Media',
                                'Sphere',
                            ];
                        @endphp
                        <label for="validationDefault03" class="form-label nplabelText">Lead Source</label>
                        <select name="lead_source" type="text" class="form-select npinputinfo" placeholder="Peter Hunt" id="validationDefault03">
                            <option value="">-None-</option>
                            @foreach ($leadSources as $leadSource)
                                <option value="{{ $leadSource }}" {{ $contact['Lead_Source'] == $leadSource ? 'selected' : '' }} >{{ $leadSource }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Lead source details</label>
                        <input type="text" value="{{ $contact['lead_source_detail']}}" name="lead_source_detail" class="form-control npinputinfo"
                            placeholder="Raoul P Associate" id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Envelope Salutation</label>
                        <input type="text" value="{{ $contact['envelope_salutation']}}"   name="envelope_salutation" class="form-control npinputinfo"
                            placeholder="Mr." id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                       
                        <label for="validationDefault03" class="form-label nplabelText">Spouse/Partner</label>
                        <select type="text" name="spouse_partner" class="form-select npinputinfo"
                            placeholder="Mary Long" id="validationDefault03">
                            @php
                            $spause_partner = $contact['spouse_partner'];
                        @endphp
                            <option value="">-None-</option>
                            @if (!empty($contacts))
                                @foreach ($contacts as $contactrefs)
                                    <option
                                        value="{{ json_encode(['id' => $contactrefs['zoho_contact_id'], 'Full_Name' => $contactrefs['first_name'] . ' ' . $contactrefs['last_name']]) }}" {{ $contactrefs['zoho_contact_id'] == $spause_partner ? 'selected' : '' }}>
                                        {{ $contactrefs['first_name'] }} {{ $contactrefs['last_name'] }}</option>
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
                        <input type="text" value="{{$contact['business_name']}}" name="business_name" placeholder="Burn Co."
                            class="form-control npinputinfo" id="validationDefault02">
                    </div>
                    <div>
                        @php
                        $abcd = [
                            'A+',
                            'A',
                            'B',
                            'C',
                            'D',
                        ];
                    @endphp
                        <label for="validationDefault02" class="form-label nplabelText">ABCD Class</label>
                        <select name="abcd_class" class="form-select npinputinfo" id="validationDefault04">
                            <option selected disabled value="">-None-</option>
                            @foreach ($abcd as $abcdIndex)
                                <option value="{{ $abcdIndex }}" {{ $contact['abcd'] == $abcdIndex ? 'selected' : '' }} >{{ $abcdIndex }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="validationDefault02" class="form-label nplabelText">Business Information{{$contact['business_information']}}</label>
                        <textarea  name="business_information" type="text" rows="4" class="form-control nctextarea"
                            id="validationDefault02">{{ $contact['business_information'] }}</textarea>
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
                        <input type="text" value="{{$contact['mailing_address']}}" name="address_line1" class="form-control npinputinfo"
                            placeholder="22 Smith St." id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label nplabelText">Address line 2</label>
                        <input type="text" name="address_line2" placeholder="Dane Sq."
                            class="form-control npinputinfo" id="validationDefault02">
                    </div>

                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">City</label>
                        <input type="text" value="{{$contact['mailing_city']}}" name="city" class="form-control npinputinfo" placeholder="Enter City"
                            id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">State</label>
                        <input type="text" value="{{$contact['mailing_state']}}" name="state" class="form-control npinputinfo" placeholder="Enter State"
                            id="validationDefault04">
                        {{-- <select name="state" class="form-select npinputinfo" id="validationDefault04" >
                            <option selected disabled value=""></option>
                            <option>...</option>
                        </select> --}}
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">ZIP code</label>
                        <input type="text" value="{{$contact['mailing_zip']}}" name="zip_code" class="form-control npinputinfo" placeholder="Mr."
                            id="validationDefault03">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault03" class="form-label nplabelText">Email</label>
                        <input type="text" value="{{$contact['secondory_email']}}" name="email_primary" class="form-control npinputinfo"
                            placeholder="Mary Long" id="validationDefault03">
                    </div>
                    {{-- <div class="col-md-6">
                        <input class="form-check-input" name="primary_address" type="checkbox" value="false"
                            id="primary_address">
                        <label class="form-check-label nplabelText" for="flexCheckChecked">
                            Primary Address
                        </label>
                    </div>
                    <div class="col-md-6">
                        <input class="form-check-input" name="secondry_address" id="secondry_address" type="checkbox"
                            value="false" id="flexCheckChecked">
                        <label class="form-check-label nplabelText" for="flexCheckChecked">
                            Secondary Address
                        </label>
                    </div> --}}
                </div>
            </div>
            <div>
                <button class="submit_button btn btn-primary" id="submit_button" type="button"
                    onclick="validateContactForm()">Submit</button>
            </div>
        </form>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropContact">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
    </div>


    {{-- Note Modal --}}
    <div class="modal fade" id="staticBackdropContact" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                            <select class="form-select dmodaltaskSelect" id="related_to" onchange="moduleSelectedforContact(this)"
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
                        <button type="button" id="validate-button" onclick="validateFormc()"
                            class="btn btn-secondary dNoteModalmarkBtn">
                            <i class="fas fa-save saveIcon"></i> Add Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
   {{-- task modal --}}
    <div class="modal fade" id="newTaskContactModalId" tabindex="-1">
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
                                    Add the current ID to the encountered IDs array
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
                    <button type="button"
                     onclick="addTaskforContact('{{ $contact['zoho_contact_id'] }}')"
                      class="btn btn-secondary taskModalSaveBtn">
                        <i class="fas fa-save saveIcon"></i> Save Changes
                    </button>
    
                </div>
    
            </div>
        </div>
    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#primary_address').change(function() {
            if ($(this).is(':checked')) {
                $(this).val(true);
            } else {
                $(this).val(false);
            }
        });

        // Secondary Address Checkbox
        $('#secondry_address').change(function() {
            if ($(this).is(':checked')) {
                $(this).val(true);
            } else {
                $(this).val(false);
            }
        });

        document.getElementById("note_text").addEventListener("keyup", validateFormc);
        document.getElementById("related_to").addEventListener("change", validateFormc);

    })

    function showValidation(e) {
        let lastName = e.value;
        let regex = /^[a-zA-Z ]{1,20}$/; // Regular expression to match only letters and spaces up to 20 characters

        if (lastName.trim() === "" || !regex.test(lastName)) {
            $("#last_name_error_message").text(
                "Last name must be between 1 and 20 characters long and contain only letters and spaces").show();
        } else {
            $("#last_name_error_message").hide(); // Hide the error message if the last name is valid
        }
    }

    function validateContactForm() {
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

    function validateForm() {
            let noteText = document.getElementById("note_text").value;
            let relatedTo = document.getElementById("related_to").value;
            let isValid = true;

            // Reset errors
            document.getElementById("note_text_error").innerText = "";
            document.getElementById("related_to_error").innerText = "";

            // Validate note text length
            if (noteText.trim().length > 100) {
                document.getElementById("note_text_error").innerText = "Note text must be 100 characters or less";
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

    function addTaskforContact(conID){
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
                        // "Created_Time":new Date()
                        // "Priority": "High",
                        "What_Id":{
                            "id":conID
                        },
                        "$se_module":"Contacts"
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

    function moduleSelectedforContact(selectedModule) {
            // console.log(accessToken,'accessToken')
            var selectedOption = selectedModule.options[selectedModule.selectedIndex];
            var selectedText = selectedOption.text;
            //    var id = '{{ request()->route('id') }}'; 
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/task/get-' + selectedText+'?contactId={{$contact['zoho_contact_id']}}',
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
                        console.log(task,'task');
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
