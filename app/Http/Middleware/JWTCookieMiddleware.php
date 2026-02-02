<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Symfony\Component\HttpFoundation\Response;

class JWTCookieMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // ambil access token dari cookie
        $token = $request->cookie('accessToken');

        // return 401 if access token not found
        if(!$token){
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Access token not found',
            ], 401);
        }

        // validasi access token
        try{

        // $request->headers->set('Authorization', "Bearer $token");

            /** @var JWTGuard $guard */

            $guard = auth('api');

            $guard->setToken($token);
            // cek apakah token valid
            $user = $guard->authenticate();

            if(!$user){
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Invalid token'
                ], 401);
            }               
            
            $request->setUserResolver(fn () => $user);
//             dd([
//     'cookies' => $request->cookies->all(),
//     'headers' => $request->headers->all(),
// ]);
            

        }catch(JWTException $e){
            // jika gagal return response 401
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Token invalid or expired'
            ], 401);
            
        }
        

        
        return $next($request);
    }
}