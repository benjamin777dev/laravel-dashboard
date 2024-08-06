<script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>

        <div class="card-body">
            <div class="mb-3 row">
                <label for="templateSubject" class="col-md-2 col-form-label">Subject</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" value="{{$templateDetail['subject']}}" id="templateSubject{{$templateDetail['id']}}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="templateContent" class="col-md-2 col-form-label">Content</label>
                <div class="col-md-10">
                    <form method="post">
                        <textarea class="form-control" id="templateContent{{$templateDetail['id']}}" name="area">{{ $templateDetail['content'] }}</textarea>
                    </form>
                </div>
            </div>
        </div>

<script>
    var template = @json($templateDetail);
    $(document).ready(function(){
        tinymce.init({
            selector: `textarea#templateContent${template.id}`,
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
    })
</script>