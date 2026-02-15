@extends('layouts.admin')
@section('title', 'Edit Viewer')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Viewer</h1>

    <form method="POST" action="{{ url('/adm/viewers/' . $viewer->id) }}" class="bg-white rounded-lg shadow p-6 max-w-lg">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <p class="text-sm text-gray-500">{{ $viewer->email }}</p>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $viewer->name) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
        </div>

        <div class="mb-4">
            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Permission Level</label>
            <select name="level" id="level" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="full" {{ old('level', $level) === 'full' ? 'selected' : '' }}>Full</option>
                <option value="limited" {{ old('level', $level) === 'limited' ? 'selected' : '' }}>Limited</option>
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Update</button>
            <a href="{{ url('/adm/viewers') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Cancel</a>
        </div>
    </form>
@endsection
