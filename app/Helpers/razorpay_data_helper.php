<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
require(APPPATH . 'views/razorpay/Razorpay.php');
use Razorpay\Api\Api;

if (!function_exists('paymentLinkCreateForUser_post')) {
	function paymentLinkCreateForUser_post($user_data) {
		if ($user_data['token']==tkncutm) {
			$ci = get_instance();
			$api = new Api(keyId, keySecret);
			/* $api = new Api("rzp_test_ScnxlFQB8k5Cwx","q941crAsQo3FkuiWuCXeUSR8"); */

			$paymentLinkCreate = $api->paymentLink->create(array(
				'amount'=>$user_data['amount'], 
				'expire_by' => $user_data['expire_by'],
				'reference_id' => $user_data['reference_id'], 
				'description' => $user_data['description'], 
				'customer' => array(
					'name'=>$user_data['name'],
					'email' => $user_data['email'], 
					'contact'=>$user_data['contact']
				),  
				'notify'=>array(
					'sms'=>$user_data['smsOn'], 
					'email'=>$user_data['emailOn']
				) ,
				'notes'=>array(
					'user_id'=> $user_data['user_id'],
					'type'=> $user_data['type']
				),
				'callback_url' => $user_data['callback_url'],
				'callback_method'=>$user_data['callback_method']
				)
			);

			$mobile = str_replace("+91","",$user_data['contact']);
			/* paymentlink remove if avalable */
			/* $this->db->where("mobile",$mobile)->delete('payment_link'); */
			
            $insertData = array(
				'paymentLinkId' => $paymentLinkCreate->id,
                'user_id' => $user_data['user_id'],
                'mobile' => $mobile,
                'attempts' => 1,
                'exp_date' => date('Y-m-d H:i:s', $user_data['expire_by']),
                'link' => $paymentLinkCreate->short_url,
                'created_at' => CURRENT_DATE,
            );
			
            $ci->db->insert('payment_link', $insertData);

			/* whatsapp business api call start */
			whatsappPaymentLinkSend($mobile,$paymentLinkCreate->short_url);
			/* whatsapp business api call end */

			/* $data['status'] = true;
			$data['message'] = 'Payment Link Created';
			$data['data'] = $paymentLinkCreate; */
			return true;
		} else {
			return false;
			/* $data['status'] = false;
			$data['message'] = 'User is not authorized to use.';
			$data['data'] = array(); */
		}
		/* echo json_encode($data); */
	}
}

if (!function_exists('paymentlinkResend')) {
	function paymentlinkResend($user_data) {
        $ci = get_instance();
		if ($user_data['token']==tkncutm) {
			$api = new Api(keyId, keySecret);
            $api->paymentLink->fetch($user_data['paymentid'])->notifyBy($user_data['options']);
            
            $ci->db->where('paymentLinkId', $user_data['paymentid']);
            $ci->db->set('attempts', '`attempts`+ 1', FALSE);
            $ci->db->update('payment_link');
			
			/* whatsapp business api call start */
			whatsappPaymentLinkSend($user_data['mobile'],$user_data['link']);
			/* whatsapp business api call end */
			
			/* $data['status'] = true;
			$data['message'] = 'Payment Link resend...';
			$data['data'] = array(); */
			return true;
		} else {
			return false;
			/* $data['status'] = false;
			$data['message'] = 'User is not authorized to use.';
			$data['data'] = array(); */
		}
		/* echo json_encode($data); */
	}
}

function whatsappPaymentLinkSend($mobile,$link) {
	$ci = get_instance();
	/* whatsapp business api call start */
	$payID = explode("/i/",$link);
	$paymentID = $payID[1];
	$parm1 = $link;
	$paramiter = '{
		"type": "button",
		"sub_type" : "url",
		"index": "0", 
		"parameters": [
			{
				"type": "text",
				"text": "'.$paymentID.'"
			}
		]
	},
	{
		"type": "body",
		"parameters": [
			{
				"type": "text",
				"text": "'.$parm1.'"
			}
		]
	}';
	/* Whatsapp_api libraries */
	$ci->whatsapp_api->send_media_whatsapp_temp($mobile,"payment_failed_link",$paramiter,1,"auto");
}

if (!function_exists('getOrderByRazorPayAllList')) {
	function getOrderByRazorPayAllList() {
        $ci = get_instance();

		$api = new Api(keyId, keySecret);
		$options = array(
			"count"=>100, /* total record get defult 10 and max-100*/
			//"authorized"=>1, /* paid shivay na all record */
			//"skip"=>5, /* pagiation mate kyarthi start krvu te */
			//"from"=>strtotime(CURRENT_DATE), /* start date */
			//"to"=>strtotime("2023-03-30 23:59:59"), /* end date */
			
		);
		$res = $api->order->all($options);
		return $res["items"];
	}
}

if (!function_exists('getOrderByRazorPayAllListBulk')) {
	function getOrderByRazorPayAllListBulk($start_date,$end_date) {
		$end_date = date('Y-m-d', strtotime("+1 day", strtotime($end_date)));
		$api = new Api(keyId, keySecret);
		$options = array(
			"count"=>100, /* total record get defult 10 and max-100*/
			//"authorized"=>1, /* paid shivay na all record */
			//"skip"=>5, /* pagiation mate kyarthi start krvu te */
			"from"=>strtotime($start_date), /* start date */
			"to"=>strtotime($end_date), /* end date */
			
		);
		$res = $api->order->all($options);
		return $res["items"];
	}
}

if (!function_exists('getUserMobileNumber')) {
	function getUserMobileNumber($userID) {
        $ci = get_instance();

		$ci->db->select('mobile');
		$ci->db->where('id', $userID)->limit(1);
        $mobile = $ci->db->get('admin');
		if ($mobile->num_rows() > 0) {
			return $mobile->row()->mobile;
		}else{
			return "not found!";
		}
	}
}