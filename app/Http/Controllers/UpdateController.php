<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use App\Models\VideoView;
use App\Services\AuthService;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function index()
    {
        if ($this->auth->isUser()) {
            // Users see their own videos
            $videos = $this->auth->currentUser()
                ->videos()
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        } else {
            // Viewers see videos from their linked users, filtered by permission level
            $viewer = $this->auth->currentViewer();
            $userIds = $viewer->users()->pluck('users.id');

            // Get the highest permission level per user
            $userLevels = $viewer->users()
                ->get()
                ->mapWithKeys(fn ($u) => [$u->id => $u->pivot->level]);

            $videos = Video::whereIn('user_id', $userIds)
                ->where(function ($query) use ($userLevels) {
                    foreach ($userLevels as $userId => $level) {
                        $query->orWhere(function ($q) use ($userId, $level) {
                            $q->where('user_id', $userId);
                            if ($level === 'limited') {
                                $q->where('level', 'limited');
                            }
                        });
                    }
                })
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        return view('public.index', compact('videos'));
    }

    public function show(Video $video)
    {
        // Authorization check
        if ($this->auth->isUser()) {
            if ($video->user_id !== $this->auth->currentUser()->id) {
                abort(403);
            }
            $comments = $video->comments()->with('viewer')->orderBy('video_time')->get();
            $isOwner = true;
        } else {
            $viewer = $this->auth->currentViewer();
            $userLink = $viewer->users()->where('users.id', $video->user_id)->first();
            if (!$userLink) {
                abort(403);
            }
            // Check permission level
            if ($userLink->pivot->level === 'limited' && $video->level === 'full') {
                abort(403);
            }
            $comments = $video->comments()->where('viewer_id', $viewer->id)->orderBy('video_time')->get();
            $isOwner = false;
        }

        return view('public.show', compact('video', 'comments', 'isOwner'));
    }

    public function storeComment(Request $request, Video $video)
    {
        if (!$this->auth->isViewer()) {
            abort(403, 'Only viewers can leave comments.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'video_time' => 'nullable|numeric|min:0',
        ]);

        Comment::create([
            'content' => $validated['content'],
            'viewer_id' => $this->auth->currentViewer()->id,
            'video_id' => $video->id,
            'video_time' => $validated['video_time'],
        ]);

        return redirect("/update/{$video->id}")->with('success', 'Comment added.');
    }

    public function trackView(Request $request, Video $video)
    {
        if (!$this->auth->isViewer()) {
            return response()->json(['ok' => true]);
        }

        $validated = $request->validate([
            'video_view_time' => 'required|numeric|min:0',
            'finished' => 'boolean',
        ]);

        $view = VideoView::updateOrCreate(
            [
                'video_id' => $video->id,
                'viewer_id' => $this->auth->currentViewer()->id,
            ],
            [
                'video_view_time' => $validated['video_view_time'],
            ]
        );

        if (!empty($validated['finished']) && $view->finished_watch_at === null) {
            $view->update(['finished_watch_at' => now()]);
        }

        return response()->json(['ok' => true]);
    }
}
