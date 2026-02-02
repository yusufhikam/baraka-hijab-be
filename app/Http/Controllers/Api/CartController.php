<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\CartResource;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class CartController extends Controller
{

    /** @var JWTGuard $guard */
    protected $guard;
    protected $cartService;

    public function __construct(CartService $cartService){
        $this->guard = auth('api');
        $this->cartService = $cartService;
    }
    public function index()
    {
        $userId = $this->guard->id();

        // check if user is authenticated
        if(!$userId){
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. You need to login first.'
            ], 401);
        }

        // inject to service
        $carts = $this->cartService->getCartsByUserId($userId);

        return response()->json([
            'status' => true,
            'message' => 'Cart data fetched successfully',
            'data' => CartResource::collection($carts)
        ]);
    }

    // add data cart
    public function store(Request $request)
    {

        // make validation
        $validator = Validator::make($request->all(), [
            'product_variant_option_id' => 'required|exists:product_variant_options,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // display error message if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'error' => $validator->errors()
            ], 422);
        }
        
        // get payload
        $payload = $validator->validated();
        
        $userId = $this->guard->id();

        // check if user is authenticated
        if(!$userId){
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. You need to login first.'
            ], 401);
        }

        // inject to service
       $cart = $this->cartService->store($payload, $userId);

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully',
            'data' => new CartResource($cart)
        ]);
    }


    // untuk sinkronisasi data dari localstorage client simpan ke server
    public function syncFromLocalStorage(Request $request)
    {
        $request->validate([
            'carts' => 'required|array',
            'carts.*.product_variant_option_id' => 'required|exists:product_variant_options,id',
            'carts.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = $this->guard->id();

        // check if user is authenticated
        if(!$userId){
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. You need to login first.'
            ], 401);
        }

        $this->cartService->syncFromLocalStorage($request->carts, $userId);

        return response()->json([
            'success' => true,
            'message' => 'Cart synced successfully'
        ],200);
    }

    // patch data quantity cart berdasarkan Product Variant ID
    public function update(Request $request, $productVariantOptionId)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'error' => $validator->errors()
            ], 422);
        }

        $payload = $validator->validated();

        $userId = $this->guard->id();

        // check if user is authenticated
        if(!$userId){
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. You need to login first.'
            ], 401);
        }

        $cart = $this->cartService->update(
                                    $payload,
                                    $userId, 
                    $productVariantOptionId
                                    );

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Cart quantity updated successfully',
            'data' => new CartResource($cart)
        ]);
    }

    
    // todo : hapus data cart berdasarkan product variant id
    public function destroy($cartId)
    {
        $userId = $this->guard->id();

        $cart = $this->cartService->delete($userId, $cartId);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart deleted successfully'
        ], 200);
    }
}