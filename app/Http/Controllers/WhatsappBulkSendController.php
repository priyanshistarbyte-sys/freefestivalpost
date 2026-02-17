<?php

namespace App\Http\Controllers;

use App\Models\WhatsappLog;
use App\Models\WhatsappTemplate;
use App\Models\CampingList;
use App\Models\Admin;
use App\Models\WebhookFailed;
use App\Services\WhatsappApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Str;

class WhatsappBulkSendController extends Controller
{
    public function index()
    {
        $whatsapp_templates = WhatsappTemplate::orderBy('sort', 'asc')->get();
        $camping_lists = DB::table('camping_list')->where('title','!=','default')->get();
        
        // Add countHour result to each camping
        foreach($camping_lists as $camping) {
            $camping->countTime = $this->countHour($camping->created_at);
        }
        
        return view('whatsapp-bulk.index',compact('whatsapp_templates','camping_lists'));
    }

    private function countHour($date) {
        $start = new DateTime($date);
        $end = new DateTime();
        
        $interval = $start->diff($end);
        
        $totalHours = ($interval->days * 24) + $interval->h;
        $minutes = $interval->i;
        
        if($totalHours < 24){
            return [
                'time' => $totalHours . ":" . str_pad($minutes, 2, '0', STR_PAD_LEFT),
                'status' => true
            ];
        }
        
        return [
            'time' => 0,
            'status' => false
        ];
    }

    public function sendBulkCamping(Request $request)
    {
        $typeoffilter = $request->typeoffilter;
        $cam_title = $request->cam_title;
        $tamp_id = $request->temp_list;
        $numbers_menually = $request->numbers_menually;
        $custom_auto = "bulk";

        if($typeoffilter == "filter"){
            $filter_type = $request->filter_type;
            
            // Test device
            if($filter_type == 11){
                $filter_final_result = [];
                $sendMsgCount = $this->sendMsgInsertLog($filter_final_result, 1, $tamp_id, $custom_auto);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Test Camping Send Successfully...!--'.$sendMsgCount
                ]);
            }
            
