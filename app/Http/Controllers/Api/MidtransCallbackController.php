<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransCallbackController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService){
        $this->midtransService = $midtransService;
    }

    public function handle(Request $request){
        $this->midtransService->handleCallback($request->all());

        return  response()->json(['message' => 'Callback Processed']);
    }
}
