<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\AuthService;
use App\Services\CheckoutService;
use App\Services\RefreshTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;


class AuthController extends RoutingController
{

    /** @var JWTGuard $guard */
    protected $guard;
    protected AuthService $authService;

    public function __construct(AuthService $authService){
        $this->guard = auth('api');
        
        $this->authService = $authService;
    }

    public function register(Request $request) :JsonResponse{
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'phone_number' => 'required|numeric',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:password',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $credentials = $validator->validated();

        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'phone_number' => $credentials['phone_number'],
            'password' => bcrypt($credentials['password']),
        ]);


        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }


    public function login(Request $request)
    {

        // /** @var JWTGuard $guard */
        // $guard = auth('api');


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        
        if (!$accessToken = $this->guard->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'The provided credentials do not match our records.'], 401
            );
        }

        /** @var \PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject $user */
        $user = $this->guard->user();
        $userId = $this->guard->id();

        // todo : buat refresh token 
        $refreshToken = $this->guard
                        ->claims(['type' => 'refresh'])
                        ->setTTL(60 * 24 * 7)// for 7 days
                        ->login($user);

        // todo: simpan HASH token to DB
        $refreshTokenService = new RefreshTokenService();

        $refreshTokenService->create(
            $userId,
            $refreshToken,
            $request->userAgent(),
            $request->ip(),
            now()->addDays(7)
        );

        $generatedTokenCookie = $this->authService->generateTokenCookie($accessToken, $refreshToken);

        return response()->json([
                    'status' => true,
                    'message' => 'Login Successfull',
                    'data' => [
                        // 'token_type' => 'Bearer',
                        // 'access_token' => $accessToken,
                        'expires_in' => (int) env('JWT_TTL', 60),
                        'user'=> $user
                    ]
                ])
                ->withCookie($generatedTokenCookie['accessToken'])
                ->withCookie($generatedTokenCookie['refreshToken']);
    }

    public function logout(Request $request)
    {
        $this->guard->logout();

        // todo: check refresh token 
        $refreshToken = $request->cookie('refreshToken');
        $refreshTokenService = new RefreshTokenService();

        // todo: revoke refresh token if exist
        if($refreshToken && $this->guard->setToken($refreshToken)->check()){
            $userId = $this->guard->id();

            $record = $refreshTokenService->validate(
                $userId,
                $refreshToken);

            if($record) $refreshTokenService->revoke($record);
            
        }

        // todo: delete cookie
        $cookieRefreshToken = cookie()->forget('refreshToken');
        $cookieAccessToken = cookie()->forget('accessToken');
        $activeCheckoutSession = cookie()->forget('checkout_active_session');

        $forgetCheckoutSession = new CheckoutService();
        $forgetCheckoutSession->removeSession();

        return response()->json([
            'status' => true,
            'message' => 'Logout Successfull',
        ])
        ->withoutCookie('accessToken')
        ->withoutCookie('refreshToken')
        ->withoutCookie('checkout_active_session');
    }

    public function refresh(Request $request){

        $refreshTokenService = new RefreshTokenService();

        // todo: check refresh token
        $refreshToken = $request->cookie('refreshToken');

        // if refresh token not found return 401
        if(!$refreshToken) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Refresh token invalid or expired.'
            ], 401);
        }

        try{
            // 
            $payload = $this->guard->setToken($refreshToken)->getPayload();

            // validasi tipe token
            if($payload->get('type') !== 'refresh'){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid refresh token type'
                ], 401);
            }

            /** @var \PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject $user */
            $user = $this->guard->user();
            $userId = $this->guard->id();

            // todo: validate token dari DB
            $record = $refreshTokenService->validate(
                $userId,
                $refreshToken
            );

            if(!$record){
                // $this->guard->logout();
                
                return response()->json([
                            'status' => false,
                            'message' => "Refresh token invalid"
                        ], 401)
                        ->withoutCookie('refreshToken');

            }

            // todo: revoke old token
            $refreshTokenService->revoke($record);

            // todo: generate access token baru
            $newAccessToken = $this->guard->login($user);

            // todo: generate refresh token baru
            $newRefreshToken = $this->guard
                                ->claims([
                                    'type' => 'refresh',
                                    'jti' => (string) Str::uuid()
                                ])
                                ->setTTL(60 * 24 * 7)
                                ->login($user);

            // todo: create new refresh token to DB
            $refreshTokenService->create(
                $userId,
                $newRefreshToken,
                $request->userAgent(),
                $request->ip(),
                now()->addDays(7)
            );

            // todo: buat cookie refresh token
            $tokens = $this->authService->generateTokenCookie(
                $newAccessToken,
                $newRefreshToken
            );

            return response()->json([
                    'status' => true,
                    'message' => 'Token refreshed.',
                    'data' => [
                        // 'token_type' => 'Bearer',
                        // 'access_token' => $newAccessToken,
                        'expires_in' => env('JWT_TTL', 60),
                    ]
                ])
                ->withCookie($tokens['accessToken'])
                ->withCookie($tokens['refreshToken']);

            
        }catch(JWTException $e){
            return response()->json([
                'status' => false,
                'message' => 'Token Expired'
            ], 401);
        }
    }



    public function getMe(Request $request) {
    
        $user = $request->user()->only('id','name', 'email', 'role', 'phone_number', 'google_avatar');

        return response()->json([
            'status' => true,
            'message' => "Successfully fetched current user",
            'data' => $user
        ]);
    }
}