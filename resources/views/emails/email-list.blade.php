@php
use Carbon\Carbon;
@endphp
<div class="email-rightbar mb-3">

    <div class="card">
        <div class="btn-toolbar p-3" role="toolbar">
            <div class="btn-group me-2 mb-2 mb-sm-0">
                {{-- <button type="button" class="btn btn-dark waves-light waves-effect"><i class="fa fa-inbox"></i></button>
                <button type="button" class="btn btn-dark waves-light waves-effect"><i class="fa fa-exclamation-circle"></i></button> --}}
                <button type="button" class="btn btn-dark waves-light waves-effect" id="trashButton" onclick = "moveToTrashEmail(true)"><i class="far fa-trash-alt"></i><span class="ms-1">  Move to trash</button>
                
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
            <div class="btn-group me-2 mb-2 mb-sm-0" id="removeEmail" style="display:none;">
                <button type="button" class="btn btn-dark waves-light waves-effect" onclick = "openDeleteEmail()"><i class="far fa-trash-alt"></i><span class="ms-1">  Remove selected</button>
            </div>
            <div class="btn-group me-2 mb-2 mb-sm-0" id="restoreEmail" style="display:none;">
                <button type="button" class="btn btn-dark waves-light waves-effect" onclick = "moveToTrashEmail(false)"><i class="far fa-trash-alt"></i><span class="ms-1">  Restore selected</button>
            </div>
        </div> 
        <div>
            <ul class="message-list">
                @if($emails->isNotEmpty())
                    @foreach($emails as $email)
                        <li>
                            <div class="col-mail col-mail-1">
                                <div class="checkbox-wrapper-mail">
                                    <input type="checkbox" id="{{$email['id']}}" value="{{$email['id']}}" onclick="handleCheckboxClick(this)">
                                    <label for="{{$email['id']}}" class="toggle"></label>
                                </div>
                                @php
                                    $contacts = $email->toUserData;
                                    if (is_array($contacts) || $contacts instanceof \Illuminate\Support\Collection) {
                                        $contacts = collect($contacts); // Convert to collection if it's an array
                                    } else {
                                        $contacts = collect([]); // Handle as empty collection if not countable
                                    }
                                    $firstContact = $contacts->first();
                                    $remainingCount = $contacts->count() - 1;
                                @endphp
                                <a href="javascript: void(0);" class="title text-dark" onclick = "getEmail({{$email}})">To: {{ $firstContact ? $firstContact->first_name . ' ' . $firstContact->last_name : 'No contacts available' }}
                                @if ($remainingCount > 0)
                                    ,({{ $remainingCount }})
                                @endif</a><span class="star-toggle far fa-star"></span>
                            </div>
                            <div class="col-mail col-mail-2">
                                <a href="javascript: void(0);" class="subject text-dark" onclick = "getEmail({{$email}})">{{$email['subject']}} <span class="teaser text-muted"> - {{ htmlspecialchars(strip_tags($email['content'])) }}</span>
                                </a>
                                <div class="date">{{ Carbon::parse($email['created_at'])->format('M j') }}</div>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li>
                        <div class="col-mail col-mail-2">
                        No Email Found
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        

    </div><!-- card -->

    @if($emails->count()>0)
    <div class="row">
            <div class="col-7">
                Showing 1 - 20 of {{$emails->count()}}
            </div>
            <div class="col-5">
                <div class="btn-group float-end">
                    <button type="button" class="btn btn-sm btn-success waves-effect"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-sm btn-success waves-effect"><i class="fa fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
        @endif

</div>

<div class="modal fade p-5" id="draftModal" tabindex="-1" role="dialog" aria-labelledby="draftModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modalValues">
            
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-center show" id="confirmEmailDeleteModal" tabindex="-1" role="dialog" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to do this?</p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-dark" onclick="updateTemplate('${id}')">Update template</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>
   
    
    let selectedEmails = [];
    
    function handleCheckboxClick(checkbox) {
        // Get the value of the checkbox
        const emailValue = checkbox.value;

        // Check if the checkbox is checked
        if (checkbox.checked) {
            // Add value to the array if not already present
            if (!selectedEmails.includes(emailValue)) {
                selectedEmails.push(emailValue);
            }
        } else {
            // Remove value from the array if unchecked
            selectedEmails = selectedEmails.filter(value => value !== emailValue);
        }

        // Optional: Log the array to see the changes
        console.log(selectedEmails);
    }

    window.getEmail=function(email){
        console.log((email));
        console.log(email.id);
        if(window.clickedValue == 'Draft'){
            $.ajax({
                url: "{{ route('email.detail.draft', ['emailId' => ':id']) }}".replace(':id', email.id),
                method: 'GET',
                success: function(response) {
                    $('#draftModal').find('#modalValues').html(response);
                    $('#draftModal').modal('show')
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                    showToastError(xhr.responseText);
                }
            });
        } else{
            $.ajax({
                url: "{{ route('email.detail', ['emailId' => ':id']) }}".replace(':id', email.id),
                method: 'GET',
                success: function(response) {
                    $('#emailList').html(response);
                    if(window.clickedValue=='Trash'){
                        $('#readEmailTrash').hide();
                        $('#readRemoveEmail').show();
                        $('#readRestoreEmail').show();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                    showToastError(xhr.responseText);
                }
            });
        }
    }

    function moveToTrashEmail(isDeleted) {
        console.log("CheckedEmail", selectedEmails);

        $.ajax({
            url: "{{ route('email.moveToTrash') }}",
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ emailIds: selectedEmails,isDeleted:isDeleted }),
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

    function openDeleteEmail(){
        $("#confirmEmailDeleteModal").modal('show')
    }
   function deleteEmail() {
        $.ajax({
            url: "{{ route('email.delete') }}",
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ emailIds: selectedEmails}),
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