<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Risk Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('risks.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Asset -->
                        <div>
                            <label for="asset_id" class="block text-sm font-medium text-gray-700">Asset</label>
                            <select name="asset_id" id="asset_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Threat -->
                        <div>
                            <label for="threat" class="block text-sm font-medium text-gray-700">Threat</label>
                            <input type="text" name="threat" id="threat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <!-- Vulnerability -->
                        <div>
                            <label for="vulnerability" class="block text-sm font-medium text-gray-700">Vulnerability</label>
                            <input type="text" name="vulnerability" id="vulnerability" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <!-- Likelihood -->
                        <div>
                            <label for="likelihood" class="block text-sm font-medium text-gray-700">Likelihood (1-5)</label>
                            <input type="number" name="likelihood" id="likelihood" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <!-- Impact -->
                        <div>
                            <label for="impact" class="block text-sm font-medium text-gray-700">Impact (1-5)</label>
                            <input type="number" name="impact" id="impact" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <!-- Treatment -->
                        <div>
                            <label for="treatment" class="block text-sm font-medium text-gray-700">Treatment</label>
                            <select name="treatment" id="treatment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="accept">Accept</option>
                                <option value="mitigate">Mitigate</option>
                                <option value="transfer">Transfer</option>
                                <option value="avoid">Avoid</option>
                            </select>
                        </div>

                        <!-- Residual Likelihood (Optional) -->
                        <div>
                            <label for="residual_likelihood" class="block text-sm font-medium text-gray-700">Residual Likelihood (1-5)</label>
                            <input type="number" name="residual_likelihood" id="residual_likelihood" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <!-- Residual Impact (Optional) -->
                        <div>
                            <label for="residual_impact" class="block text-sm font-medium text-gray-700">Residual Impact (1-5)</label>
                            <input type="number" name="residual_impact" id="residual_impact" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                            Create Risk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>