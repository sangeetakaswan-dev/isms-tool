<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $assessment->name }}</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Status: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $assessment->status)) }}</span>
                    </p>
                </div>
                <div class="flex space-x-2">
                    @if($assessment->status === 'draft')
                        <form method="POST" action="{{ route('assessments.start', $assessment) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Start Assessment
                            </button>
                        </form>
                    @endif
                    
                    @if($assessment->status === 'in_progress')
                        <a href="{{ route('assessments.responses.index', $assessment) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Conduct Assessment
                        </a>
                    @endif
                    
                    <a href="{{ route('assessments.edit', $assessment) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Edit
                    </a>
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Overall Progress</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $progressStats['progress_percentage'] }}%</div>
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full bg-indigo-600" style="width: {{ $progressStats['progress_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Compliant</div>
                    <div class="text-3xl font-bold text-green-600">{{ $progressStats['compliant'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Non-Compliant</div>
                    <div class="text-3xl font-bold text-red-600">{{ $progressStats['non_compliant'] }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-500">Not Assessed</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $progressStats['not_assessed'] }}</div>
                </div>
            </div>

            <!-- Domain Compliance -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Domain Compliance</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($domainCompliance as $domain)
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-900">{{ $domain['domain_code'] }} - {{ $domain['domain_name'] }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $domain['compliant'] }}/{{ $domain['applicable_controls'] }} controls compliant
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-32">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full 
                                            {{ $domain['compliance_percentage'] >= 80 ? 'bg-green-500' : '' }}
                                            {{ $domain['compliance_percentage'] >= 50 && $domain['compliance_percentage'] < 80 ? 'bg-yellow-500' : '' }}
                                            {{ $domain['compliance_percentage'] < 50 ? 'bg-red-500' : '' }}"
                                             style="width: {{ $domain['compliance_percentage'] }}%">
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold">{{ $domain['compliance_percentage'] }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Team Members</h3>
                </div>
                <div class="p-4">
                    @if($teamMembers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($teamMembers as $member)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold">{{ substr($member->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->assessmentTeam->first()->role ?? 'assessor' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No team members assigned yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>