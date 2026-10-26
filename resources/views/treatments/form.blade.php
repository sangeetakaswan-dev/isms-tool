@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('risks.show', $risk) }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ $plan ? 'Edit' : 'New' }} Treatment Plan
        </h2>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto space-y-4">

        {{-- Breadcrumb Context --}}
        <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="text-sm font-medium text-indigo-900">Linked Risk</p>
                <p class="text-sm text-indigo-700">{{ $risk->title }}</p>
            </div>
        </div>

        <form method="POST"
            action="{{ $plan ? route('risks.treatments.update', $plan) : route('risks.treatments.store', $risk) }}"
            class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            @csrf
            @if($plan) @method('PUT') @endif

            <div class="p-6 md:p-8 space-y-8">

                {{-- Section 1: Plan Details --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Plan Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Strategy <span
                                    class="text-red-500">*</span></label>
                            <select name="strategy" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach(['mitigate', 'accept', 'transfer', 'avoid'] as $s)
                                    <option value="{{ $s }}" @selected(old('strategy', $plan?->strategy ?? '') === $s)>
                                        {{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Mitigate = reduce risk, Accept = tolerate, Transfer =
                                insure, Avoid = stop activity.</p>
                            @error('strategy')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Action Plan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="action" rows="4" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Describe the specific steps to implement this treatment...">{{ old('action', $plan?->action ?? '') }}</textarea>
                            @error('action')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2: Timeline & Cost --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Timeline & Cost
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cost Estimate (₹)</label>
                            <input type="number" step="0.01" name="cost_estimate"
                                value="{{ old('cost_estimate', $plan?->cost_estimate ?? '') }}"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="e.g. 50000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Due Date</label>
                            <input type="date" name="due_date"
                                value="{{ old('due_date', $plan?->due_date?->toDateString() ?? '') }}"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Assignment --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Assignment</h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Assign To</label>
                        <select name="assigned_to"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">— Unassigned —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('assigned_to', $plan?->assigned_to ?? '') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">The assignee will be notified via email.</p>
                    </div>
                </div>

            </div>

            {{-- Form Actions --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('risks.show', $risk) }}"
                    class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 transition">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    {{ $plan ? 'Update Plan' : 'Create Plan' }}
                </button>
            </div>
        </form>
    </div>
@endsection