<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Assessments</h2>
                    <p class="mt-1 text-sm text-gray-600">Manage your ISO 27001 compliance assessments</p>
                </div>
                <a href="{{ route('assessments.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Assessment
                </a>
            </div>

            <!-- Status Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Draft</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statusCounts['draft'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">In Progress</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $statusCounts['in_progress'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Completed</div>
                    <div class="text-2xl font-bold text-green-600">{{ $statusCounts['completed'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Archived</div>
                    <div class="text-2xl font-bold text-gray-400">{{ $statusCounts['archived'] }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('assessments.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                                   placeholder="Search by name..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="in_progress" {{ ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="archived" {{ ($filters['status'] ?? '') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Assessment List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($assessments->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($assessments as $assessment)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <a href="{{ route('assessments.show', $assessment) }}" class="text-lg font-semibold text-indigo-600 hover:text-indigo-800">
                                            {{ $assessment->name }}
                                        </a>
                                        <div class="mt-1 text-sm text-gray-500">
                                            {{ $assessment->description ? Str::limit($assessment->description, 100) : 'No description' }}
                                        </div>
                                        <div class="mt-2 flex items-center space-x-4 text-xs text-gray-400">
                                            <span>Start: {{ $assessment->start_date->format('M d, Y') }}</span>
                                            @if($assessment->target_date)
                                                <span>Target: {{ $assessment->target_date->format('M d, Y') }}</span>
                                            @endif
                                            <span>{{ $assessment->progress_percentage }}% complete</span>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $assessment->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $assessment->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $assessment->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $assessment->status === 'archived' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                                        </span>
                                        @if($assessment->status === 'draft')
                                            <form method="POST" action="{{ route('assessments.start', $assessment) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-green-600 hover:text-green-800">Start</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full 
                                            {{ $assessment->progress_percentage >= 80 ? 'bg-green-500' : '' }}
                                            {{ $assessment->progress_percentage >= 50 && $assessment->progress_percentage < 80 ? 'bg-yellow-500' : '' }}
                                            {{ $assessment->progress_percentage < 50 ? 'bg-red-500' : '' }}"
                                             style="width: {{ $assessment->progress_percentage }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4">
                        {{ $assessments->links() }}
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="mt-2">No assessments found</p>
                        <a href="{{ route('assessments.create') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">
                            Create your first assessment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>