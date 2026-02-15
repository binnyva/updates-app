@extends('layouts.admin')
@section('title', 'Edit Video')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Video</h1>

    <form method="POST" action="{{ url('/adm/videos/' . $video->id) }}" enctype="multipart/form-data"
          class="bg-white rounded-lg shadow p-6 max-w-lg">
        @csrf @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $video->name) }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="time_period" class="block text-sm font-medium text-gray-700 mb-1">Time Period</label>
            <input type="text" name="time_period" id="time_period" value="{{ old('time_period', $video->time_period) }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            @error('time_period') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="content" id="content" rows="3"
                      class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('content', $video->content) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Permission Level</label>
            <select name="level" id="level" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="full" {{ old('level', $video->level) === 'full' ? 'selected' : '' }}>Full</option>
                <option value="limited" {{ old('level', $video->level) === 'limited' ? 'selected' : '' }}>Limited</option>
            </select>
        </div>

        <div class="mb-4">
            <p class="text-sm text-gray-500">Current video: {{ $video->video_url ? 'Uploaded' : 'None' }}</p>
        </div>

        <div class="mb-4">
            <label for="subtitle_file" class="block text-sm font-medium text-gray-700 mb-1">Subtitles</label>
            @if($video->subtitles)
                <p class="text-xs text-gray-500 mb-1">Current: {{ basename($video->subtitles) }}</p>
            @endif
            <input type="file" name="subtitle_file" id="subtitle_file" accept=".vtt,.srt"
                   class="w-full text-sm">
            @error('subtitle_file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Replace Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                   class="w-full text-sm">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Update</button>
            <a href="{{ url('/adm/videos') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Cancel</a>
        </div>
    </form>
@endsection
