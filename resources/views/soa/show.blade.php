@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('soa.index', $assessment ?? 1) }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">SoA Entry Details</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        @php
            $entry = $soaEntry ?? $entry ?? null;
        @endphp

        @if($entry)
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <span
                            class="text-sm font-mono font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-md">{{ $entry->control->code ?? 'N/A' }}</span>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $entry->control->title ?? 'Control Title' }}</h1>
                    </div>
                    <p class="text-sm text-slate-500 mt-1">{{ $entry->control->domain->name ?? 'Domain' }}</p>
                </div>
                <div>
                    @if($entry->applicable)
                        <span
                            class="inline-flex items-center rounded-md px-3 py-1.5 text-sm font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Applicable</span>
                    @else
                        <span
                            class="inline-flex items-center rounded-md px-3 py-1.5 text-sm font-semibold bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-600/20">Not
                            Applicable</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: Justification & Implementation --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                        <h3 class="text-base font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Justification
                        </h3>
                        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $entry->justification ?? 'No justification provided.' }}</p>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                        <h3 class="text-base font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Implementation
                            Details</h3>
                        <div class="space-y-4">
                            <div>
                                <dt class="text-xs text-slate-500 mb-1">Implementation Status</dt>
                                <dd class="text-sm font-medium text-slate-900">
                                    {{ str_replace('_', ' ', ucfirst($entry->implementation_status ?? 'pending')) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500 mb-1">Description</dt>
                                <dd class="text-sm text-slate-700">{{ $entry->implementation_description ?? 'No description.' }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Metadata & Approval --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                        <h3 class="text-sm font-semibold text-slate-900 mb-4 border-b border-slate-100 pb-3">Approval Status
                        </h3>
                        @if($entry->approved_at)
                            <div class="flex items-center gap-2 text-emerald-600 mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-bold">Approved</span>
                            </div>
                            <p class="text-xs text-slate-500">By {{ $entry->approver->name ?? 'System' }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $entry->approved_at->format('M d, Y H:i') }}</p>
                        @else
                            <div class="flex items-center gap-2 text-amber-600 mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-bold">Pending Approval</span>
                            </div>
                            <form method="POST" action="{{ route('soa.approve', $entry) }}" class="mt-4">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                    Approve Entry
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 p-12 text-center shadow-sm">
                <p class="text-slate-500">SoA entry not found.</p>
            </div>
        @endif
    </div>
@endsection