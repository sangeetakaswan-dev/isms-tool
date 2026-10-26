@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('soa.index', $assessment ?? 1) }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Compare Versions</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Statement of Applicability — Comparison</h1>
                <p class="text-sm text-slate-500 mt-1">Compare changes between two SoA versions.</p>
            </div>
        </div>

        {{-- Version Selector --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form method="GET" action="{{ route('soa.compare', $assessment ?? 1) }}"
                class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">From Version (Old)</label>
                    <select name="from_version"
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach($versions ?? [] as $v)
                            <option value="{{ $v->id }}" @selected(request('from_version') == $v->id)>
                                v{{ $v->version }} — {{ $v->created_at->format('M d, Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-center pb-2">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">To Version (New)</label>
                    <select name="to_version"
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach($versions ?? [] as $v)
                            <option value="{{ $v->id }}" @selected(request('to_version') == $v->id)>
                                v{{ $v->version }} — {{ $v->created_at->format('M d, Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    Compare
                </button>
            </form>
        </div>

        {{-- Summary Cards --}}
        @php
            $addedCount = count($added ?? []);
            $removedCount = count($removed ?? []);
            $changedCount = count($changed ?? []);
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-emerald-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Added</p>
                        <p class="text-2xl font-mono font-bold text-emerald-600">{{ $addedCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-red-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Removed</p>
                        <p class="text-2xl font-mono font-bold text-red-600">{{ $removedCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-amber-200 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Changed</p>
                        <p class="text-2xl font-mono font-bold text-amber-600">{{ $changedCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Diff Table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-base font-semibold text-slate-900">Changes Detail</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-3 w-24">Code</th>
                            <th class="px-6 py-3">Control</th>
                            <th class="px-6 py-3 w-32">Change</th>
                            <th class="px-6 py-3">Old Value</th>
                            <th class="px-6 py-3">New Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- Added Items --}}
                        @foreach($added ?? [] as $item)
                            <tr class="bg-emerald-50/30 hover:bg-emerald-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-mono text-emerald-700 font-semibold">
                                    {{ $item['code'] ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $item['title'] ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded bg-emerald-100 text-emerald-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Added
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-400 italic">Not present</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $item['new_value'] ?? 'Applicable' }}</td>
                            </tr>
                        @endforeach

                        {{-- Removed Items --}}
                        @foreach($removed ?? [] as $item)
                            <tr class="bg-red-50/30 hover:bg-red-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-mono text-red-700 font-semibold">{{ $item['code'] ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $item['title'] ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4" />
                                        </svg>
                                        Removed
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $item['old_value'] ?? 'Applicable' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-400 italic">Not present</td>
                            </tr>
                        @endforeach

                        {{-- Changed Items --}}
                        @foreach($changed ?? [] as $item)
                            <tr class="bg-amber-50/30 hover:bg-amber-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-mono text-amber-700 font-semibold">{{ $item['code'] ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $item['title'] ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded bg-amber-100 text-amber-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Changed
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-red-600 line-through">{{ $item['old_value'] ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-emerald-600 font-medium">{{ $item['new_value'] ?? '—' }}</td>
                            </tr>
                        @endforeach

                        {{-- Empty State --}}
                        @if(($addedCount + $removedCount + $changedCount) === 0)
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No changes detected</h3>
                                    <p class="mt-1 text-sm text-slate-500">The two versions are identical.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection