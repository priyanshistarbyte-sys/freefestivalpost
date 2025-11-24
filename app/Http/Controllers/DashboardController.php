<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        // $totalUser = DB::table('admin')->count();
        // $totalDeactiveUser = DB::table('admin')->where('status', 0)->count();       
        // $totalTodayNewUser = DB::table('admin') ->whereDate('created_date', '<=', date('Y-m-d'))->whereDate('created_date', '>=', date('Y-m-d'))->count();
        // $totalUserPost = DB::table('makepost')->count('post_id');
        // $totalUserPostToday = DB::table('makepost')->whereDate('created_date', '=', date('Y-m-d'))->count('post_id');
        // $videoanalytics =  DB::table('videoanalytics')->sum('ca_count');
        // $videoanalyticsToday = DB::table('videoanalytics')->whereDate('va_date', date('Y-m-d'))->sum('ca_count');
        // $totalTamplate = DB::table('template')->count('tid');
        // $totalPositione = DB::table('position')->count('pid');
        // $totalCategory = DB::table('main_category')->count('mid');
       
        return view('admin.dashboard');
    }
}
