@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('assets.show', $asset) }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Edit Asset</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('assets.update', $asset) }}"
            class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-8">

                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Basic Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Asset Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $asset->name) }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Asset Type <span
                                    class="text-red-500">*</span></label>
                            <select name="asset_type" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach(['hardware', 'software', 'data', 'people', 'facility', 'service'] as $type)
                                    <option value="{{ $type }}" @selected(old('asset_type', $asset->asset_type) === $type)>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $asset->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Ownership</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Owner</label>
                            <input type="text" name="owner" value="{{ old('owner', $asset->owner) }}"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                            <input type="text" name="department" value="{{ old('department', $asset->department) }}"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                        <input type="text" name="location" value="{{ old('location', $asset->location) }}"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">CIA Ratings (1-5)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Confidentiality</label>
                            <input type="number" name="confidentiality_rating" min="1" max="5"
                                value="{{ old('confidentiality_rating', $asset->confidentiality_rating) }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="bg-slate-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Integrity</label>
                            <input type="number" name="integrity_rating" min="1" max="5"
                                value="{{ old('integrity_rating', $asset->integrity_rating) }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="bg-slate-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Availability</label>
                            <input type="number" name="availability_rating" min="1" max="5"
                                value="{{ old('availability_rating', $asset->availability_rating) }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <form method="POST" action="{{ route('assets.destroy', $asset) }}"
                    onsubmit="return confirm('Delete this asset permanently?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900 transition">Delete
                        Asset</button>
                </form>
                <div class="flex items-center gap-3">
                    <a href="{{ route('assets.show', $asset) }}"
                        class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 transition">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        Update Asset
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection