<?php

use Razorpay\Api\Api;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

if (!function_exists('paymentLinkCreateForUser_post')) {
    function paymentLinkCreateForUser_post($user_data)
    {
        if ($user_data['token'] == config('app.token_cutm')) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $paymentLinkCreate = $api->paymentLink->create([
                'amount' => $user_data['amount'],
                'expire_by' => $user_data['expire_by'],
                'reference_id' => $user_data['reference_id'],
                'description' => $user_data['description'],
                'customer' => [
                    'name' => $user_data['name'],
                    'email' => $user_data['email'],
                    'contact' => $user_data['contact']
                ],
                'notify' => [
                    'sms' => $user_data['smsOn'],
                    'email' => $user_data['emailOn']
                ],
                'notes' => [
                    'user_id' => $user_data['user_id'],
                    'type' => $user_data['type']
                ],
                'callback_url' => $user_data['callback_url'],
                'callback_method' => $user_data['callback_method']
            ]);

            $mobile = str_replace("+91", "", $user_data['contact']);

            $insertData = [
                'paymentLinkId' => $paymentLinkCreate->id,
                'user_id' => $user_data['user_id'],
                'mobile' => $mobile,
                'attempts' => 1,
                'exp_date' => date('Y-m-d H:i:s', $user_data['expire_by']),
                'link' => $paymentLinkCreate->short_url,
                'created_at' => now(),
            ];

            DB::table('payment_link')->insert($insertData);

            whatsappPaymentLinkSend($mobile, $paymentLinkCreate->short_url);

            return true;
        }
        return false;
    }
}

if (!function_exists('paymentlinkResend')) {
    function paymentlinkResend($user_data)
    {
        if ($user_data['token'] == config('app.token_cutm')) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->paymentLink->fetch($user_data['paymentid'])->notifyBy($user_data['options']);

            DB::table('payment_link')
                ->where('paymentLinkId', $user_data['paymentid'])
                ->increment('attempts');

            whatsappPaymentLinkSend($user_data['mobile'], $user_data['link']);

            return true;
        }
        return false;
    }
}

if (!function_exists('whatsappPaymentLinkSend')) {
    function whatsappPaymentLinkSend($mobile, $link)
    {
        $payID = explode("/i/", $link);
        $paymentID = $payID[1];
        $parm1 = $link;
        
        $paramiter = '{
            "type": "button",
            "sub_type" : "url",
            "index": "0", 
            "parameters": [
                {
                    "type": "text",
                    "text": "' . $paymentID . '"
                }
            ]
        },
        {
            "type": "body",
            "parameters": [
                {
                    "type": "text",
                    "text": "' . $parm1 . '"
                }
            ]
        }';
        
        // Call WhatsApp API service - you'll need to implement this
        // app('whatsapp_api')->send_media_whatsapp_temp($mobile, "payment_failed_link", $paramiter, 1, "auto");
    }
}

if (!function_exists('getOrderByRazorPayAllList')) {
    function getOrderByRazorPayAllList()
    {
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $options = [
                "count" => 100,
            ];
            $res = $api->order->all($options);
            return $res["items"];
        } catch (\Exception $e) {
            \Log::error('Razorpay API Error: ' . $e->getMessage());
            return [];
        }
    }
}

if (!function_exists('getOrderByRazorPayAllListBulk')) {
    function getOrderByRazorPayAllListBulk($start_date, $end_date)
    {
        $end_date = date('Y-m-d', strtotime("+1 day", strtotime($end_date)));
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $options = [
            "count" => 100,
            "from" => strtotime($start_date),
            "to" => strtotime($end_date),
        ];
        $res = $api->order->all($options);
        return $res["items"];
    }
}

if (!function_exists('getUserMobileNumber')) {
    function getUserMobileNumber($userID)
    {
        $admin = Admin::where('id', $userID)->first();
        return $admin ? $admin->mobile : "not found!";
    }
}