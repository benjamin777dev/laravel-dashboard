<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $video = $request->file('file');
            $path = 'videos/' . $video->getClientOriginalName();
            $originalName = $video->getClientOriginalName();
            $mimeType = $video->getClientMimeType(); // Mime type (e.g., video/mp4)
            $size = $video->getSize(); // File size in bytes
            $uploaded = Storage::disk('s3')->put($path, file_get_contents($video), 'public');

            if ($uploaded) {
                // Generate the URL after uploading
                $url = Storage::disk('s3')->url($path);
    
                return response()->json([
                    'url' => $url,
                    'original_name' => $originalName,
                    'mime_type' => $mimeType,
                    'size' => $size
                ], 200);
                // return response()->json(['url' => $url], 200);
            } else {
                return response()->json(['message' => 'Failed to upload video'], 500);
            }
        }

        return response()->json(['message' => 'No video uploaded'], 400);
    }
}
