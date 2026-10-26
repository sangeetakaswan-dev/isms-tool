@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('assets.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Asset Details</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900">{{ $asset->name }}</h1>
                    @php
                        $typeColors = [
                            'hardware' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            'software' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                            'data' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                            'facility' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                            'people' => 'bg-pink-50 text-pink-700 ring-pink-600/20',
                            'service' => 'bg-teal-50 text-teal-700 ring-teal-600/20',
                        ];
                        $typeClass = $typeColors[$asset->asset_type] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                    @endphp
                    <span
                        class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $typeClass }}">
                        {{ ucfirst($asset->asset_type) }}
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-1">Asset ID: #{{ $asset->id }}</p>
            </div>
            <a href="{{ route('assets.edit', $asset) }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition shadow-sm">
                Edit Asset
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Info --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Description --}}
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Description</h3>
                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $asset->description ?? 'No description provided.' }}</p>
                </div>

                {{-- CIA Ratings --}}
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">CIA Ratings</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-600 font-semibold uppercase tracking-wider">Confidentiality</p>
                            <p class="text-4xl font-mono font-bold text-blue-900 mt-2">{{ $asset->confidentiality_rating }}
                            </p>
                            <p class="text-xs text-blue-500 mt-1">out of 5</p>
                        </div>
                        <div class="text-center p-4 bg-emerald-50 rounded-lg">
                            <p class="text-xs text-emerald-600 font-semibold uppercase tracking-wider">Integrity</p>
                            <p class="text-4xl font-mono font-bold text-emerald-900 mt-2">{{ $asset->integrity_rating }}</p>
                            <p class="text-xs text-emerald-500 mt-1">out of 5</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-xs text-purple-600 font-semibold uppercase tracking-wider">Availability</p>
                            <p class="text-4xl font-mono font-bold text-purple-900 mt-2">{{ $asset->availability_rating }}
                            </p>
                            <p class="text-xs text-purple-500 mt-1">out of 5</p>
                        </div>
                    </div>
                </div>

                {{-- Linked Risks --}}
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Linked Risks</h3>
                    @if(isset($asset->risks) && $asset->risks->count() > 0)
                        <ul class="space-y-2">
                            @foreach($asset->risks as $risk)
                                <li
                                    class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition">
                                    <div>
                                        <a href="{{ route('risks.show', $risk) }}"
                                            class="font-medium text-indigo-600 hover:underline">{{ $risk->title }}</a>
                                        <p class="text-xs text-slate-500 mt-0.5">Score: {{ $risk->risk_score }} ·
                                            {{ ucfirst($risk->risk_level) }}</p>
                                    </div>
                                    @php
                                        $lvlColors = [
                                            'critical' => 'bg-red-100 text-red-700',
                                            'high' => 'bg-orange-100 text-orange-700',
                                            'medium' => 'bg-amber-100 text-amber-700',
                                            'low' => 'bg-emerald-100 text-emerald-700',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-medium rounded {{ $lvlColors[$risk->risk_level] ?? '' }}">
                                        {{ ucfirst($risk->risk_level) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-slate-500">No risks linked to this asset.</p>
                    @endif
                </div>

            </div>

            {{-- RIGHT: Metadata --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Details</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Owner</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $asset->owner ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Department</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $asset->department ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Location</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $asset->location ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Created</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $asset->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection