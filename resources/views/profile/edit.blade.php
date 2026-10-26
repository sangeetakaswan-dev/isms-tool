@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-slate-900 leading-tight">Profile Settings</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Your Profile</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your account information and security settings.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: Forms --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Profile Info --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Password --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Danger Zone --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8 bg-red-50/50">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection