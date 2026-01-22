<?php

namespace App\Http\Controllers;

use App\Models\NotificationSend; 
use App\Models\SubCategory;
use App\Models\Tamplet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = NotificationSend::orderBy('id', 'desc');
            return DataTables::of($query)
                 ->editColumn('created_at', function ($notification) {
                    return $notification->created_at
                        ? \Carbon\Carbon::parse($notification->created_at)->format('d-m-Y h:i A')
                        : '';
                })
                ->addColumn('actions', function ($notification) {
                    $buttons = '';
                    $deleteUrl = route('notification.destroy', $notification->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['created_at','actions'])
                ->make(true);
        }
        return view('notification.index');
    } 

    public function create()
    {
        $categories = SubCategory::where('status','1')->get();
        return view('notification.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => ['required', 'string', 'max:255'],
            'message'   => ['required'],
            'url'       => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->getMessageBag()->first()
            ]);
        }

        $url = $request->url;
        $title = $request->title;
        $message = $request->message;
        $userFilter = $request->userFilter;
        
        $fina_url = $this->buildFinalUrl($url);
        
        $dataInsert = [
            'title' => $title,
            'url' => $fina_url,
            'message' => $message,
            'status' => 1,
        ];
        
        $img_icone = $this->handleImageUpload($request, $dataInsert);
        
        if ($request->savenote == 1 && empty($userFilter)) {
            NotificationSend::create($dataInsert);
        }
        
        $notificationData = [
            'bit' => 1,
            'body' => $message,
            'title' => $title,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'newpatten' => true,
            'url' => $fina_url,
            'vibrate' => 1,
            'sound' => 1,
            'icon' => $img_icone,
            'largeIcon' => $img_icone,
            'smallIcon' => $img_icone,
            'baseUrl' => url('/'),
            'mytoken' => env('FCM_TOKEN_CUTM', ''),
        ];

        if ($request->topictoken == 1) {
            push_notification_android($notificationData, $userFilter);
            // $responseMessage = 'Notification Successfully Added...!!';
            return redirect()->route('notification.index')->with('success', 'Notification Successfully Added.');
        } else {
            $result = $this->sendTopic($title, $fina_url, $notificationData);
            // $responseMessage = $result['message'];
            return redirect()->route('notification.index')->with('success', $result['message']);
        }

        return response()->json([
            'status' => 'success',
            'message' => $responseMessage
        ]);
    }

    public function destroy($id)
    {
        $notification = NotificationSend::findOrFail($id);
        $notification->delete();
        return redirect()->route('notification.index')->with('success', 'Notification deleted successfully.');
    }

    public function getCategoryDataById(Request $request)
    {
        $id = $request->id;

        if (!$id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category ID required',
                'data'    => []
            ]);
        }

        $category = SubCategory::where('id', $id)->first();

        if (!$category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category not found',
                'data'    => []
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Category data fetched successfully',
            'data'    => [
                'id'         => $category->id,
                'mtitle'     => $category->mtitle,
                'noti_quote' => $category->noti_quote,
                'noti_banner' => $category->noti_banner,
            ]
        ]);
    }

    private function buildFinalUrl($url)
    {
        if (empty($url)) {
            return '';
        }

        $url_parts = explode('_', $url);
        $type = $url_parts[0];

        switch ($type) {
            case 'update':
            case 'today':
            case 'plan':
            case 'general':
            case 'complaint':
            case 'logout':
            case 'appVideo':
            case 'editAc':
                return $type . '-_-0-_-0';

            case 'cat':
                if (isset($url_parts[1])) {
                    $category = SubCategory::find($url_parts[1]);
                    if ($category) {
                        return $type . '-_-' . $category->id . '-_-' . $category->mtitle;
                    }
                }
                return $type . '-_-0-_-0';

            case 'post':
                if (isset($url_parts[1])) {
                    $template = Tamplet::find($url_parts[1]);
                    if ($template) {
                        $catName = $this->getCategoryName($template->sub_category_id);
                        return $type . '-_-' . $template->id . '-_-' . $catName . '-_-' . $template->path . '-_-image-_-' . $template->free_paid;
                    }
                }
                return $type . '-_-0-_-0';

            case 'status':
                return $type . '-_-12-_-Thank You Wishes';

            case 'family':
                return $type . '-_-82-_-Fathers Day';

            default:
                return '';
        }
    }

    private function handleImageUpload($request, &$dataInsert)
    {
        $img_icone = '';
        
        if ($request->imgsend == 1 && $request->topictoken == 1) {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('uploads/images/notification', 'public'); 
                if ($path) {
                    $dataInsert['image'] = $path;
                    $img_icone = $path;
                } else {
                    $img_icone = asset('assets/images/default.jpg');
                    $dataInsert['image'] = '';
                }
            } else {
                $img_icone = asset('assets/images/default.jpg');
                $dataInsert['image'] = '';
            }
        } else {
            $dataInsert['image'] = '';
        }
        
        return $img_icone;
    }

    private function sendTopic($title, $redirectUrl, $message)
    {
       
        $appPackageName = "com.freefestivalpost.freefestivalpost";
        $topic = $appPackageName;
        $serviceKey = env('FCM_SERVER_KEY');
        
        if (empty($topic) || empty($title) || empty($message)) {
            return [
                'status' => 'error',
                'message' => 'Some fields required',
                'test' => ''
            ];
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = [
            'to' => "/topics/" . $topic,
            'priority' => "high",
            'data' => $message,
        ];

        // dd($fields);
        $headers = [
            'Authorization: key=' . $serviceKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);

        return [
            'status' => 'success',
            'message' => ($result->message_id ?? 'Unknown') . ' -> Notification successfully sent.',
            'test' => $result
        ];
    }

    private function getCategoryName($categoryId)
    {
        if (empty($categoryId)) {
            return 'Unknown';
        }

        $category = SubCategory::find($categoryId);
        return $category ? $category->mtitle : 'Unknown';
    }
}
