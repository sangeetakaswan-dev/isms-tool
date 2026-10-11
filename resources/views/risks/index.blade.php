<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Risk Register</h2>
            <a href="{{ route('risks.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                + New Risk
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="text-2xl font-bold">{{ $summary['total'] }}</div>
                </div>
                <div class="bg-red-50 shadow rounded-lg p-4">
                    <div class="text-sm text-red-600">Critical</div>
                    <div class="text-2xl font-bold text-red-700">{{ $summary['critical'] }}</div>
                </div>
                <div class="bg-orange-50 shadow rounded-lg p-4">
                    <div class="text-sm text-orange-600">High</div>
                    <div class="text-2xl font-bold text-orange-700">{{ $summary['high'] }}</div>
                </div>
                <div class="bg-blue-50 shadow rounded-lg p-4">
                    <div class="text-sm text-blue-600">Open</div>
                    <div class="text-2xl font-bold text-blue-700">{{ $summary['open'] }}</div>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">Closed</div>
                    <div class="text-2xl font-bold">{{ $summary['by_status']['closed'] ?? 0 }}</div>
                </div>
            </div>

            {{-- Risk Table --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($risks as $risk)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('risks.show', $risk) }}" class="text-indigo-600 hover:underline font-medium">
                                        {{ $risk->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $risk->asset?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">{{ $risk->risk_score }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded
                                        @if($risk->risk_level === 'critical') bg-red-100 text-red-700
                                        @elseif($risk->risk_level === 'high') bg-orange-100 text-orange-700
                                        @elseif($risk->risk_level === 'medium') bg-yellow-100 text-yellow-700
                                        @else bg-green-100 text-green-700 @endif">
                                        {{ ucfirst($risk->risk_level) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ ucfirst($risk->status) }}</td>
                                <td class="px-4 py-3 text-sm">{{ $risk->owner?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('risks.edit', $risk) }}" class="text-gray-500 hover:text-gray-700">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    No risks registered yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>