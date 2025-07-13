<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\AddressController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\MidtransCallbackController;
use App\Http\Controllers\Api\OauthController;
use App\Http\Controllers\Api\RajaOngkirController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Laravel\Socialite\Facades\Socialite;



Route::get('/user', function (Request $request) {
    return $request->user()->only('id','name', 'email', 'role', 'phone_number');
})->middleware('auth:sanctum');

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    
    // Google Auth on web.php
    
});

Route::patch('/user/{user}', [UserController::class, 'updateProfile'])->middleware(['auth:sanctum', 'role:customer']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{product:slug}', [ProductController::class, 'show']);
Route::get('/products/new-arrivals', [ProductController::class, 'newArrivals']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/sub-categories/carousel', [SubCategoryController::class, 'carousel'])->name('api.carousel');
Route::get('/sub-categories', [SubCategoryController::class, 'index']);


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('/categories', CategoryController::class)->except(['index']);
    Route::apiResource('/products', ProductController::class)->except(['index']);
});

// CART [customer]
Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::get('/carts', [CartController::class, 'index']);
    Route::post('/carts', [CartController::class, 'store']);
    Route::post('/carts/sync', [CartController::class, 'syncFromLocalStorage']);
    Route::delete('/carts/{id}', [CartController::class, 'destroy']);
    Route::patch('/carts/{id}', [CartController::class, 'update']);
});

// RAJA ONGKIR
Route::get('/rajaongkir/destinations', [RajaOngkirController::class, 'searchDestination']);
Route::post('/rajaongkir/cost', [RajaOngkirController::class, 'cekOngkir']);
Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'getProvinsi']);
Route::get('/rajaongkir/kabupaten/{codeProvince}', [RajaOngkirController::class, 'getKabupaten']);
Route::get('/rajaongkir/kecamatan/{codeKabupaten}', [RajaOngkirController::class, 'getKecamatan']);
Route::get('/rajaongkir/kelurahan/{codeKecamatan}', [RajaOngkirController::class, 'getKelurahan']);


// ADDRESS [customer]
Route::apiResource('/addresses', AddressController::class)->middleware(['auth:sanctum', 'role:customer']);
// get address by id
Route::get('/address/{address}', [AddressController::class, 'show'])->middleware(['auth:sanctum', 'role:customer']);
// set primary address
Route::patch('/addresses/{address}/set-primary', [AddressController::class, 'setPrimary'])->middleware(['auth:sanctum', 'role:customer']);

// get primary address
Route::get('/addresses/user/primary-address', [AddressController::class, 'getPrimaryAddress'])->middleware(['auth:sanctum', 'role:customer']);


// TRANSACTION [customer] MIDTRANS
Route::post('/checkout', [TransactionController::class, 'store'])->middleware(['auth:sanctum', 'role:customer']);
Route::post('/midtrans-callback', [TransactionController::class, 'callback']);
// get 'pending' user transactions
Route::get('/user/transactions', [TransactionController::class, 'userTransactions'])->middleware(['auth:sanctum', 'role:customer']);
// get user history transactions [expired, canceled, paid/settlement]
Route::get('/user/transactions/history', [TransactionController::class, 'userTransactionHistory'])->middleware(['auth:sanctum', 'role:customer']);
// cancel transaction
Route::post('/user/transactions/cancel/{order_id}', [TransactionController::class, 'cancelTransaction'])->middleware(['auth:sanctum', 'role:customer']);