<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\Admin;
use App\Services\SmsService;
use App\Services\Msg91Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $user = Admin::where('email',$request->email)->first();
         if (!$user) {
            return back()->withErrors('User not found');
        }
        // Delete previous OTPs
        Otp::where('user_id', $user->id)->delete();

        // Generate 6-digit OTP
        $otpCode = rand(100000, 999999);

       
        if(!empty($user->mobile))
        {
            // Create new OTP
            Otp::create([
                'user_id' => $user->id,
                'mobile_number' => $user->mobile,
                'otp_code' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(5)
            ]);

            // Format mobile number with country code
            $formattedNumber = $this->formatMobileNumber($user->mobile);
          

            if (!$formattedNumber) {
                return back()->withErrors('Invalid mobile number format.');
            }

            // SMS functionality commented for local testing
            // $msg91Service = new Msg91Service();
            // $sent = $msg91Service->sendOtp($formattedNumber, $otpCode);
            
            // For local testing - log OTP
            \Log::info("OTP for {$user->mobile}: {$otpCode}");
            
            session(['otp_user_id' => $user->id]);
            
            return redirect()->route('otp.verify')->with('success', 'OTP: ' . $otpCode . ' (Check SMS)');
        }
        else
        {
            return back()->withErrors('Mobile number not found');
        }
    }

    public function showVerifyForm()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        
        return view('auth.verify-otp');
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

        // Login user
        Auth::loginUsingId($userId);
        session()->forget('otp_user_id');

        return redirect()->intended('dashboard');
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