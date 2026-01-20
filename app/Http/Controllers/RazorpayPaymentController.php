<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebhookFailed;
use App\Models\WebhookAuthorized;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class RazorpayPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role != 0) {
                return redirect()->route('dashboard');
            }
            return $next($request);
        });
    }

    public function failed(Request $request)
    {
        
        $razorPayOrderList = getOrderByRazorPayAllList();
        return view('payment.failed', [
            'ordersList' => $razorPayOrderList
        ]);
    }

    public function success()
    {
        return view('admin.razorpayPayment.successList');
    }

    public function getFailedList(Request $request)
    {
        if ($request->ajax()) {
            $query = WebhookFailed::query();
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date("d/m/Y", strtotime($row->date));
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? date("d/m/Y h:i:s", strtotime($row->created_at)) : "";
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? date("d/m/Y h:i:s", strtotime($row->updated_at)) : "";
                })
                ->rawColumns(['date', 'created_at', 'updated_at'])
                ->make(true);
        }
    }

    public function getSuccessList(Request $request)
    {
        if ($request->ajax()) {
            $query = WebhookAuthorized::query();
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('whatsapp_link', function ($row) {
                    return '<a target="_blank" href="https://api.whatsapp.com/send/?phone=%2B91'.$row->mobile.'&text=&app_absent=0">'.$row->mobile.'</a>';
                })
                ->rawColumns(['whatsapp_link'])
                ->make(true);
        }
    }
}