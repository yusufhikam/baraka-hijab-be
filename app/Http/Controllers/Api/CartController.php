<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $carts = Cart::with(['user', 'productVariant', 'productVariant.product', 'productVariant.product.subCategory.category'])->where('user_id', $user->id)->latest()->get();

        return CartResource::collection($carts);
    }

    // add data cart
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'error' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)->where('product_variant_id', $request->product_variant_id)->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {

            $cart = Cart::create([
                'user_id' => $user->id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return new CartResource($cart);
    }


    // untuk sinkronisasi data dari localstorage client simpan ke server
    public function syncFromLocalStorage(Request $request)
    {
        $request->validate([
            'carts' => 'required|array',
            'carts.*.product_variant_id' => 'required|exists:product_variants,id',
            'carts.*.quantity' => 'required|integer|min:1',
        ]);


        $user = Auth::user();

        foreach ($request->carts as $item) {
            $existing = Cart::where('user_id', $user->id)->where('product_variant_id', $item['product_variant_id'])->first();

            if ($existing) {
                $existing->quantity += $item['quantity'];
                $existing->save();
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity']
                ]);
            }
        }

        return response()->json([
            'message' => 'Cart synced successfully'
        ]);
    }

    // patch data quantity cart berdasarkan Product Variant ID
    public function update(Request $request, $productVariantId)
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
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)->where('product_variant_id', $productVariantId)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart item not found'
            ], 404);
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return new CartResource($cart);
    }
    // hapus data cart berdasarkan product variant id
    public function destroy($productVariantId)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('product_variant_id', $productVariantId)->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart item not found'
            ], 404);
        }


        $cart->delete();

        return response()->json([
            'message' => 'Cart deleted successfully'
        ], 200);
    }
}