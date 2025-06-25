<?php 

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface{
    public function updateProfile(User $user, array $data)
    {
        
    $user->update($data);        
    return $user->fresh(); // Return the updated user
}
}