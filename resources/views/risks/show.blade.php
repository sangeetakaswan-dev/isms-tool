<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $risk->title }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('risks.edit', $risk) }}" class="px-3 py-2 border rounded">Edit</a>
                <a href="{{ route('risks.treatments.create', $risk) }}"
                    class="px-3 py-2 bg-indigo-600 text-white rounded">
                    + Add Treatment Plan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Risk Details --}}
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Risk Score</dt>
                        <dd class="text-2xl font-bold">{{ $risk->risk_score }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Level</dt>
                        <dd class="text-lg font-semibold">{{ ucfirst($risk->risk_level) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Likelihood × Impact</dt>
                        <dd>{{ $risk->likelihood }} × {{ $risk->impact }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd>{{ ucfirst($risk->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Owner</dt>
                        <dd>{{ $risk->owner?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Asset</dt>
                        <dd>{{ $risk->asset?->name ?? '—' }}</dd>
                    </div>
                </dl>

                @if($risk->description)
                    <div class="mt-4 pt-4 border-t">
                        <dt class="text-sm text-gray-500">Description</dt>
                        <dd class="mt-1 whitespace-pre-line">{{ $risk->description }}</dd>
                    </div>
                @endif
            </div>

            {{-- Treatment Plans --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Treatment Plans</h3>

                @forelse($risk->treatmentPlans as $plan)
                    <div class="border rounded p-4 mb-3">
                        <div class="flex justify-between">
                            <div>
                                <span class="px-2 py-1 text-xs rounded bg-indigo-100 text-indigo-700">
                                    {{ ucfirst($plan->strategy) }}
                                </span>
                                <span class="ml-2 text-sm">{{ ucfirst($plan->status) }}</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                Due: {{ $plan->due_date?->toDateString() ?? '—' }}
                            </div>
                        </div>
                        <p class="mt-2">{{ $plan->action }}</p>
                        <div class="mt-2 text-sm text-gray-500">
                            Assigned: {{ $plan->assignedUser?->name ?? '—' }}
                            @if($plan->cost_estimate) · Cost: {{ number_format($plan->cost_estimate, 2) }} @endif
                        </div>

                        @if($plan->status !== 'completed')
                            <form method="POST" action="{{ route('risks.treatments.complete', $plan) }}"
                                class="mt-3 flex gap-2">
                                @csrf
                                <input type="text" name="completion_notes" placeholder="Completion notes..."
                                    class="flex-1 rounded border-gray-300 text-sm">
                                <button class="px-3 py-1 bg-green-600 text-white rounded text-sm">Mark Complete</button>
                            </form>
                        @else
                            <div class="mt-2 text-sm text-green-700">✓ Completed {{ $plan->completed_at?->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No treatment plans yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>