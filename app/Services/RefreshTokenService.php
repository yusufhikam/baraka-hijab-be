<?php

namespace App\Services;

use App\Models\RefreshToken;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RefreshTokenService{


    public function create(int $userId,string $token,$userAgent,$ipAddress,$expiresAt){

        $payload = JWTAuth::setToken($token)->getPayload();
        $jti = $payload['jti'] ?? throw new \Exception('jti not found');
        
        return RefreshToken::create([
            'user_id' => $userId,
            'jti' => $jti,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
            'expires_at' => $expiresAt
        ]);
    }


    /**
     * * validate refresh token
    */
    public function validate(int $userId, string $rawToken){
        try{

            $payload = JWTAuth::setToken($rawToken)->getPayload();

            if($payload['type'] !== 'refresh'){
                return null;
            }

            $jti = $payload['jti'] ?? null;

            if(!$jti){
                return null;
            }

            return RefreshToken::where('user_id', $userId)
                                ->where('jti', $jti)
                                ->whereNull('revoked_at')
                                ->where('expires_at', '>', now())
                                ->first();

        }catch(\Exception $e){
            return null;
        }
    }


    
    public function revoke(RefreshToken $token){
        $token->update([
            'revoked_at' => now()
        ]);
    }


    public function revokeAll($userId){
        RefreshToken::where('user_id', $userId)
                    ->whereNull('revoked_at')
                    ->update([
                        'revoked_at' => now()
                    ]);
    }
}