@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Organizations</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Your Organizations</h1>
                <p class="text-sm text-slate-500 mt-1">Manage your workspaces and team members.</p>
            </div>
        </div>

        {{-- Organizations Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tenants as $tenant)
                <div
                    class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                    {{-- Active Indicator --}}
                    @if(auth()->user()->current_tenant_id === $tenant->id)
                        <div class="absolute top-0 left-0 w-full h-1 bg-indigo-600"></div>
                    @endif

                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        @if(auth()->user()->current_tenant_id === $tenant->id)
                            <span class="px-2.5 py-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-full">Active</span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $tenant->name }}</h3>
                    <p class="text-sm text-slate-500 mb-4">{{ $tenant->industry ?? 'Organization' }} ·
                        {{ $tenant->size ?? 'Team' }}</p>

                    <div class="flex items-center gap-4 mt-auto pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            {{ $tenant->users_count ?? $tenant->users->count() ?? 0 }} members
                        </div>
                        <a href="{{ route('tenants.show', $tenant) }}"
                            class="ml-auto text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            Manage
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl border border-slate-200 p-12 text-center shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No organizations found</h3>
                    <p class="mt-1 text-sm text-slate-500">Get started by creating your first organization.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection