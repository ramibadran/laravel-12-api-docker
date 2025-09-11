<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user           = null;
        $serviceName    = 'auth';
        try{
            if (empty($request->header('Authorization'))) {
                return responseJson(Response::HTTP_UNAUTHORIZED,__('messages.general.authorization_header_missing'), $request, $serviceName, null, [], [], $user);
            }

            $user = JWTAuth::parseToken()->authenticate();

            if(!empty($user)){
                $request->attributes->set('auth_user', $user);
            }else{
                return responseJson(Response::HTTP_UNAUTHORIZED,__('messages.general.user_not_found_in_token'), $request, $serviceName, null, [], [], $user);
            }
        }catch (TokenExpiredException $e) {
            Log::error('TokenExpiredException: ' . $e->getMessage());
            return responseJson(Response::HTTP_UNAUTHORIZED, __('messages.general.token_expired'), $request, $serviceName, null, [], [], $user);
        }catch (TokenInvalidException $e) {
            Log::error('TokenInvalidException: ' . $e->getMessage());
            return responseJson(Response::HTTP_UNAUTHORIZED, __('messages.general.invalid_token_provided'), $request, $serviceName, null, [], [], $user);
        }catch (JWTException $e) {
            Log::error('JWTException: ' . $e->getMessage());
            return responseJson(Response::HTTP_UNAUTHORIZED, __('messages.general.error_parsing_token'), $request, $serviceName, null, [], [], $user);
        }catch (Exception $e) {
            Log::error('General Exception: ' . $e->getMessage());
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'), $request, $serviceName, null, [], [], $user);
        }
        return $next($request);
    }
}
