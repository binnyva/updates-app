@extends('layouts.admin')
@section('title', 'Add User')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Add User</h1>

    <form method="POST" action="{{ url('/adm/users') }}" class="bg-white rounded-lg shadow p-6 max-w-lg">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin') ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Super Admin</span>
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Create</button>
            <a href="{{ url('/adm/users') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">Cancel</a>
        </div>
    </form>
@endsection
