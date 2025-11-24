<x-guest-layout>
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Verify OTP') }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                {{ __('Please enter the 6-digit OTP sent to your mobile number.') }}
            </p>
        </div>

        <!-- OTP Code -->
        <div>
            <x-input-label for="otp_code" :value="__('OTP Code')" />
            <x-text-input id="otp_code" class="block mt-1 w-full" type="text" name="otp_code" :value="old('otp_code')" required autofocus maxlength="6" />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verify OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>