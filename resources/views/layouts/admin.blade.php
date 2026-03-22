<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Life Updates')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-56 bg-gray-800 text-white flex-shrink-0">
            <div class="p-4">
                <a href="{{ url('/adm') }}" class="text-lg font-bold">Life Updates</a>
                <p class="text-xs text-gray-400 mt-1">Admin Panel</p>
            </div>
            <nav class="mt-4">
                <a href="{{ url('/adm') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->is('adm') ? 'bg-gray-700' : '' }}">Dashboard</a>
                @if($currentUser && $currentUser->is_super_admin)
                    <a href="{{ url('/adm/users') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->is('adm/users*') ? 'bg-gray-700' : '' }}">Users</a>
                @endif
                <a href="{{ url('/adm/viewers') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->is('adm/viewers*') ? 'bg-gray-700' : '' }}">Viewers</a>
                <a href="{{ url('/adm/videos') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->is('adm/videos*') ? 'bg-gray-700' : '' }}">Videos</a>
            </nav>
            <div class="absolute bottom-0 w-56 p-4 border-t border-gray-700">
                <p class="text-sm text-gray-300">{{ $currentUser ? explode(' ', $currentUser->name)[0] : '' }}</p>
                <div class="flex gap-3 mt-2">
                    <a href="{{ url('/') }}" class="text-xs text-gray-400 hover:text-white">View Site</a>
                    <form method="POST" action="{{ url('/logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-red-400 hover:text-red-300">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 p-8">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</body>
</html>
