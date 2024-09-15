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
                editor.ui.registry.addIcon('recordIcon', 
                    '<i class="mdi mdi-record-circle-outline fs-3"></i>'
                );
                editor.ui.registry.addButton('customSelect', {
                    icon: 'gallery',
                    tooltip: 'Select Template',
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
                    icon: 'recordIcon',
                    tooltip: `Record Video`,
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
                                                <select class="mb-2" id="videoInputSource"></select>
                                                <div class="mb-3" id="recordVideoInterface">
                                                    <div class="position-relative d-flex justify-content-center">
                                                        <div id="recordVideoModalContent" class="d-flex position-relative">
                                                            <div class="d-inline-block">
                                                                <video id="videoRecording" style = "width: 480px; height: 360px; background-color: black" autoplay></video>
                                                                <video id="recordedVideo" style = "width: 480px; height: 360px; display: none" controls></video>
                                                            </div>
                                                            <div class="d-inline-block position-absolute">
                                                                <button class="btn" type="button" id="startRecordButton"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-title="This top tooltip is themed via CSS variables.">
                                                                    <i class="mdi mdi-play-circle-outline"></i>
                                                                </button>
                                                                <button type="button" id="stopRecordButton"><i class="mdi mdi-stop-circle-outline"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a class="mt-2" data-bs-toggle="collapse" href="#v-pills-profile" role="button" aria-expanded="false" aria-controls="v-pills-profile">
                                                    Set Fallback Image / GIF. (Optional)
                                                </a>
                                                <div class="collapse mt-2" id="v-pills-profile">
                                                    <div class = "d-block card card-body p-3">
                                                        <div id="imgUploadContainer" class="d-flex justify-content-around">
                                                            <div class="position-relative text-center">
                                                                <label class="image-upload-wrapper">
                                                                    <input type="file" id="formFile" accept="image/jpeg, img/png, img/webp">
                                                                    <span class="upload-placeholder">Click to upload an image</span>
                                                                    <img id="imagePreview" src="#" alt="Image Preview">
                                                                </label>
                                                            </div>
                                                            <div class="position-relative text-center">
                                                                <label class="image-upload-wrapper">
                                                                    <input type="file" id="formFileGIF" accept="image/gif">
                                                                    <span class="upload-placeholderGIF">Click to upload a GIF</span>
                                                                    <img id="imagePreviewGIF" src="#" alt="Image Preview">
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <canvas id="snapshotCanvas" width="240" height="180" style="display:none;"></canvas>
                                                        <div style="display: flex; margin-top: 20px; justify-content: space-around">
                                                            <button type="button" id="cropButton" class="px-5 py-2">Create Img/GIF for me</button>
                                                        </div>
                                                    </div>
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
                                        let recordData = new FormData();

                                        $('.tox-button[title="Insert"]')[0].disabled = true;
                                        const recordedVideoElement = document.getElementById('recordedVideo');
                                        const videoElementUrl = recordedVideoElement.src;
                                        let imgElementUrl = document.getElementById('imagePreview').src;
                                        let gifElement = document.getElementById('imagePreviewGIF');
                                        let gifElementUrl = gifElement.src;

                                        if(imgElementUrl.slice(-1) == "#") {
                                            captureThumbnail(recordedVideoElement, 1)
                                            .then((url) => {
                                                imgElementUrl = url;
                                                if(gifElementUrl.slice(-1) == "#") {
                                                    convertVideoToGif(videoElementUrl)
                                                    .then(gifURL => {
                                                        gifElement.src = gifURL;
                                                        gifImage.style.display = 'block';
                                                        document.querySelector('.upload-placeholderGIF').style.display = "none";
                                                        submitFunc();
                                                    })
                                                    .catch(error => {
                                                        showToastError("Failed to generate Img/GIF");
                                                        $('.tox-button[title="Insert"]')[0].disabled = false;
                                                        api.close();
                                                    })
                                                } else {
                                                    submitFunc();
                                                }
                                            })
                                            .catch(err => {
                                                showToastError("Failed to generate Img/GIF");
                                                $('.tox-button[title="Insert"]')[0].disabled = false;
                                                api.close();
                                            })
                                        } else {
                                            if(gifElementUrl.slice(-1) == "#") {
                                                convertVideoToGif(videoElementUrl)
                                                .then(gifURL => {
                                                    gifElement.src = gifURL;
                                                    submitFunc();
                                                })
                                                .catch(error => {
                                                    showToastError("Failed to generate preview GIF.");
                                                    api.close();
                                                })
                                            } else {
                                                submitFunc();
                                            }
                                        }
                                        

                                        function submitFunc()
                                        {
                                            fetchBlobFromUrl(videoElementUrl).then(function(videoBlob) {
                                                if (videoBlob) {
                                                    recordData.append('video', videoBlob, 'video.webm');
                                                    processGIF();
                                                } else {
                                                    showToastError("Failed to fetch video.");
                                                    
                                                }
                                            });

                                            function processGIF() {
                                                fetchBlobFromUrl(gifElementUrl).then(function(gifBlob) {
                                                    if (gifBlob) {
                                                        recordData.append('gif', gifBlob, 'animation.gif');
                                                        processImage();
                                                    } else {
                                                        showToastError("Failed to generate preview GIF.");
                                                    }
                                                });
                                            }

                                            function processImage() {
                                                fetchBlobFromUrl(imgElementUrl).then(function(imageBlob) {
                                                    if (imageBlob) {
                                                        recordData.append('img', imageBlob, 'image.png');
                                                        sendData();
                                                    } else {
                                                        showToastError("Failed to generate preview image.");
                                                    }
                                                });
                                            }
                                            function sendData() {
                                                $.ajax({
                                                    url: route('video.upload'),
                                                    method: "POST",
                                                    headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },
                                                    data: recordData,
                                                    processData: false,
                                                    contentType: false,
                                                    success: function (data) {
                                                        contentWithFallback = data;
                                                        if(data.message) {
                                                            showToastError(data.message);
                                                        } else {
                                                            editor.insertContent(contentWithFallback);
                                                            $('.tox-button[title="Insert"]')[0].disabled = false;
                                                            api.close();
                                                        }
                                                        },
                                                        error: function (err) {
                                                            showToastError("Failed to upload files to S3.");
                                                        }
                                                        })                                                
                                            }
                                        }
                                    }
                                });
                                let mediaRecorder;
                                let recordedBlobs;
                                let currentStream;
                                const videoElement = document.getElementById('videoRecording');
                                const recordedVideoElement = document.getElementById('recordedVideo');
                                const snapshotCanvas = document.getElementById('snapshotCanvas');
                                const snapshotImage = document.getElementById('imagePreview');
                                const gifImage = document.getElementById('imagePreviewGIF');
                                const cropButton = document.getElementById('cropButton');
                                const startButton = document.getElementById('startRecordButton');
                                const stopButton = document.getElementById('stopRecordButton');

                                function startVideoStream(deviceId) {
                                    if (currentStream) {
                                        currentStream.getTracks().forEach(track => track.stop());
                                    }

                                    navigator.mediaDevices.getUserMedia({ 
                                        video: { deviceId: deviceId ? { exact: deviceId } : undefined }, 
                                        audio: true 
                                    })
                                    .then((stream) => {
                                        currentStream = stream;
                                        videoElement.srcObject = stream;
                                    })
                                    .catch((error) => {
                                        showToast("Failed to access media devices. Please allow the permission.");
                                    });
                                }

                                const videoInputSource = document.getElementById("videoInputSource");
                                navigator.mediaDevices.enumerateDevices()
                                .then((devices) => {
                                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                                    videoDevices.forEach((device, index) => {
                                        const option = document.createElement('option');
                                        option.value = device.deviceId;
                                        option.text = device.label || `Camera ${index + 1}`;
                                        videoInputSource.appendChild(option);
                                    });
                                    if (videoDevices.length > 0) {
                                        startVideoStream(videoDevices[0].deviceId);
                                    }
                                });

                                videoInputSource.addEventListener('change', () => {
                                    const selectedDeviceId = videoInputSource.value;
                                    startVideoStream(selectedDeviceId);
                                });

                                startButton.addEventListener('click', async () => {
                                    videoElement.style.display = "block";
                                    recordedVideoElement.style.display = "none";
                                    recordedBlobs = [];

                                    mediaRecorder = new MediaRecorder(currentStream);
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
                                    stopButton.style.display = "block";
                                    startButton.style.display = "none";
                                    cropButton.disabled = true;
                                });

                                stopButton.addEventListener('click', () => {
                                    mediaRecorder.stop();
                                    videoElement.style.display = "none";
                                    recordedVideoElement.style.display = "block";
                                    stopButton.style.display = "none";
                                    startButton.style.display = "block";
                                    cropButton.disabled = false;
                                });
                                
                                cropButton.addEventListener('click', () => {
                                    captureThumbnail(recordedVideoElement, 1);
                                    convertVideoToGif(recordedVideoElement.src)
                                    .then(function(gifUrl) {
                                        gifImage.src = gifUrl;
                                        gifImage.style.display = 'block';
                                        document.querySelector('.upload-placeholderGIF').style.display = "none";
                                    }).catch(function(error) {
                                        console.error('Error creating GIF:', error);
                                    });
                                });

                                document.getElementById('formFile').addEventListener('change', function(event) {
                                    const file = event.target.files[0];
                                    const preview = snapshotImage;
                                    const placeholder = document.querySelector('.upload-placeholder');

                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            preview.src = e.target.result;
                                            preview.style.display = 'block';
                                            placeholder.style.display = 'none';
                                        }
                                        reader.readAsDataURL(file);
                                    } else {
                                        preview.src = '#';
                                        preview.style.display = 'none';
                                        placeholder.style.display = 'block';
                                    }
                                });

                                document.getElementById('formFileGIF').addEventListener('change', function(event) {
                                    const file = event.target.files[0];
                                    const preview = document.getElementById('imagePreviewGIF');
                                    const placeholder = document.querySelector('.upload-placeholderGIF');

                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            preview.src = e.target.result;
                                            preview.style.display = 'block';
                                            placeholder.style.display = 'none';
                                        }
                                        reader.readAsDataURL(file);
                                    } else {
                                        preview.src = '#';
                                        preview.style.display = 'none';
                                        placeholder.style.display = 'block';
                                    }
                                });

                                function captureThumbnail(videoElement, captureTime) {
                                    return new Promise((resolve, reject) => {
                                        videoElement.currentTime = captureTime;
                                        
                                        videoElement.onseeked = function () {
                                            try {
                                                snapshotCanvas.width = videoElement.videoWidth;
                                                snapshotCanvas.height = videoElement.videoHeight;
                                                const ctx = snapshotCanvas.getContext('2d');
                                                ctx.drawImage(videoElement, 0, 0, snapshotCanvas.width, snapshotCanvas.height);
                                                const dataUrl = snapshotCanvas.toDataURL('image/png');
        
                                                snapshotImage.src = dataUrl;
                                                document.getElementById('imagePreview').style.display = "block";
                                                document.querySelector('.upload-placeholder').style.display = "none";
                                                resolve(dataUrl);
                                            } catch (error) {
                                                reject(error);
                                            }
                                        };

                                        videoElement.onerror = function(error) {
                                            reject(error);
                                        }
                                    });
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

    function convertVideoToGif(videoUrl) {
        return new Promise((resolve, reject) => {
            const tempVideo = document.createElement('video');
            tempVideo.src = videoUrl;

            tempVideo.addEventListener('loadeddata', () => {
                const gif = new GIF({
                    workers: 2,
                    quality: 20,
                    width: tempVideo.videoWidth,
                    height: tempVideo.videoHeight,
                    workerScript: '/build/js/gif.worker.js',
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
                    const gifUrl = URL.createObjectURL(blob);
                    resolve(gifUrl);
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
    
    window.fetchBlobFromUrl = function(url) {
        return fetch(url).then(function(response) {
            if(response.ok) {
                return response.blob();
            } else {
                return null;
            }
        });
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
        var formData = 
        {
            "to": to,
            "cc": cc,
            "bcc": bcc,
            "subject": subject,
            
            "content": content,
            "isEmailSent":isEmailSent
        }

        let contentElement = document.createElement('div');
        contentElement.innerHTML = content;
        if(contentElement.querySelector('.record-video-existence-check')) {
            formData.emailType = "video";
        } else {
            formData.emailType = "regular";
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
                    $("#emailModalClose").click();
                    fetchEmails();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                    showToastError(xhr.responseText);
                    $("#emailModalClose").click();
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
                        }, 5000);
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
                    $("#emailModalClose").click();
                    fetchEmails();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    showToastError(xhr.responseText);
                    $("#emailModalClose").click();

                }
            });
        }

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
</script>
            