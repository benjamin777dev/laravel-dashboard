{{-- contact roles --}}
<div class="table-responsive dtranstiontable mt-3">
    <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
        <p class="nproletext">Contact Roles</p>
    </div>
    <div class="row npNom-TM-Table">
        <div class="col-md-1 "></div>
        <div class="col-md-2 ">Role</div>
        <div class="col-md-2 ">Name</div>
        <div class="col-md-3 ">Phone</div>
        <div class="col-md-3 ">Email</div>
    </div>
    @if ($contactRoles->isEmpty())
    <div>
        <p class="text-center notesAsignedText">No Contact Role assigned</p>
    </div>
    @else
    @foreach($contactRoles as $role)
    <div class="row npNom-TM-Body">
        <div class="col-md-1 commonTextEllipsis"></div>
        <div class="col-md-2">{{ $role['role'] }}</div>
        <div class="col-md-2">{{ $role['name'] }}</div>
        <div class="col-md-3">{{ $role['phone'] ?? 'N/A' }}</div>
        <div class="col-md-3 commonTextEllipsis">{{ $role['email'] ?? 'N/A' }}</div>
    </div>
    @endforeach
    @endif
    <div class="ptableCardDiv">
        @foreach ($contactRoles as $role)
        <div class="npRoleCard vprolecard">
            <div>
                <p class="npcommonheaderText">Role</p>
                <p class="npcommontableBodytext">{{ $role['role'] }}</p>
            </div>
            <div class="d-flex justify-content-between align-items-center npCardPhoneDiv">
                <div>
                    <p class="npcommonheaderText">Name</p>
                    <p class="npcommontableBodyDatetext">{{ $role['name'] }}</p>
                </div>
                <div>
                    <p class="npcommonheaderText">Phone</p>
                    <p class="npcommontableBodyDatetext">{{ $role['phone'] ?? 'N/A' }}</p>
                </div>
            </div>
            <div>
                <p class="npcommonheaderText">Email</p>
                <p class="npcommontableBodyDatetext">{{ $role['email'] ?? 'N/A' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
