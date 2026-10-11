@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Report Export History</h1>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-4 py-2">Date</th>
                    <th class="text-left px-4 py-2">Report</th>
                    <th class="text-left px-4 py-2">Format</th>
                    <th class="text-left px-4 py-2">By</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($history as $row)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">{{ $row->report_type }}</td>
                        <td class="px-4 py-2 uppercase">{{ $row->format }}</td>
                        <td class="px-4 py-2">{{ $row->user?->name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4 text-gray-500">No exports yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection