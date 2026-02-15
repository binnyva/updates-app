@extends('layouts.app')
@section('title', 'Updates')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Updates</h1>

    @if($videos->isEmpty())
        <p class="text-gray-500">No updates available yet.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($videos as $video)
                <a href="{{ url('/update/' . $video->id) }}" class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition">
                    <div class="aspect-video bg-gray-200 flex items-center justify-center">
                        @if($video->thumbnail_url)
                            <img src="{{ asset('storage/' . $video->thumbnail_url) }}" alt="{{ $video->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <h2 class="font-semibold text-gray-800">{{ $video->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $video->time_period }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $videos->links() }}</div>
    @endif
@endsection
