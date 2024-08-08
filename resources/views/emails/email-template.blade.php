<script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>
<div class="email-rightbar mb-3">

    <div class="card">
        <div class="card-body">
            <form method="post">
                <textarea class="form-control" id="template" name="area"></textarea>
            </form>
            <a href="javascript: void(0);" class="btn btn-secondary waves-effect mt-4"><i class="mdi mdi-save"></i> Save</a>
        </div>
    </div>
</div>
<script>
    tinymce.init({
            selector: 'textarea#template',
            plugins: 'lists, link, image, media',
            toolbar: 'h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link image media | removeformat help',
            menubar: false,
            statusbar:false
        });
</script>