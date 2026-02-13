<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function nodeSideEmailSend(Request $request)
    {
        if ($request->input('mytoken') !== 'mVfHmPbTudbqJBWMiqoAPA91bH6gSTssOVJwlpJeuIVwdSbZGFUd4b7HoNZ5FyaNN4LVLbdmffp9') {
            return response()->json(['status' => false, 'message' => 'Unauthorized']);
        }

        $email = $request->input('email');
        $tampType = $request->input('tamp_type');
        $var1 = $request->input('var1', '');
        $var2 = $request->input('var2', '');
        $var3 = $request->input('var3', '');

        // Add your email sending logic here
        Log::info('Email Send Request', compact('email', 'tampType', 'var1', 'var2', 'var3'));

        return response()->json(['status' => true, 'message' => 'Email sent successfully']);
    }

    public function nodeSideSMSSend(Request $request)
    {
        if ($request->input('mytoken') !== 'mVfHmPbTudbqJBWMiqoAPA91bH6gSTssOVJwlpJeuIVwdSbZGFUd4b7HoNZ5FyaNN4LVLbdmffp9') {
            return response()->json(['status' => false, 'message' => 'Unauthorized']);
        }

        $mobile = $request->input('mobile');
        $msg91TampId = $request->input('msg91_tamp_id');
        $otp = $request->input('otp', '');
        $sshcode = $request->input('sshcode', '');
        $smsType = $request->input('sms_type');
        $var1 = $request->input('var1', '');
        $var2 = $request->input('var2', '');
        $var3 = $request->input('var3', '');

        // Add your SMS sending logic here (MSG91 API)
        Log::info('SMS Send Request', compact('mobile', 'msg91TampId', 'smsType', 'otp', 'var1', 'var2', 'var3'));

        return response()->json(['status' => true, 'message' => 'SMS sent successfully']);
    }

    public function nodeSideWhatsAppSMS(Request $request)
    {
        if ($request->input('mytoken') !== 'mVfHmPbTudbqJBWMiqoAPA91bH6gSTssOVJwlpJeuIVwdSbZGFUd4b7HoNZ5FyaNN4LVLbdmffp9') {
            return response()->json(['status' => false, 'message' => 'Unauthorized']);
        }

        $mobile = $request->input('mobile');
        $tempname = $request->input('tempname');
        $userName = $request->input('userName', '');
        $expired = $request->input('expired', '');
        $term = $request->input('term', '');

        // Add your WhatsApp sending logic here
        Log::info('WhatsApp Send Request', compact('mobile', 'tempname', 'userName', 'expired', 'term'));

        return response()->json(['status' => true, 'message' => 'WhatsApp message sent successfully']);
    }
}
