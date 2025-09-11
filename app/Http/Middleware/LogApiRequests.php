<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogApiRequests
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
        // Log the request
        $this->logRequest($request);

        // Proceed with the request
        $response = $next($request);

        // Log the response
        $this->logResponse($response);

        return $response;
    }

    /**
     * Log the request details.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function logRequest(Request $request)
    {
        $logData = [
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->getContent(),
        ];

        $separator = "#################### API Request ####################\n";
        Log::channel('api')->info($separator . json_encode($logData, JSON_PRETTY_PRINT));
    }

    /**
     * Log the response details.
     *
     * @param \Illuminate\Http\Response $response
     * @return void
     */
    protected function logResponse($response)
    {
        $logData = [
            'status' => $response->status(),
            'headers' => $response->headers->all(),
            'body' => $response->getContent(),
        ];
        
        $separator = "#################### API Response ####################\n";
        Log::channel('api')->info($separator . json_encode($logData, JSON_PRETTY_PRINT) . "####################################################################################################################################################################################\n\n");
    }
}