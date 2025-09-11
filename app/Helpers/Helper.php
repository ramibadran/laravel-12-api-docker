<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\Idempotency;
use App\Events\RequestEvent;

function generateHMAC($message, $secret){
    return hash_hmac('sha256', $message, $secret);
}

function responseJson($status, $message, $request = null, $name = null , $data = null,$authorization=[],$headers = [],$user = null,$logMe=null): JsonResponse{
    if(!is_null($data)){
        $response = ['message' => $message,'data' => $data];
        if(!empty($authorization)){
            $response = ['message' => $message,'data' => $data,'authorization'=>$authorization];
        }
    }else{
        $response = ['message' => $message];
        if(!empty($authorization)){
            $response = ['message' => $message,'authorization'=>$authorization];
        }
    }

    $res = response()->json($response, $status);
    foreach($headers as $key => $header){
        $res->header($key,$header);
    }

    if(!is_null($logMe)){
        //Idempotency::addRequest($user,$status,$name,$message,$request,$res);
        //will enable when we have a microservice for api logging
        //event(new RequestEvent($user,$status,$message,$request,$name,$res));
    }
    return $res;
}

function middleWare() {
    //echo Config::get('app.env');exit;
    if(config('custom.app_env') == 'production'){
        return ['throttle:api','local'];
    }else {
        return ['throttle:api','local','profiler'];
    }
}




