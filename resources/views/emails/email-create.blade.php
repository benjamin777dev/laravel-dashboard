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
                    @foreach($contacts as $contactDetail)
                        <option value="{{ $contactDetail['id'] }}"
 {{$contactDetail['email']==(isset($contact['email'])?$contact['email']:false)?'selected':''}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
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
                        <option value="{{ $contactDetail['id'] }}"
 {{$contactDetail['email']==(isset($contact['email'])?$contact['email']:false)?'selected':''}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
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
                        <option value="{{ $contactDetail['id'] }}"
 {{$contactDetail['email']==(isset($contact['email'])?$contact['email']:false)?'selected':''}}>{{$contactDetail['first_name']}} {{$contactDetail['last_name']}}</option>
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
            console.log("HIdden BS",modalData );
            if (modalData) {
                modalData.querySelectorAll('input,  textarea').forEach(function (element) {
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
        
        var secondModalEl = document.getElementById('templateModal');
        secondModalEl.addEventListener('hidden.bs.modal', function () {
            var firstModal = new bootstrap.Modal(document.getElementById('composemodal'));
            firstModal.show();
        });
    })

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
        $('#templateContent').val(templateContent);
        var secondModal = new bootstrap.Modal(document.getElementById('templateModal'));
        secondModal.show();
    }
</script>
            