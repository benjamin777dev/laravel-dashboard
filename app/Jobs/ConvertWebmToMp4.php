<?php

namespace App\Jobs;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        // Perform conversion
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($this->filePath);
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
            $urls['video'] = $url;
            dd($url);
        } else {
            dd("Error");
            // return response()->json(['message' => 'Failed to upload video'], 500);
        }

        // Send the email with the MP4 file
        // Mail::send('emails.video', [], function($message) use ($outputFilePath) {
        //     $message->to($this->email)
        //         ->subject('Your converted video')
        //         ->attach($outputFilePath, [
        //             'as' => 'video.mp4',
        //             'mime' => 'video/mp4',
        //         ]);
        // });
    }
}
