<div>
    <!-- Legend -->
    <div class="mb-3 flex gap-4 text-sm">
        <span class="inline-block w-4 h-4 bg-green-500"></span> Low (1-4)
        <span class="inline-block w-4 h-4 bg-yellow-400"></span> Medium (5-9)
        <span class="inline-block w-4 h-4 bg-orange-500"></span> High (10-16)
        <span class="inline-block w-4 h-4 bg-red-600"></span> Critical (17-25)
    </div>

    <!-- Heatmap Grid -->
    <div class="grid grid-cols-6 gap-1 mb-2">
        <div></div>
        @for ($i = 1; $i <= 5; $i++)
            <div class="text-center font-semibold text-sm">Impact {{ $i }}</div>
        @endfor
    </div>

    @for ($likelihood = 1; $likelihood <= 5; $likelihood++)
        <div class="grid grid-cols-6 gap-1 mb-1">
            <div class="text-sm font-semibold flex items-center">Likelihood {{ $likelihood }}</div>
            @for ($impact = 1; $impact <= 5; $impact++)
                @php
                    $count = $heatmapData[$likelihood][$impact] ?? 0;
                    $score = $likelihood * $impact;
                    $bgColor = $score <= 4 ? '#10B981' : ($score <= 9 ? '#F59E0B' : ($score <= 16 ? '#F97316' : '#EF4444'));
                    $textColor = $score <= 4 ? '#000' : '#fff';
                @endphp
                <div
                    wire:click="filterByHeatmapCell({{ $likelihood }}, {{ $impact }})"
                    class="p-2 text-center rounded cursor-pointer transition hover:opacity-80"
                    style="background-color: {{ $bgColor }}; color: {{ $textColor }};"
                    title="Likelihood {{ $likelihood }}, Impact {{ $impact }}: {{ $count }} risk(s)"
                >
                    {{ $count }}
                </div>
            @endfor
        </div>
    @endfor

    <!-- Selected Cell Details -->
    @if ($selectedCell)
        <div class="mt-4 p-3 bg-gray-100 rounded">
            <strong>Selected: Likelihood {{ $selectedCell['likelihood'] }}, Impact {{ $selectedCell['impact'] }}</strong>
            <ul class="list-disc pl-5 mt-2">
                @forelse ($risks as $risk)
                    <li>{{ $risk->threat }} (Asset: {{ $risk->asset->name ?? 'N/A' }})</li>
                @empty
                    <li>No risks in this cell.</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>