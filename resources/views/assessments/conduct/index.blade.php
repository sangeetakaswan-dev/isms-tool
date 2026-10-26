@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('assessments.show', $assessment) }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Conduct Assessment</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $assessment->title }}</h1>
                <p class="text-sm text-slate-500 mt-1">Evaluate each control and record evidence.</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $progress = $assessment->progress ?? 0;
                @endphp
                <div class="flex items-center gap-3 bg-white rounded-lg border border-slate-200 px-4 py-2 shadow-sm">
                    <div class="w-32 bg-slate-100 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                    <span class="text-sm font-mono font-semibold text-slate-700">{{ $progress }}%</span>
                </div>
            </div>
        </div>

        {{-- Controls List by Domain --}}
        <div class="space-y-6">
            @forelse($domains ?? [] as $domain)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    {{-- Domain Header --}}
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">{{ $domain->name }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $domain->controls->count() }} controls in this domain
                            </p>
                        </div>
                        <span class="text-xs font-mono text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded-md">
                            {{ $domain->code ?? '' }}
                        </span>
                    </div>

                    {{-- Controls --}}
                    <div class="divide-y divide-slate-100">
                        @foreach($domain->controls as $control)
                            @php
                                $response = $control->responses->first() ?? null;
                                $currentStatus = $response->compliance_status ?? null;
                            @endphp
                            <div class="p-6 hover:bg-slate-50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $control->code }}</span>
                                            @if($currentStatus)
                                                @php
                                                    $statusColors = [
                                                        'implemented' => 'bg-emerald-50 text-emerald-700',
                                                        'partially_implemented' => 'bg-amber-50 text-amber-700',
                                                        'not_implemented' => 'bg-red-50 text-red-700',
                                                        'not_applicable' => 'bg-slate-100 text-slate-600',
                                                    ];
                                                    $statusClass = $statusColors[$currentStatus] ?? 'bg-slate-100 text-slate-600';
                                                @endphp
                                                <span class="text-xs font-medium px-2 py-0.5 rounded {{ $statusClass }}">
                                                    {{ str_replace('_', ' ', ucfirst($currentStatus)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm font-medium text-slate-900">{{ $control->title }}</p>
                                        @if($control->description)
                                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $control->description }}</p>
                                        @endif
                                    </div>

                                    {{-- Quick Action --}}
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('assessments.responses.domain', [$assessment, $control->domain_id]) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg transition">
                                            {{ $currentStatus ? 'Update' : 'Evaluate' }}
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-slate-200 p-12 text-center shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No controls to evaluate</h3>
                    <p class="mt-1 text-sm text-slate-500">Your assessment has no controls yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection