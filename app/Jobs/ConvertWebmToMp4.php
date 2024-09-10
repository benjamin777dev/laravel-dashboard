<?php

namespace App\Jobs;

use DOMDocument;
use DOMXPath;
use App\Models\RecordedMedia;
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
        $auth = $this->inputData["to"];
        $auth = array_merge($auth, $this->inputData["cc"]);
        $auth = array_merge($auth, $this->inputData["bcc"]);
        $directory = storage_path('app/convertedRecordedVideos');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true); // Create the directory with appropriate permissions
        }
        // Perform conversion
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($storagePath);
        $format = new X264('libmp3lame', 'libx264');
        $video->save($format, storage_path('app/' . $outputVideoPath));

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
            $record = new RecordedMedia();
            $record->uuid = $unID;
            $record->auth_users = $auth;
            $record->save();
            
            $dom = new DOMDocument();
            @$dom->loadHTML($this->inputData['content']);
            $xpath = new DOMXPath($dom);
            $videoElement = $xpath->query('//*[@id="recordedVideo"]//video/source');
            if ($videoElement->length > 0) {
                $videoElement->item(0)->setAttribute('src', $unID . '/video.mp4');
            }

            $imgElement = $xpath->query('//*[@id="recordedVideo"]//a/img');
            if ($imgElement->length > 0) {
                $imgElement->item(0)->setAttribute('src', $unID . '/image.png');
            }

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
        }

    }
}
