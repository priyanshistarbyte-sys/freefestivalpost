<?php

namespace App\Http\Controllers;

use App\Models\ApplicationAdd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;


class ApplicationAddController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
              $query = DB::table('application_add as a')
                ->leftJoin(DB::raw('(SELECT app_id, COUNT(id) as totalUnite FROM ads_api GROUP BY app_id) as ap'), 'a.id', '=', 'ap.app_id')
                ->select('a.*', DB::raw('COALESCE(ap.totalUnite, 0) as totalUnite'))
                ->get();
                
              return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('mode', function ($applicationAdd) {
                    if ($applicationAdd->mode == 1) {
                       return '
                            <a href="javascript:void(0);">
                                <button type="button" 
                                    class="btn btn-sm btn-success"
                                    data-toggle="tooltip" title="Live">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                </button>
                            </a>
                        ';  
                    } else {
                        return 'Test';
                    }
                })
                ->addColumn('status', function ($applicationAdd) {
                    // status = 0 → Off
                    if ($applicationAdd->status == 0) {
                        return '
                            <a href="javascript:void(0);">
                                <button type="button" class="btn btn-sm btn-light" 
                                    data-toggle="tooltip" title="Off">
                                    <i class="fa fa-power-off"></i>
                                </button>
                            </a>
                        ';
                    }

                    // status = 1 → Google
                    else if ($applicationAdd->status == 1) {
                        return '
                            <a href="javascript:void(0);">
                                <button type="button" class="btn btn-sm btn-danger" 
                                    data-toggle="tooltip" title="Google">
                                    <i class="fa fa-google"></i>
                                </button>
                            </a>
                        ';
                    }

                    // status = anything else → Facebook
                    else {
                        return '
                            <a href="javascript:void(0);">
                                <button type="button" class="btn btn-sm btn-primary" 
                                    data-toggle="tooltip" title="Facebook">
                                    <i class="fa fa-facebook"></i>
                                </button>
                            </a>
                        ';
                    }

                })
                ->addColumn('actions', function ($applicationAdd) {
                    $buttons  = '';
                    $editUrl  = route('application.show', $applicationAdd->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit View" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-eye me-2"></i>
                            </a>
                            ';
                    $deleteUrl  = route('application.destroy', $applicationAdd->id);
                    $buttons   .= '
                            <button type = "button" class = "btn btn-sm delete-btn"
                                    data-url = "' . $deleteUrl . '"
                                    title = "Delete">
                            <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
            })
            ->rawColumns(['mode','status','actions'])
            ->make(true);
        }
       return view('application.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('application.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationAdd $applicationAdd)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplicationAdd $applicationAdd)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApplicationAdd $applicationAdd)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationAdd $applicationAdd)
    {
        //
    }
}
