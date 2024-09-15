<?php

namespace App\Http\Controllers;

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
        
        $uuid = Str::uuid()->toString();
        
        try { 
            if ($request->hasFile('gif') && $request->hasFile('img') && $request->hasFile('video')) {
                $gif = $request->file('gif');
                $path = $uuid . "/animation.gif";
                $uploaded = Storage::disk('s3')->put($path, file_get_contents($gif), 'public');
                if (!$uploaded)  {
                    return response()->json(['message' => 'Failed to upload the GIF file.'], 500);
                }
                $dbrecord = new RecordedMedia();
                $dbrecord['uuid'] = $uuid;
                $dbrecord['s3path'] = Storage::disk('s3')->url($path);
                $dbrecord['file_name'] = "animation.gif";
                $dbrecord->save();

                $img = $request->file('img');
                $path = $uuid . "/image.png";
                $uploaded = Storage::disk('s3')->put($path, file_get_contents($img), 'public');
                if (!$uploaded) {
                    return response()->json(['message' => 'Failed to upload the image file.'], 500);
                }
                $dbrecord = new RecordedMedia();
                $dbrecord['uuid'] = $uuid;
                $dbrecord['s3path'] = Storage::disk('s3')->url($path);;
                $dbrecord['file_name'] = "image.png";
                $dbrecord->save();

                $video = $request->file('video');
                $filePath = Storage::put('recordData/' . $uuid, contents: $video);
                if (!$filePath) {
                    return response()->json(['message' => 'Failed to upload video'], 500);
                }
                ConvertWebmToMp4::dispatch($uuid, $filePath);
                return view('emails.email-record-template', compact('uuid',))->render();
            } else {
              return response()->json(['message' => 'Unable to upload data due to insufficient data.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Video Upload Failed:' . $e->getMessage());
            return response()->json(['message' => 'Failed to upload Image/GIF.'], 400);
        }
    }
}
