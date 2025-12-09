<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Complain::with('user');
            
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $query->orderBy('id', 'DESC');
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('business_name', function ($row) {
                    return $row->user ? $row->user->business_name : '-';
                })
                ->filterColumn('business_name', function($query, $keyword) {
                    $query->whereHas('user', function($q) use ($keyword) {
                        $q->where('business_name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('message', function ($row) {
                    return '<div style="max-width: 400px; white-space: normal; word-wrap: break-word;">' . 
                           (strlen($row->message) > 150 ? substr($row->message, 0, 150) . '...' : $row->message) . 
                           '</div>';
                })
                ->addColumn('reply', function ($row) {
                    if(!empty($row->reply))
                    {
                        return '<div style="max-width: 400px; white-space: normal; word-wrap: break-word;">' . 
                           (strlen($row->reply) > 150 ? substr($row->reply, 0, 150) . '...' : $row->reply) . 
                           '</div>';
                    }
                    else
                    {
                        return '-';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '';
                })
                ->editColumn('status', function ($row) {
                    $statusLabels = [
                        '0' => "<span class='badge bg-warning'>Pending</span>",
                        '1' => "<span class='badge bg-info'>On Progress</span>",
                        '2' => "<span class='badge bg-secondary'>Hold</span>",
                        '3' => "<span class='badge bg-success'>Solved</span>",
                    ];
                    return $statusLabels[$row->status] ?? "<span class='badge bg-dark'>Unknown</span>";
                })
                ->filterColumn('status', function($query, $keyword) {
                    $statusMap = [
                        'pending' => '0',
                        'progress' => '1',
                        'hold' => '2',
                        'solved' => '3'
                    ];
                    $keyword = strtolower($keyword);
                    foreach ($statusMap as $text => $value) {
                        if (strpos($text, $keyword) !== false) {
                            $query->orWhere('status', $value);
                        }
                    }
                })
                ->addColumn('actions', function ($row) {
                    $buttons  = '';
                    $editUrl  = route('compain.reply', $row->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Reply" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    return $buttons;
                })
                ->rawColumns(['business_name','message','reply','created_at','status','actions'])
                ->make(true);
            }
        
        return view('complain.index');
    }

    public function reply(Request $request, $id)
    {
        $complain = Complain::find($id);
        if ($complain) {
            return view('complain.reply', compact('complain'));
        } else {
            return redirect()->route('compain.index')->with('error', __('Complain not found.'));
        }
    }
    
    public function replyStore(Request $request, $id)
    {
        $complain = Complain::find($id);
        if ($complain) {
            $validator = Validator::make($request->all(), [
                'reply' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $complain->reply = $request->reply;
            $complain->status = $request->status;
            $complain->save();
            return redirect()->route('complain.list')->with('success', __('Complain updated successfully.'));
        } else {
            return redirect()->route('complain.list')->with('error', __('Complain not found.'));
        }
    }
}
