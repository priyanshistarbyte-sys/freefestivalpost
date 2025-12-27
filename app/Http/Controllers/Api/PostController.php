<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Tamplet;
use App\Models\Makepost;

class PostController extends Controller
{
    public function makePostByUser(Request $request)
    {
        $token = $request->input('token');
        $user_id = $request->input('user_id');
        
        if (!$this->checkToken($user_id, $token)) {
            return response()->json([
                'status' => false,
                'message' => 'User is not authorized to use.',
                'data' => []
            ]);
        }

        $logo = null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('uploads/images/business_logo', 'public');
        }

        $result = [
            'user_id' => $user_id,
            'logo' => $logo,
            'business_name' => ucwords($request->input('business_name')),
            'name' => ucwords($request->input('name')),
            'mobile1' => $request->input('mobile1'),
            'mobile2' => $request->input('mobile2'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
            'address' => ucwords($request->input('address')),
            'tamplate_id' => $request->input('tamplate_id'),
            'birthdayPhoto' => $request->input('birthdayPhoto'),
            'birthdayName' => ucwords($request->input('birthdayName')),
        ];

        $total_today_post_limit = 5;
        $user_paid = $this->userCheckPaidFree($user_id);

        if ($user_paid) {
            $userPostUrl = $this->makePost($result);
            return response()->json([
                'status' => true,
                'message' => 'Result Successfully get!....',
                'data' => $userPostUrl
            ]);
        } else {
            $totalUserPostCount = $this->countUserPost($user_id);
            if ($totalUserPostCount >= $total_today_post_limit) {
                return response()->json([
                    'status' => false,
                    'message' => "Today your limit is over. Daily {$total_today_post_limit} Post. Please go to Premium",
                    'data' => []
                ]);
            } else {
                $userPostUrl = $this->makePost($result);
                return response()->json([
                    'status' => true,
                    'message' => 'Result Successfully get!....',
                    'data' => $userPostUrl
                ]);
            }
        }
    }

    private function makePost($result)
    {
        if (empty($result)) return false;

        $business_logo = $result['logo'];
        $personName = $result['name'];
        $userbusiness_name = $result['business_name'];
        $business_email = $result['email'];
        
        $business_mobile = '';
        if ($result['mobile2'] && $result['mobile1']) {
            $business_mobile = "+91 " . $result['mobile1'] . ' / ' . "+91 " . $result['mobile2'];
        } elseif ($result['mobile1']) {
            $business_mobile = "+91 " . $result['mobile1'];
        } elseif ($result['mobile2']) {
            $business_mobile = "+91 " . $result['mobile2'];
        }

        $business_website = $result['website'];
        $business_address = $result['address'];
        $birthdayPhoto = $result['birthdayPhoto'];
        $birthdayName = $result['birthdayName'];

        $template = Tamplet::find($result['tamplate_id']);
        if (!$template) return false;

        $userNewFileName = $this->copyTemplate($template->path, $result['tamplate_id'], $result['user_id']);
        if (!$userNewFileName) return false;

        if (in_array($template->type, [2, 5]) && $birthdayPhoto) {
            $this->mergeBirthdayPhoto($userNewFileName, $birthdayPhoto, $template, $birthdayName);
        }

        $templatePath = public_path("media/upload/" . $userNewFileName);

        $this->addTextElements($templatePath, $template, [
            'email' => $business_email,
            'website' => $business_website,
            'mobile' => $business_mobile,
            'address' => $business_address,
            'personName' => $personName,
            'businessName' => $userbusiness_name
        ]);

        if ($business_logo && $template->logo_pos) {
            $this->addLogo($templatePath, $business_logo, $template->logo_pos);
        } elseif (!$business_logo && $template->logo_pos && $userbusiness_name) {
            $this->addBusinessNameAsLogo($templatePath, $userbusiness_name, $template);
        }

        Makepost::create([
            'filename' => $userNewFileName,
            'user_id' => $result['user_id'],
            'tamplate_id' => $result['tamplate_id'],
            'created_at' => now()
        ]);

        return url('media/upload/' . $userNewFileName);
    }

    private function addTextElements($templatePath, $template, $data)
    {
        if (empty($template->email_pos) && !empty($template->website_pos)) {
            $emailWeb = '';
            if ($data['email'] && $data['website']) {
                $emailWeb = $data['website'] . '  ||  ' . $data['email'];
            } elseif ($data['email']) {
                $emailWeb = $data['email'];
            } elseif ($data['website']) {
                $emailWeb = $data['website'];
            }
            if ($emailWeb) {
                $this->addTextToImage($templatePath, $emailWeb, $template, $template->website_pos);
            }
        } else {
            if ($data['email'] && $template->email_pos) {
                $this->addTextToImage($templatePath, $data['email'], $template, $template->email_pos);
            }
            if ($data['website'] && $template->website_pos) {
                $this->addTextToImage($templatePath, $data['website'], $template, $template->website_pos);
            }
        }

        if ($data['mobile'] && $template->mobile_pos) {
            $this->addTextToImage($templatePath, $data['mobile'], $template, $template->mobile_pos);
        }

        if ($data['address'] && $template->address_pos) {
            $this->addTextToImage($templatePath, $data['address'], $template, $template->address_pos);
        }

        if ($data['personName'] && $template->name_pos) {
            $this->addTextToImage($templatePath, $data['personName'], $template, $template->name_pos);
        }
    }

