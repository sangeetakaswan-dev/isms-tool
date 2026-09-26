<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Upload Document</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Title</label>
                            <input type="text" name="title" class="mt-1 w-full border rounded px-2 py-1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Type</label>
                            <select name="document_type" class="mt-1 w-full border rounded px-2 py-1" required>
                                <option value="policy">Policy</option>
                                <option value="procedure">Procedure</option>
                                <option value="record">Record</option>
                                <option value="template">Template</option>
                                <option value="evidence">Evidence</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Category</label>
                            <input type="text" name="category" class="mt-1 w-full border rounded px-2 py-1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Review Date</label>
                            <input type="date" name="review_date" class="mt-1 w-full border rounded px-2 py-1">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Description</label>
                            <textarea name="description" class="mt-1 w-full border rounded px-2 py-1"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Tags (comma separated)</label>
                            <input type="text" name="tags" class="mt-1 w-full border rounded px-2 py-1">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">File</label>
                            <input type="file" name="file" class="mt-1 w-full" required>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>