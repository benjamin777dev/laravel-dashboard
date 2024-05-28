  {{-- contact roles --}}
    <div class="table-responsive dtranstiontable mt-3">
        <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
            <p class="nproletext">Contact Roles</p>
            <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#contactRoleModal{{$deal['id']}}">
                <i class="fas fa-plus plusicon">
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
                    <div class="col-md-3 ">
                        {{ $dealContact->contactData ? $dealContact->contactData->first_name . ' ' . $dealContact->contactData->last_name : ($dealContact->userData ? $dealContact->userData->name : 'N/A') }}
                    </div>
                    <div class="col-md-2 ">{{ $dealContact['contactRole'] }}</div>
                    <div class="col-md-3 ">
                        {{ $dealContact->contactData ? ($dealContact->contactData->phone ? $dealContact->contactData->phone : 'N/A') : 'N/A' }}
                    </div>
                    <div class="col-md-4 commonTextEllipsis">
                        {{ $dealContact->contactData ? $dealContact->contactData->email : ($dealContact->userData ? $dealContact->userData->email : 'N/A') }}
                    </div>
                </div>
            @endforeach
        @endif


        @foreach ($dealContacts as $dealContact)
            <div class="npRoleCard vprolecard">
                <div>
                    <p class="npcommonheaderText">Role</p>
                    <p class="npcommontableBodytext">
                        {{ $dealContact->contactData ? $dealContact->contactData->first_name . ' ' . $dealContact->contactData->last_name : ($dealContact->userData ? $dealContact->userData->name : 'N/A') }}
                    </p>
                </div>
                <div class="d-flex justify-content-between align-items-center npCardPhoneDiv">
                    <div>
                        <p class="npcommonheaderText">Role Name</p>
                        <p class="npcommontableBodyDatetext">{{ $dealContact['contactRole'] }}</p>
                    </div>
                    <div>
                        <p class="npcommonheaderText">Phone</p>
                        <p class="npcommontableBodyDatetext">
                            {{ $dealContact->contactData ? ($dealContact->contactData->phone ? $dealContact->contactData->phone : 'N/A') : 'N/A' }}
                        </p>
                    </div>
                </div>
                <div>
                    <p class="npcommonheaderText">Email</p>
                    <p class="npcommontableBodyDatetext">
                        {{ $dealContact->contactData ? $dealContact->contactData->email : ($dealContact->userData ? $dealContact->userData->email : 'N/A') }}
                    </p>
                </div>
            </div>
        @endforeach

        <div class="dpagination">
            <div onclick="removeAllSelected()"
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
         @include('contactRole.create',['dealContacts'=>$dealContacts,'deal'=>$deal])               

    </div>