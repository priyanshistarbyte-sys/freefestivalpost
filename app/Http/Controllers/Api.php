<?php
defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');
/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            http://localhost/restapi/index.php/
 */

class Api extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->methods['users_get']['limit'] = 500;
		$this->methods['users_post']['limit'] = 100;
		$this->methods['users_delete']['limit'] = 50;
		$this->post = $_REQUEST;
		$this->load->model('admin/api/mdl_api');
		$this->load->helper('imgupload_helper');
		$this->load->helper('sms_helper');
		$this->load->helper('razorpay_data');
		$this->load->library('image_lib');

		$this->load->library('excel');
	}
	
	/* 
	Make Post  - Post create using API For AutoPost
	url:https://freefestivalpost.in/api/api/makePostByUser
	tamplate_id:2
	user_id:3
	token:
	business_name: Techbit
	name: Sandip
	mobile1: 8989898989
	mobile2:
	email: info@techbitinfo.com
	website: www.techbitinfo.com
	address: Surat
	logo:
	birthdayPhoto:
	birthdayName:

	*/
	public function makePostByUser_post()
	{
		$data = array();
		$token = $this->input->post('token');
		$user_id = $this->input->post('user_id');
		if ($this->checkToken($user_id, $token) == false) {
			$data['status'] = false;
			$data['message'] = 'User is not authorized to use.';
			/* do not change this string , check makePost.dart */
			$data['data'] = array();
			echo json_encode($data);
			exit;
		}
		if($this->input->post('logo')!=""){
			$filestring = PUBPATH . "media/logo/" . $this->input->post('logo');
			if (!file_exists($filestring)) {
				$data['status'] = false;
				$data['message'] = 'Something wrong please check your logo or retry login.';
				$data['data'] = array();
				echo json_encode($data);
				exit;
			}
		}

		$logo = $this->input->post('logo');
		$name = $this->input->post('name');
		$business_name = $this->input->post('business_name');
		$mobile1 = $this->input->post('mobile1');
		$mobile2 = $this->input->post('mobile2');
		$email = $this->input->post('email');
		$website = $this->input->post('website');
		$address = $this->input->post('address');
		$tamplate_id = $this->input->post('tamplate_id');
		$birthdayPhoto = $this->input->post('birthdayPhoto');
		$birthdayName = $this->input->post('birthdayName');

		/* if ($this->checkToken($user_id, $token)) { */
		$result = array(
			'user_id' => $user_id,
			'logo' => $logo,
			'business_name' => ucwords($business_name),
			'name' => ucwords($name),
			'mobile1' => $mobile1,
			'mobile2' => $mobile2,
			'email' => $email,
			'website' => $website,
			'address' => ucwords($address),
			'tamplate_id' => $tamplate_id,
			'birthdayPhoto' => $birthdayPhoto,
			'birthdayName' => ucwords($birthdayName),
		);
		$total_today_post_limit = TOTALTODAYPOSTLIMIT;
		$user_paid = $this->mdl_api->userCheckPaidFree($user_id);
		
			if($user_paid){
				$userPostUrl = $this->makePost($result);
				$data['status'] = true;
				$data['message'] = 'Result Successfully get!....';
				$data['data'] = $userPostUrl;
			}else{
				$totalUserPostCount = $this->countUserPost($user_id);
				if ($totalUserPostCount >= $total_today_post_limit) {
					$data['status'] = false;
					$data['message'] = 'Today your limit is over. Daily '.$total_today_post_limit.' Post. Please go to Premium';
					$data['data'] = array();
				} else {
					$userPostUrl = $this->makePost($result);
					$data['status'] = true;
					$data['message'] = 'Result Successfully get!....';
					$data['data'] = $userPostUrl;
				}
			}
		
		echo json_encode($data);
	}

	function makePost($result)
	{
		if (!empty($result)) {
			/* user details */
			$business_logo = "";
			$personName = "";
			$userbusiness_name = "";
			$business_email = "";
			$business_mobile = "";
			$business_website = "";
			$business_address = "";
			$birthdayPhoto = "";
			$birthdayName = "";

			$business_logo = $result['logo'];
			$personName = $result['name'];
			$userbusiness_name = $result['business_name'];
			$business_email = $result['email'];

			if ($result['mobile2'] != "" && $result['mobile1'] != "") {
				$business_mobile = "+91 " . $result['mobile1'] . ' / ' . "+91 " . $result['mobile2'];
			} else if ($result['mobile1'] != "") {
				$business_mobile = "+91 " . $result['mobile1'];
			} else if ($result['mobile2'] != "") {
				$business_mobile = "+91 " . $result['mobile2'];
			}
			

			$business_website = $result['website'];
			$business_address = $result['address'];
			$birthdayPhoto = $result['birthdayPhoto'];
			$birthdayName = $result['birthdayName'];

			/* tamplate detials */
			$free_paid = "0";
			$tamplateName = "";
			$logo_position = "";
			$fontName = "";
			$fontSize = "";
			$fontColor = "";
			$mobile_p = "";
			$website_p = "";
			$email_p = "";
			$address_p = "";
			$personName_pos = "";
			$birthdayPhoto_pos = "";
			$birthdayName_pos = "";
			$birthday_font = "";
			$type = "";
			/* get tamplate details of database */
			$tamplate_result = $this->mdl_api->getTamplateById($result['tamplate_id']);

			$free_paid = $tamplate_result['free_paid'];
			$tamplateName = $tamplate_result['path'];
			$logo_position = $tamplate_result['logo_pos'];
			$fontName = $tamplate_result['font_type'];
			$fontSize = $tamplate_result['font_size'];
			$fontColor = $tamplate_result['font_color'];
			$mobile_p = $tamplate_result['mobile_pos'];
			$website_p = $tamplate_result['website_pos'];
			$email_p = $tamplate_result['email_pos'];
			$address_p = $tamplate_result['address_pos'];
			$personName_pos = $tamplate_result['name_pos'];
			$birthdayPhoto_pos = $tamplate_result['birthdayPhoto_pos'];
			$birthdayName_pos = $tamplate_result['birthdayName_pos'];
			$birthday_font = $tamplate_result['birthday_font'];

			$type = $tamplate_result['type'];

			$userNewFileName = $this->MyCopy($tamplateName, $result['tamplate_id'], $result['user_id']);

		


			/* birthday photo create || Family*/
			if ($type == 2 || $type == 5) {
				if ($userNewFileName != false) {
					$main_image = realpath("media/upload/" . $userNewFileName);
					$user_photo = realpath('media/birthday_user/' . $birthdayPhoto);
					$filesave_name_location = realpath('media/upload/' . $userNewFileName);
					if ($this->merge($main_image, $user_photo, $filesave_name_location, $birthdayPhoto_pos, $birthdayName_pos, $birthday_font, $birthdayName)) {
					}
					$path = $_SERVER['DOCUMENT_ROOT']."/"."media/birthday_user/". $birthdayPhoto;
					$remove_path = base_url('media/birthday_user/' . $birthdayPhoto);
					if (file_exists($path)) {
						unlink($path);
					}
				}
			}

			

			if ($userNewFileName != false) {
				$tamplate_path = realpath("media/upload/" . $userNewFileName);
				
				

				if ($email_p == "") {
					if ($website_p != "") {
						/* website */
						if ($business_email != "" && $business_website != "") {
							$email_web = $business_website . '  ||  ' . $business_email;
						} else if ($business_email != "" && $business_website == "") {
							$email_web = $business_email;
						} else if ($business_website != "" && $business_email == "") {
							$email_web = $business_website;
						}else{
							$email_web='';
						}
						
			
						$this->textWebsite($tamplate_path, $email_web, $fontName, $fontSize, $fontColor, $website_p);
					}
				} else {
					if ($business_email != "" && $email_p != "") {
						/* email */
						$this->textEmail($tamplate_path, $business_email, $fontName, $fontSize, $fontColor, $email_p);
					}
					if ($business_website != "" && $website_p != "") {
						/* website */
						$this->textWebsite($tamplate_path, $business_website, $fontName, $fontSize, $fontColor, $website_p);
					}
				}

				if ($business_mobile != "" && $mobile_p != "") {
					/* mobile */
					$this->textMobile($tamplate_path, $business_mobile, $fontName, $fontSize, $fontColor, $mobile_p);
				
				}

				if ($business_address != "" && $address_p != "") {
					/* address */
					$this->textAddress($tamplate_path, $business_address, $fontName, $fontSize, $fontColor, $address_p);
				}

				if ($personName != "" && $personName_pos != "") {
					/* website */
					$this->textPersonName($tamplate_path, $personName, $fontName, $fontSize, $fontColor, $personName_pos);
					
				}
				/* if ($birthdayName != "" && $birthdayName_pos != "") {
					$this->textBirthdayName($tamplate_path, $birthdayName, $fontName, $fontSize, $fontColor, $birthdayName_pos);
				}
				if ($wishName != "" && $wishName_pos != "") {
					$this->textwishName($tamplate_path, $wishName, $fontName, $fontSize, $fontColor, $wishName_pos);
				} */

				if ($business_logo != "" && $logo_position != "") {
					/* water mark add free tamplate ne */
					/* if($free_paid=='0'){
						$this->userWaterMarkLogo($tamplate_path);
					} */
					/* logo */
					
					$this->userLogo($tamplate_path, $business_logo, $logo_position);
				}

				if ($business_logo == "" && $logo_position != "" && $userbusiness_name != "") {
					
					/* logo ni jagya e business name print*/
					$this->textBusinessName($tamplate_path, $userbusiness_name, $fontName, '38', $fontColor, $logo_position);
					/* if($free_paid=='0'){
						$this->userWaterMarkLogo($tamplate_path);
					} */
					
					$this->userLogo($tamplate_path, $business_logo, $logo_position);
					
				}
								
				/* dataase insert data in makePost table */
				$this->mdl_api->addUserPost($userNewFileName, $result['user_id'], $result['tamplate_id']);
				return base_url('media/upload/') . $userNewFileName;
			}
		} else {
			return false;
		}
	}

	public function textEmail($tamplate_path, $business_email, $fontName, $fontSize, $fontColor, $email_p)
	{
		$email_p1 = explode('_', $email_p);
		$email_position = explode('-', $email_p1[0]);

		$font_style = realpath('assets/fonts/' . $fontName);

		$config['source_image'] = $tamplate_path;
		$config['wm_text'] = $business_email;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = $font_style;
		$config['wm_font_size'] = $fontSize;
		$config['wm_font_color'] = $fontColor;
		$config['wm_vrt_alignment'] = $email_position[0];/* top, middle, bottom */
		$config['wm_hor_alignment'] = $email_position[1];/* left, center, right */
		$config['wm_hor_offset'] = $email_p1[1]; /* addu - left*/
		$config['wm_vrt_offset'] = $email_p1[2]; /* ubbhu - top */

		$this->image_lib->initialize($config);
		if (!$this->image_lib->watermark()) {
			return $this->image_lib->display_errors();
		}
	}

	public function textMobile($tamplate_path, $business_mobile, $fontName, $fontSize, $fontColor, $mobile_p)
	{
		$mobile_p1 = explode('_', $mobile_p);
		$mobile_position = explode('-', $mobile_p1[0]);
		$font_style = realpath('assets/fonts/' . $fontName);
		$config['source_image'] = $tamplate_path;
		$config['wm_text'] = $business_mobile;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = $font_style;
		$config['wm_font_size'] = $fontSize;
		$config['wm_font_color'] = $fontColor;
		$config['wm_vrt_alignment'] = $mobile_position[0];/* top, middle, bottom */
		$config['wm_hor_alignment'] = $mobile_position[1];/* left, center, right */
		$config['wm_hor_offset'] = $mobile_p1[1]; /* addu - left*/
		$config['wm_vrt_offset'] = $mobile_p1[2]; /* ubbhu - top */

		$this->image_lib->initialize($config);
		if (!$this->image_lib->watermark()) {
			return $this->image_lib->display_errors();
		}
	}

	public function textAddress($tamplate_path, $business_address, $fontName, $fontSize, $fontColor, $address_p)
	{
		$address_p1 = explode('_', $address_p);
		$address_position = explode('-', $address_p1[0]);
		$font_style = realpath('assets/fonts/' . $fontName);
		$config['source_image'] = $tamplate_path;
		$config['wm_text'] = $business_address;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = $font_style;
		$config['wm_font_size'] = $fontSize;
		$config['wm_font_color'] = $fontColor;
		$config['wm_vrt_alignment'] = $address_position[0];/* top, middle, bottom */
		$config['wm_hor_alignment'] = $address_position[1];/* left, center, right */
		$config['wm_hor_offset'] = $address_p1[1]; /* addu - left*/
		$config['wm_vrt_offset'] = $address_p1[2]; /* ubbhu - top */

		$this->image_lib->initialize($config);
		if (!$this->image_lib->watermark()) {
			return $this->image_lib->display_errors();
		}
	}

	public function textWebsite($tamplate_path, $business_website, $fontName, $fontSize, $fontColor, $website_p)
	{
		$website_p1 = explode('_', $website_p);
		$website_position = explode('-', $website_p1[0]);
		$font_style = realpath('assets/fonts/' . $fontName);
		$config['source_image'] = $tamplate_path;
		$config['wm_text'] = $business_website;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = $font_style;
		$config['wm_font_size'] = $fontSize;
		$config['wm_font_color'] = $fontColor;
		$config['wm_vrt_alignment'] = $website_position[0];/* top, middle, bottom */
		$config['wm_hor_alignment'] = $website_position[1];/* left, center, right */
		$config['wm_hor_offset'] = $website_p1[1]; /* addu - left*/
		$config['wm_vrt_offset'] = $website_p1[2]; /* ubbhu - top */

		
		
		$this->image_lib->initialize($config);
		
		
			
		if (!$this->image_lib->watermark()) {
			return $this->image_lib->display_errors();
		}
	}

	public function textPersonName($tamplate_path, $personName, $fontName, $fontSize, $fontColor, $website_p)
	{
		$website_p1 = explode('_', $website_p);
		$website_position = explode('-', $website_p1[0]);
		$font_style = realpath('assets/fonts/' . $fontName);
		$config['source_image'] = $tamplate_path;
		$config['wm_text'] = $personName;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = $font_style;
		$config['wm_font_size'] = $fontSize;
		$config['wm_font_color'] = $fontColor;
		$config['wm_vrt_alignment'] = $website_position[0];/* top, middle, bottom */
		$config['wm_hor_alignment'] = $website_position[1];/* left, center, right */
		$config['wm_hor_offset'] = $website_p1[1]; /* addu - left*/
		$config['wm_vrt_offset'] = $website_p1[2]; /* ubbhu - top */

		$this->image_lib->initialize($config);
		if (!$this->image_lib->watermark()) {
			return $this->image_lib->display_errors();
		}
	}

	public function textBusinessName($tamplate_path, $personName, $fontName, $fontSize, $fontColor, $website_p)
	{
		$website_p1 = explode('_', $website_p);
		$website_position = explode('-', $website_p1[0]);
		switch ($website_p1[0]) {
			case 'top-left':
				$f_left = 20;
				$f_top = 120;
			break;
			case 'top-center':
				$f_left = 0;
				$f_top = 120;
			break;
			case 'top-right':
				$f_left = -20;
				$f_top = 120;
			break;
			case 'middle-left':
				$f_left = 20;
				$f_top = 0;
			break;
			case 'middle-center':
				$f_left = 0;
				$f_top = 0;
			break;
			case 'middle-right':
				$f_left = -20;
				$f_top = 0;
			break;
			case 'bottom-left':
				$f_left = 20;
				$f_top = -200;
			break;
			case 'bottom-center':
				$f_left = 0;
				$f_top = -200;
			break;
			case 'bottom-right':
				$f_left = -20;
				$f_top = -200;
			break;
			default:
				$f_left = 20;
				$f_top=120;
		  }

		$font_style = realpath('assets/fonts/' . $fontName);
		$config['source_image'] = $tamplate_path;
		$config['wm_text'] = $personName;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = $font_style;
		$config['wm_font_size'] = $fontSize;
		$config['wm_font_color'] = $fontColor;
		$config['wm_vrt_alignment'] = $website_position[0];/* top, middle, bottom */
		$config['wm_hor_alignment'] = $website_position[1];/* left, center, right */
		$config['wm_hor_offset'] = $f_left; /* addu - left*/
		$config['wm_vrt_offset'] = $f_top; /* ubbhu - top */

		$this->image_lib->initialize($config);
		if (!$this->image_lib->watermark()) {
			return $this->image_lib->display_errors();
		}
	}

	public function userLogo($tamplate_path, $business_logo, $logo_position)
	{
		$logo = explode('_', $logo_position);
		$logo_position = explode('-', $logo[0]);

		$abc = realpath('media/logo/' .$business_logo);


		/* https://codeigniter.com/userguide2/libraries/image_lib.html */
		$config['image_library'] = 'gd2';
		$config['source_image'] = $tamplate_path; /* none */
		$config['create_thumb'] = TRUE;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		/* $config['width'] = 750;
		$config['height'] = 50; */
		$config['wm_type'] = 'overlay';  /* text, overlay */
		/*$config['dynamic_output'] = 'TRUE';*/   /*TRUE/FALSE  */
		$config['wm_overlay_path'] = $abc;    /* the overlay image */
		$config['quality'] = 100;
		$config['padding'] = 100;
		$config['wm_opacity'] = 100;
		$config['wm_vrt_alignment'] = $logo_position[0]; /* top, middle, bottom */
		$config['wm_hor_alignment'] = $logo_position[1]; /* left, center, right */
		$config['wm_hor_offset'] = $logo[1]; /* addu - left*/
		$config['wm_vrt_offset'] = $logo[2];/* ubbhu - top */

		$this->image_lib->initialize($config);
		
		/* $data['status'] = true;
		$data['message'] = 'error...!';
		$data['data'] = $this->image_lib->watermark();
		echo json_encode($data);exit; */

		if (!$this->image_lib->watermark()) {
			return $this->image_lib->display_errors();
		}
	}

	public function userWaterMarkLogo($tamplate_path)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = $tamplate_path; /* none */
		$config['create_thumb'] = TRUE;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['wm_type'] = 'overlay';  /* text, overlay */
		$config['wm_overlay_path'] = './media/watermark1.png';    /* the overlay image */
		$config['quality'] = 100;
		$config['padding'] = 100;
		$config['wm_opacity'] = 100;
		$config['wm_vrt_alignment'] = 'bottom'; /* top, middle, bottom */
		$config['wm_hor_alignment'] = 'left'; /* left, center, right */
		$config['wm_hor_offset'] = 0; /* addu - left*/
		$config['wm_vrt_offset'] = 150;/* ubbhu - top */

		$this->image_lib->initialize($config);
		if (!$this->image_lib->watermark()) {
			return $this->image_lib->display_errors();
		}
	}

	public function MyCopy($filename, $tamplate, $userid)
	{
		$imagePath = realpath("media/template/" . $filename);
		$newPath = "media/upload/";
		$ext = '.png';
		$newFileName = time() . '_' . $tamplate . '_' . $userid . $ext;
		$newName  = $newPath . $newFileName;
		$copied = copy($imagePath, $newName);

		if ((!$copied)) {
			return false;
		} else {
			return $newFileName;
		}
	}

	function merge($mainImage, $logoImage, $destination_image_url, $birthdayPhoto_pos, $birthdayName_pos, $birthday_font, $birthdayName)
	{
		$photo_pos = explode('_', $birthdayPhoto_pos);
		$logo_left = $photo_pos[0]; /*left*/
		$logo_top = $photo_pos[1]; /*top*/

		if($birthdayName_pos!=""){
			$name_pos = explode('_', $birthdayName_pos);
			$text_roted = $name_pos[2]; /*rotar*/
			$text_left = $name_pos[0];/* left */
			$text_top = $name_pos[1];/* top */
		}
		if($birthday_font !=""){
			$font_pos = explode(',', $birthday_font);
			$font_style = realpath('assets/fonts/' . $font_pos[0]);
			/*text paramiter*/
			$text_size = $font_pos[1]; /*font size*/
		}


		list($width_main, $height_main) = getimagesize($mainImage);
		list($width_logo, $height_logo) = getimagesize($logoImage);

		$image = imagecreatetruecolor($width_main, $height_main);
		$image_main = imagecreatefromPNG($mainImage);
		$image_logo = imagecreatefromPNG($logoImage);

		imagecopy($image, $image_logo, $logo_left, $logo_top, 0, 0, $width_logo, $height_logo);/* logo create */
		imagecopy($image, $image_main, 0, 0, 0, 0, $width_main, $height_main);/* background image */
		
		/* $white = imagecolorallocate($image, 255, 255, 255); */
		/* imagefilledrectangle($image, 20, 10, 100, 100, $white); */ /* squre box koy pan colornu */
		
		/*text add thay che*/
		if($birthdayName!=""){
			$color = imagecolorallocate($image, $font_pos[2], $font_pos[3], $font_pos[4]);
			imagettftext($image, $text_size, $text_roted, $text_left, $text_top, $color, $font_style, $birthdayName);
		}

		imagePNG($image, $destination_image_url);
		imagedestroy($image);
		imagedestroy($image_main);
		imagedestroy($image_logo);
		/*comparess images and replce start .....*/
		$this->compress_image($destination_image_url, $destination_image_url, 80);
		return true;
	}

	function compress_image($source_url, $destination_url, $quality)
	{
		$info = getimagesize($source_url);
		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source_url);

		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source_url);

		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source_url);

		imagejpeg($image, $destination_url, $quality);
		return $destination_url;
	}

	public function failedRepaymentRemove($mobile)
	{
		if($mobile!=""){
			$this->db->where('w_mobile', $mobile);
            $this->db->delete('webhook_failed');
		}
		return true;
	}

	/*****   *****/
	/***** Common Function Only   *****/
	public function checkMyToken($token)
	{	/* old */                                      /* new */
		if ($token == "mycustomapi123321" || $token == "mVfHmPbTudbqJBWMiqoAPA91bH6gSTssOVJwlpJeuIVwdSbZGFUd4b7HoNZ5FyaNN4LVLbdmffp9") {
			return true;
		}else{
			return false;
		}
	}

	public function countUserPost($user_id)
	{
		$todayDate = ONLY_DATE;
		$this->db->select('count(post_id) as totalPost');
		$this->db->from('makepost');
		$this->db->where('user_id', $user_id);
		$this->db->where('created_at', $todayDate);
		$result = $this->db->get()->row_array();
		return $result['totalPost'];
	}

	/* check token  */
	public function checkToken($user_id, $token)
	{
		if ($user_id != "" && $token != "") {
			$total = $this->db->select('tid')->where('user_id', $user_id)->where('token', $token)->get("token");
			if ($total->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/* razor pay webhook */
	public function webhookPayment_post()
	{
		$rawData = "";
		$rawData = file_get_contents("php://input");
		if($rawData == ""){
			$rawData = json_encode($this->input->post());
		}
		if($rawData == ""){
			$rawData = json_encode($this->input->get());
		}

		$insertData = array(
			'testName' => "webhooks1",
			'testData' => $rawData,
			'created_at' => CURRENT_DATE,
		);
		$this->db->insert('test', $insertData);
		
		/* app thi payment kriye ena mate sample code */
		/* $rawData = '{
			"entity": "event",
			"account_id": "acc_INR6CnL6dSb1r4",
			"event": "payment.authorized",
			"contains": [
				"payment"
			],
			"payload": {
				"payment": {
					"entity": {
						"id": "pay_Js840yxfBcs5FB",
						"entity": "payment",
						"amount": 59900,
						"currency": "INR",
						"status": "authorized",
						"order_id": null,
						"invoice_id": null,
						"international": false,
						"method": "upi",
						"amount_refunded": 0,
						"refund_status": null,
						"captured": false,
						"description": "Reference No. #2",
						"card_id": null,
						"bank": null,
						"wallet": null,
						"vpa": "pandurangdeokar@ybl",
						"email": "freefestivepost@gmail.com",
						"contact": "+918141639069",
						"notes": {
							"user_id": "3",
							"type": "app"
						},
						"fee": null,
						"tax": null,
						"error_code": null,
						"error_description": null,
						"error_source": null,
						"error_step": null,
						"error_reason": null,
						"acquirer_data": {
							"rrn": "219262470104"
						},
						"created_at": 1657557811
					}
				}
			},
			"created_at": 1657557832
		}'; */


		/* payment link result sample */
		/* $rawData = '{
			"entity": "event",
			"account_id": "acc_INR6CnL6dSb1r4",
			"event": "payment.authorized",
			"contains": [
			  "payment"
			],
			"payload": {
			  "payment": {
				"entity": {
				  "id": "pay_LL330DVl88BFdM",
				  "entity": "payment",
				  "amount": 23900,
				  "currency": "INR",
				  "status": "authorized",
				  "order_id": "order_LL2zjPIRVenCFn",
				  "invoice_id": null,
				  "international": false,
				  "method": "card",
				  "amount_refunded": 0,
				  "refund_status": null,
				  "captured": false,
				  "description": "#LL2zR44rPTpOpa",
				  "card_id": "card_LL330GtjicS9dS",
				  "card": {
					"id": "card_LL330GtjicS9dS",
					"entity": "card",
					"name": "",
					"last4": "1111",
					"network": "Visa",
					"type": "debit",
					"issuer": null,
					"international": false,
					"emi": false,
					"sub_type": "consumer",
					"token_iin": null
				  },
				  "bank": null,
				  "wallet": null,
				  "vpa": null,
				  "email": "info@techbitinfo.com",
				  "contact": "+918141639069",
				  "token_id": "token_LL330RVBOe8WEd",
				  "notes": {
						"user_id": "3"
						"type": "app"
					},
				  "fee": null,
				  "tax": null,
				  "error_code": null,
				  "error_description": null,
				  "error_source": null,
				  "error_step": null,
				  "error_reason": null,
				  "acquirer_data": {
					"auth_code": "482385"
				  },
				  "created_at": 1677409095
				}
			  }
			},
			"created_at": 1677409098
		  }'; */

		$arr = json_decode($rawData, true);

		if(!empty($arr) && count($arr)!=0){
			$event = $arr['event'];
			$status = $arr['payload']['payment']['entity']['status'];
			$payment_id = $arr['payload']['payment']['entity']['id'];
			$amount = $arr['payload']['payment']['entity']['amount'];
			$email = $arr['payload']['payment']['entity']['email'];
			$mobile = $arr['payload']['payment']['entity']['contact'];
			
			$notes = $arr['payload']['payment']['entity']['notes'];
			if(!empty($notes)){
				$user_id = $notes['user_id'];
				/* $notes['type']; */
			}else{
				$description = $arr['payload']['payment']['entity']['description'];
			
				if(strpos($description, "#") == false){
					$description = "abc#xyz";
				}

				$user_data = explode("#",$description);
				$user_id = $user_data[1];
			}

			if($event=="payment.authorized" && $status == "authorized"){
				$query = $this->db->select('pid')
					->where("ptransactionid",$payment_id)
					->get('payments');
				$transactionIDCheck = $query->num_rows();
				
				if($transactionIDCheck <= 0){
					/* paymentlink remove if avalable */
					/* $this->db->where("mobile",$mobile)->delete('payment_link'); */
					$mobile = str_replace("+91","",$mobile);
					$amount = ($amount / 100);
					
					$this->failedRepaymentRemove($mobile);

					/* user id get */
					$query_user = $this->db->select('id')
					->where("id",$user_id)
					->limit(1)
					->get('admin');
					$result_user_data = $query_user->row_array();

					/* package ID get  */
					$query_plan = $this->db->select('*')
					->where("price",$amount)
					/* ->where("status",1) */
					->limit(1)
					->get('subscriptionPlan');
					$result_plan = $query_plan->row_array();

					if(!empty($result_plan) && !empty($user_id) && !empty($result_user_data)){
						/* $packageid = $result_plan['plan_id']; */
						$this->userSubPaymentHistoryFun($user_id,$payment_id,$result_plan);
					}else{
						/* do insert */
						$webhook_authorized_insert = array(
							'w_date' => ONLY_DATE,
							'w_event' => $event,
							'transaction_id' => $payment_id,
							'w_amount' => $amount,
							'w_email' => $email,
							'w_mobile' => $mobile,
							'w_status' => 0,
							'created_at' => CURRENT_DATE,
						);
						$this->mdl_api->doInsertSubscriptionPlanAutomatic($webhook_authorized_insert);
					}
					
				}
			}
		}
		return true;
	}

	public function userSubPaymentHistoryFun($user_id,$ptransactionid,$result_plan)
	{
		$data = array();

        if ($user_id != "" && !empty($result_plan) && $ptransactionid!="") {

			/* user e trile use kru ke ny te check krva */
			$query = $this->db->select('pid')
			->where('u_id', $user_id)
			->get('payments');
			$countPlan = $query->num_rows();

			if($countPlan > 0 && $result_plan['month']==0){
				$data['status'] = "error";
				$data['message'] = 'Sorry, You are not eligible for a trial plan. Thank you';
				$data['data'] = array();
			}else{
				/* user no plan active che ke ny te check krva - jo  active hoy to e exp date ma atla day add krva */
				$isCheckPaidDateAdd = $this->db->select('*')
					->where('id', $user_id)
					->where('planStatus', 2)
					->where('ispaid', 1)
					->where('expdate >', ONLY_DATE)
					->limit(1)
					->get('admin');
				$userDataGetCheck = $isCheckPaidDateAdd->row_array();
				
				$cstomStatus ="";
				if($result_plan['month'] == 0){
					$month = '+7 days';
					$cstomStatus = 1; /* trial */
				}else{
					$month = '+'.$result_plan['month']." months";
					$cstomStatus = 2; /* paid */
				}
				
				if(!empty($userDataGetCheck)){
					$pexpdate = date("Y-m-d", strtotime($month, strtotime($userDataGetCheck['expdate'])));
				}else{
					$pexpdate = date("Y-m-d", strtotime($month, strtotime(ONLY_DATE)));
				}
				/* if($userDataGetCheck['planStatus']==2 && $userDataGetCheck['ispaid'] ==1 && $userDataGetCheck['expdate'] > ONLY_DATE){
					$pexpdate = date("Y-m-d", strtotime($month, strtotime($userDataGetCheck['expdate'])));
				}else{
					$pexpdate = date("Y-m-d", strtotime($month, strtotime(ONLY_DATE)));
				} */
				
				$ispaidUpdate = array(
					'ispaid' => '1',
					'expdate' => $pexpdate,
					'planStatus' => $cstomStatus,
					'status' => '1',
				);
				$this->db->where('id', $user_id);
				$this->db->update('admin', $ispaidUpdate);
				
				$SubPayInsert = array(
					'u_id' => $user_id,
					'pamount' =>$result_plan['price'],
					'pdate' => ONLY_DATE,
					'ptransactionid' => $ptransactionid,
					'pstatus' => $result_plan['plan_name'],
					'packageid' => $result_plan['plan_id'],
					'pprice' => $result_plan['price'],
					'pmonth' => $result_plan['month'],
					'ref_status' => 0,
					'refund_id' => null,
					'refundDate' => null,
					'userRole' => null,
					'created_at' => CURRENT_DATE,
				);
				
				$this->mdl_api->insertUserSubPayment($SubPayInsert);
				$data['status'] = "success";
				$data['message'] = 'User Transaction Successfully!....';
				$data['data'] = "";
				
				/* sms send */
				$query = $this->db->select('*')
				->where('id', $user_id)
				->limit(1)
				->get('admin');
				$userResult = $query->row_array();
				send_sms_other($userResult['mobile'],"buy",$result_plan['month']);

				/* paymentlink remove if avalable */
				$this->db->where("mobile",$userResult['mobile'])->delete('payment_link');
			}
		} else {
			$data['status'] = "error";
			$data['message'] = 'Some field are required!...';
			$data['data'] = array();
		}
        $insertData = array(
			'testName' => "result",
			'testData' => $data,
			'created_at' => CURRENT_DATE,
		);
		$this->db->insert('test', $insertData);
		echo json_encode($data);
	}

	public function webhookPaymentFaild_post()
	{
		$rawData = "";
		$rawData = file_get_contents("php://input");
		if($rawData == ""){
			$rawData = json_encode($this->input->post());
		}
		if($rawData == ""){
			$rawData = json_encode($this->input->get());
		}
		$insertData = array(
			'testName' => "fail-payment",
			'testData' => $rawData,
			'created_at' => CURRENT_DATE,
		);
		$this->db->insert('test', $insertData);
		/* $rawData = '{
			"entity": "event",
			"account_id": "acc_INR6CnL6dSb1r4",
			"event": "payment.failed",
			"contains": [
				"payment"
			],
			"payload": {
				"payment": {
					"entity": {
						"id": "pay_Js840yxfBcs5FB",
						"entity": "payment",
						"amount": 59900,
						"currency": "INR",
						"status": "failed",
						"order_id": null,
						"invoice_id": null,
						"international": false,
						"method": "upi",
						"amount_refunded": 0,
						"refund_status": null,
						"captured": false,
						"description": "Reference No. 3",
						"card_id": null,
						"bank": null,
						"wallet": null,
						"vpa": "pandurangdeokar@ybl",
						"email": "moradiyasandip99@gmail.com",
						"contact": "+918141639069",
						"notes": {
							"user_id": "3",
							"type": "app"
						},
						"fee": null,
						"tax": null,
						"error_code": null,
						"error_description": null,
						"error_source": null,
						"error_step": null,
						"error_reason": null,
						"acquirer_data": {
							"rrn": "219262470104"
						},
						"created_at": 1657557811
					}
				}
			},
			"created_at": 1657557832
		}'; */

		$arr = json_decode($rawData, true);
		
		if(!empty($arr) && count($arr)!=0){
			$event = $arr['event'];
			$status = $arr['payload']['payment']['entity']['status'];
			$payment_id = $arr['payload']['payment']['entity']['id'];
			$amount = $arr['payload']['payment']['entity']['amount'];
			$amountRS = ($amount / 100);
			
			$email = $arr['payload']['payment']['entity']['email'];
			$mobile = $arr['payload']['payment']['entity']['contact'];
			$mobileRS = str_replace("+91","",$mobile);
			
			$notes = $arr['payload']['payment']['entity']['notes'];
			$description = $arr['payload']['payment']['entity']['description'];
			
			if(!empty($notes)){
				$user_id = $notes['user_id'];
			}else{
				if(strpos($description, "#") == false){
					$description = "abc#xyz";
				}
				$getUserIdByString = explode("#",$description);
				$user_id = $getUserIdByString[1];
			}

			if($event=="payment.failed" && $status == "failed"){
				$emailSend = false;
				if($email != "brandfotoss@gmail.com" && $email !=""){
					$emailSend = true;
				}
				$user_data = array(
					'token'=>tkncutm, 
					'amount'=>$amount, 
					'expire_by' => strtotime("+2 days"),
					'reference_id' => "ref_".time() ."_".$user_id,
					'description' => $description,
					'name'=>"",
					'email' =>$email, 
					'contact'=>$mobile,
					'smsOn'=>true, 
					'emailOn'=>$emailSend,
					'user_id'=>$user_id,
					'type'=> "link",
					'callback_url' => "",
					'callback_method'=>"",
				);

				$this->db->select('*');
				$this->db->where('mobile', $mobileRS);
				$this->db->where('exp_date >',date('Y-m-d H:i:s'));
				$this->db->order_by('paylink_id',"desc")->limit(1);
				$query_link = $this->db->get('payment_link');

				$totalrows = $query_link->num_rows();
				if($totalrows > 0){
					$totalrowsData = $query_link->row_array();
					$user_data_resend = array(
						'token'=>tkncutm, 
						'options'=>"sms", 
						'paymentid'=>$totalrowsData['paymentLinkId'],
						'link'=>$totalrowsData['link'],
						'mobile'=>$totalrowsData['mobile'],
					);
					paymentlinkResend($user_data_resend);
				}else{
					paymentLinkCreateForUser_post($user_data);
				}

				/* subscribe vala user delete kre */
				$this->db->where("w_mobile",$mobileRS)->delete('webhook_failed');
				
				$insertData = array(
					'w_date' => ONLY_DATE,
					'w_event' => $event,
					'transaction_id' => $payment_id,
					'w_amount' => $amountRS,
					'w_email' => $email,
					'w_mobile' => $mobileRS,
					'created_at' => CURRENT_DATE,
					'updated_at' => CURRENT_DATE,
				);
				$this->mdl_api->doInsertSubscriptionPlanFailed($insertData);
			}
		}
		return true;
	}
	
	/* node api side thi sms-helper mathi call thy che 
	https://freefestivalpost.in/api/api/nodeSideEmailSend
	
	*/
	public function nodeSideEmailSend_post()
	{
		$data = array();
		$mytoken = $this->input->post('mytoken');
		$email = $this->input->post('email');
		$tamp_type = $this->input->post('tamp_type');
		$var1 = $this->input->post('var1');
		$var2 = $this->input->post('var2');
		$var3 = $this->input->post('var3');

		if ($this->checkMyToken($mytoken) && $email !="" && $tamp_type !="") {
			
			send_email($email,$tamp_type,$var1,$var2,$var3);

			$data['status'] = true;
			$data['message'] = 'Successfully';
			$data['data'] = array();
		}else{
			$data['status'] = false;
			$data['message'] = 'something wrong..';
			$data['data'] = array();
		}
		echo json_encode($data);
	}

	/* node api side thi sms-helper mathi call thy che 
	https://freefestivalpost.in/api/api/nodeSideSMSSend
	*/
	public function nodeSideSMSSend_post()
	{
		$data = array();
		$mytoken = $this->input->post('mytoken');
		$mobile = $this->input->post('mobile');
		$msg91_tamp_id = $this->input->post('msg91_tamp_id');
		$var1 = $this->input->post('var1');
		$var2 = $this->input->post('var2');
		$var3 = $this->input->post('var3');
		$otp = $this->input->post('otp');
		$sshcode = $this->input->post('sshcode');
		$sms_type = $this->input->post('sms_type');

		if ($this->checkMyToken($mytoken) && $mobile !="" && $msg91_tamp_id !="" && $sms_type !="") {
			if($sms_type == "otp"){
				msg91otp($mobile,$msg91_tamp_id, $otp, $sshcode);
			}else{
				msg91sms("91".$mobile,$msg91_tamp_id,$var1, $var2, $var3);
			}

			$data['status'] = true;
			$data['message'] = 'Successfully';
			$data['data'] = array();
		}else{
			$data['status'] = false;
			$data['message'] = 'something wrong..';
			$data['data'] = array();
		}
		echo json_encode($data);
	}

	/* node side thi whatsapp meta tamplate automatic send by api php */
	public function nodeSideWhatsAppSMS_post()
	{
		$data = array();
		$mytoken = $this->input->post('mytoken');
		$mobile = $this->input->post('mobile');
		$tempname = $this->input->post('tempname');
		$userName = $this->input->post('userName');
		$expired = $this->input->post('expired');
		$term = $this->input->post('term');
		
		if ($this->checkMyToken($mytoken) && $mobile !="" && $tempname !="") {
			
			/* node_whatsapp_sms_api($mobile,$tempname,$term); */
			set_whatsapp_api_tamplate($mobile,$tempname,$userName,$expired,$term,"1","auto");
			
			$data['status'] = true;
			$data['message'] = 'Successfully';
			$data['data'] = array();
		}else{
			$data['status'] = false;
			$data['message'] = 'something wrong..';
			$data['data'] = array();
		}
		echo json_encode($data);
	}

	/* for node api start*/
	public function makePostByUserNode_post()
	{
		

		$data = array();
		$token = $this->input->post('token');
		$user_id = $this->input->post('user_id');
		/* if ($this->checkToken($user_id, $token) == false) {
			$data['status'] = false;
			$data['message'] = 'User is not authorized to use.';
			$data['data'] = array();
			echo json_encode($data);
			exit;
		} */
		if($this->input->post('logo')!=""){
			$filestring = PUBPATH . "media/logo/" . $this->input->post('logo');
			if (!file_exists($filestring)) {
				$data['status'] = false;
				$data['message'] = 'Something wrong please check your logo or retry login.';
				$data['data'] = array();
				echo json_encode($data);
				exit;
			}
		}
		
		$logo = $this->input->post('logo');
		$name = $this->input->post('name');
		$business_name = $this->input->post('business_name');
		$mobile1 = $this->input->post('mobile1');
		$mobile2 = $this->input->post('mobile2');
		$email = $this->input->post('email');
		$website = $this->input->post('website');
		$address = $this->input->post('address');
		$tamplate_id = $this->input->post('tamplate_id');
		$birthdayPhoto = $this->input->post('birthdayPhoto');
		$birthdayName = $this->input->post('birthdayName');

		/* if ($this->checkToken($user_id, $token)) { */
		$result = array(
			'user_id' => $user_id,
			'logo' => $logo,
			'business_name' => ucwords($business_name),
			'name' => ucwords($name),
			'mobile1' => $mobile1,
			'mobile2' => $mobile2,
			'email' => $email,
			'website' => $website,
			'address' => ucwords($address),
			'tamplate_id' => $tamplate_id,
			'birthdayPhoto' => $birthdayPhoto,
			'birthdayName' => ucwords($birthdayName),
		);
		
		$total_today_post_limit = TOTALTODAYPOSTLIMIT;
		$user_paid = $this->mdl_api->userCheckPaidFree($user_id);
		/* free user post count */
		$this->userPostCount($user_id);

			if($user_paid){
				
				$userPostUrl = $this->makePost($result);
				$data['status'] = true;
				$data['message'] = 'Result Successfully get!!....';
				$data['data'] = $userPostUrl;
			}else{
				$totalUserPostCount = $this->countUserPost($user_id);
				if ($totalUserPostCount >= $total_today_post_limit) {
					$data['status'] = false;
					$data['message'] = 'Today your limit is over. Daily '.$total_today_post_limit.' Post. Please go to Premium';
					$data['data'] = array();
				} else {
					
					$userPostUrl = $this->makePost($result);
					$data['status'] = true;
					$data['message'] = 'Result Successfully get!!!....'.$user_id;
					$data['data'] = $userPostUrl;
				}
			}
		
		echo json_encode($data);
	}

	/* for node api end*/
	function userPostCount($user_id){
		$query = $this->db->select("daily_id")
            ->where('user_id', $user_id)
            ->get('daily_post_count');

		$dat_resu = array();
		
        if ($query->num_rows() > 0) {
			/* update */
			$this->db->set('tamp_count', 'tamp_count+1', FALSE);
			$dat_resu["updated_at"] = CURRENT_DATE;
			$this->db->where('user_id', $user_id);
			$this->db->update('daily_post_count', $dat_resu);
		}else{
			/* insert */
			$dat_resu["user_id"] = $user_id;
			$dat_resu["tamp_count"] = 1;
			$dat_resu["created_at"] = CURRENT_DATE;
			$dat_resu["updated_at"] = CURRENT_DATE;

			$this->db->insert('daily_post_count', $dat_resu);
		}
		
	}

	/* just testing */
	public function testApi_post()
	{
		$data = array();
		//$this->leadAssignRemove(101204,0);
		//set_whatsapp_api_tamplate("8141631370","info_common_temp_1","Sandip","10-2-2023","6","1","bulk");
		//send_email("moradiyasandip99@gmail.com","AfterRegister","Moradiya Sandip","","");
		//send_sms_other("8141631370","welcome","");
		/* $result = $this->mdl_api->paymentFailUserDeleteIfPaid();
		if ($result) {
			$data['status'] = true;
			$data['message'] = 'Result Successfully get!....sandip';
			$data['data'] = $result;
		} else {
			$data['status'] = false;
			$data['message'] = 'Data not found!....sando=ip';
			$data['data'] = array();
		} */
		$data['status'] = true;
		$data['message'] = 'Result Successfully get!....sandip';
		$data['data'] = array();

		echo json_encode($data);
	}
	public function leadAssignRemove($user_id,$status)
	{
		$data = array();

		$this->db->select("*");
		$this->db->where('user_id', $user_id);
		if($status==0){
		}else{
			$this->db->where('lead_status_id', $status);
		}
		$query = $this->db->get('lead_assign_data');
        $qresult = $query->result();
		/* print_r($qresult);exit; */
		$count = 0;
		foreach ($qresult as $key => $value) {
			$this->db->where('lead_assign_id', $value->lead_assign_id)->delete('lead_customer_chat');
			$this->db->where('lead_assign_id', $value->lead_assign_id)->delete('lead_customer_review');
			$this->db->where('lead_assign_id', $value->lead_assign_id)->delete('lead_sales_report');
			$count++;
		}
		if($status==0){
			$this->db->where('user_id', $user_id)->delete('lead_assign_data');
		}else{
			$this->db->where('user_id', $user_id)->where('lead_status_id', $status)->delete('lead_assign_data');
		}
		echo "Lead-".$this->db->affected_rows();
		echo "</br>";
		echo "Sub Data-".$count;
	}

	public function extraRemoveThumbNumberSet_post()
	{
		
		$this->db->select('tid,planImgName')->where("planImgName !=","");
		$data = $this->db->get('tamplet');
		$totalrowsData = $data->result_array();
		foreach($totalrowsData as $result){
			$oldname = PUBPATH . "media/template/plan/thumb/".$result['tid'].'.jpg';
			if (file_exists($oldname)) {

			}else{
				//$this->db->set('tamp_count', '');
				$dat_resu["planImgName"] = "";
				$this->db->where('tid', $result['tid']);
				$this->db->update('tamplet', $dat_resu);
			}

		}
		echo "successfully!";

	}

	
	
}
