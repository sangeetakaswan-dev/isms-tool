@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('reports.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Export History</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Export History</h1>
                <p class="text-sm text-slate-500 mt-1">Audit trail of all generated reports.</p>
            </div>
        </div>

        {{-- KPI Cards --}}
        @php
            $totalExports = $reports->count() ?? 0;
            $lastExport = $reports->first() ? $reports->first()->created_at->diffForHumans() : 'Never';
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Exports</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $totalExports }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Last Exported</p>
                <p class="text-xl font-semibold text-slate-900 mt-2">{{ $lastExport }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Formats Used</p>
                <div class="flex items-center gap-2 mt-3">
                    <span
                        class="px-2 py-1 text-xs font-medium bg-red-50 text-red-700 rounded border border-red-100">PDF</span>
                    <span
                        class="px-2 py-1 text-xs font-medium bg-emerald-50 text-emerald-700 rounded border border-emerald-100">Excel</span>
                    <span
                        class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded border border-slate-200">CSV</span>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Report Type</th>
                            <th class="px-6 py-4">Format</th>
                            <th class="px-6 py-4">Exported By</th>
                            <th class="px-6 py-4">Date & Time</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reports as $report)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-slate-900">
                                        {{ ucwords(str_replace('_', ' ', $report->report_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $formatColors = [
                                            'pdf' => 'bg-red-50 text-red-700 ring-red-600/20',
                                            'xlsx' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'csv' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                        ];
                                        $formatClass = $formatColors[$report->format] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold uppercase tracking-wider ring-1 ring-inset {{ $formatClass }}">
                                        {{ $report->format }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                                            {{ substr($report->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="text-sm text-slate-600">{{ $report->user->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $report->created_at->format('M d, Y') }}
                                    <span class="text-xs text-slate-400 ml-1">{{ $report->created_at->format('H:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($report->file_path)
                                        <a href="{{ Storage::url($report->file_path) }}"
                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Download</a>
                                    @else
                                        <span class="text-xs text-slate-400">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No export history</h3>
                                    <p class="mt-1 text-sm text-slate-500">You haven't exported any reports yet.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('reports.index') }}"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            Generate a Report
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($reports->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection