<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ScopesList;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;
use Config;

class SettingController extends Controller{
    public function generateSignature(Request $request){
        $message    = $request->getContent();
        
        Log::info('Normalized Message for HMAC: ' . $message);
        $signature  = generateHMAC($message, Config::get('custom.encrypt_key'));

        Log::info('Generated Signature: ' . $signature);
        return responseJson(Response::HTTP_OK,__('messages.general.success'),$request,null,$signature,[],[],null);
    }
}