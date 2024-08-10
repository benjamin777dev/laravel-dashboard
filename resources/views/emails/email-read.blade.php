<div class="email-rightbar mb-3">
    <div class="card">
        <div class="btn-toolbar p-3" role="toolbar">
            <div class="btn-group me-2 mb-2 mb-sm-0">
                {{-- <button type="button" class="btn btn-dark waves-light waves-effect"><i class="fa fa-inbox"></i></button>
                <button type="button" class="btn btn-dark waves-light waves-effect"><i class="fa fa-exclamation-circle"></i></button> --}}
                <button type="button" class="btn btn-dark waves-light waves-effect" id="readEmailTrash" onclick = "moveEmailToTrash('{{$email['id']}}',true)"><i class="far fa-trash-alt"></i><span class="ms-1">  Move to trash</button>
            </div>
            {{-- <div class="btn-group me-2 mb-2 mb-sm-0">
                <button type="button" class="btn btn-dark waves-light waves-effect" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-folder"></i> <i class="mdi mdi-chevron-down ms-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Updates</a>
                    <a class="dropdown-item" href="#">Social</a>
                    <a class="dropdown-item" href="#">Team Manage</a>
                </div>
            </div>
            <div class="btn-group me-2 mb-2 mb-sm-0">
                <button type="button" class="btn btn-dark waves-light waves-effect" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-tag"></i> <i class="mdi mdi-chevron-down ms-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Updates</a>
                    <a class="dropdown-item" href="#">Social</a>
                    <a class="dropdown-item" href="#">Team Manage</a>
                </div>
            </div>

            <div class="btn-group me-2 mb-2 mb-sm-0">
                <button type="button" class="btn btn-dark waves-light waves-effect" data-bs-toggle="dropdown" aria-expanded="false">
                    More <i class="mdi mdi-dots-vertical ms-2"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Mark as Unread</a>
                    <a class="dropdown-item" href="#">Mark as Important</a>
                    <a class="dropdown-item" href="#">Add to Tasks</a>
                    <a class="dropdown-item" href="#">Add Star</a>
                    <a class="dropdown-item" href="#">Mute</a>
                </div>
            </div>--}}
            <div class="btn-group me-2 mb-2 mb-sm-0" id="readRemoveEmail" style="display:none;">
                <button type="button" class="btn btn-dark waves-light waves-effect" onclick = "openDeleteSingleEmailModal('{{$email['id']}}')"><i class="far fa-trash-alt"></i><span class="ms-1">  Remove selected</button>
            </div>
            <div class="btn-group me-2 mb-2 mb-sm-0" id="readRestoreEmail" style="display:none;">
                <button type="button" class="btn btn-dark waves-light waves-effect" onclick = "moveEmailToTrash('{{$email['id']}}',false)"><i class="far fa-trash-alt"></i><span class="ms-1">  Restore selected</button>
            </div>
        </div> 
        <div class="card-body">
            <div class="d-flex mb-4">
                <div class="flex-shrink-0 me-3">
                    <img class="rounded-circle avatar-sm" src="{{ URL::asset('/images/dummyProfile.png') }}" alt="Generic placeholder image">
                </div>
                <div class="flex-grow-1">
                    @php
                        $contacts = $email->toUserData;
                        $firstContact = $contacts->first();
                        $remainingCount = $contacts->count() - 1;
                    @endphp
                    <h5 class="font-size-14 mt-1">
                        @foreach($contacts as $contact)
                            {{$contact['first_name']??""}} {{$contact['last_name']??""}},
                        @endforeach
                    </h5>
                    <small class="text-muted">
                    @foreach($contacts as $contact)
                        {{$contact['email']}},
                    @endforeach
                    </small>
                </div>
            </div>

            <h4 class="font-size-16">{{$email['subject']}}</h4>

            {!! $email['content'] !!}
            <hr />

            {{-- <div class="row">
                <div class="col-xl-2 col-6">
                    <div class="card">
                        <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/small/img-3.jpg') }}" alt="Card image cap">
                        <div class="py-2 text-center">
                            <a href="javascript: void(0);" class="fw-medium">Download</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-6">
                    <div class="card">
                        <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/small/img-4.jpg') }}" alt="Card image cap">
                        <div class="py-2 text-center">
                            <a href="javascript: void(0);" class="fw-medium">Download</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="javascript: void(0);" class="btn btn-secondary waves-effect mt-4"><i class="mdi mdi-reply"></i> Reply</a> --}}
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-center" id="confirmEmailDeleteModal{{$email['id']}}" tabindex="-1" role="dialog" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" id="trashConfirmModalClose{{$email['id']}}" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="text-align:center;">Are you sure you want to do this?</p>
                <div class="modal-footer trashFooterModal">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-dark" onclick="deleteSingleEmail('{{$email['id']}}')">Submit</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    function moveEmailToTrash(id,isDeleted) {
        let selectedEmail = [];
        selectedEmail.push(`${id}`);
        $.ajax({
            url: "{{ route('email.moveToTrash') }}",
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ emailIds: selectedEmail,isDeleted:isDeleted }),
            success: function(response) {
                fetchEmails();
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                showToastError(xhr.responseText);
            }
        });
    };

    function openDeleteSingleEmailModal(id){
        $("#confirmEmailDeleteModal"+id).modal('show')
    }

    function deleteSingleEmail(id) {
        let selectedEmail = [];
        selectedEmail.push(`${id}`);
        $.ajax({
            url: "{{ route('email.delete') }}",
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ emailIds: selectedEmail}),
            success: function(response) {
                showToast('Email deleted successfully')
                $("#trashConfirmModalClose"+id).click();
                fetchEmails();
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                showToastError(xhr.responseText);
            }
        });
    };
</script>