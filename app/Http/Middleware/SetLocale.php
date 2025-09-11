<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Log;
use App;

class SetLocale
{
    public function handle($request, Closure $next){
        $lang = 'ar';
        if(!empty($request->attributes->get('auth_user')) && empty($request->header('Accept-Language'))){
            $lang = $request->attributes->get('auth_user')->lang;
        }
        // Log::info("auth_user " . JWTAuth::parseToken()->authenticate());
        Log::info("Accept-Language " . $request->header('Accept-Language'));
        Log::info("Local " . $lang);
        $locale = $request->header('Accept-Language', $lang);
        App::setLocale($locale);
        // Log::info("Local " . $locale);
        // Log::info("Local " . App::getLocale());
        return $next($request);
    }
}
