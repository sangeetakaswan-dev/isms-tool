@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold">Executive Dashboard</h1>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Compliance Score</div>
            <div class="text-3xl font-bold text-green-600">{{ $data->complianceScore }}%</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Controls</div>
            <div class="text-3xl font-bold">{{ $data->counts['controls'] }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Open Risks</div>
            <div class="text-3xl font-bold text-red-600">{{ $data->counts['risks'] }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Documents</div>
            <div class="text-3xl font-bold">{{ $data->counts['documents'] }}</div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Domain-wise Compliance</h2>
            <canvas id="domainChart"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Maturity Distribution</h2>
            <canvas id="maturityChart"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Risk Summary</h2>
            <canvas id="riskChart"></canvas>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Document Status</h2>
            <canvas id="docChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">Compliance Trend</h2>
        <canvas id="trendChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const metrics = @json($data->toArray());

new Chart(document.getElementById('domainChart'), {
    type: 'bar',
    data: {
        labels: metrics.domain_compliance.map(d => d.domain),
        datasets: [{
            label: 'Score %',
            data: metrics.domain_compliance.map(d => d.score),
            backgroundColor: '#3b82f6'
        }]
    },
    options: { scales: { y: { beginAtZero: true, max: 100 } } }
});

new Chart(document.getElementById('maturityChart'), {
    type: 'pie',
    data: {
        labels: metrics.maturity_distribution.map(m => 'Level ' + m.level),
        datasets: [{
            data: metrics.maturity_distribution.map(m => m.count),
            backgroundColor: ['#ef4444','#f97316','#eab308','#84cc16','#22c55e','#15803d']
        }]
    }
});

new Chart(document.getElementById('riskChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(metrics.risk_summary),
        datasets: [{
            data: Object.values(metrics.risk_summary),
            backgroundColor: ['#dc2626','#f97316','#eab308','#22c55e']
        }]
    }
});

new Chart(document.getElementById('docChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(metrics.document_status),
        datasets: [{
            data: Object.values(metrics.document_status),
            backgroundColor: ['#64748b','#3b82f6','#22c55e','#ef4444']
        }]
    }
});

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: metrics.trend.map(t => t.date),
        datasets: [{
            label: 'Compliance %',
            data: metrics.trend.map(t => t.score),
            borderColor: '#10b981',
            tension: 0.3,
            fill: false
        }]
    },
    options: { scales: { y: { beginAtZero: true, max: 100 } } }
});
</script>
@endsection