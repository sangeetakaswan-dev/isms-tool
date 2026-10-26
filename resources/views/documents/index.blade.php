@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Document Management</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header & Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Documents</h1>
                <p class="text-sm text-slate-500 mt-1">Manage your ISMS policies, procedures, and evidence.</p>
            </div>
            <a href="{{ route('documents.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload Document
            </a>
        </div>

        {{-- KPI Cards --}}
        @php
            $total = $documents->count() ?? 0;
            $approved = $documents->where('status', 'approved')->count() ?? 0;
            $review = $documents->where('status', 'under_review')->count() ?? 0;
            $drafts = $documents->where('status', 'draft')->count() ?? 0;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Documents</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $total }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-emerald-600">Approved</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $approved }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-amber-600">Under Review</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $review }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-400">Drafts</p>
                <p class="text-3xl font-mono font-semibold text-slate-900 mt-2">{{ $drafts }}</p>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Document Title</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Version</th>
                            <th class="px-6 py-4">Owner</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($documents as $document)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <a href="{{ route('documents.show', $document) }}"
                                        class="font-medium text-indigo-600 hover:text-indigo-900">
                                        {{ $document->title }}
                                    </a>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ ucfirst($document->document_type ?? 'General') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ ucfirst($document->category ?? '—') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'under_review' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'draft' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                            'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                                            'archived' => 'bg-gray-100 text-gray-700 ring-gray-600/20',
                                        ];
                                        $statusClass = $statusColors[$document->status] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20';
                                        $statusLabel = str_replace('_', ' ', $document->status ?? 'draft');
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                        {{ ucfirst($statusLabel) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-slate-600">
                                    v{{ $document->current_version ?? '1.0' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $document->owner->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div
                                        class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('documents.show', $document) }}"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition"
                                            title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No documents found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Get started by uploading your first policy or
                                        procedure.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('documents.create') }}"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            Upload Document
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection