@extends('layouts.master')
<!DOCTYPE html>
@section('title') @lang('Inbox') @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Left sidebar -->
        <div class="email-leftbar card">
            <button type="button" class="btn btn-dark btn-block waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#composemodal">
                Compose
            </button>
            <div class="mail-list mt-4" onclick="fetchEmails(event)">
                {{-- <a href="javascript: void(0);" class="active" ><i class="mdi mdi-email-outline me-2"></i> Inbox <span class="ms-1 float-end">(18)</span></a>
                <a href="javascript: void(0);"><i class="mdi mdi-star-outline me-2"></i>Starred</a>
                <a href="javascript: void(0);"><i class="mdi mdi-diamond-stone me-2"></i>Important</a> --}}
                <a href="javascript: void(0);" class="active"><i class="mdi mdi-email-check-outline me-2"></i>Sent Mail</a>
                <a href="javascript: void(0);"><i class="mdi mdi-file-outline me-2"></i>Draft</a>
                <a href="javascript: void(0);"><i class="mdi mdi-trash-can-outline me-2"></i>Trash</a>
                <a href="javascript: void(0);"><i class="mdi mdi-file-document-outline me-2"></i>Template</a>
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
        <div id = "emailList">

        </div>
         <!-- end Col-9 -->
        <div id="templateList" style="display:none;">
            @include('components.common-table', ['id' => 'template-table-list'])
        </div>

    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modalValues">
            @include('emails.email-create',['contacts'=>$contacts])
        </div>
    </div>
</div>
<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @include('emails.email_templates.email-template-create',['contact'=>null])
        </div>
    </div>
</div>
<!-- end modal -->

@endsection
@section('script')


@endsection
<script>
    window.clickedValue;
   window.onload = function(){
       fetchEmails(null);
 
   
       }
        function fetchEmails(event=null){
        console.log("EVENTTTTTTTTTTTT",event);
        
        if(event){
            let element = event.target.closest('a');
            console.log("ELEMENTTTTTTTTTTTT",element);
            if (element) {
                // Remove 'active' class from all links
                const links = document.querySelectorAll('.mail-list a');
                links.forEach(link => link.classList.remove('active'));
    
                // Add 'active' class to the clicked link
                activeElement = element.classList.add('active');
    
                // Get the clicked value
                window.clickedValue = element.innerText.trim();
                console.log(window.clickedValue);
                // Add any further actions here, like making an AJAX request or updating the UI
            }
        }
        if(window.clickedValue == 'Template'){
             $("#template-table-list").DataTable().ajax.reload();
            $("#emailList").hide();
            $("#templateList").show();
            fetchEmails
        } else {
            $("#emailList").show();
              $("#templateList").hide();
            $.ajax({
                url: "{{ route('email.list') }}",
                method: 'GET', // Change to DELETE method
                data:{
                    'filter':window.clickedValue??'Sent Mail'
                },
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
    }

    
    
</script>