    private function addTextToImage($templatePath, $text, $template, $position)
    {
        $positionParts = explode('_', $position);
        $alignment = explode('-', $positionParts[0]);
        
        $fontPath = public_path('assets/fonts/' . $template->font_type);
        $fontSize = $template->font_size;
        $fontColor = $this->hexToRgb($template->font_color);
        
        $image = imagecreatefrompng($templatePath);
        $color = imagecolorallocate($image, $fontColor[0], $fontColor[1], $fontColor[2]);
        
        $x = $positionParts[1] ?? 0;
        $y = $positionParts[2] ?? 0;
        
        if (file_exists($fontPath)) {
            imagettftext($image, $fontSize, 0, $x, $y, $color, $fontPath, $text);
        } else {
            imagestring($image, 5, $x, $y, $text, $color);
        }
        
        imagepng($image, $templatePath);
        imagedestroy($image);
    }

    private function addLogo($templatePath, $logoName, $position)
    {
        $logoPath = storage_path('app/public/' . $logoName);
        if (!file_exists($logoPath)) return;

        $positionParts = explode('_', $position);
        $x = $positionParts[1] ?? 0;
        $y = $positionParts[2] ?? 0;

        $template = imagecreatefrompng($templatePath);
        $logo = imagecreatefrompng($logoPath);
        
        list($logoWidth, $logoHeight) = getimagesize($logoPath);
        
        imagecopy($template, $logo, $x, $y, 0, 0, $logoWidth, $logoHeight);
        
        imagepng($template, $templatePath);
        imagedestroy($template);
        imagedestroy($logo);
    }

    private function copyTemplate($filename, $templateId, $userId)
    {
        $sourcePath = public_path("media/template/" . $filename);
        if (!file_exists($sourcePath)) return false;

        $newFileName = time() . '_' . $templateId . '_' . $userId . '.png';
        $destinationPath = public_path("media/upload/" . $newFileName);

        if (copy($sourcePath, $destinationPath)) {
            return $newFileName;
        }
        return false;
    }

    private function checkToken($userId, $token)
    {
        if (!$userId || !$token) return false;
        
        return DB::table('token')
            ->where('user_id', $userId)
            ->where('token', $token)
            ->exists();
    }

    private function userCheckPaidFree($userId)
    {
        return Admin::where('id', $userId)
            ->where('planStatus', 2)
            ->where('ispaid', 1)
            ->where('expdate', '>', now()->format('Y-m-d'))
            ->exists();
    }

    private function countUserPost($userId)
    {
        return Makepost::where('user_id', $userId)
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->count();
    }

    private function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }

    private function mergeBirthdayPhoto($templateFileName, $birthdayPhoto, $template, $birthdayName)
    {
        $templatePath = public_path("media/upload/" . $templateFileName);
        $birthdayPhotoPath = public_path('media/birthday_user/' . $birthdayPhoto);
        
        if (!file_exists($birthdayPhotoPath)) return;

        $templateImage = imagecreatefrompng($templatePath);
        $birthdayImage = imagecreatefrompng($birthdayPhotoPath);
        
        $photoPos = explode('_', $template->birthdayPhoto_pos);
        $x = $photoPos[0] ?? 0;
        $y = $photoPos[1] ?? 0;
        
        list($birthdayWidth, $birthdayHeight) = getimagesize($birthdayPhotoPath);
        
        imagecopy($templateImage, $birthdayImage, $x, $y, 0, 0, $birthdayWidth, $birthdayHeight);
        
        if ($birthdayName && $template->birthdayName_pos) {
            $namePos = explode('_', $template->birthdayName_pos);
            $fontInfo = explode(',', $template->birthday_font);
            
            $fontPath = public_path('assets/fonts/' . ($fontInfo[0] ?? 'arial.ttf'));
            $fontSize = $fontInfo[1] ?? 20;
            $color = imagecolorallocate($templateImage, 
                $fontInfo[2] ?? 0, 
                $fontInfo[3] ?? 0, 
                $fontInfo[4] ?? 0
            );
            
            $textX = $namePos[0] ?? 0;
            $textY = $namePos[1] ?? 0;
            $rotation = $namePos[2] ?? 0;
            
            if (file_exists($fontPath)) {
                imagettftext($templateImage, $fontSize, $rotation, $textX, $textY, $color, $fontPath, $birthdayName);
            }
        }
        
        imagepng($templateImage, $templatePath);
        imagedestroy($templateImage);
        imagedestroy($birthdayImage);
        
        if (file_exists($birthdayPhotoPath)) {
            unlink($birthdayPhotoPath);
        }
    }

    private function addBusinessNameAsLogo($templatePath, $businessName, $template)
    {
        $positionParts = explode('_', $template->logo_pos);
        $alignment = $positionParts[0];
        
        $offsets = [
            'top-left' => [20, 120],
            'top-center' => [0, 120],
            'top-right' => [-20, 120],
            'middle-left' => [20, 0],
            'middle-center' => [0, 0],
            'middle-right' => [-20, 0],
            'bottom-left' => [20, -200],
            'bottom-center' => [0, -200],
            'bottom-right' => [-20, -200],
        ];
        
        $offset = $offsets[$alignment] ?? [20, 120];
        
        $image = imagecreatefrompng($templatePath);
        $fontPath = public_path('assets/fonts/' . $template->font_type);
        $color = imagecolorallocate($image, ...$this->hexToRgb($template->font_color));
        
        if (file_exists($fontPath)) {
            imagettftext($image, 38, 0, $offset[0], $offset[1], $color, $fontPath, $businessName);
        } else {
            imagestring($image, 5, $offset[0], $offset[1], $businessName, $color);
        }
        
        imagepng($image, $templatePath);
        imagedestroy($image);
    }
}