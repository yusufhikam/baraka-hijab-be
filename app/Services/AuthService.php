<?php

namespace App\Services;


class AuthService {
    
    public function generateTokenCookie(string $accessToken, $refreshToken){
        $cookieRefreshToken = cookie(
            'refreshToken',
            $refreshToken,
            60 * 24 * 7,
            '/',
            null,
            env('SESSION_SECURE_COOKIE', false),
            true,
            false,
            'Lax'
        );
        
        $cookieAccessToken = cookie(
            'accessToken',
            $accessToken,
            env('JWT_TTL', 60),
            '/',
            null,
            env('SESSION_SECURE_COOKIE', false),
            true,
            false,
            'Lax'
        );


        return [
            'accessToken' => $cookieAccessToken,
            'refreshToken' => $cookieRefreshToken
        ];
    }
}