<?php

namespace App\Jobs;

use DOMDocument;
use DOMXPath;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Controllers\EmailController;

class ConvertWebmToMp4 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $inputData;
    protected $filePath;

    public function __construct($inputData, $filePath)
    {
        $this->inputData = $inputData;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $outputVideoPath = 'convertedRecordedVideos/' . time() . '.mp4';
        $originalPath = 'app/' . $this->filePath['videoPath'];
        $storagePath = storage_path($originalPath);
        Log::info("Checking file at :" . $storagePath);
        if (!file_exists($storagePath)) {
            Log::info("File does not exist at path: " . $storagePath);
        }
        //Add code to load image/GIF

        $directory = storage_path('app/convertedRecordedVideos');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true); // Create the directory with appropriate permissions
        }
        // Perform conversion
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($storagePath);
        $format = new X264('libmp3lame', 'libx264');
        $video->save($format, storage_path('app/' . $outputVideoPath));

        // Upload to S3 Bucket and retrieve url Section
        // $video = $this->recordedVideo;
        $unID = Str::uuid()->toString();

        $s3VideoPath = $unID . '/video.mp4';
        $s3ImagePath = $unID . '/image.png';

        $videoUploaded = Storage::disk('s3')->put($s3VideoPath, file_get_contents(storage_path('app/' . $outputVideoPath)), 'public');
        $imgUploaded = Storage::disk('s3')->put($s3ImagePath, file_get_contents(storage_path('app/' . $this->filePath['imgPath'])), 'public');
        // Add code to upload Image/GIF


        Storage::delete($this->filePath['videoPath']);
        Storage::delete($this->filePath['imgPath']);
        Storage::delete($outputVideoPath);

        if ($videoUploaded * $imgUploaded) {
            // Generate the URL after uploading
            // $url = Storage::disk('s3')->url($s3path);
            // Log::info('Uploaded Video URL:'. $url);
            $dom = new DOMDocument();
            @$dom->loadHTML($this->inputData['content']);
            $xpath = new DOMXPath($dom);
            $videoElement = $xpath->query('#recordedVideo video source');
            $videoElement->setAttribute('src', $unID . '/video.mp4');
            $imgElement = $xpath->query('#recordedVideo a img');
            $imgElement->setAttribute('src', $unID . '/image.png');

            $body = $dom->getElementsByTagName('body')->item(0);
            $innerHTML = '';
            foreach ($body->childNodes as $child) {
                $innerHTML .= $dom->saveHTML($child); // Append each child node's HTML
            }
            $this->inputData['content'] = $innerHTML;

            $controller = new EmailController();
            if($this->inputData['emailType'] == "multiple") {
                $controller->sendMultipleEmail($this->inputData);
            } else {
                $controller->sendEmail($this->inputData);
            }
        } else {
            Log::info("Something is wrong.");
            // return response()->json(['message' => 'Failed to upload video'], 500);
        }

    }
}
