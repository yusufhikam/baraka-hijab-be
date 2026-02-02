<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\AddressController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\RajaOngkirController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {

    $user = $request->user()->only('id','name', 'email', 'role', 'phone_number', 'google_avatar');
    return response()->json([
        'success' => true,
        'message' => 'Current user data',
        'data' => $user
    ]);
})->middleware('auth:api');

Route::prefix('auth')->group(function () {
    Route::post("/register", [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/refresh', [AuthController::class, 'refresh']);
    
    Route::middleware(['jwt.cookie'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/me', [AuthController::class, 'getMe']);
    });

    // Google Auth on web.php
});


Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['csrf' => csrf_token()]);
});



Route::patch('/user/{user}', [UserController::class, 'updateProfile'])->middleware(['auth:sanctum', 'role:customer']);


// get product by product_variant_option_id for localstorage Cart
Route::get('/products/variant-options', [ProductController::class, 'productByProductVariantOptionId']);
Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
Route::get('/product/{product:slug}', [ProductController::class, 'show']);
Route::get('/products/new-arrivals', [ProductController::class, 'newArrivals']);
Route::get('/products/similar-product/{id}', [ProductController::class, 'similarProducts']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/sub-categories/carousel', [SubCategoryController::class, 'carousel'])->name('api.carousel');
Route::get('/sub-categories', [SubCategoryController::class, 'index']);


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('/categories', CategoryController::class)->except(['index']);
    Route::apiResource('/products', ProductController::class)->except(['index']);
});

// CART [customer]
Route::middleware(['jwt.cookie', 'role:customer'])->group(function () {
    Route::get('/carts', [CartController::class, 'index']);
    Route::post('/carts', [CartController::class, 'store']);
    Route::post('/carts/sync', [CartController::class, 'syncFromLocalStorage']);
    Route::delete('/carts/{id}', [CartController::class, 'destroy']);
    Route::patch('/carts/{id}', [CartController::class, 'update']);
});

// RAJA ONGKIR
Route::prefix('rajaongkir')->group(function () {
    Route::get('/destinations', [RajaOngkirController::class, 'searchDestination']);
    Route::post('/cost', [RajaOngkirController::class, 'cekOngkir']);
    Route::get('/provinces', [RajaOngkirController::class, 'getProvinsi'])->name('rajaongkir.provinces');
    // Route::get('/kabupaten/{codeProvince}', [RajaOngkirController::class, 'getKabupaten']);
    Route::get('/cities/{province_id}', [RajaOngkirController::class, 'getKabupaten'])->name('rajaongkir.kabupaten');
    Route::get('/districts/{city_id}', [RajaOngkirController::class, 'getKecamatan'])->name('rajaongkir.kecamatan');
    Route::get('/sub-districts/{district_id}', [RajaOngkirController::class, 'getKelurahan'])->name('rajaongkir.kelurahan');
});


// ADDRESS [customer]
Route::middleware(['jwt.cookie', 'role:customer'])->group(function () {
    Route::apiResource('/addresses', AddressController::class);
    // Route::get('/addresses/{id}', [AddressController::class, 'show']);
    Route::patch('/addresses/{id}/set-primary', [AddressController::class, 'setPrimary']);
    // Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    // get primary address
    Route::get('/addresses/user/primary-address', [AddressController::class, 'getPrimaryAddress']);
});



// TRANSACTION [customer] MIDTRANS

Route::middleware(['jwt.cookie', 'role:customer'])->group(function (){

    Route::post('/checkout/prepare', [CheckoutController::class, 'prepare']);
    Route::post('/checkout/shipping', [CheckoutController::class, 'shipping']);
    Route::get('/checkout', [CheckoutController::class, 'summary']);
    Route::post('/checkout', [TransactionController::class, 'store']);
    Route::get('/user/transaction/validate/{order_id}', [TransactionController::class, 'validateTransactionOwnership']);

    // get 'pending' user transactions
    Route::get('/user/transactions', [TransactionController::class, 'userTransactions'])->name('api.user.transactions');
    // get user history transactions [expired, canceled, paid/settlement]
    Route::get('/user/transactions/history', [TransactionController::class, 'userTransactionHistory']);
    // cancel transaction
    Route::post('/user/transactions/cancel/{order_id}', [TransactionController::class, 'cancelTransaction']);
    
});    

    // callback midtrans after create transaction
    Route::post('/midtrans-callback', [TransactionController::class, 'callback']);
   