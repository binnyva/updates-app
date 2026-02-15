@extends('layouts.admin')
@section('title', 'Manage Viewers')
@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Viewers</h1>
        <a href="{{ url('/adm/viewers/create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Add Viewer</a>
    </div>

    @if($viewers->isEmpty())
        <p class="text-gray-500">No viewers yet. Add one to get started.</p>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Level</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Last Login</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($viewers as $viewer)
                        <tr>
                            <td class="px-4 py-3">{{ $viewer->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $viewer->email }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $viewer->pivot->level === 'full' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($viewer->pivot->level) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $viewer->last_login_on?->diffForHumans() ?? 'Never' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ url('/adm/viewers/' . $viewer->id . '/edit') }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                                <form method="POST" action="{{ url('/adm/viewers/' . $viewer->id) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Remove this viewer?')">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $viewers->links() }}</div>
    @endif
@endsection
