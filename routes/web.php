<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OauthController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/user', function (Request $request) {

    $user = $request->user()->only('id','name', 'email', 'role', 'phone_number', 'google_avatar');
    return response()->json([
        'success' => true,
        'message' => 'Current user data',
        'data' => $user
    ]);
})->middleware('auth:web');

Route::middleware('web')->prefix('auth')->group(function () {
    Route::post("/register", [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:web']);
    
    // Google Auth on web.php
    
});

// TRANSACTION CHECKOUT
Route::middleware([
    'web',
    'jwt.cookie',
    'role:customer',
])->group(function () {
    
    Route::post('/checkout/prepare', [CheckoutController::class, 'prepare']);

    Route::middleware(['checkout.active'])->group(function () {
        Route::post('/checkout/shipping', [CheckoutController::class, 'setShipping']);
        Route::get('/checkout', [CheckoutController::class, 'summary']);
        Route::post('/checkout', [TransactionController::class, 'store']);
        // clear checkout active session
        Route::post('/checkout/clear-active-session', [TransactionController::class, 'clearActiveCheckoutSession']);
    });


    Route::get('/checkout/remove-session', [CheckoutController::class, 'removeSession']);
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
})->middleware('web');

Route::withoutMiddleware('web')->group(function () {
    Route::get('/oauth/google/redirect', [OauthController::class, 'redirect']);
    Route::get('/oauth/google/callback', [OauthController::class, 'callback']);
});
// http://localhost:8000/oauth/google/callback

// transaction midtrans
// Route::post('/midtrans/callback', [TransactionController::class, 'callbackMidtrans']);