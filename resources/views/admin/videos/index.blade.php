@extends('layouts.admin')
@section('title', 'Manage Videos')
@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Videos</h1>
        <a href="{{ url('/adm/videos/create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Add Video</a>
    </div>

    @if($videos->isEmpty())
        <p class="text-gray-500">No videos yet. Upload your first update.</p>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Period</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Level</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Video</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Stats</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($videos as $video)
                        <tr>
                            <td class="px-4 py-3">{{ $video->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $video->time_period }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $video->level === 'full' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($video->level) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $video->video_url ? 'Uploaded' : 'Pending' }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ url('/adm/videos/' . $video->id . '/stats') }}" class="text-blue-600 hover:underline">{{ $video->distinct_viewer_views_count }} / {{ $video->accessible_viewer_count }}</a>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ url('/adm/videos/' . $video->id . '/edit') }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                                <form method="POST" action="{{ url('/adm/videos/' . $video->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this video?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $videos->links() }}</div>
    @endif
@endsection
