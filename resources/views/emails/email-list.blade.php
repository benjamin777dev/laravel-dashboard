@php
use Carbon\Carbon;
@endphp
<div class="email-rightbar mb-3">

    <div class="card">
        <div class="btn-toolbar p-3" role="toolbar">
            <div class="btn-group me-2 mb-2 mb-sm-0">
                {{-- <button type="button" class="btn btn-dark waves-light waves-effect"><i class="fa fa-inbox"></i></button>
                <button type="button" class="btn btn-dark waves-light waves-effect"><i class="fa fa-exclamation-circle"></i></button> --}}
                <button type="button" class="btn btn-dark waves-light waves-effect"><i class="far fa-trash-alt"></i><span class="ms-1">  Move to trash</button>
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
        </div> 
        <div>
            <ul class="message-list">
                @if($emails->count()>0)
                    @foreach($emails as $email)
                        <li>
                            <div class="col-mail col-mail-1">
                                <div class="checkbox-wrapper-mail">
                                    <input type="checkbox" id="{{$email['id']}}">
                                    <label for="{{$email['id']}}" class="toggle"></label>
                                </div>
                                <a href="javascript: void(0);" class="title" onclick = "getEmail({{json_encode($email)}})">{{$email['toEmail']}}, me (3)</a><span class="star-toggle far fa-star"></span>
                            </div>
                            <div class="col-mail col-mail-2">
                                <a href="javascript: void(0);" class="subject" onclick = "getEmail({{json_encode($email)}})">{{$email['subject']}} <span class="teaser">{{$email['content']}}</span>
                                </a>
                                <div class="date">{{ Carbon::parse($email['created_at'])->format('Y-m-d') }}</div>
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

    <div class="row">
        @if($emails->count()>0)
            <div class="col-7">
                Showing 1 - 20 of {{$emails->count()}}
            </div>
            <div class="col-5">
                <div class="btn-group float-end">
                    <button type="button" class="btn btn-sm btn-success waves-effect"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-sm btn-success waves-effect"><i class="fa fa-chevron-right"></i></button>
                </div>
            </div>
        @endif
    </div>

</div>

<div class="modal fade" id="draftModal" tabindex="-1" role="dialog" aria-labelledby="draftModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modalValues">
            
        </div>
    </div>
</div>


<script>
    console.log("activeElement",window.clickedValue);
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
                    $('.message-list').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                    showToastError(xhr.responseText);
                }
            });
        }
    }
</script>