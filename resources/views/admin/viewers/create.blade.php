@extends('layouts.admin')
@section('title', 'Add Viewer')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Viewer</h1>

    <form method="POST" action="{{ url('/adm/viewers') }}" class="bg-white rounded-lg shadow p-6 max-w-lg">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name (optional)</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
        </div>

        <div class="mb-4">
            <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Permission Level</label>
            <select name="level" id="level" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="full" {{ old('level') === 'full' ? 'selected' : '' }}>Full</option>
                <option value="limited" {{ old('level', 'limited') === 'limited' ? 'selected' : '' }}>Limited</option>
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Add Viewer</button>
            <a href="{{ url('/adm/viewers') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Cancel</a>
        </div>
    </form>
@endsection
