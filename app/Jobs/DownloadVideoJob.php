<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Video $video,
        public string $url,
    ) {}

    public function handle(): void
    {
        $response = Http::timeout(300)->get($this->url);

        if (!$response->successful()) {
            $this->video->update(['content' => ($this->video->content ?? '') . "\n[Video download failed]"]);
            return;
        }

        $extension = pathinfo(parse_url($this->url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'mp4';
        $filename = 'videos/' . Str::uuid() . '.' . $extension;

        Storage::disk('local')->put($filename, $response->body());
        $this->video->update(['video_url' => $filename]);
    }
}
