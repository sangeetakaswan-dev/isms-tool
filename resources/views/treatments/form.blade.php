<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $plan ? 'Edit' : 'New' }} Treatment Plan — {{ $risk->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST"
                  action="{{ $plan ? route('risks.treatments.update', $plan) : route('risks.treatments.store', $risk) }}"
                  class="bg-white shadow rounded-lg p-6 space-y-4">
                @csrf
                @if($plan) @method('PUT') @endif

                <div>
                    <label class="block text-sm font-medium">Strategy *</label>
                    <select name="strategy" class="mt-1 block w-full rounded border-gray-300">
                        @foreach(['mitigate','accept','transfer','avoid'] as $s)
                            <option value="{{ $s }}" @selected(old('strategy', $plan?->strategy) === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Action *</label>
                    <textarea name="action" rows="3" required class="mt-1 block w-full rounded border-gray-300">{{ old('action', $plan?->action) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Cost Estimate</label>
                        <input type="number" step="0.01" name="cost_estimate" value="{{ old('cost_estimate', $plan?->cost_estimate) }}"
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date', $plan?->due_date?->toDateString()) }}"
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Assign To</label>
                    <select name="assigned_to" class="mt-1 block w-full rounded border-gray-300">
                        <option value="">— Unassigned —</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_to', $plan?->assigned_to) == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('risks.show', $risk) }}" class="px-4 py-2 border rounded">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                        {{ $plan ? 'Update' : 'Create' }} Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>