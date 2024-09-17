<div class="modal-header">
    <h5 class="modal-title" id="templateModalTitle">New Template</h5>
    <button type="button" class="btn-close" id="templateClose" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div id="modal-data">
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">Name</label>
            <div class="col-md-10">
                <input type="text" id = "templateName" value="" class="form-control" placeholder="Template Name">
            </div>
        </div>
        <div class="mb-3 row" style="display:none;">
            <label for="example-text-input" class="col-md-2 col-form-label">Content</label>
            <div class="col-md-10">
                <input type="text" id = "templateContent" value="" class="form-control" placeholder="Template Name">
            </div>
        </div>
        <div class="mb-3 row" style="display:none;">
            <label for="example-text-input" class="col-md-2 col-form-label">Subject</label>
            <div class="col-md-10">
                <input type="text" id = "templateSubject" value="" class="form-control" placeholder="Template Name">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" id="closeAndOpenModal">Close</button>
    <button type="button" class="btn btn-dark" onclick="saveTemplate()">Save as template <i class="fab fa-telegram-plane ms-1"></i></button>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
    $("#closeAndOpenModal").click(function() {
        if ($("#templateModal").hasClass("draft")) {
            // Hide the current modal
            $("#templateModal").modal('hide');
            // Show the draft modal
            $("#draftModal").modal('show');
        } else {
            // Hide the current modal
            $("#templateModal").modal('hide');
            // Show the compose modal
            $("#composemodal").modal('show');
        }
    });
});
    window.validateTemplate = function(){
        var templateName = $("#templateName").val();
        var content = $("#templateContent").val();
        var subject = $("#templateSubject").val();
        let isValidateTemplate = true
        if(templateName==""){
            showToastError("Please enter template name");
            isValidateTemplate = false
        }

        if(content==""){
            showToastError("Please enter template name");
            isValidateTemplate = false

        }

        if(subject==""){
            showToastError("Please enter template name");
            isValidateTemplate = false

        }

        return isValidateTemplate
    }
    window.saveTemplate = function(email,isEmailSent){
        var templateName = $("#templateName").val();
        var content = $("#templateContent").val();
        var subject = $("#templateSubject").val();

        if(!validateTemplate()){
            return false;
        }
        var formData = 
        {
            "ownerId": "{{auth()->user()->id}}",
            "content": content,
            "name":templateName,
            'subject':subject,
            'templateType':'private'
        }

        $.ajax({
            url: "{{ route('create.template') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                console.info(response);
                if (response.status&&response.status === 'process') {
                    showToastError(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 5000); // Adjust the delay as needed
                }else{
                    showToast("Template added successfully");
                } 
                $("#templateClose").click();
                $("#emailModalClose").click();
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                showToastError(xhr.responseText);
            }
        });
    }
</script>