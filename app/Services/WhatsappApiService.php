<?php

namespace App\Services;

use App\Models\WhatsappLog;
use App\Models\WhatsappTemplate;

class WhatsappApiService
{
    public function set_whatsapp_api_tamplate($mobile, $tamp_name, $userName, $expired, $team, $cam_id, $custom_auto)
    {
        $tampData = WhatsappTemplate::where('template', $tamp_name)->first();
        
        if (!$tampData) {
            return false;
        }

        // Build parameter string
        $paramiter = $this->buildParameters($userName, $expired, $team);
        
        // Call send function
        return $this->send_media_whatsapp_temp($mobile, $tamp_name, $paramiter, $tampData, $cam_id, $custom_auto);
    }

    private function buildParameters($userName, $expired, $team)
    {
        $params = [];
        
        if (!empty($userName)) {
            $params[] = '{"type": "text", "text": "' . $userName . '"}';
        }
        
        if (!empty($expired)) {
            $params[] = '{"type": "text", "text": "' . $expired . '"}';
        }
        
        if (!empty($team)) {
            $params[] = '{"type": "text", "text": "' . $team . '"}';
        }
        
        if (empty($params)) {
            return '';
        }
        
        return '{
            "type": "body",
            "parameters": [' . implode(',', $params) . ']
        }';
    }

    private function send_media_whatsapp_temp($mobile, $tamp_name, $paramiter, $tampData, $cam_id, $custom_auto)
    {
        $mobileCode = "91" . $mobile;
        $language = $tampData->lang;
        $img_url = $tampData->media;

        $url = "https://graph.facebook.com/v17.0/" . env('WHATSAPP_PHONE_ID', '108289462325948') . "/messages";
        
        $data = '{
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": "' . $mobileCode . '",
            "type": "template",
            "template": {
                "name": "' . $tamp_name . '",
                "language": {
                    "policy": "deterministic",
                    "code": "' . $language . '"
                },
                "components": [
                    {
                        "type": "header",
                        "parameters": [
                            {
                                "type": "image",
                                "image": {
                                    "link": "' . $img_url . '"
                                }
                            }
                        ]
                    },
                    ' . $paramiter . '
                ]
            }
        }';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POST, true);

        $headers = [
            "Authorization: Bearer " . env('WHATSAPP_AUTH_TOKEN'),
            "Content-Type: application/json",
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            WhatsappLog::create([
                'cam_id' => $cam_id,
                'mobile' => $mobile,
                'tamp_name' => $tampData->id,
                'status' => 0,
                'msg_type' => $custom_auto,
                'response' => $err,
                'created_at' => now(),
            ]);
            return false;
        } else {
            WhatsappLog::create([
                'cam_id' => $cam_id,
                'mobile' => $mobile,
                'tamp_name' => $tampData->id,
                'status' => 1,
                'msg_type' => $custom_auto,
                'response' => $response,
                'created_at' => now(),
            ]);
            return true;
        }
    }

public function send_media_whatsapp_direct($mobile, $message)
    {
        $mobileCode = "91" . $mobile;
        $url = "https://graph.facebook.com/v17.0/" . env('WHATSAPP_PHONE_ID', '108289462325948') . "/messages";
        
        $data = '{
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": "' . $mobileCode . '",
            "type": "text",
            "text": {
                "preview_url": false,
                "body": "' . $message . '"
            }
        }';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POST, true);

        $headers = [
            "Authorization: Bearer " . env('WHATSAPP_AUTH_TOKEN'),
            "Content-Type: application/json",
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }
}
