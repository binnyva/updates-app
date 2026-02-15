<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DownloadVideoJob;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    private function currentUser()
    {
        return Auth::guard('web')->user();
    }

    public function index()
    {
        $videos = $this->currentUser()->videos()->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'time_period' => 'required|string|max:255',
            'content' => 'nullable|string',
            'level' => 'required|in:full,limited',
            'upload_type' => 'required|in:file,url',
            'video_file' => 'required_if:upload_type,file|file|mimetypes:video/*|max:512000',
            'video_url' => 'required_if:upload_type,url|nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $user = $this->currentUser();
        $videoData = [
            'name' => $validated['name'],
            'time_period' => $validated['time_period'],
            'content' => $validated['content'] ?? null,
            'level' => $validated['level'],
            'user_id' => $user->id,
        ];

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $videoData['thumbnail_url'] = $thumbPath;
        }

        // Handle video
        if ($validated['upload_type'] === 'file' && $request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('videos', 'local');
            $videoData['video_url'] = $videoPath;
        }

        $video = Video::create($videoData);

        // If URL provided, dispatch download job
        if ($validated['upload_type'] === 'url' && !empty($validated['video_url'])) {
            DownloadVideoJob::dispatch($video, $validated['video_url']);
        }

        return redirect('/adm/videos')->with('success', 'Video created successfully.');
    }

    public function edit(Video $video)
    {
        if ($video->user_id !== $this->currentUser()->id) {
            abort(404);
        }

        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        if ($video->user_id !== $this->currentUser()->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'time_period' => 'required|string|max:255',
            'content' => 'nullable|string',
            'level' => 'required|in:full,limited',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail_url) {
                Storage::disk('public')->delete($video->thumbnail_url);
            }
            $validated['thumbnail_url'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video->update($validated);

        return redirect('/adm/videos')->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        if ($video->user_id !== $this->currentUser()->id) {
            abort(404);
        }

        if ($video->video_url) {
            Storage::disk('local')->delete($video->video_url);
        }
        if ($video->thumbnail_url) {
            Storage::disk('public')->delete($video->thumbnail_url);
        }

        $video->delete();

        return redirect('/adm/videos')->with('success', 'Video deleted.');
    }
}
