<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Events\UpdateEvent;
use App\Helpers\Utilities;
use App\Http\Requests\V1\UserLoginRequest;
use App\Models\User;
use App\Models\UserDeviceToken;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Log;
use DB;
use Config;

class LoginController extends Controller{
    public function login(UserLoginRequest $request): JsonResponse{
        $user        = null;
        $serviceName = 'login';
        try{            
            $user   = User::getUserByEmail($request->email);
            if(!empty($user) && Hash::check($request->password, $user->password)) { 
                $authorization = [
                                    'token'         => Auth::login($user),
                                    'type'          => 'Bearer',
                                    'ttl'           => config('jwt.ttl'),
                                    'refresh_ttl'   => config('jwt.refresh_ttl')
                ];
                return responseJson(Response::HTTP_OK,__('messages.general.success'),$request,$serviceName,new UserResource($user),$authorization,[],$user);
            }else{
                return responseJson(Response::HTTP_OK,__('messages.general.error'),$request,$serviceName,null,[],[],$user);
            }
        }catch(QueryException $e){
            DB::rollback();
            Log::error($e);
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'),$request,$serviceName,null,[],[],$user);
        }catch(Exception $e){
            DB::rollback();
            Log::error($e);
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'),$request,$serviceName,null,[],[],$user);
        }
    }
    
    public function refresh(Request $request){
        $user        = null;
        $serviceName = 'refresh';
        try{
            if(!empty($request->header('Authorization'))){
                $authorization = [
                                    'token'         => Auth::refresh(),
                                    'type'          => 'Bearer',
                                    'ttl'           => config('jwt.ttl'),
                                    'refresh_ttl'   => config('jwt.refresh_ttl')
                ];
                return responseJson(Response::HTTP_OK,__('messages.general.success'),$request,$serviceName,null,$authorization,[],$user);
            }else{
                return responseJson(Response::HTTP_UNAUTHORIZED,"Token not Provided",$request,$serviceName,null,[],[],$user);
            }
        }catch (TokenExpiredException $e) {
            Log::error($e);
            return responseJson(Response::HTTP_UNAUTHORIZED,"Token is Expired",$request,$serviceName);
        }catch (TokenInvalidException $e) {
            Log::error($e);
            return responseJson(Response::HTTP_UNAUTHORIZED,"Invalid Token Provided",$request,$serviceName);
        }catch (JWTException $e) {
            Log::error($e);
            return responseJson(Response::HTTP_UNAUTHORIZED,"Invalid Token Provided",$request,$serviceName);
        }catch(QueryException $e){
            Log::error($e);
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'),$request,$serviceName);
        }catch(Exception $e){
            Log::error($e);
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'),$request,$serviceName);
        }   
    }
}
