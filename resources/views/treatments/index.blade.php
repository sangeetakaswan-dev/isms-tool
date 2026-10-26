@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Treatment Plans</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        @php
            $plans = $treatments ?? $plans ?? collect();
        @endphp

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">All Treatment Plans</h1>
                <p class="text-sm text-slate-500 mt-1">Track mitigation strategies across all risks.</p>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Plans</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $plans->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-amber-600">Pending</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">
                    {{ $plans->where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-indigo-600">In Progress</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">
                    {{ $plans->where('status', 'in_progress')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-emerald-600">Completed</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">
                    {{ $plans->where('status', 'completed')->count() }}</p>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Action / Strategy</th>
                            <th class="px-6 py-4">Linked Risk</th>
                            <th class="px-6 py-4">Assignee</th>
                            <th class="px-6 py-4">Due Date</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $plan->action }}</div>
                                    <span
                                        class="inline-flex items-center rounded px-2 py-0.5 text-xs font-semibold bg-indigo-50 text-indigo-700 mt-1">
                                        {{ ucfirst($plan->strategy) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-indigo-600">
                                    @if($plan->risk)
                                        <a href="{{ route('risks.show', $plan->risk) }}"
                                            class="hover:underline">{{ $plan->risk->title }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $plan->assignedUser->name ?? 'Unassigned' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $plan->due_date ? $plan->due_date->format('M d, Y') : '—' }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'in_progress' => 'bg-indigo-50 text-indigo-700',
                                            'completed' => 'bg-emerald-50 text-emerald-700',
                                        ];
                                        $statusClass = $statusColors[$plan->status] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No treatment plans</h3>
                                    <p class="mt-1 text-sm text-slate-500">Go to a risk to add a treatment plan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection