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
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-dark" onclick="saveTemplate()">Save as template <i class="fab fa-telegram-plane ms-1"></i></button>
</div>

<script>
    window.saveTemplate = function(email,isEmailSent){
        var templateName = $("#templateName").val();
        var content = $("#templateContent").val();
        var formData = 
        {
            "ownerId": "{{auth()->user()->id}}",
            "content": content,
            "templateName":templateName
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
                if (response.status === 'process') {
                    showToastError(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 5000); // Adjust the delay as needed
                } else {
                    // Handle error
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