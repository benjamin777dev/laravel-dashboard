@extends('layouts.master')

@section('title') @lang('Inbox') @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Email @endslot
@slot('title') Inbox @endslot
@endcomponent

<script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>
<div class="row">
    <div class="col-12">
        <!-- Left sidebar -->
        <div class="email-leftbar card">
            <button type="button" class="btn btn-dark btn-block waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#composemodal">
                Compose
            </button>
            <div class="mail-list mt-4" onclick = "fetchEmails(event)">
                <a href="#" class="active" ><i class="mdi mdi-email-outline me-2"></i> Inbox <span class="ms-1 float-end">(18)</span></a>
                <a href="#"><i class="mdi mdi-star-outline me-2"></i>Starred</a>
                {{-- <a href="#"><i class="mdi mdi-diamond-stone me-2"></i>Important</a>
                <a href="#"><i class="mdi mdi-file-outline me-2"></i>Draft</a> --}}
                <a href="#"><i class="mdi mdi-email-check-outline me-2"></i>Sent Mail</a>
                <a href="#"><i class="mdi mdi-trash-can-outline me-2"></i>Trash</a>
            </div>


            {{-- <h6 class="mt-4">Labels</h6>

            <div class="mail-list mt-1">
                <a href="javascript: void(0);"><span class="mdi mdi-arrow-right-drop-circle text-info float-end"></span>Theme Support</a>
                <a href="javascript: void(0);"><span class="mdi mdi-arrow-right-drop-circle text-warning float-end"></span>Freelance</a>
                <a href="javascript: void(0);"><span class="mdi mdi-arrow-right-drop-circle text-primary float-end"></span>Social</a>
                <a href="javascript: void(0);"><span class="mdi mdi-arrow-right-drop-circle text-danger float-end"></span>Friends</a>
                <a href="javascript: void(0);"><span class="mdi mdi-arrow-right-drop-circle text-success float-end"></span>Family</a>
            </div>

            <h6 class="mt-4">Chat</h6>

            <div class="mt-2">
                <a href="javascript: void(0);" class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <img class="rounded-circle" src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" alt="Generic placeholder image" height="36">
                    </div>
                    <div class="flex-grow-1 chat-user-box">
                        <p class="user-title m-0">Scott Median</p>
                        <p class="text-muted">Hello</p>
                    </div>
                </a>

                <a href="javascript: void(0);" class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <img class="rounded-circle" src="{{ URL::asset('build/images/users/avatar-3.jpg') }}" alt="Generic placeholder image" height="36">
                    </div>
                    <div class="flex-grow-1 chat-user-box">
                        <p class="user-title m-0">Julian Rosa</p>
                        <p class="text-muted">What about our next..</p>
                    </div>
                </a>

                <a href="javascript: void(0);" class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <img class="rounded-circle" src="{{ URL::asset('build/images/users/avatar-4.jpg') }}" alt="Generic placeholder image" height="36">
                    </div>
                    <div class="flex-grow-1 chat-user-box">
                        <p class="user-title m-0">David Medina</p>
                        <p class="text-muted">Yeah everything is fine</p>
                    </div>
                </a>

                <a href="javascript: void(0);" class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <img class="rounded-circle" src="{{ URL::asset('build/images/users/avatar-6.jpg') }}" alt="Generic placeholder image" height="36">
                    </div>
                    <div class="flex-grow-1 chat-user-box">
                        <p class="user-title m-0">Jay Baker</p>
                        <p class="text-muted">Wow that's great</p>
                    </div>
                </a>

            </div> --}}
        </div>
        <!-- End Left sidebar -->


        <!-- Right Sidebar -->
        <div class="email-rightbar mb-3">

            <div class="card">
                {{-- <div class="btn-toolbar p-3" role="toolbar">
                    <div class="btn-group me-2 mb-2 mb-sm-0">
                        <button type="button" class="btn btn-dark waves-light waves-effect"><i class="fa fa-inbox"></i></button>
                        <button type="button" class="btn btn-dark waves-light waves-effect"><i class="fa fa-exclamation-circle"></i></button>
                        <button type="button" class="btn btn-dark waves-light waves-effect"><i class="far fa-trash-alt"></i></button>
                    </div>
                    <div class="btn-group me-2 mb-2 mb-sm-0">
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
                    </div>
                </div> --}}
                <div id = "emailList">

                </div>
                

            </div><!-- card -->

            <div class="row">
                <div class="col-7">
                    Showing 1 - 20 of 1,524
                </div>
                <div class="col-5">
                    <div class="btn-group float-end">
                        <button type="button" class="btn btn-sm btn-success waves-effect"><i class="fa fa-chevron-left"></i></button>
                        <button type="button" class="btn btn-sm btn-success waves-effect"><i class="fa fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

        </div> <!-- end Col-9 -->

    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="composemodalTitle">New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="mb-3">
                        <select class="select2 form-control select2-multiple" multiple="multiple"
                            data-placeholder="To" id="toSelect">
                            <optgroup label="Alaskan/Hawaiian Time Zone">
                                <option value="AK">Alaska</option>
                                <option value="HI">Hawaii</option>
                            </optgroup>
                            <optgroup label="Pacific Time Zone">
                                <option value="CA">California</option>
                                <option value="NV">Nevada</option>
                                <option value="OR">Oregon</option>
                                <option value="WA">Washington</option>
                            </optgroup>
                            <optgroup label="Mountain Time Zone">
                                <option value="AZ">Arizona</option>
                                <option value="CO">Colorado</option>
                                <option value="ID">Idaho</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NM">New Mexico</option>
                                <option value="ND">North Dakota</option>
                                <option value="UT">Utah</option>
                                <option value="WY">Wyoming</option>
                            </optgroup>
                            <optgroup label="Central Time Zone">
                                <option value="AL">Alabama</option>
                                <option value="AR">Arkansas</option>
                                <option value="IL">Illinois</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="OK">Oklahoma</option>
                                <option value="SD">South Dakota</option>
                                <option value="TX">Texas</option>
                                <option value="TN">Tennessee</option>
                                <option value="WI">Wisconsin</option>
                            </optgroup>
                            <optgroup label="Eastern Time Zone">
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="IN">Indiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="OH">Ohio</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WV">West Virginia</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Subject">
                    </div>
                    <div class="mb-3">
                        <form method="post">
                            <textarea class="form-control" id="elm1" name="area"></textarea>
                        </form>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-dark">Send <i class="fab fa-telegram-plane ms-1"></i></button>
            </div>
        </div>
    </div>
</div>
<!-- end modal -->

@endsection
@section('script')

<!--tinymce js-->
<script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>

<!-- init js -->
<script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
<script>
    $(document).ready(function(){
        fetchEmails();
        $("#toSelect").select2({
            placeholder: "To"
        })
    })
    window.fetchEmails = function(event=null){
        if(event){
            let element = event.target.closest('a');
            console.log(element);
            if (element) {
                // Remove 'active' class from all links
                const links = document.querySelectorAll('.mail-list a');
                links.forEach(link => link.classList.remove('active'));
    
                // Add 'active' class to the clicked link
                element.classList.add('active');
    
                // Get the clicked value
                const clickedValue = element.innerText.trim();
                console.log(clickedValue);
                // Add any further actions here, like making an AJAX request or updating the UI
            }
        }
        $.ajax({
            url: "{{ route('email.list') }}",
            method: 'GET', // Change to DELETE method
            success: function(response) {
                $('#emailList').html(response)
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                showToastError(xhr.responseText);
            }
        });
    }
    
</script>
@endsection
