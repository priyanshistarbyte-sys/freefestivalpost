<?php

use App\Models\WhatsappLog;
use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Http;

if (!function_exists('send_media_whatsapp_direct')) {
    function send_media_whatsapp_direct($mobile, $message)
    {
        $mobileCode = "91" . $mobile;

        $data = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $mobileCode,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => $message
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('WHATSAPP_AUTH_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post('https://graph.facebook.com/v17.0/' . env('WHATSAPP_PHONE_ID') . '/messages', $data);

            return $response->body();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

if (!function_exists('set_whatsapp_api_tamplate')) {
    function set_whatsapp_api_tamplate($mobile, $tamp_name, $userName, $expired, $team, $cam_id, $custom_auto)
    {
        $tampData = WhatsappTemplate::where('template', $tamp_name)->first();
        
        if (!$tampData) {
            return false;
        }

        $mobileCode = "91" . $mobile;
        $language = $tampData->lang;
        $img_url = $tampData->media;
        $param = $tampData->param;

        // Build parameters based on template
        $parameters = [];
        if (!empty($param)) {
            $paramArray = json_decode($param, true);
            if (is_array($paramArray)) {
                foreach ($paramArray as $p) {
                    if ($p == 'userName') {
                        $parameters[] = ["type" => "text", "text" => $userName];
                    } elseif ($p == 'expired') {
                        $parameters[] = ["type" => "text", "text" => $expired];
                    } elseif ($p == 'team') {
                        $parameters[] = ["type" => "text", "text" => $team];
                    }
                }
            }
        }

        $components = [];
        
        // Add header with image if media exists
        if (!empty($img_url)) {
            $components[] = [
                "type" => "header",
                "parameters" => [
                    [
                        "type" => "image",
                        "image" => [
                            "link" => $img_url
                        ]
                    ]
                ]
            ];
        }

        // Add body parameters
        if (!empty($parameters)) {
            $components[] = [
                "type" => "body",
                "parameters" => $parameters
            ];
        }

        $data = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $mobileCode,
            "type" => "template",
            "template" => [
                "name" => $tamp_name,
                "language" => [
                    "policy" => "deterministic",
                    "code" => $language
                ],
                "components" => $components
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('WHATSAPP_AUTH_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post('https://graph.facebook.com/v17.0/' . env('WHATSAPP_PHONE_ID') . '/messages', $data);

            if ($response->successful()) {
                WhatsappLog::create([
                    'cam_id' => $cam_id,
                    'mobile' => $mobile,
                    'tamp_name' => $tampData->id,
                    'status' => 1,
                    'msg_type' => $custom_auto,
                    'response' => $response->body(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return true;
            } else {
                WhatsappLog::create([
                    'cam_id' => $cam_id,
                    'mobile' => $mobile,
                    'tamp_name' => $tampData->id,
                    'status' => 0,
                    'msg_type' => $custom_auto,
                    'response' => $response->body(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            WhatsappLog::create([
                'cam_id' => $cam_id,
                'mobile' => $mobile,
                'tamp_name' => $tampData->id,
                'status' => 0,
                'msg_type' => $custom_auto,
                'response' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return false;
        }
    }
}
