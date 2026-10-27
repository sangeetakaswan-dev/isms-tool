<x-guest-layout>
    <div class="logo-section">
        <img src="{{ asset('images/logo-icon.png') }}" class="custom-logo" alt="ISMS Logo">
        <div class="brand-name">ISMS <span>Audit</span></div>
        <div class="brand-tagline">ISO 27001 Compliance</div>
    </div>

    <div class="text-center mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text-color);">Welcome back</h2>
        <p class="text-sm mt-1" style="color: var(--text-muted);">Sign in to your workspace to continue.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="Email Address">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-red-500 text-xs" />

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input id="password" type="password" name="password" required placeholder="Password">
            <span class="password-toggle" onclick="togglePassword()"><i class="fas fa-eye-slash"></i></span>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-red-500 text-xs" />

        <div class="flex justify-between items-center mb-6 px-2">
            <label class="flex items-center gap-2 cursor-pointer" style="color: var(--text-muted); font-size: 0.85rem;">
                <input type="checkbox" name="remember" class="rounded text-indigo-600 focus:ring-indigo-500">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a class="premium-link" href="{{ route('password.request') }}">Forgot Password?</a>
            @endif
        </div>

        <button type="submit" class="premium-btn">Sign In <i class="fas fa-sign-in-alt ml-1"></i></button>

        @if (Route::has('register'))
            <p class="text-center text-sm mt-6" style="color: var(--text-muted);">
                Don't have an account? <a href="{{ route('register') }}" class="premium-link">Sign up</a>
            </p>
        @endif

        <div class="divider"><span>Demo Credentials</span></div>
        <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-3 space-y-2">
            <p class="text-xs flex justify-between" style="color: var(--text-color);"><span
                    class="font-semibold">Owner</span> <code>owner@demo.com / password</code></p>
            <p class="text-xs flex justify-between" style="color: var(--text-color);"><span
                    class="font-semibold">Assessor</span> <code>assessor@demo.com / password</code></p>
        </div>
    </form>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }
    </script>
</x-guest-layout>