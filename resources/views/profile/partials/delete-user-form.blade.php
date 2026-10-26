<section>
    <header class="mb-4">
        <h2 class="text-lg font-semibold text-red-600">Delete Account</h2>
        <p class="text-sm text-red-500/80 mt-1">Once your account is deleted, all of its resources and data will be
            permanently deleted.</p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="w-full px-4 py-2 bg-white border border-red-300 text-red-600 hover:bg-red-50 text-sm font-medium rounded-lg transition shadow-sm">
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900">
                Are you sure you want to delete your account?
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter
                your password to confirm you would like to permanently delete your account.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password" type="password" placeholder="Password"
                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    Yes, Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>