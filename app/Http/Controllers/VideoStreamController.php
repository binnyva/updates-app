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

    public function subtitles(Video $video)
    {
        if (!$video->subtitles) {
            abort(404, 'Subtitle file not found.');
        }

        $path = Storage::disk('local')->path($video->subtitles);

        if (!file_exists($path)) {
            abort(404, 'Subtitle file not found.');
        }

        // If the file is already VTT, serve it directly
        if (str_ends_with($video->subtitles, '.vtt')) {
            return response()->file($path, [
                'Content-Type' => 'text/vtt',
            ]);
        }

        // Convert SRT to WebVTT on the fly
        $srt = file_get_contents($path);
        $vtt = "WEBVTT\n\n" . preg_replace('/(\d{2}:\d{2}:\d{2}),(\d{3})/', '$1.$2', $srt);

        return response($vtt, 200, [
            'Content-Type' => 'text/vtt',
        ]);
    }
}
