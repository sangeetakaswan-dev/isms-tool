<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            SoA Version {{ $version->version }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Control ID</th>
                        <th class="px-4 py-2">Applicable</th>
                        <th class="px-4 py-2">Justification</th>
                        <th class="px-4 py-2">Implementation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td class="border px-4 py-2">{{ $entry['control_id'] }}</td>
                            <td class="border px-4 py-2">{{ $entry['applicable'] ? 'Yes' : 'No' }}</td>
                            <td class="border px-4 py-2">{{ $entry['justification'] }}</td>
                            <td class="border px-4 py-2">{{ $entry['implementation_status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>