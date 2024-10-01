<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class EncryptMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('post')) {
            $apiKey = Auth::user()->api_key;
            $requestData = $request->all();
            $encryptedData = Crypt::encrypt(json_encode($requestData), false, $apiKey);
            $request->merge(['data' => $encryptedData]);
        }

        return $next($request);
    }
}
