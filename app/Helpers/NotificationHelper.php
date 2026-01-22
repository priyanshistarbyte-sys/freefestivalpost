<?php

use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

if (!function_exists('send_app_notification')) {
    function send_app_notification($tokens, $data)
    {
        $serverKey = env('FCM_SERVER_KEY');
        
        $msg = [
            'title' => $data['title'],
            'body' => $data['body'],
            'icon' => $data['icon'] ?? '',
        ];

        $fields = [
            'registration_ids' => $tokens,
            'notification' => $msg
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
}

if (!function_exists('send_notification_by_type')) {
    function send_notification_by_type($type, $mid)
    {
        $message = '';
        $icon = '';
        $title = '';

        switch ($type) {
            case 'student_result_accepted':
                $message = '<font color="#6ba54a">તમારા બાળક નું સ્કૂલ નું રિજલ્ટ સ્વીકારવામાં આવું છે. અને ટૂક સમય માં તમારો કોંટેક્ટ કરવામાં આવશે.</font><br>';
                $title = '<font color="#6ba54a">તમારા બાળક નું સ્કૂલ નું રિજલ્ટ સ્વીકારવામાં આવું છે.</font>';
                break;
            case 'sponsor_accepted':
                $message = '<font color="#6ba54a">તમારા ધંધા ની Sponsorship સ્વીકારવામાં આવી છે. અને ટૂક સમય માં તમારો કોંટેક્ટ કરવામાં આવશે.</font><br>';
                $title = '<font color="#6ba54a">તમારા ધંધા ની Sponsorship સ્વીકારવામાં આવી છે.</font>';
                break;
            case 'donor':
                $message = '<font color="#6ba54a">કાર્યક્રમ ના દાતાશ્રીઓ માટેનું તમારું દાન સ્વીકારવામાં આવું છે. તમારો ખૂબખૂબ આબાર.</font><br>';
                $title = '<font color="#6ba54a">કાર્યક્રમ ના દાતાશ્રીઓ માટેનું તમારું દાન સ્વીકારવામાં આવું છે.</font><br>';
                break;
            case 'notice_add':
                $message = '<font color="#6ba54a">હણોલ ગામ ના સ્નેહ મિલન માટે ની Notice જાહેર કરવામાં આવી છે. જેને જોવા માટે એપ્લિકેશન માં Notice Board મેનૂ પર ક્લિક કરો.</font><br>';
                $title = '<font color="#6ba54a">હણોલ ગામ ના સ્નેહ મિલન માટે ની Notice જાહેર કરવામાં આવી છે.</font><br>';
                break;
            case 'student_result_accepted_setting':
                $message = '<p>હણોલ ગામ એપ્લિકેશન દ્વારા તમારા બાળક નું આ વર્ષ નું સ્કૂલ નું રિજલ્ટ નો ફોટો અને એના માર્કસ મોકલી આપો.</p><ul><li>Student મેનૂ માં વિધાર્થી ની માહિતી ફોટા સાથે ભરો.</li><li>Student Result મેનૂ માં વિધાર્થી ની માર્કશીટ નો ફોટો મોકલો.</li></ul><p><br></p><p><font color="#ce0000">નોંધ : તમામ માહિતી માર્કશીટ માં હોય તે જ આપવી. સાચી માહિતી હશે તો વિધાર્થી નું રિજલ્ટ સ્વીકારવામાં આવશે, અને તમારા મોબાઇલ માં મેસેજ આવી જશે, જો માહિતી ખોટી હશે તો સ્વીકારવામાં આવશે નહીં, એપ્લિકેશન માં મેસેજ આવશે, અને ફરી માહિતી સુધારી ને બીજી વાર મોકલી શકો.</font><br></p>';
                $title = '<p><font color="#6ba54a">સ્નેહ મિલન માટે વિધાર્થી ના સ્કૂલ ના રિજલ્ટ મોકલવાનો ટાઇમ આવી ગયો.</font></p>';
                break;
            case 'sponsor_accepted_setting':
                $message = '<font color="#6ba54a">હણોલ ગામ ના સ્નેહ મિલન માટે તમારા ધંધા ની Sponsorship આપી શકો છો. Sponsorship આપવા માટે એપ્લિકેશન માં Sponsorship મેનૂ માં જઈને આપી શકશો. એને સ્વીકારવામાં આવશે એટલે તમને Notification આવી જશે ને, તરત તમારો કોંટેક્ટ કરવામાં આવશે.</font>';
                $title = '<font color="#6ba54a">હવે તમે હણોલ ગામ ના સ્નેહ મિલન માટે તમારા ધંધા ની Sponsorship આપી શકો છો.</font><br>';
                break;
            case 'donor_accepted_setting':
                $message = 'હણોલ ગામ ના સ્નેહ મિલન માટે કાર્યક્રમ ના દાતાશ્રીઓ માટેનું તમારું દાન આપી શકો છો. દાન આપવા માટે એપ્લિકેશન માં Donor મેનૂ માં જઈને આપી શકશો. એને સ્વીકારવામાં આવશે એટલે તમને Notification આવી જશે ને, તરત તમારો કોંટેક્ટ કરવામાં આવશે.';
                $title = '<font color="#6ba54a">હણોલ ગામ ના સ્નેહ મિલન માટે કાર્યક્રમ ના દાતાશ્રીઓ માટેનું તમારું દાન આપી શકો છો.</font><br>';
                break;
            case 'people_accepted_setting':
                $message = '<font color="#6ba54a">હણોલ ગામ ના સ્નેહ મિલન માટે નું આયોજન જાળવી રાખવા માટે તમને તમારા ઘરેથી કેટલા સભ્યો આ પ્રસંગ માં આવી શકે એમ છે તો એનો જવાબ તમે એપ્લિકેશન માં Member Accepted મેનૂ માં જઈને એમાં આવાના હોય એટલા સભ્યો ની સંખ્યા લખી Submit પર ક્લિક કરો.</font><br>';
                $title = '<font color="#ce0000">હણોલ ગામ ના સ્નેહ મિલન માં તમારા ઘરેથી કેટલા સભ્યો આ પ્રસંગ માં આવી શકે એમ છે?</font><br>';
                break;
            case 'help_desk':
                $message = '<font color="#6ba54a">Help મેનૂ માં કઈક નવું અપડેટ કરવામાં આવું છે. એને જોવા માટે Help મેનૂ પર ક્લિક કરો.</font><br>';
                $title = '<font color="#6ba54a">Help મેનૂ માં કઈક નવું અપડેટ કરવામાં આવું છે.</font><br>';
                break;
            default:
                return;
        }

        $data = [
            'body' => $message,
            'icon' => $icon,
            'title' => $title,
            'ValueType' => ",",
        ];

        push_notification_android($mid, $data);
    }
}

if (!function_exists('push_notification_test')) {
    function push_notification_test($message)
    {
        $tokens = [
            "dNhuWnc3SUKkHz4DuRb71C:APA91bGPevdS_Yf3ucQUCiOHgdPrHT-IuE1iVsE6dQhlSAx6Oa3Q1HMZq7tswQ0knzpFveouD7_b5DFmLPgWjVj7RW9jG3yoM5bJcGEfHrBY-K5KFVUcr83e5QfDzYr1L2-UgJ-r15wj"
        ];
        
        return send_app_notification($tokens, $message);
    }
}

if (!function_exists('push_notification_android')) {
    function push_notification_android($message, $userFilter)
    {
        $perPageLimit = 500;
        
        if ($userFilter == "7") {
            $token = Notification::where('device_id', '57b03281286bc5bc')
                ->value('token');
            
            if ($token) {
                $tokens = [$token];
                return send_app_notification($tokens, $message);
            }
            return null;
        }

        $query = Notification::where('token', '!=', '');

        switch ($userFilter) {
            case "5": // Expired Trial
                $query->join('admin', 'notification.user_id', '=', 'admin.id')
                      ->where('admin.ispaid', 0)
                      ->where('admin.planStatus', 1)
                      ->where('admin.status', 1);
                break;
            case "6": // Expired Paid
                $query->join('admin', 'notification.user_id', '=', 'admin.id')
                      ->where('admin.ispaid', 0)
                      ->where('admin.planStatus', 2)
                      ->where('admin.status', 1);
                break;
            case "3": // Active Trial User
                $query->join('admin', 'notification.user_id', '=', 'admin.id')
                      ->where('admin.ispaid', 1)
                      ->where('admin.planStatus', 1)
                      ->where('admin.status', 1);
                break;
            case "2": // Active Paid User
                $query->join('admin', 'notification.user_id', '=', 'admin.id')
                      ->where('admin.ispaid', 1)
                      ->where('admin.planStatus', 2)
                      ->where('admin.status', 1);
                break;
            case "8": // Total Free User
                $query->join('admin', 'notification.user_id', '=', 'admin.id')
                      ->where('admin.ispaid', 0)
                      ->whereNull('admin.planStatus')
                      ->where('admin.status', 1);
                break;
            case "4": // Without Logo
                $query->join('admin', 'notification.user_id', '=', 'admin.id')
                      ->where('admin.photo', '');
                break;
            case "1": // New User (Last 3 days)
                $newDate = now()->subDays(3);
                $query->join('admin', 'notification.user_id', '=', 'admin.id')
                      ->where('admin.created_at', '>', $newDate);
                break;
        }

        $totalCount = $query->count();
        $totalPage = ceil($totalCount / $perPageLimit);

        for ($i = 0; $i < $totalPage; $i++) {
            $tokens = $query->skip($i * $perPageLimit)
                           ->take($perPageLimit)
                           ->pluck('token')
                           ->toArray();

            if (!empty($tokens)) {
                send_app_notification($tokens, $message);
            }
        }

        return true;
    }
}