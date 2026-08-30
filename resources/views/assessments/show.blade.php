<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $assessment->name }}</h2>
                    <p class="text-sm text-gray-500">Status: {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('assessments.responses.index', $assessment) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                        Conduct Assessment
                    </a>
                    <a href="{{ route('assessments.edit', $assessment) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md">
                        Edit
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Progress</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $progressStats['progress_percentage'] }}%</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Compliant</div>
                    <div class="text-2xl font-bold text-green-600">{{ $progressStats['compliant'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Non-Compliant</div>
                    <div class="text-2xl font-bold text-red-600">{{ $progressStats['non_compliant'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Not Assessed</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $progressStats['not_assessed'] }}</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b">
                    <h3 class="font-semibold text-gray-900">Domain Compliance</h3>
                </div>
                <div class="divide-y">
                    @foreach($domainCompliance as $domain)
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <div class="font-medium">{{ $domain['domain_code'] }} - {{ $domain['domain_name'] }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $domain['compliant'] }}/{{ $domain['applicable_controls'] }} compliant
                                </div>
                            </div>
                            <span class="font-semibold">{{ $domain['compliance_percentage'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>