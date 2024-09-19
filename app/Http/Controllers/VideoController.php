<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\RecordedMedia;
use App\Jobs\ConvertWebmToMp4;


class VideoController extends Controller
{
    public function upload(Request $request)
    {
        try { 
            
            Log::info('Session Token: ' . session()->token());
            Log::info('Request Token: ' . $request->header('X-CSRF-TOKEN'));
            $uuid = Str::uuid()->toString();
            if ($request->hasFile('gif') && $request->hasFile('img') && $request->hasFile('video')) {
                $gifPath = $uuid . "/animation.gif";
                $gif = $request->file('gif');
                if ($this->uploadFileToS3($gif, $gifPath)) {
                    $this->storeRecordedMedia($uuid, Storage::disk('s3')->url($gifPath), 'animation.gif');
                } else {
                    return response()->json(['message' => 'Failed to upload the GIF file.'], 500);
                }

                $img = $request->file('img');
                $imgPath = $uuid . "/image.png";
                if ($this->uploadFileToS3($img, $imgPath)) {
                    $this->storeRecordedMedia($uuid, Storage::disk('s3')->url($imgPath), 'image.png');
                } else {
                    return response()->json(['message' => 'Failed to upload the image file.'], 500);
                }

                $video = $request->file('video');
                $filePath = Storage::put('recordData/' . $uuid, contents: $video);
                if (!$filePath) {
                    return response()->json(['message' => 'Failed to upload video'], 500);
                }
                ConvertWebmToMp4::dispatch($uuid, $filePath);
                return view('emails.email-record-template', compact('uuid',))->render();
                // $videoTemplate = Template::where('name', 'Video Email')->first();
                // return response()->json(['content' => $videoTemplate->content, 'uuid' => $uuid], 200);
            } else {
              return response()->json(['message' => 'Unable to upload data due to insufficient data.'], 400);
            }
        } catch (\Exception $e) {
            Log::info('Video Upload Failed:' . $e->getMessage());
            return response()->json(['message' => 'Failed to upload Image/GIF.'], 400);
        }
    }

    private function uploadFileToS3($file, $path)
    {
        if (!$file || !$file->isValid()) {
            return false;
        }

        return Storage::disk('s3')->put($path, fopen($file->getPathname(), 'r'));
    }

    private function storeRecordedMedia($uuid, $s3path, $fileName)
    {
        $dbrecord = new RecordedMedia();
        $dbrecord->uuid = $uuid;
        $dbrecord->s3path = $s3path;
        $dbrecord->file_name = $fileName;
        $dbrecord->save();
    }
}
