<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoStreamController extends Controller
{
    public function stream(Video $video)
    {
        if (!$video->video_url) {
            abort(404, 'Video file not found.');
        }

        $path = Storage::disk('local')->path($video->video_url);

        if (!file_exists($path)) {
            abort(404, 'Video file not found.');
        }

        return response()->file($path);
    }
}
