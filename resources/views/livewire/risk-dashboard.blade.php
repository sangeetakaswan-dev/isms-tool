<div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Total Risks</h3>
            <p class="text-3xl">{{ $risks->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Critical Risks</h3>
            <p class="text-3xl text-red-600">{{ $criticalRisks->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Pending Treatments</h3>
            <p class="text-3xl text-yellow-600">{{ $treatmentPending }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">High Risks</h3>
            <p class="text-3xl text-orange-600">{{ $risks->where('risk_level', 'high')->count() }}</p>
        </div>
    </div>

    <!-- Risk Heatmap Section -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">Risk Heatmap</h3>
        @livewire('risk-heatmap', ['risks' => $risks])
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Treatment Distribution -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Treatment Distribution</h3>
            <canvas id="treatmentChart" height="200"></canvas>
        </div>
        <!-- Risk Trend -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Risk Trend Over Time</h3>
            <canvas id="trendChart" height="200"></canvas>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:load', function () {
                // Treatment Chart Data
                const treatmentData = @json($this->treatmentStats);
                const treatmentCtx = document.getElementById('treatmentChart').getContext('2d');
                new Chart(treatmentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(treatmentData),
                        datasets: [{
                            data: Object.values(treatmentData),
                            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                        }]
                    },
                    options: { responsive: true }
                });

                // Trend Chart Data
                const trendData = @json($this->trendData);
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(trendData),
                        datasets: [{
                            label: 'Risks Identified',
                            data: Object.values(trendData),
                            borderColor: '#3B82F6',
                            tension: 0.3,
                            fill: false
                        }]
                    },
                    options: { responsive: true }
                });
            });
        </script>
    @endpush
</div>