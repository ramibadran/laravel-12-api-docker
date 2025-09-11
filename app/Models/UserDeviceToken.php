<?php
namespace App\Models;

use App\Traits\CanGetTableNameStatically;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\HasMany;


class UserDeviceToken extends Model{

    protected $fillable = [
        'user_id',
        'device_token',
        'device_type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function addUserToken($userId,$deviceToken,$deviceType){
        return self::updateOrCreate(
                    ['user_id'      => $userId],
                    ['device_token' => $deviceToken,'device_type'  => $deviceType]
        );
    }

    public static function addOrUpdate($userId,$deviceToken,$deviceType){
        return self::updateOrCreate(
            ['user_id' => $userId],
            [
                'device_token'  => $deviceToken,
                'device_type'   => $deviceType
            ]
        );
    }

    public static function getUserToken($userId){
        return self::where('user_id',$userId)->first();
    }
}