<script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>
<div class="modal-header">
    <h5 class="modal-title" id="composemodalTitle">New Email</h5>
    <button type="button" class="btn-close" id="emailModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div id="modal-data">
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">To</label>
            <div class="col-md-10" id="toSelectDropdown">
                
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">CC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="ccSelect" multiple="multiple"
                    data-placeholder="CC" type="search">
                    
                </select>
                <span id="emailErrorCC" style="color: red; display: none;">Please enter a valid email address.</span>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 cmultipleol-form-label">BCC</label>
            <div class="col-md-10">
                <select class="select2 form-control select2-multiple" id="bccSelect" multiple="multiple"
                    data-placeholder="BCC" type="search">
                    
                </select>
                <span id="emailErrorBCC" style="color: red; display: none;">Please enter a valid email address.</span>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">Subject</label>
            <div class="col-md-10">
                <input type="text" id = "emailSubject" value="" class="form-control validate" placeholder="Subject">
            </div>
        </div>
        <div class="mb-3 row">
                <label for="example-text-input" class="col-md-2 col-form-label">Message</label>
            <form method="post">
                <textarea class="form-control" class="validate" id="elmEmail" name="area"></textarea>
            </form>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" id = "emailModalClose" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-dark" onclick="return sendEmails(this,null,false)">Save as draft</button>
    <button type="button" class="btn btn-dark" id="modalTemplate" onclick="return openTemplate()">Save as template</button>
    <button type="button" class="btn btn-dark" onclick="return sendEmails(this,null,true)">Send <i class="fab fa-telegram-plane ms-1"></i></button>
</div>

