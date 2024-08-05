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
                <select class="select2 form-control select2-multiple" id="toSelect" multiple="multiple" data-placeholder="To">
                    @foreach($contacts as $contactDetail)
                        @php
                            $selected = '';
                            if (isset($selectedContacts)) {
                                foreach ($selectedContacts as $selectedContact) {
                                    if ($contactDetail['id'] === $selectedContact['id']) {
                                        $selected = 'selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $contactDetail['id'] }}" data-email="{{ $contactDetail['email'] }}" {{ $selected }} {{!$contactDetail['email']?'disabled':''}}>
                            {{ $contactDetail['first_name'] }} {{ $contactDetail['last_name'] }}
                        </option>
                    @endforeach
                </select>


            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">CC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="ccSelect" multiple="multiple"
                    data-placeholder="To">
                    @foreach($contacts as $contactDetail)
                        @php
                            $selected = '';
                            if (isset($selectedContacts)) {
                                foreach ($selectedContacts as $selectedContact) {
                                    if ($contactDetail['id'] === $selectedContact['id']) {
                                        $selected = 'selected';
                                        break;
                                    }
                                }
                            }
                        @endphp   
                        <option value="{{ $contactDetail['id'] }}"data-email="{{ $contactDetail['email'] }}" {{ $selected }} {{!$contactDetail['email']?'disabled':''}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">BCC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="bccSelect" multiple="multiple"
                    data-placeholder="To">
                    @foreach($contacts as $contactDetail)
                        @php
                            $selected = '';
                            if (isset($selectedContacts)) {
                                foreach ($selectedContacts as $selectedContact) {
                                    if ($contactDetail['id'] === $selectedContact['id']) {
                                        $selected = 'selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $contactDetail['id'] }}" data-email="{{ $contactDetail['email'] }}" {{ $selected }} {{!$contactDetail['email']?'disabled':''}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">Subject</label>
            <div class="col-md-10">
                <input type="text" id = "emailSubject" value="" class="form-control" placeholder="Subject">
            </div>
        </div>
        <div class="mb-3 row">
                <label for="example-text-input" class="col-md-2 col-form-label">Message</label>
            <form method="post">
                <textarea class="form-control" id="elmEmail" name="area"></textarea>
            </form>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-dark" onclick="sendEmails(null,false)">Save as draft</button>
    <button type="button" class="btn btn-dark" onclick="openTemplate()">Save as template</button>
    <button type="button" class="btn btn-dark" onclick="sendEmails(null,true)">Send <i class="fab fa-telegram-plane ms-1"></i></button>
</div>

<script>
   $(document).ready(function(){
        
        // Initialize Select2 for all select elements
        $("#toSelect").select2({
            placeholder: "To",
        });

        $("#ccSelect").select2({
            placeholder: "CC",
           
        });

        $("#bccSelect").select2({
            placeholder: "BCC",
            
        });

        // Initialize TinyMCE
        tinymce.init({
            selector: 'textarea#elmEmail',
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
                                                $("#emailSubject").val(response.subject);
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

        // Handle modal reset
        var modal = document.getElementById('composemodal');
        var modalData = document.getElementById('modal-data');

        modal.addEventListener('hidden.bs.modal', function () {
            console.log("Hidden BS", modalData);
            if (modalData) {
                modalData.querySelectorAll('input, textarea').forEach(function (element) {
                    if (element.tagName.toLowerCase() === 'select') {
                        $(element).val([]).trigger('change');
                    } 
                    else if (element.tagName.toLowerCase() === 'textarea' && element.id === 'elmEmail') {
                        tinymce.get(element.id).setContent('');
                    }
                    else {
                        element.value = '';
                    }
                });
            }
        });

        // Handle nested modal behavior
        var secondModalEl = document.getElementById('templateModal');
        secondModalEl.addEventListener('hidden.bs.modal', function () {
            var firstModal = new bootstrap.Modal(document.getElementById('composemodal'));
            firstModal.show();
        });

        // Trigger change event to ensure pre-selected options are displayed correctly
        $('#toSelect').trigger('change');
        $('#ccSelect').trigger('change');
        $('#bccSelect').trigger('change');

    });


    window.sendEmails = function(email,isEmailSent){
        var to = $("#toSelect").val();
        var cc = $("#ccSelect").val();
        var bcc = $("#bccSelect").val();
        var content = tinymce.get('elmEmail').getContent();
        var subject = $("#emailSubject").val();
        // var toEmails = to.map((val)=>JSON.parse(val))
        // var ccEmails = cc.map((val)=>JSON.parse(val));
        // var bccEmails = bcc.map((val)=>JSON.parse(val));
        var formData = 
        {
            "to": to,
            "cc": cc,
            "bcc": bcc,
            "subject": subject,
            
            "content": content,
            "isEmailSent":isEmailSent
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
                $("#emailModalClose").click();
                fetchEmails()
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                showToastError(xhr.responseText);
            }
        });
    }

    window.openTemplate = function(){
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
            