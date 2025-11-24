<?php

namespace App\Services;

use Twilio\Rest\Client;

class SmsService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendOtp($mobileNumber, $otpCode)
    {
        try {
            $message = $this->twilio->messages->create(
                $mobileNumber,
                [
                    'from' => config('services.twilio.from'),
                    'body' => "Your OTP code is: {$otpCode}. Valid for 5 minutes."
                ]
            );
            
            return true;
        } catch (\Exception $e) {
            \Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }
}