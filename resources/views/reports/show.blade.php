@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">{{ $report->title }}</h1>
            <p class="text-gray-500">{{ $report->description }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.export', [$report->key, 'csv']) }}"
               class="px-3 py-2 bg-gray-200 rounded">CSV</a>
            <a href="{{ route('reports.export', [$report->key, 'xlsx']) }}"
               class="px-3 py-2 bg-green-600 text-white rounded">Excel</a>
            <a href="{{ route('reports.export', [$report->key, 'pdf']) }}"
               class="px-3 py-2 bg-red-600 text-white rounded">PDF</a>
        </div>
    </div>

    @if (!empty($report->summary))
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($report->summary as $k => $v)
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm text-gray-500">{{ $k }}</div>
                    <div class="text-2xl font-bold">{{ $v }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    @foreach ($report->columns as $col)
                        <th class="text-left px-4 py-2">{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($report->rows as $row)
                    <tr class="border-t">
                        @foreach ($row as $cell)
                            <td class="px-4 py-2">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ count($report->columns) }}"
                            class="text-center py-4 text-gray-500">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection