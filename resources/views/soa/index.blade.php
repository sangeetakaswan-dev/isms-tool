<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Statement of Applicability - {{ $assessment->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex gap-4 mb-6">
                @if($entries->isEmpty())
                    <form action="{{ route('soa.generate', $assessment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Generate SoA</button>
                    </form>
                @else
                    <form action="{{ route('soa.bulkApprove', $assessment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Bulk Approve</button>
                    </form>
                    <a href="{{ route('soa.export.excel', $assessment->id) }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded">Export Excel</a>
                    <a href="{{ route('soa.export.pdf', $assessment->id) }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded">Export PDF</a>
                    <form action="{{ route('soa.version', $assessment->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="version" placeholder="Version (e.g., 1.0)"
                            class="border rounded px-2 py-1">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Create Version</button>
                    </form>
                @endif
            </div>

            <!-- Versions List -->
            @if($versions->count())
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Versions</h3>
                    <div class="flex gap-4">
                        @foreach($versions as $version)
                            <a href="{{ route('soa.version.show', [$assessment->id, $version->id]) }}"
                                class="text-blue-600 underline">
                                {{ $version->version }} ({{ $version->created_at->format('d M Y') }})
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Entries Table -->
            @if($entries->isNotEmpty())
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Control ID</th>
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Applicable</th>
                            <th class="px-4 py-2">Implementation</th>
                            <th class="px-4 py-2">Approval</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $entry)
                            <tr>
                                <td class="border px-4 py-2">{{ $entry->control->control_id }}</td>
                                <td class="border px-4 py-2">{{ $entry->control->title }}</td>
                                <td class="border px-4 py-2">{{ $entry->applicable ? 'Yes' : 'No' }}</td>
                                <td class="border px-4 py-2">{{ $entry->implementation_status }}</td>
                                <td class="border px-4 py-2">
                                    @if($entry->approved_by)
                                        Approved by {{ $entry->approver->name }} at {{ $entry->approved_at->format('d M Y H:i') }}
                                    @else
                                        Pending
                                    @endif
                                </td>
                                <td class="border px-4 py-2">
                                    @if(!$entry->approved_by)
                                        <form action="{{ route('soa.approve', [$assessment->id, $entry->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-green-600">Approve</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No SoA entries yet. Click "Generate SoA" to start.</p>
            @endif
        </div>
    </div>
</x-app-layout>