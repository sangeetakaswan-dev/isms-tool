<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Risk</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('risks.update', $risk) }}" class="bg-white shadow rounded-lg p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $risk->title) }}" required
                           class="mt-1 block w-full rounded border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description" rows="4" class="mt-1 block w-full rounded border-gray-300">{{ old('description', $risk->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Category</label>
                        <select name="category" class="mt-1 block w-full rounded border-gray-300">
                            @foreach(['technical','organizational','physical','people','compliance'] as $cat)
                                <option value="{{ $cat }}" @selected(old('category', $risk->category) === $cat)>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Asset</label>
                        <select name="asset_id" class="mt-1 block w-full rounded border-gray-300">
                            <option value="">— None —</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" @selected(old('asset_id', $risk->asset_id) == $asset->id)>{{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Likelihood</label>
                        <input type="number" name="likelihood" min="1" max="5" value="{{ old('likelihood', $risk->likelihood) }}" required
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Impact</label>
                        <input type="number" name="impact" min="1" max="5" value="{{ old('impact', $risk->impact) }}" required
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Owner</label>
                        <select name="owner_id" class="mt-1 block w-full rounded border-gray-300">
                            <option value="">— Unassigned —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('owner_id', $risk->owner_id) == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status" class="mt-1 block w-full rounded border-gray-300">
                            @foreach(['identified','assessed','treated','monitored','closed'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $risk->status) === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-between pt-4">
                    <form method="POST" action="{{ route('risks.destroy', $risk) }}" onsubmit="return confirm('Delete this risk?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-red-600 hover:underline">Delete</button>
                    </form>
                    <div class="flex gap-2">
                        <a href="{{ route('risks.show', $risk) }}" class="px-4 py-2 border rounded">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>