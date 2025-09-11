<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Config;
use Log;

class VerifyHMAC{

    public function __construct() {
        $this->secret = Config::get('custom.encrypt_key');
    }

    public function handle(Request $request, Closure $next){
        $serviceName = 'hmac';
        if(empty($request->header('X-Signature'))){
            return responseJson(Response::HTTP_UNAUTHORIZED,__('messages.general.unauthorized'),$request,$serviceName);
        }
        
        $message    = $request->getContent();
        $signature  = $request->header('X-Signature');

        if (!$this->isValidHMAC($message, $signature)) {
            return responseJson(Response::HTTP_UNAUTHORIZED,__('messages.general.unauthorized'),$request,$serviceName);
        }
        return $next($request);
    }

    private function isValidHMAC($message, $signature){
        $computedSignature = hash_hmac('sha256', $message, $this->secret);
        Log::info('Computed HMAC: ' . $computedSignature);
        Log::info('Incoming HMAC: ' . $signature);
        return hash_equals($computedSignature, $signature);
    }

}
