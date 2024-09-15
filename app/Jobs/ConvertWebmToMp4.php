<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
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

class ConvertWebmToMp4 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uuid;
    protected $videoData;

    public function __construct($uuid, $videoData)
    {
        $this->uuid = $uuid;
        $this->videoData = $videoData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $unID = $this->uuid;
            $filePath = Storage::put('recordData/' . $unID, contents: $this->videoData);
            $outputVideoPath = 'convertedRecordedVideos/' . $unID . '.mp4';
            $originalPath = 'app/' . $filePath;
            $storagePath = storage_path($originalPath);
            if (!file_exists($storagePath)) {
                Log::info("File does not exist at path: " . $storagePath);
            }

            $directory = storage_path('app/convertedRecordedVideos');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open($storagePath);
            $format = new X264('aac', 'libx264');
            $video->save($format, storage_path('app/' . $outputVideoPath));

            $s3MP4Path = $unID . '/video.mp4';
            $s3WebmPath = $unID . '/video.webm';

            $mp4Uploaded = Storage::disk('s3')->put($s3MP4Path, file_get_contents(storage_path('app/' . $outputVideoPath)), 'public');
            $webUploaded = Storage::disk('s3')->put($s3WebmPath, file_get_contents(storage_path($originalPath)), 'public');

            Storage::delete($filePath);
            Storage::delete($outputVideoPath);
            
            if ($mp4Uploaded * $webUploaded) {
                $now = Carbon::now();
                $dbrecords = [
                    ['file_name' => 'video.mp4', 'uuid' => $unID, "s3path" => Storage::disk('s3')->url($s3MP4Path), 'created_at' => $now, 'updated_at' => $now],
                    ['file_name' => 'video.webm', 'uuid' => $unID, "s3path" => Storage::disk('s3')->url($s3WebmPath), 'created_at' => $now, 'updated_at' => $now],
                ];
                RecordedMedia::insert($dbrecords);
            } else {
                Log::info("Upload to S3 failed.");
            }
        } catch (Exception $e) {
            Log::info("File convert operation failed.");
        }

    }
}
