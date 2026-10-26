@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('risks.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Risk Details</h2>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900">{{ $risk->title }}</h1>
                
                {{-- Level Badge --}}
                @php
                    $levelColors = [
                        'critical' => 'bg-red-50 text-red-700 ring-red-600/20',
                        'high' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
                        'medium' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        'low' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                    ];
                    $levelClass = $levelColors[$risk->risk_level] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                @endphp
                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $levelClass }} uppercase tracking-wide">
                    {{ $risk->risk_level }}
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Category: <span class="font-medium text-slate-700">{{ ucfirst($risk->category) }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('risks.edit', $risk) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition shadow-sm">
                Edit Risk
            </a>
            <a href="{{ route('risks.treatments.create', $risk) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Treatment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- LEFT COLUMN: Overview & Treatments --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Risk Overview Card --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Description</h3>
                <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $risk->description ?? 'No description provided.' }}</p>
            </div>

            {{-- Treatment Plans --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                    <h3 class="text-base font-semibold text-slate-900">Treatment Plans</h3>
                    <span class="text-xs font-medium text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded-md">{{ $risk->treatmentPlans->count() }} Total</span>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @forelse($risk->treatmentPlans as $plan)
                        <div class="p-6 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ ucfirst($plan->strategy) }}
                                        </span>
                                        @if($plan->status === 'completed')
                                            <span class="inline-flex items-center text-xs font-medium text-emerald-600">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium text-slate-900">{{ $plan->action }}</p>
                                    
                                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Due: {{ $plan->due_date ? $plan->due_date->format('M d, Y') : 'Not set' }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $plan->assignedUser->name ?? 'Unassigned' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    @if($plan->status !== 'completed')
                                        <form method="POST" action="{{ route('risks.treatments.complete', $plan) }}" class="flex flex-col gap-2 items-end">
                                            @csrf
                                            <input type="text" name="completion_notes" placeholder="Completion notes..." class="text-xs border-slate-300 rounded-md w-48 focus:border-indigo-500 focus:ring-indigo-500">
                                            <button type="submit" class="text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-md transition shadow-sm">
                                                Mark Complete
                                            </button>
                                        </form>
                                    @else
                                        <div class="text-xs text-slate-400">
                                            {{ $plan->completed_at ? $plan->completed_at->format('M d, Y') : '' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-sm text-slate-500">No treatment plans added yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Metadata --}}
        <div class="space-y-6">
            
            {{-- Risk Score Card --}}
            <div class="bg-slate-900 rounded-xl p-6 shadow-sm text-white">
                <h3 class="text-sm font-medium text-slate-300 mb-4">Risk Score</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-5xl font-mono font-bold">{{ $risk->risk_score }}</span>
                    <span class="text-lg text-slate-400">/ 25</span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-6 border-t border-slate-700 pt-4">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Likelihood</p>
                        <p class="text-xl font-mono">{{ $risk->likelihood }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Impact</p>
                        <p class="text-xl font-mono">{{ $risk->impact }}</p>
                    </div>
                </div>
            </div>

            {{-- Meta Info Card --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Details</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs text-slate-500 mb-1">Status</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ ucfirst($risk->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 mb-1">Owner</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $risk->owner->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 mb-1">Linked Asset</dt>
                        <dd class="text-sm font-medium text-slate-900">
                            @if($risk->asset)
                                <a href="{{ route('assets.show', $risk->asset) }}" class="text-indigo-600 hover:underline">{{ $risk->asset->name }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 mb-1">Identified Date</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $risk->identified_at ? $risk->identified_at->format('M d, Y') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 mb-1">Last Reviewed</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $risk->reviewed_at ? $risk->reviewed_at->format('M d, Y') : 'Not reviewed' }}</dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>
</div>
@endsection