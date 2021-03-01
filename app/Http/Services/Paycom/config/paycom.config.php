<?php
// sample configuration with fake values
return [
    // Get it in merchant's cabinet in cashbox settings
    'merchant_id' => '601d0a058e37921ec75d65cb',

    // Login is always "Paycom"
    'login'       => 'Paycom',

    // File with cashbox key (key can be found in cashbox settings)
    'keyFile'     => \App\Http\Services\Paycom\Constants::PASSWORD_PAYCOM,

    // Your database settings
    'db'          => [
        'host'     => env('DB_HOST'),
        'database' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'port'     => env('DB_PORT'),
    ],
];
