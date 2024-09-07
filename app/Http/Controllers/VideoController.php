<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function upload(Request $request)
    {
        $urls =
         [
            'video' => "",
            'gif' => "",
            'img' => "",
         ];

        try { 
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $randomVideoName = Str::uuid()->toString() . '.' . $video->getClientOriginalExtension();
                $path = 'videos/' . $randomVideoName;
                $uploaded = Storage::disk('s3')->put($path, file_get_contents($video), 'public');

                if ($uploaded) {
                    // Generate the URL after uploading
                    $url = Storage::disk('s3')->url($path);
                    $urls['video'] = $url;
                } else {
                    return response()->json(['message' => 'Failed to upload video'], 500);
                }
            }
            if ($request->hasFile('gif')) {
                $gif = $request->file('gif');
                $randomGifName = Str::uuid()->toString() . '.' . $gif->getClientOriginalExtension();
                $path = 'gifs/' . $randomGifName;
                $uploaded = Storage::disk('s3')->put($path, file_get_contents($gif), 'public');

                if ($uploaded) {
                    // Generate the URL after uploading
                    $url = Storage::disk('s3')->url($path);
                    $urls['gif'] = $url;
                } else {
                    return response()->json(['message' => 'Failed to upload video'], 500);
                }
            }
            if ($request->hasFile('img')) {
                $img = $request->file('img');
                $randomImgName = Str::uuid()->toString() . '.' . $img->getClientOriginalExtension();
                $path = 'imgs/' . $randomImgName;
                $uploaded = Storage::disk('s3')->put($path, file_get_contents($img), 'public');

                if ($uploaded) {
                    // Generate the URL after uploading
                    $url = Storage::disk('s3')->url($path);
                    $urls['img'] = $url;
                } else {
                    return response()->json(['message' => 'Failed to upload video'], 500);
                }
            }
            return response()->json(['urls'=> $urls], 200);
        } catch (\Exception $e) {
            Log::error('Video Upload Failed:' . $e->getMessage());
            return response()->json(['message' => 'No video uploaded'], 400);
        }
    }
}
