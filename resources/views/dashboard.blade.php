@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Executive Dashboard</h2>
@endsection

@section('content')
    @php
        // Clean Data Extraction (Kyunki Service sahi format me data de raha hai)
        $score = $metrics->complianceScore ?? 0;
        $riskSum = $metrics->riskSummary ?? [];
        $riskTotal = $riskSum['total'] ?? 0;
        $riskOpen = $riskSum['open'] ?? 0;
        $riskCritical = $riskSum['critical'] ?? 0;
        $riskByLevel = $riskSum;

        // Domain Arrays
        $domLabels = array_column($metrics->domainCompliance ?? [], 'domain');
        $domData = array_column($metrics->domainCompliance ?? [], 'score');

        // Trend Arrays
        $trendLabels = array_column($metrics->trend ?? [], 'date');
        $trendData = array_column($metrics->trend ?? [], 'score');

        // Counts
        $totalCtrls = $metrics->counts['controls'] ?? 115;
        $implemented = $score > 0 ? round(($score / 100) * $totalCtrls) : 0;
        $approved = $metrics->counts['documents'] ?? 0;
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Compliance Posture</h1>
                <p class="text-sm text-slate-500 mt-1">Real-time overview of your ISO 27001 readiness.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-500 font-medium">Last updated: {{ now()->format('M d, Y H:i') }}</span>
            </div>
        </div>

        {{-- ROW 1: KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Card 1: Compliance Score --}}
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10">
                    <svg class="w-16 h-16 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-500">Compliance Score</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-3xl font-mono font-semibold text-slate-900">{{ $score }}%</p>
                    <span class="text-xs font-medium text-emerald-600 flex items-center">
                        <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        Live
                    </span>
                </div>
            </div>

            {{-- Card 2: Open Risks --}}
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10">
                    <svg class="w-16 h-16 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-500">Open Risks</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-3xl font-mono font-semibold text-slate-900">{{ $riskOpen }}</p>
                    @if($riskCritical > 0)
                        <span class="text-xs font-medium text-red-600">{{ $riskCritical }} Critical</span>
                    @endif
                </div>
            </div>

            {{-- Card 3: Controls Implemented --}}
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10">
                    <svg class="w-16 h-16 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-500">Controls Implemented</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-3xl font-mono font-semibold text-slate-900">{{ $implemented }}<span
                            class="text-lg text-slate-400">/{{ $totalCtrls }}</span></p>
                </div>
            </div>

            {{-- Card 4: Documents Approved --}}
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10">
                    <svg class="w-16 h-16 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-500">Documents Approved</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <p class="text-3xl font-mono font-semibold text-slate-900">{{ $approved }}</p>
                </div>
            </div>
        </div>

        {{-- ROW 2: Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            {{-- Domain Compliance (Bar Chart) --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-4 md:p-6 shadow-sm overflow-hidden">
                <h3 class="text-base font-semibold text-slate-900 mb-4">Domain-wise Compliance</h3>
                <div class="h-64 md:h-72 w-full">
                    <canvas id="domainChart"></canvas>
                </div>
            </div>

            {{-- Risk Distribution (Doughnut Chart) --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4 md:p-6 shadow-sm flex flex-col">
                <h3 class="text-base font-semibold text-slate-900 mb-4">Risk Distribution</h3>
                <div class="flex-1 flex items-center justify-center relative h-64 md:h-72">
                    <canvas id="riskChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-mono font-bold text-slate-900">{{ $riskTotal }}</span>
                        <span class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Total Risks</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Trend Line --}}
        <div class="bg-white rounded-xl border border-slate-200 p-4 md:p-6 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-slate-900">Compliance Trend (12 Months)</h3>
            </div>
            <div class="h-64 md:h-80 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

    </div>

@endsection

{{-- CHART JS SCRIPTS --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748B';

            // 1. Domain Chart
            const domainCtx = document.getElementById('domainChart');
            if (domainCtx) {
                new Chart(domainCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($domLabels) !!},
                        datasets: [{
                            label: 'Compliance %',
                            data: {!! json_encode($domData) !!},
                            backgroundColor: '#4F46E5',
                            borderRadius: 4,
                            barThickness: 24,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: 100, grid: { borderDash: [2, 4], color: '#F1F5F9' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 2. Risk Chart
            const riskCtx = document.getElementById('riskChart');
            if (riskCtx) {
                new Chart(riskCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Critical', 'High', 'Medium', 'Low'],
                        datasets: [{
                            data: [
                                                                    {{ $riskByLevel['critical'] ?? 0 }},
                                                                    {{ $riskByLevel['high'] ?? 0 }},
                                                                    {{ $riskByLevel['medium'] ?? 0 }},
                                {{ $riskByLevel['low'] ?? 0 }}
                            ],
                            backgroundColor: ['#DC2626', '#F59E0B', '#FBBF24', '#10B981'],
                            borderWidth: 0,
                            cutout: '75%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } }
                    }
                });
            }

            // 3. Trend Chart
            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($trendLabels) !!},
                        datasets: [{
                            label: 'Compliance Score',
                            data: {!! json_encode($trendData) !!},
                            borderColor: '#4F46E5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#4F46E5'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: 100, grid: { borderDash: [2, 4], color: '#F1F5F9' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
@endpush