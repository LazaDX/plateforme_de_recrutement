<?php

return [



    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'enqueteurs',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'administrateurs',
        ],
    ],

    'providers' => [
        'enqueteurs' => [
            'driver' => 'eloquent',
            'model' => App\Models\Enqueteurs::class,
        ],
        'administrateurs' => [
            'driver' => 'eloquent',
            'model' => App\Models\Administrateurs::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
