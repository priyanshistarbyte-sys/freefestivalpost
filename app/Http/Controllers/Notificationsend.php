<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Notificationsend extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_admin_login') != true) {
            redirect(ADMIN_URL . 'login');
            exit;
        }

        $this->load->model('admin/mdl_notificationsend');
        $this->load->helper('notification_helper');
        $this->load->helper('imgupload_helper');
    }

    public function index()
    {
        
        if ($this->input->post()) {
            $url = $this->input->post('url');
            $title = $this->input->post('title');
            $message = $this->input->post('message');
            $userFilter = $this->input->post('userFilter');
            
                $fina_url = '';
                if ($url != "") {
                    $url_1 = explode('_', $url);
                    if ($url_1[0] == 'update') {
                        $fina_url = $url_1[0] . '-_-0-_-0';
                    }
                    if ($url_1[0] == 'today') {
                        $fina_url = $url_1[0] . '-_-0-_-0';
                    }
                    if ($url_1[0] == 'plan') {
                        $fina_url = $url_1[0] . '-_-0-_-0';
                    }
                    if ($url_1[0] == 'general') {
                        $fina_url = $url_1[0] . '-_-0-_-0';
                    }
                    if ($url_1[0] == 'cat') {
                        $query = $this->db->select('mid,mtitle,noti_banner')->where('mid', $url_1[1])->get('main_category');
                        $result1 = $query->row_array();
                        $fina_url = $url_1[0] . '-_-' . $result1['mid'] . '-_-' . $result1['mtitle'];
                    }
                    if ($url_1[0] == 'post') {
                        $this->db->select('tid,path,type,free_paid,cat_id');
                        $this->db->where('tid', $url_1[1]);
                        $data = $this->db->get('tamplet');

                        $result1 = $data->row_array();
                        $result1['cat_name'] = $this->getCategoryName($result1['cat_id']);
                        $fina_url = $url_1[0] . '-_-' . $result1['tid'] . '-_-' . $result1['cat_name'] . '-_-' . $result1['path'] . '-_-' . $result1['type'] . '-_-' . $result['free_paid'];
                    }
                    if ($url_1[0] == 'status') {
                        $fina_url = $url_1[0] . '-_-12-_-Thank You Wishes';
                    }
                    if ($url_1[0] == 'family') {
                        $fina_url = $url_1[0] . '-_-82-_-Fathers Day';
                    }
                    if ($url_1[0] == 'complaint') {
                        $fina_url = $url_1[0] . '-_-0-_-0';
                    }
                    if ($url_1[0] == 'logout') {
                        $fina_url = $url_1[0] . '-_-0-_-0';
                    }
                    if ($url_1[0] == 'appVideo') {
                        $fina_url = $url_1[0] . '-_-0-_-0';
                    }
                    if ($url_1[0] == 'editAc') {
                        $fina_url = $url_1[0] . '-_-0-_-0';
                    }
                }
                $dataInsert = array(
                    'title' => $title,
                    'url' => $fina_url,
                    'message' => $message,
                    'status' => 1,
                    'created_date' => CURRENT_DATE
                );
                if($this->input->post('imgsend')==1 && $this->input->post('topictoken')==1){
                    if (!empty($_FILES['image']['name'])) {
                        $image = imageUpload($_FILES['image']['name'], 'image', 'notificationsend', 'media/notification');
                        if (!empty($image)) {
                            $dataInsert['image'] = $image;
                            $img_icone = base_url("media/notification/") . $image;
                        }else{
                            $img_icone = base_url("media/category/banner/noti-banner.jpg");
                            $dataInsert['image'] = "";
                        }
                    } else {
                        if($result1['noti_banner']!=""){
                            $filestring = PUBPATH . "media/category/banner/" .  $result1['noti_banner'];
                            if (file_exists($filestring)) {
                                $img_icone = base_url("media/category/banner/") .  $result1['noti_banner'];
                                $dataInsert['image'] = "";
                            }else{
                                $img_icone = base_url("media/category/banner/noti-banner.jpg");
                                $dataInsert['image'] = "";
                            }
                        }else{
                            $img_icone = base_url("media/category/banner/noti-banner.jpg");
                            $dataInsert['image'] = "";
                        }
                    }
                }else{
                    $img_icone = "";
                    $dataInsert['image'] = "";
                }
                
                if($this->input->post('savenote')==1 && $userFilter ==""){
                    $this->mdl_notificationsend->insertnotification($dataInsert);
                }
                $dataa = array(
                    'bit'  => 1,
                    'body'   => $message,
                    'title'    => $title,
                    'click_action'  => 'FLUTTER_NOTIFICATION_CLICK',
                    'newpatten'  => true,
                    'url' => $fina_url,
                    'vibrate'  => 1,
                    'sound'    => 1,
                    'icon' => $img_icone,
                    'largeIcon'  => $img_icone,
                    'smallIcon'  => $img_icone,
                    'baseUrl'  => base_url(),
                    'mytoken'  => tkncutm,
                );

                /* 'icon' => $img_icone,
                    'largeIcon'  => $img_icone,
                    'smallIcon'  => $img_icone, */
                /* print_r($dataa);exit; */

                if($this->input->post('topictoken')==1){
                    $result1 = push_notification_android($dataa,$userFilter); 
                    $data1['message'] = 'Notification Successfully Added...!!';
                }else{
                    $msg = $this->sendTopic($title,$fina_url,$dataa);
                    $data1['message'] = $msg['message'];
                }

                $data1['status'] = "success";
                   
            echo json_encode($data1);
        } else {
            $data['data'] = array(
                'notification' => $this->mdl_notificationsend->getnotificationlist(),
                "cats"=> $this->mdl_notificationsend->CategoryList(null,null),
            );

            $data['middle'] = 'admin/notificationsend/notificationsend';
            $this->load->view('admin/template', $data);
        }
    }

    public function sendTopic($title,$redirectUrl,$message)
    {
        /* [bit] => 1
        [body] => 
        [title] => 
        [click_action] => FLUTTER_NOTIFICATION_CLICK
        [newpatten] => 1
        [url] => plan-_-0-_-0
        [vibrate] => 1
        [sound] => 1
        [icon] => 
        [largeIcon] => 
        [smallIcon] => 
        [baseUrl] => 
        [mytoken] => 
        */

        /*  $title = $this->input->post('title');
        $redirectUrl = $this->input->post('url');
        $message = $this->input->post('message'); */
        $apppackagename = "com.freefestivalpost.freefestivalpost";
        /* $apppackagename = "com.testing.testingm"; */
        $topic = $apppackagename;
        $serviceKey = "AAAAFNMpGdE:APA91bHpoEEUU4MHQWjYb6yTNJslLlegQhqaNnb90y4Yrtf9M9jYb8ErtwnjbT66xs4hbRbFZSkaP_j7cL9xFPz_ji2sOeyKMSoQxFzjtCjGjFImDypajPVoWyPUo144MNxNsIPuHypO";
        
        if($topic !="" && $title !="" && $message !=""){
            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array (
                'to' => "/topics"."/".$topic,
                'priority' => "high",
                'data' => $message,
            );
            /* $fields = array (
            'to' => "/topics"."/".$topic,
            'priority' => "high",
            'data' => array (
                    "title" => $title,
                    "body" => $message,
                    "url" => $redirectUrl,
                )
            ); */

            $fields = json_encode ( $fields );
            $headers = array (
                'Authorization: key='.$serviceKey,
                'Content-Type: application/json'
            );
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_POST, true );
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        
            $result = curl_exec ( $ch );
            curl_close ( $ch );
            $result = json_decode($result);

            $msg['status'] = 'success';
            $msg['message'] = $result->message_id.' -> Notification sucessfully send.'; 
            $msg['test'] = $result; 
        }else{
            $msg['status'] = 'error';
            $msg['message'] = 'Some filed required'; 
            $msg['test'] = ''; 
        }

        return $msg;
       /*  echo json_encode($msg);
        exit(); */
    }

    /* 
    (8:45pm) 
    http://freefestivalpost.in/admin/notificationsend/automatic_send_notification/today/Smaonr313ffp
    (9:00:am)
    http://freefestivalpost.in/admin/notificationsend/automatic_send_notification/morning/Smaonr313ffp
    (9:00 pm)
    http://freefestivalpost.in/admin/notificationsend/automatic_send_notification/night/Smaonr313ffp
    (10:00pm)
    http://freefestivalpost.in/admin/notificationsend/automatic_send_notification/greeting/Smaonr313ffp
    */
    /* public function automatic_send_notification($type,$key)
    {
        if($type!="" && $key!="Smaonr313ffp"){
            $fina_url = '';
            $message = '';
            $title = '';
            $img_icone = '';
    
            $morning_cat_id = 1;
            $morning_cat_name = 'Morning Thoughts'; 
            
            $night_cat_id = 6;
            $night_cat_name = 'Good Night Thoughts';
    
            $arr = array( 
                "3"=>"Birthday Wish", 
                "9"=>"Anniversary", 
                "10"=>"Congratulations", 
                "11"=>"Baby Shower", 
                "12"=>"Engagement", 
            );
     
            $key = array_rand($arr);
            $greeting_cat_id = $key;
            $greeting_cat_name = $arr[$key];
    
            if($type == 'today'){
                $result = $this->mdl_notificationsend->gettodaySpecialList();
                $fina_url = 'cat-_-'.$result['cat_id'].'-_-'.$result['cat_name']; 
                $message = 'Today special day for your business post. Please click here and make your own business post totally free off and quite Easily';
                $title = 'Today special day for your business post.';
                $img_icone = $result['path'];
            }else if($type == 'morning'){
                $fina_url = 'cat-_-'.$morning_cat_id.'-_-'.$morning_cat_name; 
                $message = 'Make your post with the morning thoughts. if you want then please click here and make your own business post totally free off and quite Easily';
                $title = 'Share your morning thoughts with your brand and expand your business.';
                $img_icone = '';
            }else if($type == 'night'){
                $fina_url = 'cat-_-'.$night_cat_id.'-_-'.$night_cat_name; 
                $message = 'Make your post with the night thoughts. if you want then please click here and make your own business post totally free off and quite Easily';
                $title = 'Share your night thoughts with your brand and expand your business.';
                $img_icone = '';
            }else if($type == 'greeting'){
                $fina_url = 'cat-_-'.$greeting_cat_id.'-_-'.$greeting_cat_name; 
                $message = 'Make your business post with your friends, relatives, clients\' '.$greeting_cat_name.' greetings. if you want then please click here and make your own business post totally free off and quite Easily';
                $title = 'Share your friends, relatives, clients\' '.$greeting_cat_name.' greetings with your brand and expand your business.';
                $img_icone = '';
            }
            $dataa = array(
                'bit'  => 1,
                'body'   => $message,
                'title'    => $title,
                'click_action'  => 'FLUTTER_NOTIFICATION_CLICK',
                'newpatten'  => true,
                'url' => $fina_url,
                'vibrate'  => 1,
                'sound'    => 1,
                'icon' => $img_icone,
                'largeIcon'  => $img_icone,
                'smallIcon'  => $img_icone,
            );
    
            push_notification_android($dataa);
        }
        
    } */

    public function getCategoryName($cat_id)
    {
        if ($cat_id != "") {
            $user = $this->db->select('mtitle')->where('mid', $cat_id)->get("main_category");
            return $user->row_array()['mtitle'];
        } else {
            return false;
        }
    }
    public function deletenotification()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $data = array();
        $id = $this->input->post('id');

        $dat = $this->mdl_notificationsend->getnotificationbyid($id);
        $filestring = PUBPATH . "media/notification/" . $dat['image'];
        if (file_exists($filestring)) {
            if ($dat['image']) {
                unlink($filestring);
            }
        }

        $this->mdl_notificationsend->notificationdelete($id);
        $data['status'] = 'success';
        $data['message'] = 'Notification deleted !!';


        echo json_encode($data);
    }

    public function getCategoryDataById()
    {
        $id = $this->input->post('id');
        $result = array();
        $msg = array();

        if ($id != "") {
            $user = $this->db->select('*')->where('mid', $id)->get("main_category");
            $result = $user->row_array();
            
            $msg['status'] = 'success';
            $msg['message'] = 'Category data get Sucessfully.';
            $msg['data'] = $result;
        }else{
            $msg['status'] = 'error';
            $msg['message'] = 'Category ID required...';
            $msg['data'] = $result;
        }

        echo json_encode($msg);
        exit();
    }
}

/*helper function*/
