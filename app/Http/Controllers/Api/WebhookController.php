<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\WebhookFailed;

class WebhookController extends Controller
{
    public function webhookPayment(Request $request)
    {
        $rawData = $request->getContent();
        if (empty($rawData)) {
            $rawData = json_encode($request->all());
        }

        DB::table('test')->insert([
            'testName' => 'webhooks1',
            'testData' => $rawData,
            'created_at' => now()
        ]);

        $data = json_decode($rawData, true);

        if (!empty($data) && count($data) != 0) {
            $event = $data['event'];
            $status = $data['payload']['payment']['entity']['status'];
            $payment_id = $data['payload']['payment']['entity']['id'];
            $amount = $data['payload']['payment']['entity']['amount'];
            $email = $data['payload']['payment']['entity']['email'];
            $mobile = $data['payload']['payment']['entity']['contact'];

            $notes = $data['payload']['payment']['entity']['notes'] ?? [];
            if (!empty($notes)) {
                $user_id = $notes['user_id'];
            } else {
                $description = $data['payload']['payment']['entity']['description'];
                if (strpos($description, "#") === false) {
                    $description = "abc#xyz";
                }
                $user_data = explode("#", $description);
                $user_id = $user_data[1] ?? null;
            }

            if ($event == "payment.authorized" && $status == "authorized") {
                $transactionExists = Payment::where('ptransactionid', $payment_id)->exists();

                if (!$transactionExists) {
                    $mobile = str_replace("+91", "", $mobile);
                    $amount = ($amount / 100);

                    $this->failedRepaymentRemove($mobile);

                    $user = Admin::find($user_id);
                    $plan = SubscriptionPlan::where('price', $amount)->first();

                    if ($plan && $user_id && $user) {
                        $this->userSubPaymentHistory($user_id, $payment_id, $plan);
                    } else {
                        DB::table('webhook_authorized')->insert([
                            'w_date' => now()->format('Y-m-d'),
                            'w_event' => $event,
                            'transaction_id' => $payment_id,
                            'w_amount' => $amount,
                            'w_email' => $email,
                            'w_mobile' => $mobile,
                            'w_status' => 0,
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
        $rawData = $request->getContent();
        if (empty($rawData)) {
            $rawData = json_encode($request->all());
        }

        DB::table('test')->insert([
            'testName' => 'fail-payment',
            'testData' => $rawData,
            'created_at' => now()
        ]);

        $data = json_decode($rawData, true);

        if (!empty($data) && count($data) != 0) {
            $event = $data['event'];
            $status = $data['payload']['payment']['entity']['status'];
            $payment_id = $data['payload']['payment']['entity']['id'];
            $amount = $data['payload']['payment']['entity']['amount'];
            $amountRS = ($amount / 100);

            $email = $data['payload']['payment']['entity']['email'];
            $mobile = $data['payload']['payment']['entity']['contact'];
            $mobileRS = str_replace("+91", "", $mobile);

            $notes = $data['payload']['payment']['entity']['notes'] ?? [];
            $description = $data['payload']['payment']['entity']['description'];

            if (!empty($notes)) {
                $user_id = $notes['user_id'];
            } else {
                if (strpos($description, "#") === false) {
                    $description = "abc#xyz";
                }
                $getUserIdByString = explode("#", $description);
                $user_id = $getUserIdByString[1] ?? null;
            }

            if ($event == "payment.failed" && $status == "failed") {
                WebhookFailed::where('w_mobile', $mobileRS)->delete();

                WebhookFailed::create([
                    'w_date' => now()->format('Y-m-d'),
                    'w_event' => $event,
                    'transaction_id' => $payment_id,
                    'w_amount' => $amountRS,
                    'w_email' => $email,
                    'w_mobile' => $mobileRS,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function userSubPaymentHistory($user_id, $ptransactionid, $plan)
    {
        if ($user_id && $plan && $ptransactionid) {
            $countPlan = Payment::where('u_id', $user_id)->count();

            if ($countPlan > 0 && $plan->month == 0) {
                $data = [
                    'status' => 'error',
                    'message' => 'Sorry, You are not eligible for a trial plan. Thank you',
                    'data' => []
                ];
            } else {
                $userDataCheck = Admin::where('id', $user_id)
                    ->where('planStatus', 2)
                    ->where('ispaid', 1)
                    ->where('expdate', '>', now()->format('Y-m-d'))
                    ->first();

                $customStatus = $plan->month == 0 ? 1 : 2;
                $month = $plan->month == 0 ? '+7 days' : '+' . $plan->month . ' months';

                if ($userDataCheck) {
                    $pexpdate = date("Y-m-d", strtotime($month, strtotime($userDataCheck->expdate)));
                } else {
                    $pexpdate = date("Y-m-d", strtotime($month, strtotime(now()->format('Y-m-d'))));
                }

                Admin::where('id', $user_id)->update([
                    'ispaid' => 1,
                    'expdate' => $pexpdate,
                    'planStatus' => $customStatus,
                    'status' => 1,
                ]);

                Payment::create([
                    'u_id' => $user_id,
                    'pamount' => $plan->price,
                    'pdate' => now()->format('Y-m-d'),
                    'ptransactionid' => $ptransactionid,
                    'pstatus' => $plan->plan_name,
                    'packageid' => $plan->plan_id,
                    'pprice' => $plan->price,
                    'pmonth' => $plan->month,
                    'ref_status' => 0,
                    'refund_id' => null,
                    'refundDate' => null,
                    'userRole' => null,
                    'created_at' => now(),
                ]);

                $data = [
                    'status' => 'success',
                    'message' => 'User Transaction Successfully!....',
                    'data' => ''
                ];

                $user = Admin::find($user_id);
                if ($user) {
                    // Send SMS notification
                    // send_sms_other($user->mobile, "buy", $plan->month);
                    
                    // Remove payment link if available
                    DB::table('payment_link')->where('mobile', $user->mobile)->delete();
                }
            }
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Some field are required!...',
                'data' => []
            ];
        }

        DB::table('test')->insert([
            'testName' => 'result',
            'testData' => json_encode($data),
            'created_at' => now(),
        ]);

        return response()->json($data);
    }

    private function failedRepaymentRemove($mobile)
    {
        if ($mobile) {
            WebhookFailed::where('w_mobile', $mobile)->delete();
        }
        return true;
    }
}