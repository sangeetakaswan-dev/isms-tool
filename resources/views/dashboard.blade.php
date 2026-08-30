{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Welcome, {{ auth()->user()->name }}!
                    </h2>
                    <p class="mt-2 text-gray-600">
                        @if(auth()->user()->currentTenant)
                            Current Organization: <strong>{{ auth()->user()->currentTenant->name }}</strong>
                        @else
                            You don't have an organization yet.
                        @endif
                    </p>
                </div>
            </div>

            @if(!auth()->user()->currentTenant)
                <!-- No Tenant -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                You need to create or join an organization to get started.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('tenants.setup') }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Create Organization
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Total Domains -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_domains'] }}</div>
                                    <div class="text-sm text-gray-500">Domains</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Controls -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_controls'] }}</div>
                                    <div class="text-sm text-gray-500">Total Controls</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Assessments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <div class="text-2xl font-bold text-gray-900">{{ $stats['active_assessments'] }}</div>
                                    <div class="text-sm text-gray-500">Active Assessments</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Assessments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <div class="text-2xl font-bold text-gray-900">{{ $stats['completed_assessments'] }}</div>
                                    <div class="text-sm text-gray-500">Completed</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Domains Overview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">ISO 27001 Domains</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($domains as $domain)
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span
                                            class="text-xs font-semibold px-2 py-1 rounded {{ $domain->clause_type === 'annex_a' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $domain->code }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $domain->controls_count }} controls</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900">{{ $domain->name }}</h4>
                                    <p class="text-xs text-gray-500 mt-2">{{ Str::limit($domain->description, 80) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Assessments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Assessments</h3>
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-900">View All</a>
                        </div>

                        @if($recentAssessments->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentAssessments as $assessment)
                                        <div class="flex items-center justify-between border-b pb-4">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $assessment->name }}</h4>
                                                <p class="text-sm text-gray-500">
                                                    Lead Assessor: {{ $assessment->leadAssessor->name }}
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                                    {{ $assessment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                    ($assessment->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                                                </span>
                                                <span class="text-sm text-gray-500">{{ $assessment->progress_percentage }}%</span>
                                            </div>
                                        </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No assessments created yet.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection