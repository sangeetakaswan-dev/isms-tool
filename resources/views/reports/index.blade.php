@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Reports</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($types as $type)
            <a href="{{ route('reports.show', $type['key']) }}"
               class="block bg-white p-5 rounded shadow hover:shadow-lg transition">
                <div class="text-3xl">{{ $type['icon'] }}</div>
                <div class="font-semibold mt-2">{{ $type['title'] }}</div>
                <div class="text-sm text-gray-500">{{ $type['description'] }}</div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">
        <a href="{{ route('reports.history') }}" class="text-blue-600 hover:underline">
            → View export history
        </a>
    </div>
</div>
@endsection