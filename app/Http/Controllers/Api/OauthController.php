<?php

namespace App\Http\Controllers\Api;

use App\Services\RefreshTokenService;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class OauthController extends Controller
{

    protected AuthService $authService;
    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }
    public function redirect(){
        /** @var \Laravel\Socialite\Two\GoogleProvider  */

        $google = Socialite::driver('google');
        return $google->stateless()->redirect();
    }

    public function callback(Request $request){
        try {
            if ($request->has('error')) {
                $error = $request->error === 'access_denied'
                    ? 'Login dibatalkan'
                    : $request->error;
                return redirect(env('FRONTEND_URL').'/auth/login?error='.urlencode($error));
            }

            /** @var \Laravel\Socialite\Two\GoogleProvider  */
            $google = Socialite::driver('google');

            $userGoogle = $google->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $userGoogle->getEmail()],
                [
                    'name' => $userGoogle->getName(),
                    'password' => bcrypt(uniqid()),
                    'role' => 'customer',
                    'phone_number' => '',
                    'email_verified_at' => now(),
                    'google_id' => $userGoogle->getId(),
                    'google_avatar' => $userGoogle->getAvatar()
                ]
            );


            /** @var JWTGuard $guard */
            $guard = auth('api');

            $accessToken = $guard->fromUser($user);

            $refreshToken = $guard->claims(['type' => 'refresh'])
                                ->setTTL(60 * 24 * 7) //for 7 days
                                ->fromUser($user);
            
            // simpan refresh token ke DB
            $refreshTokenService = new RefreshTokenService();

            $refreshTokenService->create(
                $user->id,
                $refreshToken,
                $request->userAgent(),
                $request->ip(),
                now()->addDays(7)
            );

            $generatedTokenCookie = $this->authService->generateTokenCookie($accessToken, $refreshToken);


            // Redirect ke frontend dengan token
            return redirect()->away(env('FRONTEND_URL').'/oauth/google/callback?success=true')
                    ->withCookie($generatedTokenCookie['accessToken'])
                    ->withCookie($generatedTokenCookie['refreshToken']);

        } catch (Exception $e) {
            return redirect(env('FRONTEND_URL').'/auth/login?error='.urlencode($e->getMessage()));
        }
    }

}