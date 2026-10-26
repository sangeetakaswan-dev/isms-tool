@extends('layouts.app')

@php
    // Fallback: agar controller ne $soaEntries pass nahi kiya to alternates check karo
    $soaEntries = $soaEntries ?? $entries ?? $soaEntriesList ?? collect();
    $assessment = $assessment ?? null;
@endphp

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Statement of Applicability</h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Statement of Applicability (SoA)</h1>
            <p class="text-sm text-slate-500 mt-1">Manage control applicability and justifications for your ISMS.</p>
        </div>
        <div class="flex items-center gap-3">
            @if(isset($assessment))
                <span class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-md">Assessment: {{ $assessment->title ?? 'Q4 2026' }}</span>
            @endif
            <a href="{{ route('soa.export.pdf', $assessment ?? 1) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- Summary & Progress --}}
    @php
        $totalControls = $soaEntries->count() ?? 0;
        $applicable = $soaEntries->where('applicable', true)->count() ?? 0;
        $approved = $soaEntries->whereNotNull('approved_at')->count() ?? 0;
        $percentage = $totalControls > 0 ? round(($approved / $totalControls) * 100) : 0;
    @endphp
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">SoA Completion</h3>
                <p class="text-sm text-slate-500 mt-1">All controls require a justification and approval.</p>
            </div>
            <div class="flex items-center gap-8">
                <div class="text-center">
                    <p class="text-2xl font-mono font-bold text-slate-900">{{ $totalControls }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Total Controls</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-mono font-bold text-indigo-600">{{ $applicable }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Applicable</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-mono font-bold text-emerald-600">{{ $approved }}</p>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Approved</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <div class="flex justify-between text-sm font-medium mb-2">
                <span class="text-slate-700">Approval Progress</span>
                <span class="text-indigo-600">{{ $percentage }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
                <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4 w-24">Code</th>
                        <th class="px-6 py-4">Control Title</th>
                        <th class="px-6 py-4 text-center">Applicability</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Approval</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($soaEntries->groupBy('control.domain.name') as $domainName => $entries)
                        {{-- Domain Header --}}
                        <tr class="bg-slate-50/50">
                            <td colspan="5" class="px-6 py-3 text-sm font-bold text-slate-900">
                                {{ $domainName ?? 'Unassigned Domain' }}
                            </td>
                        </tr>
                        
                        {{-- Controls under this domain --}}
                        @foreach($entries as $entry)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4 text-sm font-mono text-indigo-600 font-medium">
                                    {{ $entry->control->code ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $entry->control->title ?? 'Unknown Control' }}</div>
                                    @if($entry->justification)
                                        <div class="text-xs text-slate-500 mt-1 line-clamp-1" title="{{ $entry->justification }}">
                                            {{ $entry->justification }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($entry->applicable)
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-indigo-50 text-indigo-700">Yes</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-600">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'fully_implemented' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'partially_implemented' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'not_implemented' => 'bg-red-50 text-red-700 ring-red-600/20',
                                            'not_applicable' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                        ];
                                        $statusClass = $statusColors[$entry->implementation_status] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                                        $statusLabel = str_replace('_', ' ', $entry->implementation_status ?? 'pending');
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                        {{ ucfirst($statusLabel) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($entry->approved_at)
                                        <div class="flex items-center justify-end gap-1.5 text-sm text-emerald-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span class="font-medium">Approved</span>
                                        </div>
                                    @else
                                        <button class="text-sm font-medium text-indigo-600 hover:text-indigo-900 px-3 py-1 bg-indigo-50 rounded-md hover:bg-indigo-100 transition opacity-0 group-hover:opacity-100">
                                            Approve
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900">No SoA entries found</h3>
                                <p class="mt-1 text-sm text-slate-500">You need to generate the SoA from an assessment first.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection