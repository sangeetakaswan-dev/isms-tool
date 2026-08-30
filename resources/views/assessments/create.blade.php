<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Assessment</h2>

            <div class="bg-white rounded-lg shadow p-6">
                <form method="POST" action="{{ route('assessments.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scope</label>
                        <textarea name="scope" rows="3" 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('scope') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Date</label>
                            <input type="date" name="target_date" value="{{ old('target_date') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lead Assessor *</label>
                        <select name="lead_assessor_id" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Lead Assessor</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}" {{ old('lead_assessor_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('assessments.index') }}" class="px-4 py-2 bg-gray-300 rounded-md text-gray-700">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 rounded-md text-white">Create Assessment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>