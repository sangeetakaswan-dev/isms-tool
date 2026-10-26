@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Risk Register</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header & Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Risks</h1>
                <p class="text-sm text-slate-500 mt-1">Identify, assess, and treat information security risks.</p>
            </div>
            <a href="{{ route('risks.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Risk
            </a>
        </div>

        {{-- KPI Cards --}}
        @php
            $total = $risks->count() ?? 0;
            $critical = $risks->where('risk_level', 'critical')->count() ?? 0;
            $high = $risks->where('risk_level', 'high')->count() ?? 0;
            $open = $risks->whereNotIn('status', ['closed'])->count() ?? 0;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Risks</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $total }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-red-600">Critical / High</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $critical + $high }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-amber-600">Open Risks</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $open }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-emerald-600">Treated / Closed</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $total - $open }}</p>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Risk Title</th>
                            <th class="px-6 py-4">Asset</th>
                            <th class="px-6 py-4 text-center">Score</th>
                            <th class="px-6 py-4">Level</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Owner</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($risks as $risk)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <a href="{{ route('risks.show', $risk) }}"
                                        class="font-medium text-indigo-600 hover:text-indigo-900">
                                        {{ $risk->title }}
                                    </a>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ ucfirst($risk->category ?? 'General') }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $risk->asset->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 font-mono font-bold text-slate-700">
                                        {{ $risk->risk_score }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $levelColors = [
                                            'critical' => 'bg-red-50 text-red-700 ring-red-600/20',
                                            'high' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
                                            'medium' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'low' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                        ];
                                        $levelClass = $levelColors[$risk->risk_level] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $levelClass }}">
                                        {{ ucfirst($risk->risk_level) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'identified' => 'bg-blue-50 text-blue-700',
                                            'assessed' => 'bg-purple-50 text-purple-700',
                                            'treated' => 'bg-indigo-50 text-indigo-700',
                                            'monitored' => 'bg-teal-50 text-teal-700',
                                            'closed' => 'bg-slate-100 text-slate-700',
                                        ];
                                        $statusClass = $statusColors[$risk->status] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($risk->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $risk->owner->name ?? 'Unassigned' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div
                                        class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('risks.show', $risk) }}"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition"
                                            title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('risks.edit', $risk) }}"
                                            class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No risks found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Get started by creating a new risk.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('risks.create') }}"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            New Risk
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