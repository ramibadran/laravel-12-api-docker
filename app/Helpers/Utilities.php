<?php

namespace App\Helpers;

use DateTimeZone;;
use DateTime;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Log;

class Utilities{
    
    public static function getTimeByTimeZone($originalTime,$timezone=null){
        return self::convertTimeTimeZone($originalTime,"GMT",($timezone)?:\request()->header("Time-Zone","Asia/Dubai"));
    }

    private static function convertTimeTimeZone($originalTime,$from,$to){
        $originalTimeZone   = new DateTimeZone($from);
        $originalDateTime   = new DateTime($originalTime, $originalTimeZone);
        $targetTimeZone     = new DateTimeZone($to);
        $originalDateTime->setTimezone($targetTimeZone);
        return $originalDateTime->format('Y-m-d H:i:s');
    }

    public static function getRemoteIp(){
        return isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    }

    public static function gen_uuid(){
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
    
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
    
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public static function generateUUIDv4(){
        // Generate 16 bytes (128 bits) of random data
        $data = random_bytes(16);

        // Set the version to 0100 (UUIDv4)
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set the variant to 10xx
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36-character UUID
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function generateUUID(){
        return (string) Str::uuid();
    }

    public static function generateRandomString($length = 6){
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }

    // Function to generate a two-word username
    public static function generateUsername(){
        $adjectives = [
            'Brave', 'Curious', 'Loyal', 'Creative', 'Strong', 'Happy', 'Noble', 'Friendly', 'Kind', 'Ambitious'
        ];

        $arabicNouns = [
            'Ali', 'Zara', 'Omar', 'Nour', 'Amir', 'Jasmine', 'Falcon', 'Desert', 'Ameerah', 'Sultan'
        ];
        return $adjectives[array_rand($adjectives)] . ' ' . $arabicNouns[array_rand($arabicNouns)];
    }

    public static function generateFirstName(){
        $arabicNouns = [
            'Ali', 'Zara', 'Omar', 'Nour', 'Amir', 'Jasmine', 'Falcon', 'Desert', 'Ameerah', 'Sultan'
        ];
        return $arabicNouns[array_rand($arabicNouns)];
    }

    public static function generateLastName(){
        $adjectives = [
            'Brave', 'Curious', 'Loyal', 'Creative', 'Strong', 'Happy', 'Noble', 'Friendly', 'Kind', 'Ambitious'
        ];
        return $adjectives[array_rand($adjectives)];
    }

    public static function fetchAndStoreImage($imageUrl,$isCloud=false,$path='profile_pictures/'){
        try{
            if(!filter_var($imageUrl, FILTER_VALIDATE_URL)){
                throw new Exception('Invalid URL');
            }

            $imageContent = file_get_contents($imageUrl);
            if($imageContent === false){
                throw new Exception('Failed to fetch the image');
            }

            $imageInfo = getimagesizefromstring($imageContent);
            if($imageInfo === false){
                throw new Exception('Unable to determine image type');
            }

            $extension = image_type_to_extension($imageInfo[2], false);
            $fileName  = time() . '_' . strtolower(self::generateRandomString(10)) . '.' . $extension;
            
            if($isCloud){
                $s3FullPath = $path . $fileName;

                if(Storage::disk('s3')->put($s3FullPath, $imageContent)){
                    //return Storage::disk('s3')->url($s3FullPath);
                    return $fileName;
                }else{
                    throw new Exception('Failed to upload image to S3 ' . $s3FullPath);
                }
            }else{
                $localFullPath = public_path($path . $fileName);

                if(!file_exists(dirname($localFullPath))){
                    mkdir(dirname($localFullPath), 0755, true);
                }

                if(file_put_contents($localFullPath, $imageContent)){
                    return $fileName;
                }else{
                    throw new Exception('Failed to save image locally');
                }
            }
        }catch(Exception $e){
            Log::error('Error fetching and storing image: ' . $e->getMessage());
            return null;
        }
    }

    public static function getDaysDifferenceFromNow($createdAt) {
        $createdAtDate  = new DateTime($createdAt);
        $now            = new DateTime();
        $difference     = $now->diff($createdAtDate);
        return $difference->days;
    }

    public static function checkDateEqual($sentDate,$lastBonusAt){
        $nowDate            = date('Y-m-d'); 
        $sentDateOnly       = date('Y-m-d', strtotime($sentDate));
        $lastBonusAtOnly    = date('Y-m-d', strtotime($lastBonusAt));

        if ($sentDateOnly === $nowDate && $sentDateOnly !== $lastBonusAtOnly) {
            return true;
        } else {
            return false;
        }
    }
}