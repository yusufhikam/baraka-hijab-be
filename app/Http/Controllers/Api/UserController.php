<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        
    }


    public function updateProfile(User $user, Request $request){

        if($user->id !== Auth::id()){
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        
        $validated = $request->validate([
            'name' => 'required|string',
            'phone_number' => 'numeric|required'
        ]);


        $updatedUser = $this->userService->updateProfile($user, $validated);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $updatedUser
        ]);
    }
}