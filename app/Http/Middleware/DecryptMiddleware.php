<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class DecryptMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('data')) {
            try {
                $apiKey = Auth::user()->api_key;
                $decryptedData = json_decode(Crypt::decrypt($request->input('data'), false, $apiKey), true);
                $request->merge($decryptedData);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Invalid encrypted data.'], 400);
            }
        }
        $response = $next($request);
        $responseData = $response->getData(true);
        $response->setContent(Crypt::encrypt(json_encode($responseData), false, $apiKey));
        return $response;
    }
}
