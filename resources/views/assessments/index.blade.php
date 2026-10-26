@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Assessments</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header & Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Compliance Assessments</h1>
                <p class="text-sm text-slate-500 mt-1">Manage your ISO 27001 readiness assessments.</p>
            </div>
            <a href="{{ route('assessments.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Assessment
            </a>
        </div>

        {{-- KPI Cards --}}
        @php
            // Safe extraction in case variables are slightly different
            $totalAssessments = $assessments->count();
            $inProgress = $assessments->where('status', 'in_progress')->count();
            $completed = $assessments->where('status', 'completed')->count();
            $draft = $assessments->where('status', 'draft')->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Assessments</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $totalAssessments }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-indigo-600">In Progress</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $inProgress }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-emerald-600">Completed</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $completed }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-400">Drafts</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $draft }}</p>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Assessment Name</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Progress</th>
                            <th class="px-6 py-4">Lead Assessor</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($assessments as $assessment)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <a href="{{ route('assessments.show', $assessment) }}"
                                        class="font-medium text-indigo-600 hover:text-indigo-900">
                                        {{ $assessment->title ?? $assessment->name ?? 'Untitled Assessment' }}
                                    </a>
                                    <div class="text-xs text-slate-500 mt-0.5">ID: #{{ $assessment->id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-slate-100 text-slate-700',
                                            'in_progress' => 'bg-indigo-50 text-indigo-700',
                                            'completed' => 'bg-emerald-50 text-emerald-700',
                                            'archived' => 'bg-amber-50 text-amber-700',
                                        ];
                                        $statusClass = $statusColors[$assessment->status] ?? 'bg-slate-100 text-slate-700';
                                        $statusLabel = str_replace('_', ' ', $assessment->status ?? 'draft');
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($statusLabel) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        // Fallback calculation if progress column is missing
                                        $progress = $assessment->progress ?? $assessment->completion_percentage ?? 0;
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="w-full bg-slate-200 rounded-full h-2 max-w-[100px]">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-mono font-medium text-slate-600">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $assessment->leadAssessor->name ?? $assessment->user->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $assessment->created_at ? $assessment->created_at->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div
                                        class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('assessments.show', $assessment) }}"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition"
                                            title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('assessments.show', $assessment) }}"
                                            class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition"
                                            title="Conduct Assessment">
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No assessments found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Get started by creating your first assessment.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('assessments.create') }}"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            New Assessment
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