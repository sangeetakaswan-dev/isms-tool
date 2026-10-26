@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('risks.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Create Risk</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('risks.store') }}"
            class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 md:p-8 space-y-8">

                {{-- Section 1: Basic Info --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Basic Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Risk Title <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('title')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Category <span
                                        class="text-red-500">*</span></label>
                                <select name="category" required
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @foreach(['technical', 'organizational', 'physical', 'people', 'compliance'] as $cat)
                                        <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ ucfirst($cat) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Linked Asset</label>
                                <select name="asset_id"
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">— No Asset Linked —</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}" @selected(old('asset_id') == $asset->id)>
                                            {{ $asset->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Risk Assessment --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Risk Assessment
                    </h3>
                    <p class="text-sm text-slate-500 mb-4">Score from 1 (Lowest) to 5 (Highest). Risk score is calculated
                        automatically.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Likelihood (1-5) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="likelihood" min="1" max="5" value="{{ old('likelihood', 3) }}"
                                required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Impact (1-5) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="impact" min="1" max="5" value="{{ old('impact', 3) }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Ownership --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Ownership & Status
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Risk Owner</label>
                            <select name="owner_id"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">— Unassigned —</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('owner_id') == $user->id)>{{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Initial Status</label>
                            <select name="status"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach(['identified', 'assessed', 'treated', 'monitored', 'closed'] as $s)
                                    <option value="{{ $s }}" @selected(old('status') === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Form Actions --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('risks.index') }}"
                    class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 transition">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    Save Risk
                </button>
            </div>
        </form>
    </div>
@endsection