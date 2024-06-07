<div class="table-responsive dtranstiontable mt-3">
    <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
        <p class="nproletext">Submittals</p>
        <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
            data-bs-target="#submittalModal{{$deal['id']}}"><i class="fas fa-plus plusicon">
            </i>
            Add New Submittal
        </div>

    </div>
    <div class="row npNom-TM-Table">
        <div class="col-md-4 ">Submittal Name</div>
        <div class="col-md-4 ">Owner</div>
        <div class="col-md-4 ">Created Time</div>
    </div>
    @if ($submittals->isEmpty())
    <div>
        <p class="text-center notesAsignedText">No Submittal assigned</p>

    </div>
    @else
    @foreach($submittals as $submittal)
    <div class="row npNom-TM-Body">
        <div class="col-md-4 ">{{$submittal['name']}}</div>
        <div class="col-md-4 ">{{$submittal['userData']['name']}}</div>
        <div class="col-md-4 commonTextEllipsis">{{$submittal['created_at']}}</div>
    </div>
    @endforeach
    @endif
    @foreach($submittals as $submittal)
    <div class="npNom-TM-Card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="npcommonheaderText">Submittal Name</p>
                <p class="npcommontableBodytext">{{$submittal['name']}}</p>
            </div>
            <div>
                <p class="npcommonheaderText">Owner</p>
                <p class="npcommontableBodyDatetext">{{$submittal['closed_date']}}</p>
            </div>
        </div>
        <div class="npCardPhoneDiv">
            <p class="npcommonheaderText">Created Time</p>
            <p class="npcommontableBodyDatetext">{{$submittal['created_at']}}</p>
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
    @include('submittals.create',['dealContacts'=>$dealContacts,'deal'=>$deal])

</div>