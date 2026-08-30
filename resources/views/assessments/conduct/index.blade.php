<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $assessment->name }} - Controls</h2>
                <a href="{{ route('assessments.show', $assessment) }}" class="text-indigo-600">Back to Assessment</a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b flex space-x-4">
                    <select class="rounded-md border-gray-300">
                        <option value="">All Domains</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->id }}">{{ $domain->code }} - {{ $domain->name }}</option>
                        @endforeach
                    </select>
                    <select class="rounded-md border-gray-300">
                        <option value="">All Statuses</option>
                        <option value="compliant">Compliant</option>
                        <option value="non_compliant">Non-Compliant</option>
                        <option value="partially_compliant">Partially Compliant</option>
                        <option value="not_assessed">Not Assessed</option>
                    </select>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Control ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maturity</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($responses as $response)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $response->control->control_id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $response->control->title }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $response->control->domain->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $response->status === 'compliant' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $response->status === 'non_compliant' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $response->status === 'partially_compliant' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $response->status === 'not_assessed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $response->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $response->maturity_level ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $responses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>