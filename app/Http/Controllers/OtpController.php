<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\Admin;
use App\Services\SmsService;
use App\Services\Msg91Service;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OtpController extends Controller
{
    const RESEND_COOLDOWN = 30; /* seconds */

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = Admin::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        // Validate password (MD5 format)
        if (md5($request->password) !== $user->password) {
            return back()->withErrors(['password' => 'Invalid password']);
        }

        if (empty($user->mobile)) {
            return back()->withErrors(['mobile' => 'Mobile number not found']);
        }

        if (!$this->formatMobileNumber($user->mobile)) {
            return back()->withErrors(['mobile' => 'Invalid mobile number format.']);
        }

        $sent = $this->generateAndSendOtp($user, $otpCode);

        session(['otp_user_id' => $user->id]);

        if (!$sent) {
            return back()->withErrors(['mobile' => 'Unable to send OTP. Please try again.']);
        }

        return redirect()->route('otp.verify')->with('success', 'OTP sent to your registered mobile number.');
    }

    public function resendOtp()
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = Admin::find($userId);
        if (!$user || !$this->formatMobileNumber($user->mobile)) {
            session()->forget('otp_user_id');
            return redirect()->route('login')->withErrors(['mobile' => 'Mobile number not found']);
        }

        // Allow a new OTP only after the cooldown from the last one
        $wait = $this->resendWaitSeconds($user->id);
        if ($wait > 0) {
            return back()->withErrors(['otp_code' => "Please wait {$wait} seconds before requesting a new OTP."]);
        }

        $sent = $this->generateAndSendOtp($user, $otpCode);

        if (!$sent) {
            return back()->withErrors(['otp_code' => 'Unable to resend OTP. Please try again.']);
        }

        return back()->with('success', 'A new OTP has been sent to your registered mobile number.');
    }

    /* Delete old OTPs, create a new one and send it by SMS */
    private function generateAndSendOtp(Admin $user, &$otpCode)
    {
        Otp::where('user_id', $user->id)->delete();

        $otpCode = rand(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'mobile_number' => $user->mobile,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5)
        ]);

        return (new TwoFactorService())->sendOtp($this->formatMobileNumber($user->mobile), $otpCode);
    }

    /* Seconds left before a new OTP can be requested */
    private function resendWaitSeconds($userId)
    {
        $lastOtp = Otp::where('user_id', $userId)->latest()->first();
        if (!$lastOtp) {
            return 0;
        }

        return max(0, (int) ceil(self::RESEND_COOLDOWN - $lastOtp->created_at->diffInSeconds(Carbon::now(), true)));
    }

    public function showVerifyForm()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        
        return view('auth.verify-otp', ['resendIn' => $this->resendWaitSeconds(session('otp_user_id'))]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6'
        ]);

        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $otp = Otp::where('user_id', $userId)
                  ->where('otp_code', $request->otp_code)
                  ->where('is_verified', false)
                  ->first();

        if (!$otp || $otp->isExpired()) {
            return back()->withErrors(['otp_code' => 'Invalid or expired OTP']);
        }

        // Mark OTP as verified
        $otp->update(['is_verified' => true]);

        // Get user and assign role if not already assigned
        $user = Admin::find($userId);
        if ($user && !$user->hasAnyRole()) {
            // Assign role based on user's role field or default to Admin
            $roleName = $user->role === 'Sub Admin' ? 'Sub Admin' : 'Admin';
            $user->assignRole($roleName);
        }

        // Login user
        Auth::loginUsingId($userId);
        session()->forget('otp_user_id');

        return redirect()->intended('/admin/dashboard');
    }

    private function formatMobileNumber($number)
    {
        $number = preg_replace('/\D/', '', $number);

        // Case 1: If user already entered with country code (e.g. 919876543210)
        if (strlen($number) > 10) {
            return '+' . $number;
        }

        // Case 2: If user entered only 10-digit Indian number
        if (strlen($number) == 10) {
            return '+91' . $number;
        }

        // Case 3: Invalid number
        return false;
    }
}