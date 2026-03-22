<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Life Updates')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">Life Updates</a>
            <div class="flex items-center gap-4">
                @if($currentUser)
                    <a href="{{ url('/adm') }}" class="text-sm text-gray-600 hover:text-gray-800">Admin</a>
                    <span class="text-sm text-gray-500">{{ explode(' ', $currentUser->name)[0] }}</span>
                    <form method="POST" action="{{ url('/logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                    </form>
                @elseif($currentViewer)
                    <span class="text-sm text-gray-500">{{ explode(' ', $currentViewer->name)[0] }}</span>
                    <form method="POST" action="{{ url('/logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                    </form>
                @endif
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
