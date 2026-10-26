@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('assessments.responses.index', $assessment) }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">{{ $domain->name ?? 'Domain' }} — Controls</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Domain Header --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <span
                        class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $domain->code }}</span>
                    <h1 class="text-xl font-bold text-slate-900 mt-2">{{ $domain->name }}</h1>
                    <p class="text-sm text-slate-500 mt-1">{{ $domain->description }}</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-mono font-bold text-slate-900">{{ $domain->controls->count() }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Controls</p>
                </div>
            </div>
        </div>

        {{-- Controls List --}}
        <div class="space-y-4">
            @forelse($domain->controls as $control)
                @php
                    // Find existing response
                    $response = \App\Models\AssessmentResponse::where('assessment_id', $assessment->id)
                        ->where('control_id', $control->id)
                        ->first();
                @endphp
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-start gap-3 mb-4">
                        <span
                            class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded flex-shrink-0">{{ $control->code }}</span>
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $control->title }}</h3>
                            <p class="text-sm text-slate-500 mt-1">{{ $control->description }}</p>
                        </div>
                    </div>

                    {{-- Response Form --}}
                    <form method="POST" action="{{ route('assessments.responses.update', [$assessment, $response?->id ?? 0]) }}"
                        class="border-t border-slate-100 pt-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Compliance Status</label>
                                <select name="status"
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="not_assessed" @selected($response?->status === 'not_assessed')>Not Assessed
                                    </option>
                                    <option value="compliant" @selected($response?->status === 'compliant')>Compliant</option>
                                    <option value="partially_compliant" @selected($response?->status === 'partially_compliant')>
                                        Partially Compliant</option>
                                    <option value="non_compliant" @selected($response?->status === 'non_compliant')>Non-Compliant
                                    </option>
                                    <option value="not_applicable" @selected($response?->status === 'not_applicable')>Not
                                        Applicable</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Maturity Level (0-5)</label>
                                <input type="number" name="maturity_level" min="0" max="5"
                                    value="{{ $response?->maturity_level ?? 0 }}"
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Evidence Notes</label>
                                <input type="text" name="evidence_notes" value="{{ $response?->evidence_notes }}"
                                    placeholder="Short note..."
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                Save Response
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-slate-200 p-12 text-center shadow-sm">
                    <p class="text-slate-500">No controls in this domain.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection