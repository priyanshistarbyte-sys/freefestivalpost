<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\SubscriptionPlan;
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
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{

    public function home()
    {
        $subscriptionPlans = SubscriptionPlan::with('descriptionsItem')->where('status', 1)->get();
        return view('welcome',compact('subscriptionPlans'));
    }
    
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

    public function upcomingFestivals(Request $request)
    {
         if ($request->ajax()) {

            $today = Carbon::today();
            $toDate = Carbon::today()->addDays(100);

            $query = DB::table('sub_categories as c')
                ->whereBetween('c.event_date', [$today, $toDate])
                ->where('c.status', 1)
                ->orderBy('c.event_date')
                ->limit(30);

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn('event_date', function ($row) {
                    return $row->event_date
                        ? Carbon::parse($row->event_date)->format('d, M Y')
                        : '';
                })

                ->addColumn('image', function ($row) {
                    $img = $row->image ? asset('storage/' . ltrim($row->image, '/')) : asset('assets/images/default.jpg');
                    return '<a target="_blank" href="'.$img.'">
                                <img src="'.$img.'" width="25%">
                            </a>';
                })

                ->addColumn('total_auto_tamp', function ($row) {
                    return DB::table('tamplet')
                        ->where('sub_category_id', $row->id)
                        ->count();
                })

                ->addColumn('totalPaidTamp', function ($row) {
                    return DB::table('tamplet')
                        ->where('sub_category_id', $row->id)
                        ->where('free_paid', 1)
                        ->count();
                })

                ->addColumn('total_video_tamp', function ($row) {
                    return DB::table('videogif')
                        ->where('sub_category_id', $row->id)
                        ->count();
                })

                ->addColumn('totalPaidvVideo', function ($row) {
                    return DB::table('videogif')
                        ->where('sub_category_id', $row->id)
                        ->where('free_paid', 1)
                        ->count();
                })

                ->addColumn('totalPlanPost', function ($row) {
                    $templates = DB::table('tamplet')
                        ->where('sub_category_id', $row->id)
                        ->pluck('path');

                    $count = 0;
                    foreach ($templates as $path) {
                        if ($path && Storage::disk('public')->exists($path)) {
                            $count++;
                        }
                    }
                    return $count;
                })

                ->addColumn('plan_auto', fn ($row) =>
                    $row->plan_auto == 1 ? 'Only Plan' : ''
                )

                ->addColumn('banner', function ($row) {
                    if (!$row->noti_banner) return '';
                    $url = $row->noti_banner ? asset('storage/' . ltrim($row->noti_banner, '/')) : asset('assets/images/default.jpg');
                    return '<img src="'.$url.'" width="25%">';
                })

                ->rawColumns([
                    'image',
                    'banner'
                ])
                ->make(true);
        }
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
        $data = $request->except('_token');

        if ($request->hasFile('sharingBanner')) {

            $path = $request
                ->file('sharingBanner')
                ->store('uploads/images/sharing_banner','public');

            // store path in DB
            DB::table('setting')
                ->where('option_name', 'sharingBanner')
                ->update(['value' => $path]);

            // remove file from loop data
            unset($data['sharingBanner']);
        }

        // ✅ Update remaining settings
        foreach ($data as $key => $value) {
            DB::table('setting')->where('option_name', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
            
            
    }

    public function createNextYearTemplates()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        
        $created = 0;
        $abc = Tamplet::select(['sub_category_id', 'font_color', 'lablebg', 'font_size', 'lable', 'font_type', 'language', 'event_date', 'event', 'free_paid', 'planImgName', 'path'])
            ->where('event', 1)
            ->whereYear('event_date', $currentYear)
            ->whereNotExists(function ($query) use ($nextYear) {
                $query->select(DB::raw(1))
                      ->from('tamplet as t2')
                      ->whereRaw('t2.sub_category_id = tamplet.sub_category_id')
                      ->whereRaw('t2.path = tamplet.path')
                      ->whereYear('t2.event_date', $nextYear);
            })
            ->chunk(1000, function ($templates) use (&$created) {
                $insertData = [];
                
                foreach ($templates as $template) {
                    $nextYearDate = date('Y-m-d', strtotime($template->event_date . ' +1 year'));
                    
                    $insertData[] = [
                        'sub_category_id' => $template->sub_category_id,
                        'font_color' => $template->font_color,
                        'lablebg' => $template->lablebg,
                        'font_size' => $template->font_size,
                        'lable' => $template->lable,
                        'font_type' => $template->font_type,
                        'language' => $template->language,
                        'event_date' => $nextYearDate,
                        'event' => $template->event,
                        'free_paid' => $template->free_paid,
                        'planImgName' => $template->planImgName,
                        'path' => $template->path,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Tamplet::insert($insertData);
                $created += count($insertData);
            });
        return response()->json([
            'success' => true,
            'message' => "Successfully created {$created} templates for next year."
        ]);
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
        $tamplets = $this->getTampList($cat_id);
        
        $zip = new ZipArchive();
        $zipFileName = storage_path('app/temp/images.zip');
        
        // Create temp directory if it doesn't exist
        if (!File::exists(dirname($zipFileName))) {
            File::makeDirectory(dirname($zipFileName), 0755, true);
        }
        
        $zip->open($zipFileName, ZipArchive::CREATE);
        $addedFiles = 0;
        
        foreach ($tamplets as $tamplet) {
            $imagePath = storage_path('app/public/' . $tamplet->path);
            if (File::exists($imagePath)) {
                $zip->addFile($imagePath, basename($imagePath));
                $addedFiles++;
            }
        }
        
        $zip->close();
        
        if ($addedFiles === 0) {
            // Delete empty zip file
            if (File::exists($zipFileName)) {
                File::delete($zipFileName);
            }
            return redirect()->back()->with('error', 'No images exist currently for the selected category.');
        }
        
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
    
    private function getTampList($cat_id)
    {
        if($cat_id!="all" && $cat_id!=""){
           $tamplets = Tamplet::where('sub_category_id', $cat_id)->get();
        } else {
           $tamplets = Tamplet::all();
        }
        return $tamplets;
    }

    
    public function dayWiseSubscription(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('payments')
                ->select(
                    'date',
                    DB::raw('COUNT(id) as total_paid'),
                    DB::raw('SUM(price) as total_amount'),
                    // PLAN WISE COUNTS (change IDs as per your plans)
                    DB::raw('SUM(CASE WHEN packageid = 1 THEN 1 ELSE 0 END) as business_1_year'),
                    DB::raw('SUM(CASE WHEN packageid = 2 THEN 1 ELSE 0 END) as one_month'),
                    DB::raw('SUM(CASE WHEN packageid = 3 THEN 1 ELSE 0 END) as business_6_month'),
                    DB::raw('SUM(CASE WHEN packageid = 4 THEN 1 ELSE 0 END) as twelve_month'),
                    DB::raw('SUM(CASE WHEN packageid = 5 THEN 1 ELSE 0 END) as advance_6_month'),
                    DB::raw('SUM(CASE WHEN packageid = 6 THEN 1 ELSE 0 END) as exclusive_plan'),
                    DB::raw('SUM(CASE WHEN packageid = 7 THEN 1 ELSE 0 END) as navratri_plan'),
                    DB::raw('SUM(CASE WHEN packageid = 8 THEN 1 ELSE 0 END) as premium_plan'),
                    // TRIAL
                    DB::raw('SUM(CASE WHEN month = 0 THEN 1 ELSE 0 END) as trial_total'),
                    // REFUND
                    DB::raw('SUM(CASE WHEN ref_status = 1 THEN 1 ELSE 0 END) as refund_count'),
                    DB::raw('SUM(CASE WHEN ref_status = 1 THEN price ELSE 0 END) as refund_amount')
                )
                ->groupBy('date')
                ->orderBy('date', 'DESC');
                if ($request->filled('start_date')) {
                    $query->whereDate('date', '>=', $request->start_date);
                }

                if ($request->filled('end_date')) {
                    $query->whereDate('date', '<=', $request->end_date);
                }

                return datatables()->of($query)->addIndexColumn()->editColumn('date', fn ($row) => Carbon::parse($row->date)->format('d-m-Y'))->make(true);
                
        }

        return view('admin.day-wise-report');
    }

    public function monthlySubscription(Request $request)
    {
        $rows = DB::table('payments')
            ->select(
                DB::raw("DATE_FORMAT(date, '%b %Y') as month"),

                // PLAN COUNTS
                DB::raw("SUM(CASE WHEN packageid = 2 THEN 1 ELSE 0 END) as monthly_total"),
                DB::raw("SUM(CASE WHEN packageid = 3 THEN 1 ELSE 0 END) as monthly_3_total"),
                DB::raw("SUM(CASE WHEN packageid = 5 THEN 1 ELSE 0 END) as monthly_6_total"),
                DB::raw("SUM(CASE WHEN packageid = 4 THEN 1 ELSE 0 END) as yearly_total"),
                DB::raw("SUM(CASE WHEN packageid = 6 THEN 1 ELSE 0 END) as total_exclusive"),

                // TRIAL (month = 0)
                DB::raw("SUM(CASE WHEN month = 0 THEN 1 ELSE 0 END) as trail_total"),

                // REFUND
                DB::raw("SUM(CASE WHEN status = 'Refund' THEN 1 ELSE 0 END) as total_refund"),
                DB::raw("SUM(CASE WHEN status = 'Refund' THEN price ELSE 0 END) as total_refund_amount"),

                // TOTAL PAID
                DB::raw("SUM(CASE WHEN status != 'Refund' THEN 1 ELSE 0 END) as total_paid"),
                DB::raw("SUM(CASE WHEN status != 'Refund' THEN price ELSE 0 END) as total_amount")
            )
            ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m')"))
            ->orderBy(DB::raw("MIN(date)"), 'DESC')
            ->get();

        return view('admin.month-subscription-report', compact('rows'));
    }

    public function repeatSubscription(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('payments as p')
                ->join('admin as a', 'p.user_id', '=', 'a.id')
                ->select(
                    'a.name',
                    'a.mobile',
                    'p.status as pstatus',
                    DB::raw('COUNT(p.id) as total'),
                    DB::raw('MIN(p.date) as firstDate'),
                    DB::raw('MAX(p.date) as lastDate')
                )
                ->groupBy('p.user_id', 'p.status', 'a.name', 'a.mobile')
                ->havingRaw('COUNT(p.id) > 1')
                ->orderByDesc('total');

            // Filter by plan/status (Day / Month / Year)
            if ($request->filled('status')) {
                $query->where('p.status', $request->status);
            }

            return datatables()->of($query)
                ->addIndexColumn()

                ->editColumn('firstDate', function ($row) {
                    return Carbon::parse($row->firstDate)->format('d-m-Y');
                })

                ->editColumn('lastDate', function ($row) {
                    return Carbon::parse($row->lastDate)->format('d-m-Y');
                })

                ->make(true);
        }

        return view('admin.repeat-subscription-report');
    }

    public function daywiseRegister(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('admin')
                ->select(
                    DB::raw("DATE(created_at) as date"),
                    DB::raw("COUNT(id) as total_user")
                )
                ->where('role','User')
                ->groupBy(DB::raw("DATE(created_at)"))
                ->orderBy(DB::raw("DATE(created_at)"), 'DESC');

            // Date filters
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            return datatables()->of($query)
                ->addIndexColumn()
                ->editColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d-m-Y');
                })
                ->make(true);
        }

        return view('admin.day-wise-register-report');
    }

    public function activePremiumUsers()
    {
        return redirect()->route('user.index', ['filter' => 'active_premium']);
    }
}
