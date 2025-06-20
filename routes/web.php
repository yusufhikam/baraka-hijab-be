<?php

use App\Http\Controllers\Api\OauthController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});



// Route::middleware(['web'])->group(function () {
    Route::get('/auth/google/redirect', [OauthController::class, 'redirect']);
    Route::get('/auth/google/callback', [OauthController::class, 'callback']);
// });
