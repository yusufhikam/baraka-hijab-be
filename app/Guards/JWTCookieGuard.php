<?php

namespace App\Guards;

use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class JWTCookieGuard extends JWTGuard {


    public function getTokenForRequest(){
        return $this->request->cookie('accessToken');
    }
    public function parseToken(){
        if($token = Request::capture()->cookie('accessToken')){
            $this->setToken($token);

            return $this;
        }

        return parent::parseToken();
    }
}