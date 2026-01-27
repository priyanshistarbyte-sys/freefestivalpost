<x-guest-layout>
    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.75rem; font-weight: 700; color: #000e3d; margin-bottom: 0.5rem;">Create Account</h2>
        <p style="color: #666; font-size: 0.875rem;">Join us today</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">{{ __('Full Name') }}</label>
            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem; font-size: 14px;">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem; font-size: 14px;">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mobile Number -->
        <div class="form-group">
            <label for="mobile" class="form-label">{{ __('Mobile Number') }}</label>
            <input id="mobile" class="form-control" type="text" name="mobile" value="{{ old('mobile') }}" required style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem; font-size: 14px;">
            <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem; font-size: 14px;">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem; font-size: 14px;">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
            <a href="{{ route('login') }}" style="color: #000e3d; text-decoration: none; font-size: 0.875rem; font-weight: 500;">Already have an account?</a>
            
            <button type="submit" class="btn btn-primary" style="background-color: #000e3d; border: none; padding: 0.75rem 2rem; border-radius: 6px; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
