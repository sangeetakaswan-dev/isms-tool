@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('assessments.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Assessment Detail</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900">{{ $assessment->title }}</h1>
                    @php
                        $statusColors = [
                            'draft' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                            'in_progress' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                            'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                            'archived' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        ];
                        $statusClass = $statusColors[$assessment->status] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                    @endphp
                    <span
                        class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClass }} uppercase tracking-wide">
                        {{ str_replace('_', ' ', $assessment->status) }}
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-1">Assessment ID: #{{ $assessment->id }} · Created
                    {{ $assessment->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('assessments.responses.index', $assessment) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Conduct Assessment
                </a>
            </div>
        </div>

        {{-- Progress Card --}}
        @php
            $progress = $assessment->progress ?? $assessment->completion_percentage ?? 0;
            $totalResponses = $assessment->responses()->count() ?? 0;
            $completedResponses = $assessment->responses()->whereNotNull('status')->where('status', '!=', 'not_assessed')->count() ?? 0;
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Assessment Progress</h3>
                    <p class="text-sm text-slate-500 mt-1">Complete control responses to calculate compliance score.</p>
                </div>
                <div class="flex items-center gap-8">
                    <div class="text-center">
                        <p class="text-2xl font-mono font-bold text-slate-900">{{ $totalResponses }}</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Total Controls</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-mono font-bold text-emerald-600">{{ $completedResponses }}</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Evaluated</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-mono font-bold text-indigo-600">{{ $progress }}%</p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Complete</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500"
                        style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Description & Stats --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Description</h3>
                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $assessment->description ?? 'No description provided.' }}
                    </p>
                </div>

                {{-- Domain Compliance Quick View --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                        <h3 class="text-base font-semibold text-slate-900">Domain Compliance</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($domainCompliance ?? [] as $domain)
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-medium text-slate-900">{{ $domain['domain'] ?? 'Domain' }}</span>
                                    <span
                                        class="text-sm font-mono font-semibold text-slate-700">{{ $domain['score'] ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ $domain['score'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-4">No compliance data yet. Start conducting the
                                assessment.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT: Metadata --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Details</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Lead Assessor</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ $assessment->leadAssessor->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Scope</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ ucfirst($assessment->scope ?? 'full') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Start Date</dt>
                            <dd class="text-sm font-medium text-slate-900">
                                {{ $assessment->start_date?->format('M d, Y') ?? $assessment->created_at->format('M d, Y') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500 mb-1">Target Completion</dt>
                            <dd class="text-sm font-medium text-slate-900">
                                {{ $assessment->end_date?->format('M d, Y') ?? 'Not set' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('assessments.responses.index', $assessment) }}"
                            class="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            Continue Assessment
                        </a>
                        @if(Route::has('soa.index'))
                            <a href="{{ route('soa.index', $assessment) }}"
                                class="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                View SoA
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection