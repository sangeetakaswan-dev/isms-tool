<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $document->title }}</h2>
            <a href="{{ route('documents.index') }}" class="px-3 py-2 border rounded text-sm hover:bg-gray-50">← All
                Documents</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Document Info --}}
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Type</dt>
                        <dd class="font-semibold">{{ ucfirst($document->document_type ?? '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Category</dt>
                        <dd class="font-semibold">{{ ucfirst($document->category ?? '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Version</dt>
                        <dd class="font-semibold">{{ $document->current_version ?? '1.0' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd>
                            <span class="px-2 py-1 text-xs rounded
                                @if($document->status === 'approved') bg-green-100 text-green-700
                                @elseif($document->status === 'under_review') bg-yellow-100 text-yellow-700
                                @elseif($document->status === 'rejected') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $document->status ?? 'draft')) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Owner</dt>
                        <dd>{{ $document->owner?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Approver</dt>
                        <dd>{{ $document->approver?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Approved At</dt>
                        <dd>{{ $document->approved_at?->format('M d, Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Next Review</dt>
                        <dd>{{ $document->review_date?->format('M d, Y') ?? '—' }}</dd>
                    </div>
                </dl>

                @if($document->description)
                    <div class="mt-4 pt-4 border-t">
                        <dt class="text-sm text-gray-500">Description</dt>
                        <dd class="mt-1 whitespace-pre-line">{{ $document->description }}</dd>
                    </div>
                @endif

                @if($document->tags && count($document->tags))
                    <div class="mt-4 pt-4 border-t">
                        <dt class="text-sm text-gray-500 mb-2">Tags</dt>
                        <div class="flex flex-wrap gap-2">
                            @foreach($document->tags as $tag)
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($document->file_path)
                    <div class="mt-4 pt-4 border-t">
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            📄 Download Document
                        </a>
                    </div>
                @endif
            </div>

            {{-- Linked Controls --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Linked Controls</h3>
                @forelse($controls ?? [] as $control)
                    <div class="border rounded p-3 mb-2 flex justify-between">
                        <div>
                            <span class="font-mono text-sm text-indigo-600">{{ $control->code }}</span>
                            <span class="ml-2">{{ $control->title }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No controls linked to this document.</p>
                @endforelse
            </div>

            {{-- Version History --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Version History</h3>
                @forelse($document->versions as $version)
                    <div class="border-l-4 border-indigo-500 pl-3 py-2 mb-2">
                        <div class="flex justify-between">
                            <div>
                                <span class="font-semibold">v{{ $version->version }}</span>
                                <span class="text-sm text-gray-500 ml-2">by {{ $version->user?->name ?? 'Unknown' }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $version->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($version->notes ?? null)
                            <p class="text-sm text-gray-600 mt-1">{{ $version->notes }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No versions yet.</p>
                @endforelse
            </div>

            {{-- Review History --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Review History</h3>
                @forelse($document->reviews as $review)
                    <div class="border rounded p-3 mb-2">
                        <div class="flex justify-between">
                            <div>
                                <span class="font-semibold">{{ ucfirst($review->action ?? 'review') }}</span>
                                <span class="text-sm text-gray-500 ml-2">by {{ $review->user?->name ?? 'Unknown' }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        @if($review->comments ?? null)
                            <p class="text-sm text-gray-600 mt-1">{{ $review->comments }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No reviews yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>