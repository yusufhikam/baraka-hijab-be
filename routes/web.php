<?php

use App\Http\Controllers\Api\OauthController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::get('/auth/google/redirect', [OauthController::class, 'redirect']);
Route::get('/auth/google/callback', [OauthController::class, 'callback']);

// transaction midtrans
// Route::post('/midtrans/callback', [TransactionController::class, 'callbackMidtrans']);