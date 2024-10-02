<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Encryption\Encrypter;
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
        if (!empty($request->get('data'))) {
            $user = Auth::user();
            if ($user && $request->bearerToken()) {
                $token = $request->bearerToken();
                $key = hash('sha256', $token, true);
                $encrypter = new Encrypter($key, 'AES-256-CBC');
                $dataToEncrypt = $request->input('data');
                $encryptedData = $encrypter->encrypt($dataToEncrypt);
                $request->merge(['data' => $encryptedData]);
            }
        }

        return $next($request);
    }
}
