@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('reports.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Report Preview</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ ucwords(str_replace('_', ' ', $type)) }}</h1>
                <p class="text-sm text-slate-500 mt-1">Preview generated on {{ now()->format('M d, Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.export', ['type' => $type, 'format' => 'csv']) }}"
                    class="px-3 py-1.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition">CSV</a>
                <a href="{{ route('reports.export', ['type' => $type, 'format' => 'pdf']) }}"
                    class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-700 rounded hover:bg-red-100 transition">PDF</a>
                <a href="{{ route('reports.export', ['type' => $type, 'format' => 'xlsx']) }}"
                    class="px-3 py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition">Excel</a>
            </div>
        </div>

        {{-- Document Preview (Paper Style) --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-xl overflow-hidden min-h-[600px] flex flex-col">

            {{-- Document Header --}}
            <div class="p-8 md:p-12 border-b border-slate-200 bg-slate-50">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded bg-indigo-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span class="font-bold text-slate-900 text-lg">ISMS Tool</span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ ucwords(str_replace('_', ' ', $type)) }}</h2>
                        <p class="text-sm text-slate-500 mt-1">ISO/IEC 27001:2022 Compliance Report</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Generated For</p>
                        <p class="text-sm font-medium text-slate-900">
                            {{ auth()->user()->currentTenant->name ?? 'Organization' }}</p>
                        <p class="text-xs text-slate-500 mt-2">Date: {{ now()->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Document Body --}}
            <div class="p-8 md:p-12 flex-1">
                @if(isset($data) && count($data) > 0)
                    {{-- If report has data, render as clean table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b-2 border-slate-900">
                                    @foreach(array_keys((array) $data[0]) as $header)
                                        <th class="py-3 px-4 text-xs font-bold text-slate-900 uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $header) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($data as $row)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        @foreach((array) $row as $cell)
                                            <td class="py-3 px-4 text-sm text-slate-700">
                                                @if(is_array($cell))
                                                    {{ json_encode($cell) }}
                                                @else
                                                    {{ $cell ?? '—' }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-20">
                        <svg class="mx-auto h-16 w-16 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-slate-900">No data available</h3>
                        <p class="mt-1 text-sm text-slate-500">There is no data to display for this report yet.</p>
                    </div>
                @endif
            </div>

            {{-- Document Footer --}}
            <div class="p-8 border-t border-slate-200 bg-slate-50 text-center">
                <p class="text-xs text-slate-400">Confidential — Generated by ISMS Tool for
                    {{ auth()->user()->currentTenant->name ?? 'Organization' }}</p>
            </div>
        </div>

    </div>
@endsection