@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Reports & Analytics</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Executive Reports</h1>
                <p class="text-sm text-slate-500 mt-1">Generate and export compliance reports for auditors and management.
                </p>
            </div>
            <a href="{{ route('reports.history') }}"
                class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Export History
            </a>
        </div>

        {{-- Reports Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- 1. Executive Summary --}}
            <div
                class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center mb-4 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Executive Summary</h3>
                <p class="text-sm text-slate-500 mb-6 flex-1">High-level compliance posture, domain scores, and maturity
                    overview for management.</p>
                <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reports.export', ['type' => 'executive_summary', 'format' => 'csv']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition">CSV</a>
                    <a href="{{ route('reports.export', ['type' => 'executive_summary', 'format' => 'pdf']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-red-50 text-red-700 rounded hover:bg-red-100 transition">PDF</a>
                    <a href="{{ route('reports.export', ['type' => 'executive_summary', 'format' => 'xlsx']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition">Excel</a>
                </div>
            </div>

            {{-- 2. Gap Analysis --}}
            <div
                class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center mb-4 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Gap Analysis</h3>
                <p class="text-sm text-slate-500 mb-6 flex-1">Applicable controls that are not yet fully implemented,
                    prioritized by domain.</p>
                <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reports.export', ['type' => 'gap_analysis', 'format' => 'csv']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition">CSV</a>
                    <a href="{{ route('reports.export', ['type' => 'gap_analysis', 'format' => 'pdf']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-red-50 text-red-700 rounded hover:bg-red-100 transition">PDF</a>
                    <a href="{{ route('reports.export', ['type' => 'gap_analysis', 'format' => 'xlsx']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition">Excel</a>
                </div>
            </div>

            {{-- 3. Risk Register --}}
            <div
                class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center mb-4 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Risk Register</h3>
                <p class="text-sm text-slate-500 mb-6 flex-1">Full list of identified risks, treatment plans, and owners.
                </p>
                <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reports.export', ['type' => 'risk_register', 'format' => 'csv']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition">CSV</a>
                    <a href="{{ route('reports.export', ['type' => 'risk_register', 'format' => 'pdf']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-red-50 text-red-700 rounded hover:bg-red-100 transition">PDF</a>
                    <a href="{{ route('reports.export', ['type' => 'risk_register', 'format' => 'xlsx']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition">Excel</a>
                </div>
            </div>

            {{-- 4. SoA Status --}}
            <div
                class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mb-4 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">SoA Status</h3>
                <p class="text-sm text-slate-500 mb-6 flex-1">Statement of Applicability with justification and
                    implementation status per control.</p>
                <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reports.export', ['type' => 'soa_status', 'format' => 'csv']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition">CSV</a>
                    <a href="{{ route('reports.export', ['type' => 'soa_status', 'format' => 'pdf']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-red-50 text-red-700 rounded hover:bg-red-100 transition">PDF</a>
                    <a href="{{ route('reports.export', ['type' => 'soa_status', 'format' => 'xlsx']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition">Excel</a>
                </div>
            </div>

            {{-- 5. Document Compliance --}}
            <div
                class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center mb-4 text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Document Compliance</h3>
                <p class="text-sm text-slate-500 mb-6 flex-1">Review status and upcoming due dates for all ISMS policies and
                    documents.</p>
                <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reports.export', ['type' => 'document_compliance', 'format' => 'csv']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition">CSV</a>
                    <a href="{{ route('reports.export', ['type' => 'document_compliance', 'format' => 'pdf']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-red-50 text-red-700 rounded hover:bg-red-100 transition">PDF</a>
                    <a href="{{ route('reports.export', ['type' => 'document_compliance', 'format' => 'xlsx']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition">Excel</a>
                </div>
            </div>

            {{-- 6. Asset Inventory --}}
            <div
                class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Asset Inventory</h3>
                <p class="text-sm text-slate-500 mb-6 flex-1">Full register of information assets with ownership and CIA
                    ratings.</p>
                <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reports.export', ['type' => 'asset_inventory', 'format' => 'csv']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition">CSV</a>
                    <a href="{{ route('reports.export', ['type' => 'asset_inventory', 'format' => 'pdf']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-red-50 text-red-700 rounded hover:bg-red-100 transition">PDF</a>
                    <a href="{{ route('reports.export', ['type' => 'asset_inventory', 'format' => 'xlsx']) }}"
                        class="flex-1 text-center py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition">Excel</a>
                </div>
            </div>

        </div>
    </div>
@endsection