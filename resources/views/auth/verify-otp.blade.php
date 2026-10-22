<x-guest-layout>
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="background: #f8f9fa; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
            <i class="fas fa-mobile-alt" style="font-size: 2rem; color: #000e3d;"></i>
        </div>
        <h2 style="font-size: 1.75rem; font-weight: 700; color: #000e3d; margin-bottom: 0.5rem;">Verify OTP</h2>
        <p style="color: #666; font-size: 0.875rem;">Enter the 6-digit code sent to your mobile</p>
        {{-- @if(session('success'))
            <div style="background: #e8f5e9; border: 1px solid #4caf50; border-radius: 6px; padding: 0.75rem; font-size: 0.875rem; color: #2e7d32; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif --}}
    </div>

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <!-- OTP Code -->
        <div class="form-group">
            <label for="otp_code" class="form-label">{{ __('OTP Code') }}</label>
            <input id="otp_code" class="form-control" type="text" name="otp_code" value="{{ old('otp_code') }}" required autofocus maxlength="6" style="border-radius: 6px; border: 1px solid #ced4da; padding: 0.75rem; font-size: 18px; text-align: center; letter-spacing: 0.5rem; font-weight: 600;">
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary" style="background-color: #000e3d; border: none; padding: 0.75rem 2rem; border-radius: 6px; font-weight: 600; width: 100%; transition: all 0.3s ease;">
                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                {{ __('Verify OTP') }}
            </button>
        </div>

    </form>

    <form method="POST" action="{{ route('otp.resend') }}" id="resend-otp-form">
        @csrf
        <div style="text-align: center; margin-top: 1rem;">
            <p style="color: #666; font-size: 0.875rem;">
                Didn't receive the code or OTP expired?
                <button type="submit" id="resend-otp-btn" style="background: none; border: none; padding: 0; color: #000e3d; font-weight: 500; cursor: pointer;">Resend</button>
                <span id="resend-otp-timer" style="color: #999;"></span>
            </p>
        </div>
    </form>

    <script>
        (function () {
            var btn = document.getElementById('resend-otp-btn');
            var timer = document.getElementById('resend-otp-timer');
            var form = document.getElementById('resend-otp-form');
            var left = {{ $resendIn ?? 0 }};

            function tick() {
                if (left > 0) {
                    btn.disabled = true;
                    btn.style.color = '#999';
                    btn.style.cursor = 'not-allowed';
                    timer.textContent = 'in ' + left + 's';
                    left--;
                    setTimeout(tick, 1000);
                } else {
                    btn.disabled = false;
                    btn.style.color = '#000e3d';
                    btn.style.cursor = 'pointer';
                    timer.textContent = '';
                }
            }
            tick();

            // Prevent double click sending two OTPs
            form.addEventListener('submit', function () {
                btn.disabled = true;
                btn.textContent = 'Sending...';
            });
        })();
    </script>
</x-guest-layout>