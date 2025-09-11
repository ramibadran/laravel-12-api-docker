<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Models\Idempotency as Idemp;
use Symfony\Component\HttpFoundation\Response;



class Idempotency{
    public function handle($request, Closure $next){
        $user   = $request->attributes->has('auth_user') ? $request->attributes->get('auth_user') : null;
        $userId = $request->attributes->has('auth_user') ? $request->attributes->get('auth_user')->id : 0;

        if(!isset($request->request_key)){
            return responseJson(Response::HTTP_PRECONDITION_FAILED,__('messages.general.request_key_required'), $request, null, null, [], [], $user);
        }

        if(!empty(Idemp::getRequest($userId,$request->request_key))){
            return responseJson(Response::HTTP_TOO_MANY_REQUESTS,__('messages.general.too_many_requests'), $request, null, null, [], [], $user);
        }

        return $next($request);
    }
}

