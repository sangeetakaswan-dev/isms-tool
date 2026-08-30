<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $tenant->name }}</h2>
                <a href="{{ route('tenants.index') }}" class="text-indigo-600 hover:text-indigo-800">Back to Organizations</a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Industry</dt>
                            <dd class="font-medium">{{ $tenant->industry ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Size</dt>
                            <dd class="font-medium">{{ $tenant->size ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="font-medium">{{ $tenant->contact_email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Phone</dt>
                            <dd class="font-medium">{{ $tenant->contact_phone ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Location</dt>
                            <dd class="font-medium">{{ $tenant->city }}, {{ $tenant->state }}, {{ $tenant->country }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b">
                    <h3 class="font-semibold text-gray-900">Team Members</h3>
                </div>
                <div class="divide-y">
                    @foreach($members as $member)
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <div class="font-medium">{{ $member->name }}</div>
                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">
                                {{ $member->pivot->role }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>