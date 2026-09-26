<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Document Library</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('documents.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Upload Document</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Title</th>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-left">Version</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Owner</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $doc->title }}</td>
                                <td class="px-4 py-2">{{ $doc->document_type }}</td>
                                <td class="px-4 py-2">{{ $doc->current_version }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs rounded
                                        @if($doc->status === 'approved') bg-green-100 text-green-800
                                        @elseif($doc->status === 'under_review') bg-yellow-100 text-yellow-800
                                        @elseif($doc->status === 'archived') bg-gray-200 text-gray-700
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $doc->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $doc->owner->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('documents.show', $doc->id) }}" class="text-blue-600">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>