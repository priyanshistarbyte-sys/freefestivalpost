<?php

namespace App\Services;

class ImageService
{
    public static function compressImage($sourceUrl, $destinationUrl, $quality = 80)
    {
        $info = getimagesize($sourceUrl);
        
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($sourceUrl);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($sourceUrl);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($sourceUrl);
        } else {
            return false;
        }

        imagejpeg($image, $destinationUrl, $quality);
        imagedestroy($image);
        
        return $destinationUrl;
    }

    public static function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }

    public static function addWatermarkText($imagePath, $text, $fontPath, $fontSize, $color, $x, $y, $angle = 0)
    {
        $image = imagecreatefrompng($imagePath);
        $textColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        
        if (file_exists($fontPath)) {
            imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $fontPath, $text);
        } else {
            imagestring($image, 5, $x, $y, $text, $textColor);
        }
        
        imagepng($image, $imagePath);
        imagedestroy($image);
    }

    public static function overlayImage($backgroundPath, $overlayPath, $x, $y, $opacity = 100)
    {
        $background = imagecreatefrompng($backgroundPath);
        $overlay = imagecreatefrompng($overlayPath);
        
        list($overlayWidth, $overlayHeight) = getimagesize($overlayPath);
        
        if ($opacity < 100) {
            imagecopymerge($background, $overlay, $x, $y, 0, 0, $overlayWidth, $overlayHeight, $opacity);
        } else {
            imagecopy($background, $overlay, $x, $y, 0, 0, $overlayWidth, $overlayHeight);
        }
        
        imagepng($background, $backgroundPath);
        imagedestroy($background);
        imagedestroy($overlay);
    }
}