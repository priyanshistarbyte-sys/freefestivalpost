<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Payment;
use App\Models\WebhookFailed;
use App\Models\WebhookAuthorized;
use App\Models\Tamplet;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function makePostByUser(Request $request)
    {
        $token = $request->input('token');
        $user_id = $request->input('user_id');
        
        if (!$this->checkToken($user_id, $token)) {
            return response()->json([
                'status' => false,
                'message' => 'User is not authorized to use.',
                'data' => []
            ]);
        }

        $logo = $request->input('logo');
        if ($logo && !file_exists(public_path("media/logo/" . $logo))) {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong please check your logo or retry login.',
                'data' => []
            ]);
        }
 
        $result = [
            'user_id' => $user_id,
            'logo' => $logo,
            'business_name' => ucwords($request->input('business_name')),
            'name' => ucwords($request->input('name')),
            'mobile1' => $request->input('mobile1'),
            'mobile2' => $request->input('mobile2'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
            'address' => ucwords($request->input('address')),
            'tamplate_id' => $request->input('tamplate_id'),
            'birthdayPhoto' => $request->input('birthdayPhoto'),
            'birthdayName' => ucwords($request->input('birthdayName')),
        ];

        $total_today_post_limit = 5; // Define your limit
        $user_paid = $this->userCheckPaidFree($user_id);

        if ($user_paid) {
            $userPostUrl = $this->makePost($result);
            return response()->json([
                'status' => true,
                'message' => 'Result Successfully get!....',
                'data' => $userPostUrl
            ]);
        } else {
            $totalUserPostCount = $this->countUserPost($user_id);
            if ($totalUserPostCount >= $total_today_post_limit) {
                return response()->json([
                    'status' => false,
                    'message' => "Today your limit is over. Daily {$total_today_post_limit} Post. Please go to Premium",
                    'data' => []
                ]);
            } else {
                $userPostUrl = $this->makePost($result);
                return response()->json([
                    'status' => true,
                    'message' => 'Result Successfully get!....',
                    'data' => $userPostUrl
                ]);
            }
        }
    }

    public function webhookPayment(Request $request)
    {
        $rawData = file_get_contents("php://input");
        if (empty($rawData)) {
            $rawData = json_encode($request->all());
        }

        DB::table('test')->insert([
            'testName' => "webhooks1",
            'testData' => $rawData,
            'created_at' => now(),
        ]);

        $arr = json_decode($rawData, true);

        if (!empty($arr) && count($arr) != 0) {
            $event = $arr['event'];
            $status = $arr['payload']['payment']['entity']['status'];
            $payment_id = $arr['payload']['payment']['entity']['id'];
            $amount = $arr['payload']['payment']['entity']['amount'];
            $email = $arr['payload']['payment']['entity']['email'];
            $mobile = $arr['payload']['payment']['entity']['contact'];

            $notes = $arr['payload']['payment']['entity']['notes'] ?? [];
            if (!empty($notes)) {
                $user_id = $notes['user_id'];
            } else {
                $description = $arr['payload']['payment']['entity']['description'];
                if (strpos($description, "#") === false) {
                    $description = "abc#xyz";
                }
                $user_data = explode("#", $description);
                $user_id = $user_data[1];
            }

            if ($event == "payment.authorized" && $status == "authorized") {
                $transactionIDCheck = Payment::where('transactionid', $payment_id)->count();

                if ($transactionIDCheck <= 0) {
                    $mobile = str_replace("+91", "", $mobile);
                    $amount = ($amount / 100);

                    $this->failedRepaymentRemove($mobile);

                    $user_data = Admin::find($user_id);
                    $plan = DB::table('subscription_plan')->where('price', $amount)->first();

                    if (!empty($plan) && !empty($user_id) && !empty($user_data)) {
                        $this->userSubPaymentHistoryFun($user_id, $payment_id, $plan);
                    } else {
                        WebhookAuthorized::create([
                            'date' => now()->toDateString(),
                            'event' => $event,
                            'transaction_id' => $payment_id,
                            'amount' => $amount,
                            'email' => $email,
                            'mobile' => $mobile,
                            'status' => 0,
                            'created_at' => now(),
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function webhookPaymentFailed(Request $request)
    {
        $rawData = file_get_contents("php://input");
        if (empty($rawData)) {
            $rawData = json_encode($request->all());
        }

        DB::table('test')->insert([
            'testName' => "fail-payment",
            'testData' => $rawData,
            'created_at' => now(),
        ]);

        $arr = json_decode($rawData, true);

        if (!empty($arr) && count($arr) != 0) {
            $event = $arr['event'];
            $status = $arr['payload']['payment']['entity']['status'];
            $payment_id = $arr['payload']['payment']['entity']['id'];
            $amount = $arr['payload']['payment']['entity']['amount'];
            $amountRS = ($amount / 100);
            $email = $arr['payload']['payment']['entity']['email'];
            $mobile = $arr['payload']['payment']['entity']['contact'];
            $mobileRS = str_replace("+91", "", $mobile);

            if ($event == "payment.failed" && $status == "failed") {
                WebhookFailed::where('mobile', $mobileRS)->delete();

                WebhookFailed::create([
                    'date' => now()->toDateString(),
                    'event' => $event,
                    'transaction_id' => $payment_id,
                    'amount' => $amountRS,
                    'email' => $email,
                    'mobile' => $mobileRS,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function makePost($result)
    {
        if (empty($result)) {
            return false;
        }

        $tamplate_result = Tamplet::find($result['tamplate_id']);
        if (!$tamplate_result) {
            return false;
        }

        $userNewFileName = $this->myCopy($tamplate_result->path, $result['tamplate_id'], $result['user_id']);

        if ($userNewFileName) {
            // Add your image processing logic here
            // This is a simplified version - you'll need to implement the full image processing

            DB::table('makepost')->insert([
                'post' => $userNewFileName,
                'user_id' => $result['user_id'],
                'tamp_id' => $result['tamplate_id'],
                'created_at' => now()
            ]);

            return asset('storage/' . ltrim($userNewFileName, '/'));
        }

        return false;
    }

    private function myCopy($filename, $tamplate, $userid)
    {
        $sourcePath = storage_path('app/public/' . $filename);
        $ext = '.png';
        $newFileName = 'uploads/posts/' . time() . '_' . $tamplate . '_' . $userid . $ext;
        $destinationPath = storage_path('app/public/' . $newFileName);

        if (!file_exists($sourcePath)) {
            return false;
        }

        // Ensure destination directory exists
        $destinationDir = dirname($destinationPath);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $copied = copy($sourcePath, $destinationPath);
        return $copied ? $newFileName : false;
    }

    private function checkToken($user_id, $token)
    {
        if (empty($user_id) || empty($token)) {
            return false;
        }

        return DB::table('token')
            ->where('user_id', $user_id)
            ->where('token', $token)
            ->exists();
    }

    private function userCheckPaidFree($user_id)
    {
        $user = Admin::find($user_id);
        if (!$user) {
            return false;
        }

        return $user->ispaid == 1 && 
               $user->planStatus == 2 && 
               $user->expdate > now()->toDateString();
    }

    private function countUserPost($user_id)
    {
        $todayDate = now()->toDateString();
        return DB::table('makepost')
            ->where('user_id', $user_id)
            ->whereDate('created_at', $todayDate)
            ->count();
    }

    private function failedRepaymentRemove($mobile)
    {
        if (!empty($mobile)) {
            WebhookFailed::where('mobile', $mobile)->delete();
        }
        return true;
    }

    private function userSubPaymentHistoryFun($user_id, $transactionid, $plan)
    {
        $countPlan = Payment::where('user_id', $user_id)->count();

        if ($countPlan > 0 && $plan->month == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, You are not eligible for a trial plan. Thank you',
                'data' => []
            ]);
        }

        $user = Admin::find($user_id);
        $month = $plan->month == 0 ? '+7 days' : '+' . $plan->month . ' months';
        $customStatus = $plan->month == 0 ? 1 : 2;

        if ($user->planStatus == 2 && $user->ispaid == 1 && $user->expdate > now()->toDateString()) {
            $pexpdate = Carbon::parse($user->expdate)->modify($month)->toDateString();
        } else {
            $pexpdate = now()->modify($month)->toDateString();
        }

        $user->update([
            'ispaid' => 1,
            'expdate' => $pexpdate,
            'planStatus' => $customStatus,
            'status' => 1,
        ]);

        Payment::create([
            'user_id' => $user_id,
            'amount' => $plan->price,
            'date' => now()->toDateString(),
            'transactionid' => $transactionid,
            'status' => $plan->plan_name,
            'packageid' => $plan->plan_id,
            'price' => $plan->price,
            'month' => $plan->month,
            'ref_status' => 0,
            'refund_id' => null,
            'refundDate' => null,
            'userRole' => null,
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User Transaction Successfully!....',
            'data' => ''
        ]);
    }
}