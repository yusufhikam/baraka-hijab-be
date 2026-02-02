<?php

// use PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    Laravel\Socialite\SocialiteServiceProvider::class,

    // for jwt
    PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider::class
];