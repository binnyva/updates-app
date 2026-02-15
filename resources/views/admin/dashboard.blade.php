@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Videos</p>
            <p class="text-3xl font-bold mt-1">{{ $videoCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Viewers</p>
            <p class="text-3xl font-bold mt-1">{{ $viewerCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Comments</p>
            <p class="text-3xl font-bold mt-1">{{ $commentCount }}</p>
        </div>
    </div>
@endsection
