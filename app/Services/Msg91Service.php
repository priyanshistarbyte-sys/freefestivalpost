<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Msg91Service
{
    protected $authKey;
    protected $templateId;

    public function __construct()
    {
        $this->authKey = config('services.msg91.auth_key');
        $this->templateId = config('services.msg91.template_id');
    }

    public function sendOtp($mobile, $otp)
    {
        try {
            $response = Http::post('https://control.msg91.com/api/v5/otp', [
                'template_id' => $this->templateId,
                'mobile' => $mobile,
                'authkey' => $this->authKey,
                'otp' => $otp
            ]);

            if ($response->successful()) {
                Log::info("MSG91 OTP sent successfully to: {$mobile}");
                return true;
            }

            Log::error("MSG91 OTP failed: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("MSG91 OTP error: " . $e->getMessage());
            return false;
        }
    }
}