<script src="https://cdn.jsdelivr.net/npm/gif.js/dist/gif.js"></script>
<script>
    let currentLocation = window.location.pathname.split("/").pop();
    var emailType = "";
    if(currentLocation == "contacts" || currentLocation == "group") {
        emailType = "multiple";
    }
    // var emailType = @json($emailType??"");
    $(document).ready(function() {
        // Initialize Select2 for all select elements
        function initializeSelect2(selector, placeholder, errorId) {
            console.log("Initialize fucntion");
            
            $(selector).select2({
                placeholder: placeholder,
                allowClear: true,
                tags: true,
                dropdownParent: $('#composemodal'),
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
                    
                    var term = params.term.toLowerCase();
                    var text = data.text.toLowerCase();
                    var email = $(data.element).data('email') || '';
                    
                    if (text.includes(term) || email.includes(term)) {
                        return data;
                    }
                    
                    return null; // No match
                },
                templateResult: function(data) {
                    var email = $(data.element).data('email') || '';
                    if (email) {
                        return $('<span>' + data.text + ' (' + email + ')</span>');
                    }
                    return $('<span>' + data.text + '</span>');
                },
                
            });
            

        }

        function updateSelectOptions() {
            console.log("Update fucntion");
            var toValues = $("#toSelect").val() || [];
            var ccValues = $("#ccSelect").val() || [];
            var bccValues = $("#bccSelect").val() || [];
            console.log("BEFORE toValues ccValues",toValues,ccValues);
           
            $("#ccSelect option").each(function() {
                const value = $(this).val();
                if (toValues.includes(value)||bccValues.includes(value)) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
            
            $("#bccSelect option").each(function() {
                const value = $(this).val();
                if (toValues.includes(value) || ccValues.includes(value)) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });
        }

        $("#toSelect, #ccSelect","bccSelect").on('change', function() {
            updateSelectOptions();
            $(this).trigger('select2:select'); // Trigger the select2:select event instead of change
        });

        initializeSelect2("#toSelect", "To", "emailErrorTo");
        initializeSelect2("#ccSelect", "CC", "emailErrorCC");
        initializeSelect2("#bccSelect", "BCC", "emailErrorBCC");

        $('#composemodal').on('shown.bs.modal', function() {
            initializeSelect2("#toSelect", "To", "emailErrorTo");
            initializeSelect2("#ccSelect", "CC", "emailErrorCC");
            initializeSelect2("#bccSelect", "BCC", "emailErrorBCC");
        });

        
        $("#toSelect, #ccSelect").on('change', updateSelectOptions);
        $("#toSelect, #ccSelect, #bccSelect").on('select2:select change', function(e) {
            var suffix = '';
            switch ($(this).attr('id')) {
                case 'toSelect':
                    suffix = 'To';
                    break;
                case 'ccSelect':
                    suffix = 'CC';
                    break;
                case 'bccSelect':
                    suffix = 'BCC';
                    break;
            }
            $("#emailError" + suffix).hide();
        });


        // Initialize TinyMCE
        tinymce.init({
            selector: 'textarea#elmEmail',
            plugins: 'lists link image media preview',
            toolbar: 'h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link image media | removeformat help | customSelect recordVideo',
            menubar: false,
            statusbar: false,
            setup: function(editor) {
                editor.ui.registry.addButton('customSelect', {
                    text: 'Select Template',
                    onAction: function() {
                        $.ajax({
                            url: '/get/templates',
                            method: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                var items = response.map(function(item) {
                                    return { text: item.name ?? "", value: JSON.stringify(item.id) ?? "" };
                                });

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
                                    onSubmit: function(api) {
                                        var data = api.getData();
                                        var selectedOption = data.options;
                                        console.log(selectedOption);
                                        $.ajax({
                                            url: '/get/template/detail/' + selectedOption,
                                            method: 'GET',
                                            success: function(response) {
                                                $("#emailSubject").val(response.subject);
                                                editor.insertContent(response.content);
                                                api.close();
                                            },
                                            error: function() {
                                                showToastError('Failed to submit the selected option');
                                            }
                                        });
                                    }
                                });
                            },
                            error: function() {
                                showToastError('Failed to fetch options');
                            }
                        });
                    }
                });

                editor.ui.registry.addButton('recordVideo', {
                    text: 'Record Video',
                    onAction: function() {
                        editor.windowManager.open({
                                    title: 'Record Video',
                                    size: 'medium',
                                    body: {
                                        type: 'panel',
                                        items: [
                                            {
                                                type: 'htmlpanel',
                                                html: `
                                                    <div id="recordVideoModalContent" style="justify-content: space-around; margin-bottom: 10px;">
                                                        <figure style="display: inline-block;">
                                                            <video id="videoRecording" style = "width: 480px; height: 360px; background-color: black" autoplay></video>
                                                            <video id="recordedVideo" style = "width: 480px; height: 360px; display: none" controls></video>
                                                            <figcaption style="text-align: center;">Preview Record</figcaption>

                                                            <div style="display: flex; margin-top: 20px; justify-content: space-around">
                                                                <button class="btn" type="button" id="startRecordButton">Start Recording</button>
                                                                <button type="button" id="stopRecordButton">Stop Recording</button>
                                                            </div>
                                                        </figure>
                                                        
                                                        <figure style="display: inline-block; width: 240px; justify-content: space-around;">
                                                            <img id="snapshotImage" style="width: 240px; height: 180px; background-color: black">
                                                            <canvas id="snapshotCanvas" width="240" height="180" style="display:none;"></canvas>
                                                            <img id="gifImage" alt="Generated GIF" style="display:block; width: 240px; height: 180px; background-color: black">
                                                            <figcaption style="text-align: center;">Preview Gif / Thumbnail</figcaption>
                                                            <div style="display: flex; margin-top: 20px; justify-content: space-around">
                                                                <button type="button" id="cropButton">Crop</button>
                                                            </div>
                                                        </figure>
                                                    </div>
                                                `
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
                                    onSubmit: function(api) {
                                        const videoElementUrl = document.getElementById('recordedVideo').src;
                                        const imgElementUrl = document.getElementById('snapshotImage').src;
                                        const gifElementUrl = document.getElementById('gifImage').src;

                                        content = `<video id="recordedVideo" width="480" height="360" src="${videoElementUrl}" controls></video>`;
                                        editor.insertContent(content);
                                        api.close();
                                    }
                                });
                                let mediaRecorder;
                                let recordedBlobs;
                                const videoElement = document.getElementById('videoRecording');
                                const recordedVideoElement = document.getElementById('recordedVideo');
                                const snapshotCanvas = document.getElementById('snapshotCanvas');
                                const snapshotImage = document.getElementById('snapshotImage');
                                const gifImage = document.getElementById('gifImage');
                                const cropButton = document.getElementById('cropButton');

                                document.getElementById('startRecordButton').addEventListener('click', async () => {
                                    videoElement.style.display = "block";
                                    recordedVideoElement.style.display = "none";
                                    const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                                    videoElement.srcObject = stream;
                                    recordedBlobs = [];

                                    mediaRecorder = new MediaRecorder(stream);
                                    mediaRecorder.ondataavailable = (event) => {
                                        if (event.data.size > 0) {
                                            recordedBlobs.push(event.data);
                                        }
                                    };
                                    mediaRecorder.onstop = function() {
                                        const blob = new Blob(recordedBlobs, { type: 'video/mp4' });
                                        const videoURL = URL.createObjectURL(blob);
                                        recordedVideoElement.src = videoURL;
                                    }
                                    mediaRecorder.start();
                                    document.getElementById('stopRecordButton').disabled = false;
                                    document.getElementById('startRecordButton').disabled = true;
                                });

                                document.getElementById('stopRecordButton').addEventListener('click', () => {
                                    mediaRecorder.stop();
                                    videoElement.style.display = "none";
                                    recordedVideoElement.style.display = "block";
                                    document.getElementById('stopRecordButton').disabled = true;
                                    document.getElementById('startRecordButton').disabled = false;
                                    // document.getElementById('createGifButton').disabled = false;
                                });
                                
                                document.getElementById("cropButton").addEventListener('click', () => {

                                    captureThumbnail(recordedVideoElement, 1);
                                    convertVideoToGif(recordedVideoElement.src)
                                    .then(function(gifBlob) {
                                        const gifUrl = URL.createObjectURL(gifBlob);
                                        gifImage.src = gifUrl;
                                        gifImage.style.display = 'block';
                                    }).catch(function(error) {
                                        console.error('Error creating GIF:', error);
                                    });
                                })

                                async function captureThumbnail(videoElement, captureTime) {
                                    videoElement.currentTime = captureTime;
                                    
                                    videoElement.onseeked = function () {
                                        snapshotCanvas.width = videoElement.videoWidth;
                                        snapshotCanvas.height = videoElement.videoHeight;
                                        const ctx = snapshotCanvas.getContext('2d');
                                        ctx.drawImage(videoElement, 0, 0, snapshotCanvas.width, snapshotCanvas.height);
                                        const dataUrl = snapshotCanvas.toDataURL('image/png');

                                        snapshotImage.src = dataUrl;
                                    };

                                    

                                }

                                
                            }
                });
            }
        });

       $('#toSelect').trigger('change');
        $('#ccSelect').trigger('change');
        $('#bccSelect').trigger('change');

        const pathname = window.location.pathname;

        const pathSegments = pathname.split('/');

        const secondSegment = pathSegments[1];

        console.log(secondSegment);
       
            
       
        var button = document.getElementById('emailModalClose');
        button.addEventListener('click', function () {
            var modal = document.getElementById('composemodal');
            
            var modalData = document.getElementById('modal-data');

            modal.addEventListener('hidden.bs.modal', function () {
                if (modalData) {
                    if (secondSegment === 'contacts-view') {
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
                    else{
                        modalData.querySelectorAll('input, textarea,select').forEach(function (element) {
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
                }
            });
        });
    });

    function validateForm() {
        let isValidate = true;
        const to = $("#toSelect").val();
        const cc = $("#ccSelect").val();
        const bcc = $("#bccSelect").val();
        const content = tinymce.get('elmEmail').getContent();
        const subject = $("#emailSubject").val();

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



    window.sendEmails = function(button,email,isEmailSent){
        
        var to = $("#toSelect").val();
        var cc = $("#ccSelect").val();
        var bcc = $("#bccSelect").val();
        var content = tinymce.get('elmEmail').getContent();
        var subject = $("#emailSubject").val();
        let isValidate = validateForm();
        console.log("checkvalues",to,cc,bcc, window.ccValuestesttest);
        if(!isValidate){
            return false;
        };
        button.disabled = true;

        function fetchBlobFromUrl(url) {
            return fetch(url).then(function(response) {
                if(response.ok) {
                    return response.blob();
                } else {
                    return null;
                }
            });
        }

        sendVideoRequest = function(formData) {
            $.ajax({
                url: "{{ route('send.email') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                data: formData,
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

                    if ($("#contact-email-table").length) {
                        $("#contact-email-table").DataTable().ajax.reload();
                    }
                    button.disabled = false;
                    fetchEmails();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                    showToastError(xhr.responseText);
                }
            });
            $("#emailModalClose").click();
        }

        var formData = 
        {
            "to": to,
            "cc": cc,
            "bcc": bcc,
            "subject": subject,
            
            "content": content,
            "isEmailSent":isEmailSent
        }
        const recordData = new FormData();
        for(const key in formData) {
            if(formData.hasOwnProperty(key)) {
                recordData.append(key, formData[key]);
            }
        }

        recordData.append("emailType", emailType);

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = content;
        if(tempDiv.querySelector("#recordedVideo")) {
            let recordedVideoUrl = tempDiv.querySelector("#recordedVideo").src;
            fetchBlobFromUrl(recordedVideoUrl).then(function(videoBlob) {
                if (videoBlob) {
                    recordData.append("recordedVideo", videoBlob);
                    sendVideoRequest(recordData);
                } else {
                    console.log("Error attach video.");
                }
            });
            
        } else {
            sendVideoRequest(recordData);
        }
        $("#emailModalClose").click();

    }
    window.validateOpenTemplate = function(){
        var content = tinymce.get('elmEmail').getContent();
        var subject = $("#emailSubject").val();
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
    window.openTemplate = function(){
        if (validateOpenTemplate()) {
            var content = tinymce.get('elmEmail').getContent();
            var subject = $("#emailSubject").val();
            $("#templateSubject").val(subject);
            $("#templateContent").val(content);
            $('#composemodal').modal('hide');
            $('#templateModal').modal('show'); // Open the modal if validation passes
            $("#templateModal").removeClass("draft");
        $("#templateModal").addClass("compose");

        }
    }

    function convertVideoToGif(videoUrl) {
        return new Promise((resolve) => {
            const tempVideo = document.createElement('video');
            tempVideo.src = videoUrl;

            tempVideo.addEventListener('loadeddata', () => {
                const gif = new GIF({
                    workers: 2,
                    quality: 20,
                    width: tempVideo.videoWidth,
                    height: tempVideo.videoHeight,
                    workerScript: '/build/gif.worker.js',
                    useWebWorkers: false,
                });

                const canvas = document.createElement('canvas');
                canvas.width = tempVideo.videoWidth;
                canvas.height = tempVideo.videoHeight;
                const ctx = canvas.getContext('2d', { willReadFrequently: true });

                let isRendering = false;
                let frameCount = 0;
                tempVideo.currentTime = 0;

                tempVideo.addEventListener('timeupdate', () => {
                    if (tempVideo.currentTime <= 3 && !isRendering) { // Capture up to 5 seconds
                        ctx.drawImage(tempVideo, 0, 0, canvas.width, canvas.height);
                        gif.addFrame(canvas, { copy: true, delay: 200 }); // Adjust delay as needed
                    } else if(!isRendering) {
                        tempVideo.pause();
                        isRendering = true;
                        try {
                            gif.render();
                        } catch (err) {
                            console.log("Rendering Error:", err);
                            reject(err);
                        }
                    }
                });

                gif.on('finished', (blob) => {
                    resolve(blob);
                });

                gif.on('abort', () => {
                    reject(new Error('GIF rendering was aborted.'));
                });

                gif.on('error', (error) => {
                    reject(error);
                });

                tempVideo.play();
            });
        });
    }

    function renderContactsDropdown(contacts, selectedContacts) {
        let dropdownContainer = document.getElementById('toSelectDropdown');
        const selectElement = document.createElement('select');
        
        selectElement.className = "select2 form-control select2-multiple";
        selectElement.id = "toSelect";
        selectElement.multiple = true;
        selectElement.setAttribute('data-placeholder', 'To');
        selectElement.type = 'search';
        if (selectedContacts.length > 0) {
            selectElement.disabled = true;
        }

        contacts.forEach(contactDetail => {
            const option = document.createElement('option');
            option.value = contactDetail.id;
            option.setAttribute('data-email', contactDetail.email);
            option.text = contactDetail.first_name + contactDetail.last_name;

            if (selectedContacts.some(selectedContact => selectedContact.id == contactDetail.id)) {
                option.selected = true;
            }
            if (!contactDetail.email) {
                option.disabled = true;
            }
            selectElement.appendChild(option);
        });

        dropdownContainer.innerHTML = '';
        dropdownContainer.appendChild(selectElement);
        dropdownContainer += `<span id="emailErrorTo" style="color: red; display: none;">Please enter a valid email address.</span>`;

        $('#toSelect').select2({
            placeholder: "To"
        });
    }

    function renderOptions(contacts, elem) {
        elem.innerHTML = '';

        // Loop through the contacts array and create options
        contacts.forEach(contactDetail => {
            // Create a new option element
            const option = document.createElement('option');
            option.value = contactDetail.id;
            option.text = contactDetail.first_name + contactDetail.last_name;
            option.setAttribute('data-email', contactDetail.email);
            // Check this condition (Is this necessary?)
            if(!contactDetail.email) {
                option.disabled = true;
            }

            // Disable the option if the contact has no email
            if (!contactDetail.email) {
                option.disabled = true;
            }
            elem.appendChild(option);
        });
    }
</script>
            