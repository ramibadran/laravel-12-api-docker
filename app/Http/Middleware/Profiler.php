<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Log;

class Profiler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $response = $next($request);
        Log::info('custom.app_env: ' . config('custom.app_env'));
        Log::info('custom.debug: ' . config('custom.debug'));
        if(config('custom.app_env') == 'local' && config('custom.debug')){
            if (
                $response instanceof JsonResponse &&
                app()->bound('debugbar') &&
                app('debugbar')->isEnabled() &&
                is_object($response->getData())
            ) {
                $response->setData($response->getData(true) + [
                    '_debugbar' => app('debugbar')->getData(),
                ]);
            }
        }

        return $response;
    }
}
