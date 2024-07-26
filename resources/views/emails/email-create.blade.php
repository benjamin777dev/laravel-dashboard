<script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>
<div class="modal-header">
    <h5 class="modal-title" id="composemodalTitle">New Message</h5>
    <button type="button" class="btn-close" id="emailModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div id="modal-data">
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">To</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="toSelect" multiple="multiple"
                    data-placeholder="To">
                    @foreach($contacts as $contact)
                        @php
                            $selected = ''; // Initialize variable to hold 'selected' attribute
                            if (isset($email['toEmail'])) {
                                foreach (json_decode($email['toEmail'],true) as $currEmail) {
                                    if (
                                        $contact['email'] ===
                                        $currEmail
                                    ) {
                                        $selected = 'selected'; // If IDs match, mark the option as selected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{$contact['email']}}" {{$selected}}>{{$contact['email']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">CC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="ccSelect" multiple="multiple"
                    data-placeholder="To">
                    @foreach($contacts as $contact)
                        @php
                            $selected = ''; // Initialize variable to hold 'selected' attribute
                            if (isset($email['ccEmail'])) {
                                foreach (json_decode($email['ccEmail'],true) as $currEmail) {
                                    if (
                                        $contact['email'] ===
                                        $currEmail
                                    ) {
                                        $selected = 'selected'; // If IDs match, mark the option as selected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{$contact['email']}}" {{$selected}}>{{$contact['email']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">BCC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="bccSelect" multiple="multiple"
                    data-placeholder="To">
                    @foreach($contacts as $contact)
                        @php
                            $selected = ''; // Initialize variable to hold 'selected' attribute
                            if (isset($email['bccEmail'])) {
                                foreach (json_decode($email['bccEmail'],true) as $currEmail) {
                                    if (
                                        $contact['email'] ===
                                        $currEmail
                                    ) {
                                        $selected = 'selected'; // If IDs match, mark the option as selected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{$contact['email']}}" {{$selected}}>{{$contact['email']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">Subject</label>
            <div class="col-md-10">
                <input type="text" id = "emailSubject" value="{{isset($email)?$email['subject']:''}}" class="form-control" placeholder="Subject">
            </div>
        </div>
        <div class="mb-3 row">
                <label for="example-text-input" class="col-md-2 col-form-label">Message</label>
            <form method="post">
                <textarea class="form-control" id="elmEmail" name="area">{!! isset($email)?$email['content']:'' !!}</textarea>
            </form>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-dark" onclick="sendEmails({{json_encode($email)}},false)">Save as draft <i class="fab fa-telegram-plane ms-1"></i></button>
    <button type="button" class="btn btn-dark" onclick="sendEmails({{json_encode($email)}},true)">Send <i class="fab fa-telegram-plane ms-1"></i></button>
</div>
<script>
    $(document).ready(function(){
        fetchEmails();
        $("#toSelect").select2({
            placeholder: "To",
        });
        $("#ccSelect").select2({
            placeholder: "CC",
        });
        $("#bccSelect").select2({
            placeholder: "BCC",
        });

        tinymce.init({
            selector: 'textarea#elmEmail',
            plugins: 'lists, link, image, media',
            toolbar: 'h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link image media | removeformat help',
            menubar: false,
            statusbar:false
        });

        var modal = document.getElementById('composemodal');
        var modalData = document.getElementById('modal-data');

        modal.addEventListener('hidden.bs.modal', function () {
            if (modalData) {
                modalData.querySelectorAll('input, select, textarea').forEach(function (element) {
                    if (element.tagName.toLowerCase() === 'select') {
                        $(element).val([]).trigger('change');
                    } else if (element.tagName.toLowerCase() === 'textarea' && element.id === 'elmEmail') {
                    tinymce.get(element.id).setContent('');
                }else {
                        element.value = '';
                    }
                });
            }
        });
        
    })

    window.sendEmails = function(email,isEmailSent){
        var to = $("#toSelect").val();
        var cc = $("#ccSelect").val();
        var bcc = $("#bccSelect").val();
        var content = tinymce.get('elmEmail').getContent();
        var subject = $("#emailSubject").val();
        var formData = 
        {
            "fromEmail": "tech@coloradohomerealty.com",
            "toEmail": to,
            "ccEmail":cc,
            "bccEmail":bcc,
            "subject": subject,
            "content": content,
            "isEmailSent": isEmailSent,
            "emailId": email?email.id:null
        }
        $.ajax({
            url: "{{ route('send.email') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                console.info(response);
                $("#emailModalClose").click();
                fetchEmails();
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                showToastError(xhr.responseText);
            }
        });
    }
</script>
            