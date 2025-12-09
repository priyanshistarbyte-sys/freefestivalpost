<?php

namespace App\Http\Controllers;

use App\Models\WebhookFailed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
   
    public function failedList(Request $request)
    {
        if ($request->ajax()) {
            $query = WebhookFailed::orderBy('web_fail_id', 'DESC');

             if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

             
             return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('w_date', function ($row) {
                    return $row->w_date ? $row->w_date->format('d/m/Y') : '';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d-m-Y H:i') : '';
                })
                ->rawColumns(['w_date','created_at','updated_at'])
                ->make(true);
        }
        return view('payment.failed');
    }
}
