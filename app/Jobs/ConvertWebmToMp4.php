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
    protected $filePath;

    public function __construct($uuid, $filePath)
    {
        $this->uuid = $uuid;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $unID = $this->uuid;
            $outputVideoPath = 'convertedRecordedVideos/' . $unID . '.mp4';
            $originalPath = 'app/' . $this->filePath;
            $storagePath = storage_path($originalPath);
            if (!file_exists($storagePath)) {
                Log::info("File does not exist at path: " . $storagePath);
                return;
            }

            $directory = storage_path('app/convertedRecordedVideos');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            Log::info('Video file loaded successfully');
            
            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open($storagePath);
            $format = new X264('aac', 'libx264');
            $video->save($format, storage_path('app/' . $outputVideoPath));

            $s3MP4Path = $unID . '/video.mp4';
            $s3WebmPath = $unID . '/video.webm';

            Log::info('File converted successfully.');

            $mp4Uploaded = Storage::disk('s3')->put($s3MP4Path, fopen(storage_path('app/' . $outputVideoPath), 'r'));
            $webUploaded = Storage::disk('s3')->put($s3WebmPath, fopen(storage_path($originalPath), 'r'));

            Storage::delete($this->filePath);
            Storage::delete($outputVideoPath);

            Log::info('File uploaded successfully.');
            
            if ($mp4Uploaded && $webUploaded) {
                $now = Carbon::now();
                $dbrecords = [
                    ['file_name' => 'video.mp4', 'uuid' => $unID, "s3path" => Storage::disk('s3')->url($s3MP4Path), 'created_at' => $now, 'updated_at' => $now],
                    ['file_name' => 'video.webm', 'uuid' => $unID, "s3path" => Storage::disk('s3')->url($s3WebmPath), 'created_at' => $now, 'updated_at' => $now],
                ];
                RecordedMedia::insert($dbrecords);
            } else {
                Log::error("Upload to S3 failed for MP4: " . $mp4Uploaded . " or WebM: " . $webUploaded);
            }
        } catch (Exception $e) {
            Log::info("File convert operation failed:" . $e->getMessage());
        }

    }
}
