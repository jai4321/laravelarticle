<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Encryption\Encrypter;
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
        if (!empty($request->get('data'))) {
            $user = Auth::user();
            if ($user && $request->bearerToken()) {

                try {
                    $token = $request->bearerToken();
                    $key = hash('sha256', $token, true);
                    $encrypter = new Encrypter($key, 'AES-256-CBC');
                    $dataToDecrypt = $request->input('data');
                    $decryptedData = $encrypter->decrypt($dataToDecrypt);
                    $request->merge(['data' => $decryptedData]);
                    $response = $next($request);
                    return $response;
                } catch (\Throwable $e) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], 500);
                }
            }
        }

    }
}
