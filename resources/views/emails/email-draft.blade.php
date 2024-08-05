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
                        <option value="{{ $contactDetail['id'] }}" {{$selected}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
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
                        <option value="{{ $contactDetail['id'] }}" {{$selected}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
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
                        <option value="{{ $contactDetail['id'] }}" {{$selected}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
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
    <button type="button" class="btn btn-dark" onclick="openDraftTemplate()">Save as template</button>
    <button type="button" class="btn btn-dark" onclick="sendDraftEmails({{isset($email)?json_encode($email):null}},true)">Send <i class="fab fa-telegram-plane ms-1"></i></button>
</div>

<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @include('emails.email_templates.email-template-create',['contact'=>$contact])
        </div>
    </div>
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
            plugins: 'lists link image media preview',
            toolbar: 'h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link image media | removeformat help customSelect',
            menubar: false,
            statusbar: false,
            setup: function (editor) {
                editor.ui.registry.addButton('customSelect', {
                    text: 'Select Template',
                    onAction: function () {
                        // Fetch data from the server
                        $.ajax({
                            url: '/get/templates',  // Replace with your API endpoint
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                // Assuming response is an array of options
                                var items = response.map(function(item) {
                                    return { text: item.name, value: JSON.stringify(item.id) };
                                });

                                // Open the dialog with the fetched data
                                editor.windowManager.open({
                                    title: 'Select Template',
                                    body: {
                                        type: 'panel',
                                        items: [
                                            {
                                                type: 'selectbox',
                                                name: 'options',
                                                label: 'Select Option',
                                                items: items
                                            }
                                        ]
                                    },
                                    buttons: [
                                        {
                                            type: 'cancel',
                                            text: 'Close'
                                        },
                                        {
                                            type: 'submit',
                                            text: 'Insert',
                                            primary: true
                                        }
                                    ],
                                    onSubmit: function (api) {
                                        var data = api.getData();
                                        var selectedOption = data.options;
                                        console.log(selectedOption);
                                        // Call the API with the selected option
                                        $.ajax({
                                            url: '/get/template/detail/'+selectedOption,  // Replace with your submission API endpoint
                                            method: 'GET',
                                            success: function (response) {
                                                // Insert the response content or selected option into the editor
                                                editor.insertContent(response.content);
                                                api.close();
                                            },
                                            error: function () {
                                                // Handle any errors
                                                alert('Failed to submit the selected option');
                                            }
                                        });
                                        
                                    }
                                });
                            },
                            error: function () {
                                // Handle any errors
                                alert('Failed to fetch options');
                            }
                        });
                    }
                });
            }
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
        // var toEmails = to.map((val)=>JSON.parse(val));
        // var ccEmails = cc.map((val)=>JSON.parse(val));
        // var bccEmails = bcc.map((val)=>JSON.parse(val));
        var formData = 
        {
            "to": to,
            "cc": cc,
            "bcc": bcc,
            "subject": subject,
            // "from": {
            //     "email": "{{auth()->user()->email}}",
            //     "name": "{{auth()->user()->name}}",
            // },
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

    window.openDraftTemplate = function(){
        var firstModalEl = document.getElementById('composemodal');
        var firstModal = bootstrap.Modal.getInstance(firstModalEl);
        if (firstModal) {
            firstModal.hide();
        }
        var templateContent= tinymce.get('elmEmail').getContent()
        var templateSubject= $("#emailSubject").val();
        $('#templateContent').val(templateContent);
        $('#templateSubject').val(templateSubject);
        var secondModal = new bootstrap.Modal(document.getElementById('templateModal'));
        secondModal.show();
    }
</script>
            