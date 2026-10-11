<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold mb-4">Risk Heatmap (Likelihood × Impact)</h3>
    <div id="riskHeatmap" class="grid gap-1" style="grid-template-columns: 40px repeat(5, 1fr);">
        {{-- Header row --}}
        <div></div>
        @for($i = 1; $i <= 5; $i++)
            <div class="text-center text-xs text-gray-500 py-1">I{{ $i }}</div>
        @endfor

        {{-- Rows --}}
        @for($l = 5; $l >= 1; $l--)
            <div class="text-xs text-gray-500 flex items-center">L{{ $l }}</div>
            @for($i = 1; $i <= 5; $i++)
                @php
                    $score = $l * $i;
                    $color = match(true) {
                        $score >= 20 => 'bg-red-500',
                        $score >= 12 => 'bg-orange-400',
                        $score >= 6  => 'bg-yellow-300',
                        default      => 'bg-green-300',
                    };
                @endphp
                <div class="heatmap-cell {{ $color }} text-white text-center py-3 rounded cursor-pointer hover:opacity-80"
                     data-l="{{ $l }}" data-i="{{ $i }}" title="L{{ $l }} × I{{ $i }} = {{ $score }}">
                    <span class="text-sm font-bold" data-count>0</span>
                </div>
            @endfor
        @endfor
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('{{ route('risks.heatmap.data') }}');
        const data = await res.json();

        document.querySelectorAll('.heatmap-cell').forEach(cell => {
            const l = cell.dataset.l;
            const i = cell.dataset.i;
            const count = data.grid[l]?.[i] ?? 0;
            cell.querySelector('[data-count]').textContent = count;
        });

        document.querySelectorAll('.heatmap-cell').forEach(cell => {
            cell.addEventListener('click', () => {
                window.location.href = `{{ route('risks.index') }}?level=&likelihood=${cell.dataset.l}&impact=${cell.dataset.i}`;
            });
        });
    } catch (e) {
        console.error('Heatmap load failed', e);
    }
});
</script>
@endpush