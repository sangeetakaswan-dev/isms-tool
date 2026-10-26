@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Asset Register</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header & Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Assets</h1>
                <p class="text-sm text-slate-500 mt-1">Manage your information assets and their CIA ratings.</p>
            </div>
            <a href="{{ route('assets.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Asset
            </a>
        </div>

        {{-- KPI Cards --}}
        @php
            $total = $assets->count();
            $hardware = $assets->where('asset_type', 'hardware')->count();
            $software = $assets->where('asset_type', 'software')->count();
            $data = $assets->where('asset_type', 'data')->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Assets</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $total }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-blue-600">Hardware</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $hardware }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-purple-600">Software</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $software }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-amber-600">Data</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $data }}</p>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Asset Name</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4 text-center">CIA Ratings</th>
                            <th class="px-6 py-4">Owner</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $asset->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $asset->location ?? 'Location not specified' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'hardware' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                            'software' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                            'data' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'facility' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                            'people' => 'bg-pink-50 text-pink-700 ring-pink-600/20',
                                            'service' => 'bg-teal-50 text-teal-700 ring-teal-600/20',
                                        ];
                                        $colorClass = $typeColors[$asset->asset_type] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colorClass }}">
                                        {{ ucfirst($asset->asset_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Confidentiality --}}
                                        <div class="flex flex-col items-center" title="Confidentiality">
                                            <span
                                                class="text-[10px] text-slate-400 font-semibold uppercase leading-none mb-1">C</span>
                                            <span
                                                class="inline-flex items-center justify-center w-6 h-6 rounded bg-slate-100 text-xs font-mono font-bold text-slate-700">{{ $asset->confidentiality_rating }}</span>
                                        </div>
                                        <div class="w-px h-6 bg-slate-200 mx-1"></div>
                                        {{-- Integrity --}}
                                        <div class="flex flex-col items-center" title="Integrity">
                                            <span
                                                class="text-[10px] text-slate-400 font-semibold uppercase leading-none mb-1">I</span>
                                            <span
                                                class="inline-flex items-center justify-center w-6 h-6 rounded bg-slate-100 text-xs font-mono font-bold text-slate-700">{{ $asset->integrity_rating }}</span>
                                        </div>
                                        <div class="w-px h-6 bg-slate-200 mx-1"></div>
                                        {{-- Availability --}}
                                        <div class="flex flex-col items-center" title="Availability">
                                            <span
                                                class="text-[10px] text-slate-400 font-semibold uppercase leading-none mb-1">A</span>
                                            <span
                                                class="inline-flex items-center justify-center w-6 h-6 rounded bg-slate-100 text-xs font-mono font-bold text-slate-700">{{ $asset->availability_rating }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $asset->owner ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div
                                        class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('assets.show', $asset) }}"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition"
                                            title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('assets.edit', $asset) }}"
                                            class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this asset?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No assets found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Get started by creating a new asset.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('assets.create') }}"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                            </svg>
                                            New Asset
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection