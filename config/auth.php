<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'clinicians',
        ],
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'clinicians',
        ],
    ],

    'providers' => [
        'clinicians' => [
            'driver' => 'eloquent',
            'model' => App\Models\Clinician::class,
        ],
    ],

    'passwords' => [
        'clinicians' => [
            'provider' => 'clinicians',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];