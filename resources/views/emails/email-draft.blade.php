<script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>
<div class="modal-header">
    <h5 class="modal-title" id="draftModalTitle">{{isset($email)?$email['subject']:''}}</h5>
    <button type="button" class="btn-close" id="emaildraftModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div id="modal-data">
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">To</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="toDraftSelect" multiple="multiple"
                    data-placeholder="To" type="search">
                    @foreach($contacts as $contactDetail)
                        @php
                            $selected = ''; // Initialize variable to hold 'selected' attribute
                            if (isset($email['toEmail'])&&(!is_null($email['toEmail']))) {
                                foreach ($email['toEmail'] as $currEmail) {
                                    if (
                                        (string)$contactDetail['id'] ===
                                        $currEmail
                                    ) {
                                        $selected = 'selected'; // If IDs match, mark the option as selected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $contactDetail['id'] }}" data-email="{{ $contactDetail['email'] }}" {{$selected}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
                    @endforeach
                </select>
                <span id="emailErrorDraftTo" style="color: red; display: none;">Please enter a valid email address.</span>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">CC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="ccDraftSelect" multiple="multiple"
                    data-placeholder="CC">
                    @foreach($contacts as $contactDetails)
                        @php
                            $ccSelected = ''; // Initialize variable to hold 'ccSelected' attribute
                            if (isset($email['ccEmail'])&&(!is_null($email['ccEmail']))) {
                                foreach ($email['ccEmail'] as $currEmail) {
                                    if (
                                        (string)$contactDetails['id'] ===
                                        $currEmail
                                    ) {
                                        $ccSelected = 'selected'; // If IDs match, mark the option as ccSelected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $contactDetails['id'] }}" data-email="{{ $contactDetail['email'] }}" {{$ccSelected}}>{{$contactDetails['first_name']}} {{$contactDetails['last_name']}}</option>
                    @endforeach
                </select>
                <span id="emailErrorDraftCC" style="color: red; display: none;">Please enter a valid email address.</span>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">BCC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="bccDraftSelect" multiple="multiple"
                    data-placeholder="BCC">
                    @foreach($contacts as $contactDetail)
                        @php
                            $bccSelected = ''; // Initialize variable to hold 'selected' attribute
                            if (isset($email['bccEmail'])&&(!is_null($email['bccEmail']))) {
                                foreach ($email['bccEmail'] as $currEmail) {
                                    if (
                                        (string)$contactDetail['id'] ===
                                        $currEmail
                                    ) {
                                        $bccSelected = 'selected'; // If IDs match, mark the option as selected
                                        break; // Exit loop once a match is found
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $contactDetail['id'] }}" data-email="{{ $contactDetail['email'] }}" {{$bccSelected}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
                    @endforeach
                </select>
                <span id="emailErrorDraftBCC" style="color: red; display: none;">Please enter a valid email address.</span>
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
    <button type="button" class="btn btn-dark" onclick="sendDraftEmails(this,{{isset($email)?json_encode($email):null}},false)">Save as draft <i class="fab fa-telegram-plane ms-1"></i></button>
    <button type="button" class="btn btn-dark" id="modalTemplate" onclick="return openDraftTemplate()">Save as template</button>
    <button type="button" class="btn btn-dark" onclick="sendDraftEmails(this,{{isset($email)?json_encode($email):null}},true)">Send <i class="fab fa-telegram-plane ms-1"></i></button>
</div>


<script>
    var emailType = @json($emailType??"");
    $(document).ready(function(){
        function initializeSelect2(selector, placeholder, errorId) {
            $(selector).select2({
                placeholder: placeholder,
                allowClear: true,
                tags: true,
                dropdownParent: $('#draftModal'),
                createTag: function(params) {
                    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    var isDuplicate = false;
                    var allOptions = $(selector).find('option').map(function() {
                        return $(this).data('email');
                    }).get();
                    
                    // Check if the entered value already exists
                    if (allOptions.includes(params.term.toLowerCase())) {
                        isDuplicate = true;
                    }
                    
                    if (isDuplicate) {
                        return null; // Ignore duplicate tags
                    }
                    
                    if (!emailPattern.test(params.term)) {
                        $("#" + errorId).show();
                        return null; // Ignore invalid email
                    }
                    
                    $("#" + errorId).hide();
                    return {
                        id: params.term,
                        text: params.term,
                        newTag: true
                    };
                },
                matcher: function(params, data) {
                    // If there are no search terms, return all data
                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    // Convert the search term to lower case for case-insensitive matching
                    var term = params.term.toLowerCase();

                    // Check if the term matches the name or email
                    var text = data.text.toLowerCase();
                    var email = $(data.element).data('email') || '';

                    if (text.includes(term) || email.includes(term)) {
                        return data;
                    }

                    // If no match, return null to exclude this item
                    return null;
                },
                templateResult: function(data) {
                    // Format the result to display both name and email (if available)
                    var email = $(data.element).data('email') || '';
                    if (email) {
                        return $('<span>' + data.text + ' (' + email + ')</span>');
                    }
                    return $('<span>' + data.text + '</span>');
                }
            });
        }

        function updateSelectOptions() {
            var toValues = $("#toDraftSelect").val() || [];
            var ccValues = $("#ccDraftSelect").val() || [];

            // Filter ccSelect options based on toSelect values
            $("#ccDraftSelect option").each(function() {
                if (toValues.includes($(this).val())) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });

            // Filter bccSelect options based on ccSelect values
            $("#bccDraftSelect option").each(function() {
                if (toValues.includes($(this).val()) || ccValues.includes($(this).val())) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
        }
            $("#toDraftSelect, #ccDraftSelect").on('change', function() {
                updateSelectOptions();
                $(this).trigger('select2:select'); // Trigger the select2:select event instead of change
            });

        // Initialize Select2 for all select elements
        initializeSelect2("#toDraftSelect", "To", "emailErrorDraftTo");
        initializeSelect2("#ccDraftSelect", "CC", "emailErrorDraftCC");
        initializeSelect2("#bccDraftSelect", "BCC", "emailErrorDraftBCC");

        // Ensure Select2 dropdowns work within modals
        $('#composemodal').on('shown.bs.modal', function () {
            initializeSelect2("#toDraftSelect", "To", "emailErrorDraftTo");
            initializeSelect2("#ccDraftSelect", "CC", "emailErrorDraftCC");
            initializeSelect2("#bccDraftSelect", "BCC", "emailErrorDraftBCC");
        });

        $("#toDraftSelect, #ccDraftSelect").on('change', updateSelectOptions);

        $("#toDraftSelect, #ccDraftSelect, #bccDraftSelect").on('select2:select change', function(e) {
            var suffix = '';
            switch ($(this).attr('id')) {
                case 'toDraftSelect':
                    suffix = 'DraftTo';
                    break;
                case 'ccDraftSelect':
                    suffix = 'DraftCC';
                    break;
                case 'bccDraftSelect':
                    suffix = 'DraftBCC';
                    break;
            }
            $("#emailError" + suffix).hide();
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

    function validateForm() {
        let isValidate = true;
        const to = $("#toDraftSelect").val();
        const content = tinymce.get('draftEmailEditor').getContent();
        const subject = $("#emailDraftSubject").val();

        const fields = [
            { value: to, message: "Please enter To emails" },
            { value: content, message: "Please enter content" },
            { value: subject, message: "Please enter subject" }
        ];

        for (const field of fields) {
            if (field.value === '' || field.value.length === 0) {
                showToastError(field.message);
                isValidate = false;
                break;
            }
        }

        return isValidate;
    }

    window.sendDraftEmails = function(button,email,isEmailSent){
        
        var to = $("#toDraftSelect").val();
        var cc = $("#ccDraftSelect").val();
        var bcc = $("#bccDraftSelect").val();
        var content = tinymce.get('draftEmailEditor').getContent();
        var subject = $("#emailDraftSubject").val();
        let isValidate = validateForm();
        if(!isValidate){
            return false;
        };
        button.disabled = true;
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

       
        if(emailType=="multiple"){
             $.ajax({
                url: "{{ route('send.multiple.email') }}",
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
                    if(isEmailSent){
                        showToast("Email sent successfully");
                    }else{

                        showToast("Draft saved successfully");
                    }
                    button.disabled = false;
                    $("#emaildraftModalClose").click();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                    showToastError(xhr.responseText);
                    $("#emaildraftModalClose").click();

                }
            });
        }else{
            
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
                    if(isEmailSent){
                        showToast("Email sent successfully");
                    }else{

                        showToast("Draft saved successfully");
                    }
                    $("#contact-email-table").DataTable().ajax.reload();
                    button.disabled = false;
                    $("#emaildraftModalClose").click();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                    showToastError(xhr.responseText);
                    $("#emaildraftModalClose").click();

                }
            });
        }
    }

    window.validateOpenDraftTemplate = function(){
        var content = tinymce.get('draftEmailEditor').getContent();
        var subject = $("#emailDraftSubject").val();
        let isValidateTemplate = true
        

        if(content==""){
            showToastError("Please enter content");
            isValidateTemplate = false;
            
        }

        if(subject==""){
            showToastError("Please enter subject");
            isValidateTemplate = false;
            
        }

        return isValidateTemplate
    }
    window.openDraftTemplate = function(){
    if (validateOpenDraftTemplate()) {
        var content = tinymce.get('draftEmailEditor').getContent();
        var subject = $("#emailDraftSubject").val();
        $("#templateSubject").val(subject);
        $("#templateContent").val(content);
        $('#draftModal').modal('hide');
        $('#templateModal').modal('show'); // Open the modal if validation passes
        $("#templateModal").removeClass("compose");
       $("#templateModal").addClass("draft");

    }
}
    
</script>
            