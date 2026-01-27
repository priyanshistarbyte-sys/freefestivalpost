<x-guest-layout>
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="background: #f8f9fa; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
            <i class="fas fa-key" style="font-size: 2rem; color: #000e3d;"></i>
        </div>
        <h2 style="font-size: 1.75rem; font-weight: 700; color: #000e3d; margin-bottom: 0.5rem;">Forgot Password?</h2>
        <p style="color: #666; font-size: 0.875rem;">Enter your email to receive a password reset link</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem; font-size: 14px;">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary" style="background-color: #000e3d; border: none; padding: 0.75rem 2rem; border-radius: 6px; font-weight: 600; width: 100%; transition: all 0.3s ease;">
                <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i>
                {{ __('Send Reset Link') }}
            </button>
        </div>

        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('login') }}" style="color: #000e3d; text-decoration: none; font-size: 0.875rem; font-weight: 500;">Back to Login</a>
        </div>
    </form>
</x-guest-layout>
