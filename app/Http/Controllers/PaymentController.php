<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\WebhookFailed;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class PaymentController extends Controller
{
   
    public function failedList(Request $request)
    {
        if ($request->ajax()) {
            $query = WebhookFailed::orderBy('id', 'DESC');

             if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

             
             return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('date', function ($row) {
                    return $row->date ? \Carbon\Carbon::parse($row->date)->format('d/m/Y') : '';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d-m-Y H:i') : '';
                })
                ->rawColumns(['date','created_at','updated_at'])
                ->make(true);
        }
        $razorPayOrderList = getOrderByRazorPayAllList();
        return view('payment.failed', [
            'ordersList' => $razorPayOrderList
        ]);
    }

     public function  paidsubscriptionList(Request $request)
     {
         $plans = SubscriptionPlan::where('status', 1)->get();
         if ($request->ajax()) {
         }
         return view('payment.paid-subscription',compact('plans'));
     }

     public function othernumberpayment(Request $request)
     {
        if ($request->ajax()) {
            $query = DB::table('webhook_authorized')->orderBy('id', 'DESC');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $buttons  = '';
                    $deleteUrl = route('payment.othernumberpayment.destroy', $row->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['image','lablebg' ,'actions'])
                ->make(true);
        }
        return view('payment.paid-subscription');
     }

    public function deleteOthernumberpayment($id)
    {
        $other_payment = DB::table('webhook_authorized')->where('id', $id)->first();
        $other_payment->delete();
        return redirect()->route('payment.paid-subscription')->with('success', 'Payment deleted successfully.');
    }

    public function paymentActive(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('payments as p')
                ->leftJoin('admin as u', 'p.user_id', '=', 'u.id')
                ->where('p.price', '!=', 0)
                ->where('u.ispaid', 1)
                ->select(
                    'p.id',
                    'p.user_id',
                    'p.transactionid',
                    'p.price',
                    'p.status',
                    'p.created_at',
                    'p.packageid',
                    'p.month',
                    'u.business_name as business_name',
                    'u.mobile as mobile',
                    'u.ispaid as ispaid',
                    'u.expdate as expdate'
                )
                ->orderBy('p.id', 'desc');

               return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn('mobile', function ($row) {
                    return '<a target="_blank" style="text-decoration: underline; color:#0088cc"
                            href="https://web.whatsapp.com/send?phone=91' . $row->mobile . '">'
                            . $row->mobile .
                        '</a>';
                })

                ->editColumn('ispaid', function ($row) {
                    return $row->ispaid
                        ? '<span class="badge bg-success">Paid</span>'
                        : '<span class="badge bg-danger">Free</span>';
                })

                ->editColumn('business_name', function ($row) {
                    return '<strong>' . $row->user_id . '</strong> - ' . e($row->business_name);
                })

                ->addColumn('actions', function ($row) {
                    $deleteUrl = route('payment.othernumberpayment.destroy', $row->id);
                    return '
                        <button type="button" class="btn btn-sm delete-btn"
                            data-url="' . $deleteUrl . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['mobile', 'ispaid', 'business_name', 'actions'])
                ->make(true);
        }

        return view('payment.paid-subscription');
    }

    public function paymentDeactive(Request $request)
    {
         if ($request->ajax()) {
            $query = DB::table('payments as p')
                ->leftJoin('admin as u', 'p.user_id', '=', 'u.id')
                ->where('p.price', '!=', 0)
                ->where('u.ispaid', 0)
                ->select(
                    'p.id',
                    'p.user_id',
                    'p.transactionid',
                    'p.price',
                    'p.status',
                    'p.created_at',
                    'p.packageid',
                    'p.month',
                    'u.business_name as business_name',
                    'u.mobile as mobile',
                    'u.ispaid as ispaid',
                    'u.expdate as expdate'
                )
                ->orderBy('p.id', 'desc');

               return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn('mobile', function ($row) {
                    return '<a target="_blank" style="text-decoration: underline; color:#0088cc"
                            href="https://web.whatsapp.com/send?phone=91' . $row->mobile . '">'
                            . $row->mobile .
                        '</a>';
                })

                ->editColumn('ispaid', function ($row) {
                    return $row->ispaid
                        ? '<span class="badge bg-success">Paid</span>'
                        : '<span class="badge bg-danger">Free</span>';
                })

                ->editColumn('business_name', function ($row) {
                    return '<strong>' . $row->user_id . '</strong> - ' . e($row->business_name);
                })

                ->addColumn('actions', function ($row) {
                    $deleteUrl = route('payment.othernumberpayment.destroy', $row->id);
                    return '
                        <button type="button" class="btn btn-sm delete-btn"
                            data-url="' . $deleteUrl . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['mobile', 'ispaid', 'business_name', 'actions'])
                ->make(true);
         }
         return view('payment.paid-subscription');
    }

    public function getUserData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request',
                'data' => []
            ]);
        }

        $mobile = str_replace(' ', '', $request->mobile);

        if (!$mobile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobile number is required',
                'data' => []
            ]);
        }

        /* 🔹 Get admin user */
        $user = DB::table('admin')
            ->where('mobile', $mobile)
            ->where('role', 'User')
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User data not found',
                'data' => []
            ]);
        }

        /* 🔹 Get latest payment */
        $payment = DB::table('payments')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'status' => 'success',
            'message' => 'User get successfully!',
            'data' => [
                /* admin table */
                'id'            => $user->id,
                'mobile'        => $user->mobile,
                'business_name' => $user->business_name,
                'b_email'       => $user->b_email,
                'admin_status'  => $user->status,        // ✅ admin.status
                'expdate'       => $user->expdate,
                'last_login'    => $user->last_login,

                /* payments table */
                'payment_status' => $payment->status ?? 'Free', // ✅ payments.status
                'month'          => $payment->month ?? 0,
            ]
        ]);
    }

    public function paymentManually(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'mobile'         => ['required'],
            'transationid'   => ['required'],
            'select_plan'    => ['required'],
            'buyDate'        => ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $user_id       = $request->userid;
        $packageid     = $request->select_plan;
        $transactionid = $request->transationid;
        $freeDays      = $request->freeDays ?? 0;
        $buyDate       = $request->buyDate;
       
        if(!empty($user_id))
        {
            if(!empty($packageid) && !empty($transactionid))
            {
                $ref_status = 0;
                $plan  = SubscriptionPlan::where('id', $packageid)->first();
                if (!$plan) {
                    return redirect()->back()->with('error', 'Plan Not Found!');
                }
                $payment = Payment::where('user_id',$user_id)->get();
                if (!$payment) {
                    return redirect()->back()->with('error', 'Payment Not Found!');
                }

                $user = Admin::where('id', $user_id)->first();
                if (!$user) {
                     return redirect()->back()->with('error', 'User not found!');
                }
              
                 /* Calculate duration */
                 if ($plan->is_free == 0) {
                    // Trial/Free
                    $days = ($freeDays > 0) ? $freeDays : 7;
                    $expiry = Carbon::parse($buyDate)->addDays($days);
                    $transactionId = null;
                    $planStatus = 1; // Trial
                } else {
                    // Paid
                    if ($plan->duration_type == 'year') {
                        $expiry = Carbon::parse($buyDate)->addYears((int)$plan->duration);
                    } elseif ($plan->duration_type == 'month') {
                        $expiry = Carbon::parse($buyDate)->addMonths((int)$plan->duration);
                    } else {
                        $expiry = Carbon::parse($buyDate)->addDays((int)$plan->duration);
                    }
                    $transactionId = $transactionid;
                    $planStatus = 2; // Paid
                }
                
                 /* If already active → extend date */
                if (
                    $user->planStatus == 2 &&
                    $user->ispaid == 1 &&
                    $user->expdate && Carbon::parse($user->expdate)->gt(now())
                ) {
                    if ($plan->duration_type == 'year') {
                        $expiry = Carbon::parse($user->expdate)->addYears((int)$plan->duration);
                    } elseif ($plan->duration_type == 'month') {
                        $expiry = Carbon::parse($user->expdate)->addMonths((int)$plan->duration);
                    } else {
                        $expiry = Carbon::parse($buyDate)->addDays((int)$plan->duration);
                    }
                }
                

                 /* Update admin table */
                DB::table('admin')
                    ->where('id', $user_id)
                    ->update([
                        'ispaid'     => 1,
                        'expdate'    => $expiry->format('Y-m-d'),
                        'planStatus' => $planStatus,
                        'status'     => 1,
                    ]);

                /* Remove payment link if paid */
                if ($plan->is_free == 1) {
                    DB::table('payment_link')->where('mobile', $user->mobile)->delete();
                }

                 /* Insert payment record */
                DB::table('payments')->insert([
                    'user_id'       => $user_id,
                    'amount'        => $plan->discount_price ?? $plan->price,
                    'date'          => Carbon::parse($buyDate)->format('Y-m-d'),
                    'transactionid' => $transactionId,
                    'status'        => $plan->plan_name,
                    'packageid'     => $packageid,
                    'price'         => $plan->discount_price ?? $plan->price,
                    'month'         => $plan->duration,
                    'ref_status'    => 0,
                    'created_at'    => now(),
                ]);

                 /* Update counter */
               DB::table('counter')->where('id', 1)
                ->update([
                    'paidUser' => DB::raw('paidUser + 1')
                ]);
                /* remove failed mpayment record */
                $this->failedRepaymentRemove($user->mobile);
                return redirect()->back()->with('success', 'User Transaction Successfully!');
            }   
            else{
                return redirect()->back()->with('error', 'Some field are required!...');
            }
        }else{
            return redirect()->back()->with('error', 'This User is already paid');
        }

    }

    private function failedRepaymentRemove($mobile)
    {
        if (!empty($mobile)) {
            DB::table('webhook_failed')->where('mobile', $mobile)->delete();
		}
    }
    
    public function trialsubscriptionList(Request $request)
    {
       if ($request->ajax()) {

            $query = DB::table('payments as p')
                ->leftJoin('admin as u', 'p.user_id', '=', 'u.id')
                ->where('p.price', '=', 0)
                ->where('u.ispaid', '=', 0)
                ->select(
                    'p.id',
                    'p.user_id',
                    'p.transactionid',
                    'p.price',
                    'p.status',
                    'p.created_at',
                    'u.business_name as business_name',
                    'u.mobile as mobile',
                    'u.ispaid as ispaid',
                    'u.expdate as expdate',
                    'p.packageid',
                    'p.month'
                )
                ->orderBy('p.id', 'desc');

               return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn('mobile', function ($row) {
                    return '<a target="_blank" style="text-decoration: underline; color:#0088cc"
                            href="https://web.whatsapp.com/send?phone=91' . $row->mobile . '">'
                            . $row->mobile .
                        '</a>';
                })

                ->editColumn('ispaid', function ($row) {
                    return $row->ispaid
                        ? '<span class="badge bg-success">Paid</span>'
                        : '<span class="badge bg-danger">Free</span>';
                })

                ->editColumn('business_name', function ($row) {
                    return '<strong>' . $row->user_id . '</strong> - ' . e($row->business_name);
                })

                ->addColumn('actions', function ($row) {
                    $deleteUrl = route('payment.othernumberpayment.destroy', $row->id);
                    return '
                        <button type="button" class="btn btn-sm delete-btn"
                            data-url="' . $deleteUrl . '" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['mobile', 'ispaid', 'business_name', 'actions'])
                ->make(true);
        }

        return view('payment.trial-subscription');
    }
   
}
