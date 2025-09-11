<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use App\Http\Resources\V1\UserResource;
use App\Http\Collections\V1\UserCollection;
use App\Http\Requests\V1\UserRegisterRequest;
use App\Http\Requests\V1\UserUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDeviceToken;
use Illuminate\Database\QueryException;
use App\Helpers\Utilities;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Log;
use DB;
use Config;

class UserController extends Controller{

    public function create(UserRegisterRequest $request): JsonResponse{
        $user        = null;
        $serviceName = 'create';
        try{
            DB::beginTransaction();

            $data = [
                    'first_name'        => $request->first_name,
                    'last_name'         => $request->last_name,
                    'password'          => Hash::make($request->password),
                    'email'             => $request->email,
                    'created_ip'        => Utilities::getRemoteIp(),
                    'last_login_date'   => now(),
                    'last_login_ip'     => Utilities::getRemoteIp(),
            ];

            $user = User::create($data);
            UserDeviceToken::addUserToken($user->id,$request->device_token,$request->device_type);
            DB::commit();
            
            if(!empty($user)){
                return responseJson(Response::HTTP_CREATED,HTTP_OK,__('messages.general.created'),$request,$serviceName,new UserResource($user),null,[],$user);
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

    public function profile(Request $request){
        $user        = null;
        $serviceName = 'profile';
        try{
            if(!empty($request->header('Authorization'))){
                $user = JWTAuth::parseToken()->authenticate();
                if($user){
                    return responseJson(Response::HTTP_OK,__('messages.general.success'),$request,$serviceName,new UserResource($user),[],[],$user);
                }
            }elseif(!empty($request->id)){ 
                $user   = User::getUserById($request->id); 
                if($user){
                    $authorization = [
                                        'token'         => Auth::login($user),
                                        'type'          => 'Bearer',
                                        'ttl'           => config('jwt.ttl'),
                                        'refresh_ttl'   => config('jwt.refresh_ttl')
                    ];
                    return responseJson(Response::HTTP_OK,__('messages.general.success'),$request,$serviceName,new UserResource($user),$authorization,[],$user);
                }
            }else{                
                return responseJson(Response::HTTP_UNAUTHORIZED,"Invalid Data Provided",$request,$serviceName,null,[],[],$user);
            }
            return responseJson(Response::HTTP_OK,"Profile Not Exist",$request,$serviceName,null,[],[],$user);
        }catch (TokenExpiredException $e) {
            Log::error($e);
            return responseJson(Response::HTTP_UNAUTHORIZED,"Token is Expired",$request,$serviceName,null,[],[],$user);
        }catch (TokenInvalidException $e) {
            Log::error($e);
            return responseJson(Response::HTTP_UNAUTHORIZED,"Invalid Token Provided",$request,$serviceName,null,[],[],$user);
        }catch (JWTException $e) {
            Log::error($e);
            return responseJson(Response::HTTP_UNAUTHORIZED,"Invalid Token Provided",$request,$serviceName,null,[],[],$user);
        }catch(QueryException $e){
            Log::error($e);
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'),$request,$serviceName,null,[],[],$user);
        }catch(Exception $e){
            Log::error($e);
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'),$request,$serviceName,null,[],[],$user);
        }  
    }

    public function users(Request $request){
        $user        = null;
        $serviceName = 'users';
        try{
            $users  = User::getUsers(); 
            return responseJson(Response::HTTP_OK,__('messages.general.success'),$request,$serviceName,new UserCollection($users),null,[],$user);            
        }catch(QueryException $e){
            Log::error($e);
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'),$request,$serviceName,null,[],[],$user);
        }catch(Exception $e){
            Log::error($e);
            return responseJson(Response::HTTP_INTERNAL_SERVER_ERROR,__('messages.general.unexpected_error'),$request,$serviceName,null,[],[],$user);
        }  
    }

    public function update(UserUpdateRequest $request): JsonResponse{
        $user        = null;
        $serviceName = 'update_profile';
        try{
            $user   = $request->attributes->get('auth_user');
            if(!empty($user)){
                DB::beginTransaction();
                
                $user->first_name   = $request->first_name;
                $user->last_name    = $request->last_name;
                $user->save();
                DB::commit();
                $user->refresh();
                return responseJson(Response::HTTP_OK,__('messages.general.success'),$request,$serviceName,null,[],[],$user);
            }
            return responseJson(Response::HTTP_UNAUTHORIZED,"Invalid Data Provided",$request,$serviceName,null,[],[],$user);
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
}
