@extends('layouts.app')
@section('title', $video->name)
@section('content')
    <div class="max-w-4xl mx-auto" x-data="videoPlayer()">
        <h1 class="text-2xl font-bold mb-1">{{ $video->name }}</h1>
        <p class="text-sm text-gray-500 mb-4">{{ $video->time_period }}</p>

        @if($video->content)
            <p class="text-gray-600 mb-4">{{ $video->content }}</p>
        @endif

        {{-- Video Player --}}
        @if($video->video_url)
            <div class="bg-black rounded-lg overflow-hidden mb-6">
                <video x-ref="videoEl"
                       class="mx-auto w-auto max-w-full"
                       style="max-height: 80vh;"
                       controls
                       @pause="onPause"
                       @play="onPlay"
                       @timeupdate="onTimeUpdate">
                    <source src="{{ url('/video/' . $video->id . '/stream') }}" type="video/mp4">
                    @if($video->subtitles)
                        <track src="{{ url('/video/' . $video->id . '/subtitles') }}" kind="subtitles" srclang="en" label="English" default
                               x-init="$el.addEventListener('load', () => {
                                   Array.from($el.track.cues || []).forEach(cue => { cue.line = -4; });
                               })">
                    @endif
                    Your browser does not support the video tag.
                </video>
            </div>
        @else
            <div class="bg-gray-200 rounded-lg p-12 text-center mb-6">
                <p class="text-gray-500">Video is still being processed...</p>
            </div>
        @endif

        {{-- Comments Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Comment Form (viewers only) --}}
            @if($actorType === 'viewer')
                <div>
                    <h2 class="text-lg font-semibold mb-3">Leave a Comment</h2>
                    <form method="POST" action="{{ url('/update/' . $video->id . '/comment') }}">
                        @csrf
                        <input type="hidden" name="video_time" :value="isPaused ? currentTime : null">
                        <div class="mb-2">
                            <textarea name="content" rows="3" required placeholder="Write your comment..."
                                      class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></textarea>
                            @error('content') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Post Comment</button>
                            <span x-show="isPaused" class="text-xs text-gray-500">
                                Tagging at <span x-text="formatTime(currentTime)"></span>
                            </span>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Comments List --}}
            <div>
                <h2 class="text-lg font-semibold mb-3">
                    Comments
                    @if($comments->isEmpty())
                        <span class="text-sm font-normal text-gray-400">— none yet</span>
                    @endif
                </h2>
                <div class="space-y-3">
                    @foreach($comments as $comment)
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <div class="flex items-start gap-2">
                                @if($comment->video_time !== null)
                                    <button @click="seekTo({{ $comment->video_time }})"
                                            class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 shrink-0">
                                        {{ gmdate('H:i:s', (int) $comment->video_time) }}
                                    </button>
                                @endif
                                <div>
                                    @if($isOwner && $comment->viewer)
                                        <p class="text-xs text-gray-400 mb-1">{{ $comment->viewer->name ?? $comment->viewer->email }}</p>
                                    @endif
                                    <p class="text-sm text-gray-700">{{ $comment->content }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function videoPlayer() {
            return {
                currentTime: 0,
                isPaused: true,
                trackInterval: null,
                onPause() {
                    this.isPaused = true;
                    this.currentTime = this.$refs.videoEl.currentTime;
                },
                onPlay() {
                    this.isPaused = false;
                    this.startTracking();
                },
                onTimeUpdate() {
                    this.currentTime = this.$refs.videoEl.currentTime;
                },
                seekTo(time) {
                    if (this.$refs.videoEl) {
                        this.$refs.videoEl.currentTime = time;
                        this.$refs.videoEl.play();
                    }
                },
                formatTime(seconds) {
                    const h = Math.floor(seconds / 3600);
                    const m = Math.floor((seconds % 3600) / 60);
                    const s = Math.floor(seconds % 60);
                    return [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
                },
                startTracking() {
                    if (this.trackInterval) return;
                    this.trackInterval = setInterval(() => {
                        if (!this.$refs.videoEl || this.$refs.videoEl.paused) {
                            clearInterval(this.trackInterval);
                            this.trackInterval = null;
                            return;
                        }
                        fetch('/update/{{ $video->id }}/view', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ video_view_time: this.$refs.videoEl.currentTime }),
                        });
                    }, 30000); // Every 30 seconds
                },
            };
        }
    </script>
@endsection
