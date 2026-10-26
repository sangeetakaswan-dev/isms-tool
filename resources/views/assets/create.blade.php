@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('assets.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Create Asset</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('assets.store') }}"
            class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 md:p-8 space-y-8">

                {{-- Section 1: Basic Info --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Basic Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Asset Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="e.g. CRM Database Server">
                            @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Asset Type <span
                                    class="text-red-500">*</span></label>
                            <select name="asset_type" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach(['hardware', 'software', 'data', 'people', 'facility', 'service'] as $type)
                                    <option value="{{ $type }}" @selected(old('asset_type') === $type)>{{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_type')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="What does this asset do?">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Ownership --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Ownership</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Owner</label>
                            <input type="text" name="owner" value="{{ old('owner') }}"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="e.g. IT Infrastructure Team">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                            <input type="text" name="department" value="{{ old('department') }}"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="e.g. IT, HR, Operations">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="e.g. Data Center A, Cloud (AWS)">
                    </div>
                </div>

                {{-- Section 3: CIA Ratings --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">CIA Ratings (1-5)
                    </h3>
                    <p class="text-sm text-slate-500 mb-4">Confidentiality, Integrity, and Availability criticality.</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Confidentiality <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="confidentiality_rating" min="1" max="5"
                                value="{{ old('confidentiality_rating', 3) }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('confidentiality_rating')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="bg-slate-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Integrity <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="integrity_rating" min="1" max="5"
                                value="{{ old('integrity_rating', 3) }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('integrity_rating')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="bg-slate-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Availability <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="availability_rating" min="1" max="5"
                                value="{{ old('availability_rating', 3) }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('availability_rating')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- Form Actions --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('assets.index') }}"
                    class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 transition">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    Create Asset
                </button>
            </div>
        </form>
    </div>
@endsection