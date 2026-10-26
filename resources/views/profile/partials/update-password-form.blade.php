<section>
    <header class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-lg font-semibold text-slate-900">Update Password</h2>
        <p class="text-sm text-slate-500 mt-1">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password"
                class="block text-sm font-medium text-slate-700 mb-1.5">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password"
                autocomplete="current-password"
                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-slate-700 mb-1.5">New
                Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password_confirmation"
                class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password"
                class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                Update Password
            </button>

            @if (session('status') === 'password-updated' || session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-emerald-800">Password updated successfully.</p>
                </div>
            @endif
        </div>
    </form>
</section>