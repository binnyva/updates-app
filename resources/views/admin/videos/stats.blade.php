@extends('layouts.admin')
@section('title', 'Video Stats')
@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Stats: {{ $video->name }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $video->time_period }}</p>
        </div>
        <a href="{{ url('/adm/videos') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Back to Videos</a>
    </div>

    @if($viewers->isEmpty())
        <p class="text-gray-500">No viewers have been added yet.</p>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Viewer</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Watched</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($viewers as $viewer)
                        <tr>
                            <td class="px-4 py-3">{{ $viewer->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $viewer->email }}</td>
                            <td class="px-4 py-3">
                                @if(in_array($viewer->id, $viewerIdsSeen))
                                    <span class="text-green-600 font-semibold">&#10003;</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
