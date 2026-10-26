@extends('layouts.app')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('documents.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">Upload Document</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data"
            class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 md:p-8 space-y-8">

                {{-- Section 1: Document Details --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Document Details
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Document Title <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="e.g. Information Security Policy v2">
                            @error('title')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Document Type <span
                                        class="text-red-500">*</span></label>
                                <select name="document_type" required
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="policy" @selected(old('document_type') === 'policy')>Policy</option>
                                    <option value="procedure" @selected(old('document_type') === 'procedure')>Procedure
                                    </option>
                                    <option value="guideline" @selected(old('document_type') === 'guideline')>Guideline
                                    </option>
                                    <option value="record" @selected(old('document_type') === 'record')>Record / Evidence
                                    </option>
                                    <option value="other" @selected(old('document_type') === 'other')>Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                                <input type="text" name="category" value="{{ old('category') }}"
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="e.g. Access Control, HR, Operations">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Briefly describe the purpose and scope of this document...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section 2: File Upload --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">File Upload</h3>

                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors bg-slate-50 relative group">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition-colors"
                                stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="file-upload"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-2 py-1">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="file" type="file" class="sr-only" required
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                                </label>
                                <p class="pl-1 py-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-slate-500">PDF, DOCX, XLSX, PNG up to 10MB</p>
                        </div>
                    </div>
                    @error('file')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Section 3: Review & Metadata --}}
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2 mb-4">Review & Metadata
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Next Review Date</label>
                            <input type="date" name="review_date" value="{{ old('review_date') }}"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tags</label>
                            <input type="text" name="tags" value="{{ old('tags') }}"
                                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="e.g. iso27001, critical, hr (comma separated)">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Form Actions --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('documents.index') }}"
                    class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 transition">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    Upload Document
                </button>
            </div>
        </form>
    </div>
@endsection