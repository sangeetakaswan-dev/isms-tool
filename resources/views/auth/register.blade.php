<x-guest-layout>
    <div class="logo-section" style="margin-bottom: 25px;">
        <img src="{{ asset('images/logo-icon.png') }}" class="custom-logo" alt="ISMS Logo">
        <div class="brand-name">ISMS <span>Audit</span></div>
        <div class="brand-tagline">ISO 27001 Compliance</div>
    </div>

    <div class="text-center mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text-color);">Create your account</h2>
        <p class="text-sm mt-1" style="color: var(--text-muted);">Start your 14-day free trial.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group">
            <i class="fas fa-user"></i>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                placeholder="Full Name">
        </div>
        <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-red-500 text-xs" />

        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Work Email">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-red-500 text-xs" />

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input id="password" type="password" name="password" required placeholder="Password">
            <span class="password-toggle" onclick="togglePassword('password')"><i class="fas fa-eye-slash"></i></span>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-red-500 text-xs" />

        <div class="input-group">
            <i class="fas fa-check-circle"></i>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                placeholder="Confirm Password">
            <span class="password-toggle" onclick="togglePassword('password_confirmation')"><i
                    class="fas fa-eye-slash"></i></span>
        </div>

        <div class="flex items-start mb-6 px-2">
            <input id="terms" name="terms" type="checkbox" required
                class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <label for="terms" class="ml-2 text-xs" style="color: var(--text-muted);">
                I agree to the <a href="#" class="premium-link">Terms</a> and <a href="#" class="premium-link">Privacy
                    Policy</a>.
            </label>
        </div>

        <button type="submit" class="premium-btn">Create Account <i class="fas fa-arrow-right ml-1"></i></button>

        <p class="text-center text-sm mt-6" style="color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" class="premium-link">Sign in</a>
        </p>
    </form>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = input.parentElement.querySelector('.password-toggle i');
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