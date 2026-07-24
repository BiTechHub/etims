<?php

use App\Models\Agency;
use App\Models\User;

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => 'users', 
    ],

    'guards' => [
        'web' => [
            'driver' => 'session', 
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'hostel' => [
            'driver' => 'session',
            'provider' => 'hostels',  
        ],
        'agency' => [
            'driver' => 'session',
            'provider' => 'agencies',
        ],
         'faculty' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
       
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // ✅ Directly reference the model
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class, // ✅ Directly reference the model
        ],
        'agencies' => [
            'driver' => 'eloquent',
            'model' => Agency::class, // Use your Agency model here
        ],
            'agencies' => [
            'driver' => 'eloquent',
            'model' => Agency::class, // Use your Agency model here
        ],
            'faculty' => [
            'driver' => 'eloquent',
            'model' => User::class, // Use your Agency model here
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [ // ✅ Added password reset config for admins
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800, // 3 hours
];
