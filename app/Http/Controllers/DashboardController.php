<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Tamplet;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
       
        if(\Auth::user()->role == 'Admin'){
            $totalUser              = DB::table('admin')->count();
            $totalDeactiveUser      = DB::table('admin')->where('status', 0)->count();
            $totalTodayNewUser      = DB::table('admin') ->whereDate('created_at', '<=', date('Y-m-d'))->whereDate('created_at', '>=', date('Y-m-d'))->count();
            $totalUserPost          = DB::table('makepost')->count();
            $totalUserPostToday     = DB::table('makepost')->whereDate('created_at', '=', date('Y-m-d'))->count();
            $videoanalytics         = DB::table('videoanalytics')->sum('count');
            $videoanalyticsToday    = DB::table('videoanalytics')->whereDate('date', date('Y-m-d'))->sum('count');
            $totalTamplate          = DB::table('tamplet')->count();
            $totalCategory          = DB::table('categories')->count();
            $totalSubCategory       = DB::table('sub_categories')->count();
            $totalPremiumUser       = DB::table('admin')->where('planStatus', 2)->count();
            $totalActivePremiumUser = DB::table('admin')->where('ispaid', 1)->where('planStatus', 2)->count();
            $totalTodayPremiumUser  = DB::table('admin as a')->leftJoin('payments as p', 'a.id', '=', 'p.user_id')->where('a.ispaid', 1)
                                    ->whereDate('p.date', Carbon::today())
                                    ->where('p.packageid', '!=', 1)
                                    ->count('a.id');
            $totalExpiredTodayUser = DB::table('admin')
                                    ->whereDate('expdate', date('Y-m-d'))
                                    ->where('ispaid', 1)
                                    ->where('planStatus', 2)
                                    ->count();
            $totalExpiredUser     = DB::table('admin')->whereDate('expdate', '<' , date('Y-m-d'))->where('ispaid', 0)->where('planStatus', 2)->count();
            $totalTrialUser       = DB::table('admin')->where('planStatus', 1)->count();
            $totalActiveTrialUser = DB::table('admin')->where('ispaid', 1)->where('planStatus', 1)->count();
            $totalTodayTrialUser  = DB::table('admin as a')->leftJoin('payments as p', 'a.id', '=', 'p.user_id')->where('a.ispaid', 1)
                                    ->whereDate('p.date', Carbon::today())->where('a.planStatus', 1)
                                    ->where('p.packageid', '!=', 1)
                                    ->count('a.id'); 
            $totalExpiredTodayTrialUser = DB::table('admin')->whereDate('expdate', date('Y-m-d'))->where('ispaid', 1)->where('planStatus', 1)->count();
            $totalExpiredTrialUser      = DB::table('admin')->where('ispaid', 0)->where('planStatus', 1)->count();
            $versionwiseUserCount       = DB::table('notification')->select('app_version', DB::raw('COUNT(id) as totalUser'))->groupBy('app_version')->orderBy('app_version', 'DESC')->get();
            /* Delete Video Analytics older than 60 days */
            $deleteBeforeDate = Carbon::today()->subDays(60)->toDateString();
            DB::table('videoanalytics')
                ->where('date', '<', $deleteBeforeDate)
                ->delete();
            /* Get Last 7 Video Analytics Records */
            $videoanalyticsLast7Days = DB::table('videoanalytics')->orderBy('date', 'DESC')->limit(7)->get()
            ->map(function ($row) {
                $row->date = (!empty($row->date) && $row->date !== '0000-00-00')
                    ? Carbon::parse($row->date)->format('d/m/Y')
                    : '';
                return $row;
            });
            $croneReportFetch = DB::table('crone_report')->orderBy('id','DESC')->limit(10) ->get();
            $todayPaidSubscriptionUser = DB::table('payments as p')->leftJoin('admin as u', 'p.user_id', '=', 'u.id')->where('p.month', '!=', 0)->orderBy('p.id', 'DESC')->limit(10)
                                        ->select('p.id','p.date','u.mobile','p.transactionid','p.price','p.month' )->get();
            $todayTrialSubscriptionUser = DB::table('payments as p')->leftJoin('admin as u', 'p.user_id', '=', 'u.id')->where('p.month', '=', 0)->orderBy('p.id', 'DESC')->limit(10)
                                        ->select('p.id','p.date','u.mobile','p.transactionid','p.price','p.month' )->get();
            $posts = DB::table('makepost')
                    ->selectRaw('DATE(created_at) as report_date, COUNT(id) as totalPost')
                    ->groupBy('report_date')
                    ->orderBy('report_date', 'DESC')
                    ->limit(30)
                    ->get();

            $customReport = [];

            foreach ($posts as $row) {
                $date = $row->report_date;

                $totalRegister = DB::table('admin')
                    ->whereDate('created_at', $date)
                    ->count();

                $totalPaid = DB::table('payments')
                    ->whereDate('date', $date)
                    ->where('packageid', '!=', 1)
                    ->where('price', '!=', 0)
                    ->count();

                $totalFail = DB::table('webhook_failed')
                    ->whereDate('date', $date)
                    ->distinct('mobile')
                    ->count('mobile');

                $totalTrial = DB::table('payments')
                    ->whereDate('date', $date)
                    ->where('packageid', 1)
                    ->where('price', 0)
                    ->distinct('user_id')
                    ->count('user_id');

                $totalRevenue = DB::table('payments')
                    ->whereDate('date', $date)
                    ->sum('price');

                $totalVideo = DB::table('videoanalytics')
                    ->whereDate('date', $date)
                    ->sum('count');

                $customReport[] = [
                    'date'          => Carbon::parse($date)->format('d-m-Y'),
                    'totalPost'     => $row->totalPost,
                    'totalVideo'    => $totalVideo ?? 0,
                    'totalRegister' => $totalRegister,
                    'totalPaid'     => $totalPaid,
                    'totalFail'     => $totalFail,
                    'totalTrial'    => $totalTrial,
                    'totalRevenue'  => $totalRevenue ?? 0,
                ];
            }

            $smsReport = DB::table('sms_log')
                        ->selectRaw("
                            date,
                            COUNT(IF(type = 'forgotpass', 1, NULL)) AS total_forgot_sms,
                            COUNT(IF(type = 'signup', 1, NULL)) AS total_signup_sms,
                            COUNT(DISTINCT IF(type = 'signup', mobile, NULL)) AS total_unique_signup_sms
                        ")
                        ->groupBy('date')->orderBy('id', 'DESC')->limit(7)->get();
                       
            return view('admin.dashboard',compact('totalUser','totalDeactiveUser','totalTodayNewUser','totalUserPost','totalUserPostToday','videoanalytics','videoanalyticsToday','totalTamplate','totalCategory','totalSubCategory','totalPremiumUser','totalActivePremiumUser','totalTodayPremiumUser','totalExpiredTodayUser','totalExpiredUser','totalTrialUser','totalActiveTrialUser','totalTodayTrialUser','totalExpiredTodayTrialUser','totalExpiredTrialUser','versionwiseUserCount','videoanalyticsLast7Days','croneReportFetch','todayPaidSubscriptionUser','todayTrialSubscriptionUser','customReport','smsReport'));
        }elseif(\Auth::user()->role == 'Sub Admin')
        {
            $versionwiseUserCount       = DB::table('notification')->select('app_version', DB::raw('COUNT(id) as totalUser'))->groupBy('app_version')->orderBy('app_version', 'DESC')->get();
            /* Delete Video Analytics older than 60 days */
            $deleteBeforeDate = Carbon::today()->subDays(60)->toDateString();
            DB::table('videoanalytics')
                ->where('date', '<', $deleteBeforeDate)
                ->delete();
            /* Get Last 7 Video Analytics Records */
            $videoanalyticsLast7Days = DB::table('videoanalytics')->orderBy('date', 'DESC')->limit(7)->get()
            ->map(function ($row) {
                $row->date = (!empty($row->date) && $row->date !== '0000-00-00')
                    ? Carbon::parse($row->date)->format('d/m/Y')
                    : '';
                return $row;
            });
            $croneReportFetch = DB::table('crone_report')->orderBy('id','DESC')->limit(10) ->get();
            
            return view('admin.sub-admin-dashboard',compact('versionwiseUserCount','videoanalyticsLast7Days','croneReportFetch'));
        }
    }

    public function todayFestivalPosts(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('tamplet as t')
                ->leftJoin('sub_categories as s', 't.sub_category_id', '=', 's.id')
                ->whereDate('s.event_date', date('Y-m-d'))
                ->select(
                    't.id as tid',
                    't.planImgName',
                    't.path',
                    's.id as mid',
                    's.event_date',
                    's.mtitle',
                    's.mslug'
                );

                return DataTables::of($query)
                    ->addIndexColumn()
                    // Date
                    ->editColumn('event_date', function ($row) {
                        return Carbon::parse($row->event_date)->format('d-m-Y');
                    })
                    // Category ID
                    ->addColumn('category_id', function ($row) {
                        return $row->mid;
                    })
                    // Image
                    ->addColumn('image', function ($row) {
                        $imagePath = $row->path
                            ? asset('storage/' . ltrim($row->path, '/'))
                            : asset('assets/images/default.jpg');
                        return '
                        <a class="image-popup-no-margins" href="' . $imagePath . '" target="_blank">
                            <img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">
                        </a>
                        ';
                })

                ->rawColumns(['event_date','category_id','image',])
                ->make(true);
        }

        return view('admin.dashboard');
    }
    
    public function upcomingFestivalPosts(Request $request)
    {
        if ($request->ajax()) {
            $dt = Carbon::now()->addDay()->format('Y-m-d');
            $query = DB::table('tamplet as t')
                ->leftJoin('sub_categories as s', 't.sub_category_id', '=', 's.id')
                ->whereDate('s.event_date', $dt)
                ->select(
                    't.id as tid',
                    't.planImgName',
                    't.path',
                    's.id as mid',
                    's.event_date',
                    's.mtitle',
                    's.mslug'
                );
                return DataTables::of($query)
                    ->addIndexColumn()

                    // Date
                    ->editColumn('event_date', function ($row) {
                        return Carbon::parse($row->event_date)->format('d-m-Y');
                    })

                    // Category ID
                    ->addColumn('category_id', function ($row) {
                        return $row->mid;
                    })

                    // Image
                    ->addColumn('image', function ($row) {
                        $imagePath = $row->path
                            ? asset('storage/' . ltrim($row->path, '/'))
                            : asset('assets/images/default.jpg');
                        return '
                        <a class="image-popup-no-margins" href="' . $imagePath . '" target="_blank">
                            <img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">
                        </a>
                        ';
                    })

                ->rawColumns(['event_date','category_id','image',])
                ->make(true);
        }
        return view('admin.dashboard');
    }

    public function categoryWiseTemplateCount(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('sub_categories as s')
                ->leftJoin('tamplet as t', 't.sub_category_id', '=', 's.id')
                ->where('s.status', 1)
                ->select(
                    's.mtitle',
                    's.event_date',
                    DB::raw('COUNT(t.id) as totalTemplate')
                )
                ->groupBy(
                    's.id',
                    's.mtitle',
                    's.event_date',
                );
                

            return DataTables::of($query)
                ->addIndexColumn()
                // Event Date
                ->editColumn('event_date', function ($row) {
                    return ($row->event_date && $row->event_date !== '0000-00-00')
                        ? Carbon::parse($row->event_date)->format('d/m/Y')
                        : 'No';
                })
                // Total Templates
                ->addColumn('totalTemplate', function ($row) {
                    return $row->totalTemplate;
                })
                ->rawColumns(['event_date', 'totalTemplate'])
                ->make(true);
        }

        return view('admin.dashboard');
    }

    public function categoryWisePhotoCount(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('photo_status as ps')
                ->leftJoin('photos as p', 'p.photo_status_id', '=', 'ps.id')
                ->select(
                    'ps.id',
                    'ps.title',
                    DB::raw('COUNT(p.id) as totalPhoto')
                )
                ->groupBy('ps.id', 'ps.title');

                return DataTables::of($query)
                ->addIndexColumn()
                // Category Title
                ->editColumn('title', function ($row) {
                    return $row->title ?? '-';
                })
                // Total Photos
                ->editColumn('totalPhoto', function ($row) {
                    return $row->totalPhoto;
                })
               ->make(true);
        }

        return view('admin.dashboard');
    }

    // public function paidWiseUserCount(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $query = DB::table('payments as p')
    //             ->leftJoin('admin as u', 'p.user_id', '=', 'u.id')
    //             ->where('p.month', '!=', 0)
    //             ->orderBy('p.id', 'DESC')
    //             ->limit(10)
    //             ->select(
    //                 'p.id',
    //                 'p.date',
    //                 'u.mobile',
    //                 'p.transactionid',
    //                 'p.price',
    //                 'p.month'
    //             );

    //         return DataTables::of($query)
    //             ->addIndexColumn()

    //             // Date format
    //             ->editColumn('date', function ($row) {
    //                 return Carbon::parse($row->date)->format('d-m-Y');
    //             })

    //             // Price format
    //             ->editColumn('price', function ($row) {
    //                 return '₹ ' . number_format($row->price, 2);
    //             })

    //             ->rawColumns(['price'])
    //             ->make(true);
    //     }

    //     return view('admin.dashboard');
    // }

    public function settings()
    {
        $settings = DB::table('setting')->pluck('value', 'option_name')->toArray();

        return view('admin.settings', compact('settings'));
    }


    public function updateSettings(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        foreach ($data as $key => $value) {
            DB::table('setting')->where('option_name', $key)->update(['value' => $value]);
        }
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function imagezipDownload(Request $request)
    {
        $categories = SubCategory::get();
        return view('admin.imagezipDownload',compact('categories'));
    }

    public function imagezipDownloadStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_category_id'  =>  ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $cat_id = $request->sub_category_id;
        $tamplet = $this->getTampList($cat_id);
        $zip = new ZipArchive();
        $zipFileName = 'images.zip';
        $zip->open($zipFileName, ZipArchive::CREATE);
        foreach ($tamplet as $tamp) {
            $imagePath = storage_path('app/public/' . $tamp->path);
            if (File::exists($imagePath)) {
                $zip->addFile($imagePath, basename($imagePath));
            }
        }
        $zip->close();
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
    
    private function getTampList($cat_id)
    {
        if($cat_id!="all" && $cat_id!=""){
           $tamplet = Tamplet::where('sub_category_id', $cat_id)->get();
        }
        return $tamplet;
    }
}
