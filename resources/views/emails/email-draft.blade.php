<script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>
<div class="modal-header">
    <h5 class="modal-title" id="draftModalTitle">New Message</h5>
    <button type="button" class="btn-close" id="emaildraftModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div id="modal-data">
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">To</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="toDraftSelect" multiple="multiple"
                    data-placeholder="To">
                    @foreach($contacts as $contactDetail)
                        @php
                            $selected = ''; // Initialize variable to hold 'selected' attribute
                            if (isset($email['toEmail'])) {
                                foreach (json_decode($email['toEmail'],true) as $currEmail) {
                                    if (
                                        $contactDetail['email'] ===
                                        $currEmail['email']
                                    ) {
                                        $selected = 'selected'; // If IDs match, mark the option as selected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ json_encode(['email' => $contactDetail['email'], 'name' => $contactDetail['first_name'] . ' ' . $contactDetail['last_name']]) }}" {{$selected}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">CC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="ccDraftSelect" multiple="multiple"
                    data-placeholder="To">
                    @foreach($contacts as $contactDetail)
                        @php
                            $selected = ''; // Initialize variable to hold 'selected' attribute
                            if (isset($email['ccEmail'])) {
                                foreach (json_decode($email['ccEmail'],true) as $currEmail) {
                                    if (
                                        $contactDetail['email'] ===
                                        $currEmail['email']
                                    ) {
                                        $selected = 'selected'; // If IDs match, mark the option as selected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ json_encode(['email' => $contactDetail['email'], 'name' => $contactDetail['first_name'] . ' ' . $contactDetail['last_name']]) }}" {{$selected}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">BCC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="bccDraftSelect" multiple="multiple"
                    data-placeholder="To">
                    @foreach($contacts as $contactDetail)
                        @php
                            $selected = ''; // Initialize variable to hold 'selected' attribute
                            if (isset($email['bccEmail'])) {
                                foreach (json_decode($email['bccEmail'],true) as $currEmail) {
                                    if (
                                        $contactDetail['email'] ===
                                        $currEmail['email']
                                    ) {
                                        $selected = 'selected'; // If IDs match, mark the option as selected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ json_encode(['email' => $contactDetail['email'], 'name' => $contactDetail['first_name'] . ' ' . $contactDetail['last_name']]) }}" {{$selected}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">Subject</label>
            <div class="col-md-10">
                <input type="text" id = "emailDraftSubject" value="{{isset($email)?$email['subject']:''}}" class="form-control" placeholder="Subject">
            </div>
        </div>
        <div class="mb-3 row">
                <label for="example-text-input" class="col-md-2 col-form-label">Message</label>
            <form method="post">
                <textarea class="form-control" id="draftEmailEditor" name="area">{!! isset($email)?$email['content']:'' !!}</textarea>
            </form>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-dark" onclick="sendDraftEmails({{isset($email)?json_encode($email):null}},false)">Save as draft <i class="fab fa-telegram-plane ms-1"></i></button>
    <button type="button" class="btn btn-dark" onclick="sendDraftEmails({{isset($email)?json_encode($email):null}},true)">Send <i class="fab fa-telegram-plane ms-1"></i></button>
</div>
<script>
    $(document).ready(function(){
        $("#toDraftSelect").select2({
            placeholder: "To",
        });
        $("#ccDraftSelect").select2({
            placeholder: "CC",
        });
        $("#bccDraftSelect").select2({
            placeholder: "BCC",
        });

        tinymce.init({
            selector: 'textarea#draftEmailEditor',
            plugins: 'lists, link, image, media',
            toolbar: 'h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link image media | removeformat help',
            menubar: false,
            statusbar:false
        });

        var modal = document.getElementById('draftModalTitle');
        var modalData = document.getElementById('modal-data');

        modal.addEventListener('hidden.bs.modal', function () {
            if (modalData) {
                modalData.querySelectorAll('input, select, textarea').forEach(function (element) {
                    if (element.tagName.toLowerCase() === 'select') {
                        $(element).val([]).trigger('change');
                    } else if (element.tagName.toLowerCase() === 'textarea' && element.id === 'draftEmailEditor') {
                    tinymce.get(element.id).setContent('');
                }else {
                        element.value = '';
                    }
                });
            }
        });
        
    })

    window.sendDraftEmails = function(email,isEmailSent){
        var to = $("#toDraftSelect").val();
        var cc = $("#ccDraftSelect").val();
        var bcc = $("#bccDraftSelect").val();
        var content = tinymce.get('draftEmailEditor').getContent();
        var subject = $("#emailDraftSubject").val();
        var toEmails = to.map((val)=>JSON.parse(val));
        var ccEmails = cc.map((val)=>JSON.parse(val));
        var bccEmails = bcc.map((val)=>JSON.parse(val));
        var formData = 
        {
            "to": toEmails,
            "cc": ccEmails,
            "bcc": bccEmails,
            "subject": subject,
            "from": {
                "email": "{{auth()->user()->email}}",
                "name": "{{auth()->user()->name}}",
            },
            "content": content,
            "isEmailSent":isEmailSent,
            "emailId":email.id
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
                if (response.status === 'process') {
                    showToastError(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 5000); // Adjust the delay as needed
                } else {
                    // Handle error
                }
                $("#emaildraftModalClose").click();
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                showToastError(xhr.responseText);
            }
        });
    }
</script>
            