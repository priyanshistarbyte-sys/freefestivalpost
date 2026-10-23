<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwoFactorService
{
    protected $apiKey;
    protected $templateName;

    public function __construct()
    {
        $this->apiKey = config('services.twofactor.api_key');
        $this->templateName = config('services.twofactor.otp_template');
    }

    public function sendOtp($mobile, $otp)
    {
        if (empty($this->apiKey)) {
            Log::error('2Factor OTP error: TWOFACTOR_API_KEY is not set in .env');
            return false;
        }

        // Login user's mobile number in +91XXXXXXXXXX format
        $mobile = '+91' . substr(preg_replace('/\D/', '', $mobile), -10);

        $url = "https://2factor.in/API/V1/{$this->apiKey}/SMS/{$mobile}/{$otp}";
        if (!empty($this->templateName)) {
            $url .= '/' . rawurlencode($this->templateName);
        }

        try {
            $response = Http::timeout(15)->get($url);

            if ($response->successful() && $response->json('Status') === 'Success') {
                Log::info("2Factor OTP sent successfully to: {$mobile} | 2Factor ID: " . $response->json('Details'));
                return true;
            }

            Log::error('2Factor OTP failed: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('2Factor OTP error: ' . $e->getMessage());
            return false;
        }
    }
}
