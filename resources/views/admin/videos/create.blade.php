@extends('layouts.admin')
@section('title', 'Add Video')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Video</h1>

    <form method="POST" action="{{ url('/adm/videos') }}" enctype="multipart/form-data"
          class="bg-white rounded-lg shadow p-6 max-w-lg"
          x-data="{ uploadType: '{{ old('upload_type', 'file') }}', serverFiles: [], serverFilesLoaded: false }"
          x-effect="if (uploadType === 'server' && !serverFilesLoaded) { serverFilesLoaded = true; fetch('{{ url('/adm/videos/server-files') }}').then(r => r.json()).then(data => serverFiles = data) }">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="time_period" class="block text-sm font-medium text-gray-700 mb-1">Time Period</label>
            <input type="text" name="time_period" id="time_period" value="{{ old('time_period') }}" required
                   placeholder="e.g. January 2026"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            @error('time_period') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
            <textarea name="content" id="content" rows="3"
                      class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('content') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Permission Level</label>
            <select name="level" id="level" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="full" {{ old('level') === 'full' ? 'selected' : '' }}>Full</option>
                <option value="limited" {{ old('level', 'limited') === 'limited' ? 'selected' : '' }}>Limited</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Video Source</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-1">
                    <input type="radio" name="upload_type" value="file" x-model="uploadType"> <span class="text-sm">File Upload</span>
                </label>
                <label class="flex items-center gap-1">
                    <input type="radio" name="upload_type" value="url" x-model="uploadType"> <span class="text-sm">URL</span>
                </label>
                <label class="flex items-center gap-1">
                    <input type="radio" name="upload_type" value="server" x-model="uploadType"> <span class="text-sm">Server File</span>
                </label>
            </div>
        </div>

        <div class="mb-4" x-show="uploadType === 'file'">
            <label for="video_file" class="block text-sm font-medium text-gray-700 mb-1">Video File</label>
            <input type="file" name="video_file" id="video_file" accept="video/*"
                   class="w-full text-sm">
            @error('video_file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4" x-show="uploadType === 'url'">
            <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">Video URL</label>
            <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                   placeholder="https://example.com/video.mp4"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            @error('video_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4" x-show="uploadType === 'server'">
            <label for="server_file" class="block text-sm font-medium text-gray-700 mb-1">Server File</label>
            <p class="text-xs text-gray-500 mb-2">Upload your video via FTP to <code class="bg-gray-100 px-1 rounded">storage/app/private/videos/</code> first, then select it here.</p>
            <select name="server_file" id="server_file"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                <template x-if="serverFiles.length === 0">
                    <option value="">No files available</option>
                </template>
                <template x-for="file in serverFiles" :key="file">
                    <option :value="file" x-text="file"></option>
                </template>
            </select>
            @error('server_file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="subtitle_file" class="block text-sm font-medium text-gray-700 mb-1">Subtitles (optional)</label>
            <input type="file" name="subtitle_file" id="subtitle_file" accept=".vtt,.srt"
                   class="w-full text-sm">
            @error('subtitle_file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (optional)</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                   class="w-full text-sm">
            @error('thumbnail') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Create</button>
            <a href="{{ url('/adm/videos') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Cancel</a>
        </div>
    </form>
@endsection
