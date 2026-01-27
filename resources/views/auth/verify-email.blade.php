<x-guest-layout>
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="background: #f8f9fa; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
            <i class="fas fa-envelope-open" style="font-size: 2rem; color: #000e3d;"></i>
        </div>
        <h2 style="font-size: 1.75rem; font-weight: 700; color: #000e3d; margin-bottom: 0.5rem;">Verify Email</h2>
        <p style="color: #666; font-size: 0.875rem;">Check your email for verification link</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; text-align: center;">
            <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary" style="background-color: #000e3d; border: none; padding: 0.75rem 2rem; border-radius: 6px; font-weight: 600; width: 100%; transition: all 0.3s ease;">
                <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i>
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background: none; border: none; color: #666; text-decoration: underline; font-size: 0.875rem; cursor: pointer; width: 100%; padding: 0.5rem;">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
