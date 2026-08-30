<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Assessments</h2>
                <a href="{{ route('assessments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    New Assessment
                </a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($assessments->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($assessments as $assessment)
                            <div class="p-4 flex items-center justify-between">
                                <div>
                                    <a href="{{ route('assessments.show', $assessment) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">
                                        {{ $assessment->name }}
                                    </a>
                                    <div class="text-sm text-gray-500">
                                        Status: {{ ucfirst(str_replace('_', ' ', $assessment->status)) }} | 
                                        Progress: {{ $assessment->progress_percentage }}%
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $assessment->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $assessment->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $assessment->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4">
                        {{ $assessments->links() }}
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        No assessments found.
                        <a href="{{ route('assessments.create') }}" class="text-indigo-600">Create one</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>