<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;  
    }

    public function updateProfile(User $user, array $data){
        return $this->userRepository->updateProfile($user, $data);
    }
}