<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Organizations</h2>
                <a href="{{ route('tenants.setup') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    New Organization
                </a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($tenants->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($tenants as $tenant)
                            <div class="p-4 flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $tenant->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $tenant->contact_email }}</div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('tenants.show', $tenant) }}"
                                        class="text-indigo-600 hover:text-indigo-800">View</a>
                                    <form method="POST" action="{{ route('tenants.switch', $tenant) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800">Switch</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        No organizations found.
                        <a href="{{ route('tenants.setup') }}" class="text-indigo-600">Create one</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>