            if($filter_type == "10"){
                $custom_auto = "auto";
            }
            
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            
            if(empty($start_date) || empty($end_date)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Start and End Date are required!'
                ]);
            }
            
            $filter_result = $this->getFilterUserDateForWhatsapp($filter_type, $start_date, $end_date);
            
            if(empty($filter_result)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found...!'
                ]);
            }
            
            $filter_final_result = [];
            foreach($filter_result as $key => $retarg_resu){
                $filter_final_result[$key][] = $retarg_resu["mobile"];
                $filter_final_result[$key][] = !empty($retarg_resu["business_name"]) ? $retarg_resu["business_name"] : "User";
                $filter_final_result[$key][] = "";
            }
            
            $cam_id = $this->insertCampingTitle($cam_title, $filter_type == 10 ? 2 : 0);
            
            if($cam_id === false){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Camping Title already exist..!'
                ]);
            }
            
            $sendMsgCount = $this->sendMsgInsertLog($filter_final_result, $cam_id, $tamp_id, $custom_auto);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Camping Send Successfully Added...!!--'.$sendMsgCount
            ]);
            
        } elseif($typeoffilter == "bulk"){
            
            if($request->hasFile('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                
                if(!in_array($extension, ['csv', 'xls', 'xlsx'])){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Only CSV files are allowed'
                    ]);
                }
                
                $csvFilePath = $file->store('uploads/whatsapp/csv', 'public');
                $fullPath = storage_path('app/public/' . $csvFilePath);
                
                if(file_exists($fullPath)){
                    $csvData = file_get_contents($fullPath);
                    $rows = str_getcsv($csvData, "\n");
                    $csvArray = [];
                    
                    foreach($rows as $row){
                        $csvArray[] = str_getcsv($row, ",");
                    }
                    
                    array_shift($csvArray); // Remove header
                    
                    if(count($csvArray) <= 0){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'CSV file is empty'
                        ]);
                    }
                    
                    $cam_id = $this->insertCampingTitle($cam_title, 0);
                    
                    if($cam_id === false){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Camping Title already exist..!'
                        ]);
                    }
                    
                    $sendMsgCount = $this->sendMsgInsertLog($csvArray, $cam_id, $tamp_id, $custom_auto);
                    
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Camping Send Successfully Added...!!--'.$sendMsgCount
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'CSV file not found.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File not upload...!'
                ]);
            }
            
        } elseif($typeoffilter == "manually"){
            
            $menually_array = explode(",", $numbers_menually);
            $custom_array = [];
            
            for($i = 0; $i < count($menually_array); $i++){
                $custom_array[$i][] = preg_replace('/\s+/', '', $menually_array[$i]);
                $custom_array[$i][] = "User";
                $custom_array[$i][] = "";
            }
            
            $cam_id = $this->insertCampingTitle($cam_title, 0);
            
            if($cam_id === false){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Camping Title already exist..!'
                ]);
            }
            
            $sendMsgCount = $this->sendMsgInsertLog($custom_array, $cam_id, $tamp_id, $custom_auto);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Camping Send Successfully Added...!!--'.$sendMsgCount
            ]);
            
        } elseif($typeoffilter == "retarget"){
            
            $previus_camping = explode("<->", $request->previus_camping);
            $previus_camping_id = $previus_camping[0];
            
            $retarget_result = $this->getCampingSubDetails($previus_camping_id);
            $retarget_final_result = [];
            
            foreach($retarget_result as $key => $retarg_resu){
                if(!($retarg_resu["ispaid"] == 1 && $retarg_resu["planStatus"] == 2)){
                    $retarget_final_result[$key][] = $retarg_resu["mobile"];
                    $retarget_final_result[$key][] = "User";
                    $retarget_final_result[$key][] = "";
                }
            }
            
            $cam_id = $this->insertCampingTitle("Retarget__".$previus_camping[1]."__".$cam_title, 1);
            
            if($cam_id === false){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Camping Title already exist..!'
                ]);
            }
            
            $sendMsgCount = $this->sendMsgInsertLog($retarget_final_result, $cam_id, $tamp_id, $custom_auto);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Camping Send Successfully Added...!!--'.$sendMsgCount
            ]);
        }
    }

    private function getFilterUserDateForWhatsapp($type, $start_date, $end_date)
    {
        $finalResult = [];
        
        if($type == 10){
            $finalResult1 = DB::table('whatsapp_logs as l')
                ->leftJoin('admin as a', 'l.mobile', '=', 'a.mobile')
                ->select('l.mobile', 'l.created_at', 'a.business_name')
                ->where('l.cam_id', 1)
                ->where('a.ispaid', 0)
                ->where('a.role', 1)
                ->whereDate('l.created_at', '>=', $start_date)
                ->whereDate('l.created_at', '<=', $end_date)
                ->get()
                ->toArray();
            
            $filterData = [];
            foreach($finalResult1 as $key => $datResu){
                $countTime = $this->countHour($datResu->created_at);
                if($countTime['status']){
                    $filterData[$key] = (array)$datResu;
                }
            }
            $finalResult = $filterData;
            
        } elseif($type == 8){
            $finalResult1 = DB::table('webhook_failed as w')
                ->leftJoin('admin as a', 'w.w_mobile', '=', 'a.mobile')
                ->select('w.w_date', 'w.w_mobile', 'w.created_at', 'a.business_name')
                ->where('a.ispaid', 0)
                ->where('a.role', 1)
                ->where('w.w_date', '>=', $start_date)
                ->where('w.w_date', '<=', $end_date)
                ->get()
                ->toArray();
            
            $filterData = [];
            foreach($finalResult1 as $key => $datResu){
                $filterData[$key] = (array)$datResu;
                $filterData[$key]['mobile'] = $datResu->w_mobile;
                unset($filterData[$key]['w_mobile']);
                unset($filterData[$key]['w_date']);
            }
            $finalResult = $filterData;
            
        } else {
            $query = Admin::select('business_name', 'mobile');
            
            // Free user
            if($type == 1){
                $query->where('ispaid', 0)
                      ->whereNull('planStatus')
                      ->whereNull('expdate');
            }
            // Plan active
            if($type == 2){
                $query->where('ispaid', 1)
                      ->where('planStatus', 2)
                      ->whereNotNull('expdate');
            }
            // Plan expired
            if($type == 3){
                $query->where('ispaid', 0)
                      ->where('planStatus', 2);
            }
            // Trial active
            if($type == 4){
                $query->where('ispaid', 1)
                      ->where('planStatus', 1)
                      ->whereNotNull('expdate');
            }
            // Trial expired
            if($type == 5){
                $query->where('ispaid', 0)
                      ->where('planStatus', 1);
            }
            // Last login - free user
            if($type == 7){
                $query->where('ispaid', 0)
                      ->whereDate('last_login', '>=', $start_date)
                      ->whereDate('last_login', '<=', $end_date);
            }
            
            if($type == 3 || $type == 5){
                $query->where('expdate', '>=', $start_date)
                      ->where('expdate', '<=', $end_date);
            } else {
                if($type != 7){
                    $query->whereDate('created_date', '>=', $start_date)
                          ->whereDate('created_date', '<=', $end_date);
                }
            }
            
            $finalResult = $query->get()->toArray();
        }
        
        return $finalResult;
    }

    private function getCampingSubDetails($cam_id)
    {
        return DB::table('whatsapp_logs as w')
            ->leftJoin('admin as a', 'w.mobile', '=', 'a.mobile')
            ->select('w.mobile', 'a.ispaid', 'a.planStatus')
            ->where('w.cam_id', $cam_id)
            ->get()
            ->toArray();
    }

    private function insertCampingTitle($title, $status)
    {
        $slug = Str::slug($title);
        
        $exists = CampingList::where('title', $slug)->exists();
        
        if($exists){
            return false;
        }
        
        $camping = CampingList::create([
            'title' => $slug,
            'date' => date('Y-m-d'),
            'retarget' => $status,
            'created_at' => now()
        ]);
        
        return $camping->id;
    }

    private function sendMsgInsertLog($csvArray, $cam_id, $tamp_id, $custom_auto)
    {
        $mobile = "";
        $userName = "User";
        $expired = "";
        $team = "";
        
        $tampData = WhatsappTemplate::find($tamp_id);
        $tamp_name = $tampData->template;
        $total_recordinsert = 0;
        $sleep_cnt = 0;
        
        $mynumber = [
            "0" => [
                "0" => "8141631370",
                "1" => "Techbit Infotech",
                "2" => "",
            ]
        ];
        
        $csvArray = array_merge($mynumber, $csvArray);
        
        $whatsappService = new WhatsappApiService();
        
        foreach($csvArray as $key => $users){
            $mobile = $users[0];
            $userResult = Admin::where('mobile', $users[0])->first();
            
            if($userResult){
                $userName = $userResult->business_name;
                $expired = (!empty($userResult->expdate) && $userResult->expdate != "0000-00-00") 
                    ? date('d/m/Y', strtotime($userResult->expdate)) : "";
                $team = $users[2] . " Month";
            } else {
                $userName = $users[1];
            }
            
            // Call WhatsApp API
            $whatsappService->set_whatsapp_api_tamplate($mobile, $tamp_name, $userName, $expired, $team, $cam_id, $custom_auto);
            
            if($sleep_cnt == 50){
                sleep(1);
                $sleep_cnt = 0;
            }
            
            $total_recordinsert++;
            $sleep_cnt++;
        }
        
        return $total_recordinsert;
    }
}
