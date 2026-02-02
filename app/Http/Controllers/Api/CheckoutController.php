<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }
    public function prepare(Request $request){
        $request->validate([
            'source' => 'required|in:cart,buy_now',
            'items' => 'required_if:source,buy_now|array'
        ]);

        // for debugging to check is session are working or not
        // return response()->json([
        //     'session_id' => session()->getId(),
        //     'session_all' => session()->all(),
        // ]);

        // cookie for session id
        $cookie = cookie(
            'checkout_active_session',
            '1',
            30, // minutes
            '/',
            null,
            env('SESSION_SECURE_COOKIE', false), // secure
            true, // httpOnly
            false,
            'Lax'
        );

        return response()->json([
            'status' => true,
            'message' => 'Checkout prepared successfully',
            'data' => $this->checkoutService->CheckoutPrepare($request)
        ])->withCookie($cookie);
    }

    public function removeSession(){

        session()->forget([
            'checkout.items',
            'checkout.source'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Checkout session removed successfully',
            'session_id' => session()->getId(),
            'session_all' => session()->all(),
        ]);
    }

    public function setShipping(Request $request){

        $request->validate([
            'courier' => 'required|string',
            'service' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'etd' => 'nullable|string',
        ]);

        if(!session()->has('checkout.items')){
            throw new \Exception('Checkout session expired');
        }

        session([
            'checkout.shipping' => [
                'courier' => $request->courier,
                'service' => $request->service,
                'cost' => (int) $request->cost,
                'etd' => $request->etd
            ]
        ]);

        // create session for summary after set shipping
        $checkoutSummary = $this->checkoutService->summary();
        session(['checkout.summary' => $checkoutSummary]);


        return response()->json([
            'status' => true,
            'message' => "Successfully set shipping cost",
        ]);
    }
    
    public function summary(){


        // for debugging to check is session are working or not
        // return response()->json([
        //     'session_id' => session()->getId(),
        //     'session_all' => session()->all(),
        // ]);

        return response()->json($this->checkoutService->summary());
    }
}