<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{
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
                return redirect(env('FRONTEND_URL').'/login?error='.urlencode($error));
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
                    'phone_number' => '0',
                    'email_verified_at' => now(),
                    'google_id' => $userGoogle->getId()
                ]
            );

            // $token = $user->createToken('auth_token')->plainTextToken;
            Auth::login($user, true);

            // Redirect ke frontend dengan token
            return redirect(env('FRONTEND_URL').'/oauth/google/callback');

        } catch (Exception $e) {
            return redirect(env('FRONTEND_URL').'/login?error='.urlencode($e->getMessage()));
        }
    }

}