// webrtc.js

let mediaRecorder;
let recordedBlobs = [];
const startButton = document.getElementById('start');
const stopButton = document.getElementById('stop');
const clipButton = document.getElementById('clip');
const thumbnailButton = document.getElementById('thumbnail');
const preview = document.getElementById('preview');
const recordedVideo = document.getElementById('recorded');
const thumbnailCanvas = document.createElement('canvas');
const thumbnailImage = document.createElement('img');

startButton.addEventListener('click', async () => {
    recordedBlobs = [];

    const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    preview.srcObject = stream;

    try {
        mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm' });
    } catch (e) {
        console.error('Exception while creating MediaRecorder:', e);
        return;
    }

    mediaRecorder.ondataavailable = function(event) {
        if (event.data && event.data.size > 0) {
            recordedBlobs.push(event.data);
        }
    };

    mediaRecorder.onstop = function() {
        const blob = new Blob(recordedBlobs, { type: 'video/webm' });
        const url = URL.createObjectURL(blob);
        recordedVideo.src = url;

        // Enable the buttons for clipping and thumbnail capture
        clipButton.disabled = false;
        thumbnailButton.disabled = false;
    };

    mediaRecorder.start();
    startButton.disabled = true;
    stopButton.disabled = false;
    clipButton.disabled = true;
    thumbnailButton.disabled = true;
});

stopButton.addEventListener('click', () => {
    mediaRecorder.stop();
    startButton.disabled = false;
    stopButton.disabled = true;
});

clipButton.addEventListener('click', () => {
    extractClip(recordedVideo, 3); // Extract a 3-second clip
});

thumbnailButton.addEventListener('click', () => {
    captureThumbnail(recordedVideo, 1); // Capture a thumbnail at 1 second
});

function extractClip(videoElement, clipDuration) {
    const videoUrl = videoElement.src;
    const startTime = 0; // Start at the beginning
    const endTime = clipDuration;

    const clipBlob = recordedBlobs.slice(); // Clone the original blobs
    const clip = new Blob(clipBlob, { type: 'video/webm' });
    const clipUrl = URL.createObjectURL(clip);

    const newVideo = document.createElement('video');
    newVideo.src = clipUrl;
    newVideo.controls = true;
    document.body.appendChild(newVideo);
}

function captureThumbnail(videoElement, captureTime) {
    videoElement.currentTime = captureTime;

    videoElement.onseeked = function () {
        thumbnailCanvas.width = videoElement.videoWidth;
        thumbnailCanvas.height = videoElement.videoHeight;
        const ctx = thumbnailCanvas.getContext('2d');
        ctx.drawImage(videoElement, 0, 0, thumbnailCanvas.width, thumbnailCanvas.height);
        const dataUrl = thumbnailCanvas.toDataURL('image/png');

        thumbnailImage.src = dataUrl;
        document.body.appendChild(thumbnailImage);
    };
}
