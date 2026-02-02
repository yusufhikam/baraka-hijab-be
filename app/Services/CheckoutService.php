<?php  

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\ProductVariantOption;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CheckoutService {

    protected $guard;

    public function __construct(){
        $this->guard = auth('api');
    }

    public function CheckoutPrepare(Request $request){
        $userId = $this->guard->id();

        // resolve items
        if($request->source === 'cart'){
            $items = Cart::where('user_id', $userId)->get()
                        ->map(fn($cart) => [
                            'product_variant_option_id' => $cart->product_variant_option_id,
                            'quantity' => $cart->quantity
                        ]);
            
        } else {
            $items = collect($request->items);
        }

        // NORMALIZE & VALIDATE
        $itemsNormalized = $items->map(function($item) {
            $variantOption = ProductVariantOption::with('productVariant.product')->findOrFail($item['product_variant_option_id']);

            if($variantOption->stock < $item['quantity']){
                throw new \Exception('Out of stock');
            }


            $productPrice = $variantOption->productVariant->product->price;

            return [
                'product_variant_option_id' => $variantOption->id,
                'quantity' => $item['quantity'],
                'price' => $productPrice,
                'subtotal' => $productPrice * $item['quantity']
            ];
        });

        // create session for source context
        session([
            'checkout.source' => $request->source,
            'checkout.items' => $itemsNormalized
        ]);
    }

    public function summary(){
        $items = collect(session('checkout.items', []));
        $shipping = session('checkout.shipping');

        $items = $items->map(function ($item) {
            $variantOption = ProductVariantOption::with('productVariant.product')
                                    ->find($item['product_variant_option_id']);

            return [
                
                'product_variant_option_id' => $variantOption->id,
                'name' => $variantOption->productVariant->product->name,
                'variant' => [
                    'weight' => $variantOption->productVariant->weight,
                    'color' => $variantOption->productVariant->color,
                ],
                'size' => $variantOption->size,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal']
            ];
        });

        $itemsTotalAmount = $items->sum('subtotal');
        $sumTotalItemWeight = $items->sum('variant.weight');
        $shippingCost = $shipping['cost'] ?? 0;

        // calculate TAX 
        $taxableAmount = $itemsTotalAmount;
        // PPN 11%
        $taxRate = 0.11; 

        $taxAmount = round($taxableAmount * $taxRate);

        $address = Address::where('user_id', $this->guard->id())
                            ->where('is_primary', true)
                            ->first();

        $grandTotal = $taxableAmount + $shippingCost;

        return [
            'address_id' => $address->id,
            'items' => $items,
            'items_total' => $itemsTotalAmount,  
            'total_items_weight' => $sumTotalItemWeight,
            'shipping' => $shipping,
            'shipping_cost' => $shippingCost,
            'tax' => [
                'type' => "PPN",
                'rate' => $taxRate,
                'amount' => $taxAmount
            ],
            'grand_total' => $grandTotal
        ];
    }


    public function removeSession(){

        session()->forget([
            'checkout.items',
            'checkout.source',
            'checkout.shipping'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Checkout session removed successfully',
            'session_id' => session()->getId(),
            'session_all' => session()->all(),
        ]);
    }
}