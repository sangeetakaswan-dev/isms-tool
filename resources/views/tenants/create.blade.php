{{-- resources/views/tenants/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Organization')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Your Organization</h2>

                    <form method="POST" action="{{ route('tenants.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Organization Name -->
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Organization Name
                                    *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Industry -->
                            <div>
                                <label for="industry" class="block text-sm font-medium text-gray-700">Industry</label>
                                <select name="industry" id="industry"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select Industry</option>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Retail">Retail</option>
                                    <option value="Education">Education</option>
                                    <option value="Government">Government</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Organization Size -->
                            <div>
                                <label for="size" class="block text-sm font-medium text-gray-700">Organization Size
                                    *</label>
                                <select name="size" id="size" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select Size</option>
                                    <option value="1-10">1-10 employees</option>
                                    <option value="11-50">11-50 employees</option>
                                    <option value="51-200">51-200 employees</option>
                                    <option value="201-1000">201-1000 employees</option>
                                    <option value="1000+">1000+ employees</option>
                                </select>
                                @error('size')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Email -->
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email
                                    *</label>
                                <input type="email" name="contact_email" id="contact_email"
                                    value="{{ old('contact_email') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('contact_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact
                                    Phone</label>
                                <input type="text" name="contact_phone" id="contact_phone"
                                    value="{{ old('contact_phone') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Address -->
                            <div class="col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- State -->
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" name="state" id="state" value="{{ old('state') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" name="country" id="country" value="{{ old('country', 'India') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Pincode -->
                            <div>
                                <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode</label>
                                <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-4">
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Organization
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection