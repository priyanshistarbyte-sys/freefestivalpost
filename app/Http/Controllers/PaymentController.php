<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\WebhookFailed;
use Illuminate\Http\Request;
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
        return view('payment.failed');
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
                    'u.business_name',
                    'u.mobile',
                    'u.ispaid',
                    'u.expdate',
                    'p.packageid',
                    'p.month',

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
                    'u.business_name',
                    'u.mobile',
                    'u.ispaid',
                    'u.expdate',
                    'p.packageid',
                    'p.month',

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

        /* Get admin user */
        $user = DB::table('admin')
            ->where('mobile', $mobile)
            ->where('role','User')
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User data not found',
                'data' => []
            ]);
        }

        /* Get latest payment */
        $payment = DB::table('payments')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $user->month  = $payment->month ?? 0;
        $user->status = $payment->status ?? '';

        return response()->json([
            'status' => 'success',
            'message' => 'User get successfully!',
            'data' => $user
        ]);
    }
}
