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
                <button type="button" class="btn btn-dark waves-light waves-effect" onclick = "deleteSingleEmail('{{$email['id']}}')"><i class="far fa-trash-alt"></i><span class="ms-1">  Remove selected</button>
            </div>
            <div class="btn-group me-2 mb-2 mb-sm-0" id="readRestoreEmail" style="display:none;">
                <button type="button" class="btn btn-dark waves-light waves-effect" onclick = "moveEmailToTrash('{{$email['id']}}',false)"><i class="far fa-trash-alt"></i><span class="ms-1">  Restore selected</button>
            </div>
        </div> 
        <div class="card-body">
            <div class="d-flex mb-4">
                <div class="flex-shrink-0 me-3">
                    <img class="rounded-circle avatar-sm" src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" alt="Generic placeholder image">
                </div>
                <div class="flex-grow-1">
                    <h5 class="font-size-14 mt-1">Humberto D. Champion</h5>
                    @php
                        $contacts = $email->toUserData;
                        $firstContact = $contacts->first();
                        $remainingCount = $contacts->count() - 1;
                    @endphp
                    @foreach($contacts as $contact)
                    <small class="text-muted">{{$contact['email']}},</small>
                    @endforeach
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