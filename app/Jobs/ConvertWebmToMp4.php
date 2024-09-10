<?php

namespace App\Jobs;

use DOMDocument;
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
        $originalPath = 'app/' . $this->filePath;
        $storagePath = storage_path($originalPath);
        Log::info("Checking file at :" . $storagePath);
        if (!file_exists($storagePath)) {
            Log::info("File does not exist at path: " . $storagePath);
        }
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
        $randomVideoName = Str::uuid()->toString() . '.mp4';
        $s3path = 'videos/' . $randomVideoName;
        $uploaded = Storage::disk('s3')->put($s3path, file_get_contents(storage_path('app/' . $outputVideoPath)), 'public');


        Storage::delete($this->filePath);
        Storage::delete($outputVideoPath);

        if ($uploaded) {
            // Generate the URL after uploading
            $url = Storage::disk('s3')->url($s3path);
            Log::info('Uploaded Video URL:'. $url);
            $dom = new DOMDocument();
            @$dom->loadHTML($this->inputData['content']);
            $videoElement = $dom->getElementById('recordedVideo');
            $newSrc = $url;
            $videoElement->setAttribute('src', $newSrc);
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
