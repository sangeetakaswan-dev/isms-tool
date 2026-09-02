<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Compare SoA Versions
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div>
                    <h3>Version 1: {{ $v1->version }}</h3>
                </div>
                <div>
                    <h3>Version 2: {{ $v2->version }}</h3>
                </div>
                <div></div>
            </div>

            @foreach($differences as $diff)
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <strong>Control: {{ $diff['control_id'] }}</strong>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            Old: {{ json_encode($diff['old']) }}
                        </div>
                        <div>
                            New: {{ json_encode($diff['new']) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout> 