<x-guest-layout>
    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.75rem; font-weight: 700; color: #000e3d; margin-bottom: 0.5rem;">Welcome Back</h2>
        <p style="color: #666; font-size: 0.875rem;">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <div style="position: relative;">
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem 3rem 0.75rem 0.75rem; font-size: 14px;">
                <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #666; font-size: 16px;">
                    <i class="fas fa-user"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <div style="position: relative;">
                <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem 3rem 0.75rem 0.75rem; font-size: 14px;">
                <span onclick="togglePassword()" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 16px;">
                    <i id="toggleIcon" class="fas fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
            <a href="{{ route('register') }}" style="color: #000e3d; text-decoration: none; font-size: 0.875rem; font-weight: 500;">Need an account?</a>
            
            <button type="submit" class="btn btn-primary" style="background-color: #000e3d; border: none; padding: 0.75rem 2rem; border-radius: 6px; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>
                {{ __('Sign In') }}
            </button>
        </div>
    </form>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }
    </script>
</x-guest-layout>
