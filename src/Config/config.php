<?php

return [
    'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
    'api_key' => env('TRIPAY_API_KEY'),
    'ppob_api_key' => env('TRIPAY_PPOB_API_KEY'),
    'private_key' => env('TRIPAY_PRIVATE_KEY'),
    'ppob_pin' => env('TRIPAY_PPOB_PIN'),
    'ppob_endpoints' => [
        'dev' => 'https://tripay.id/api-sandbox/v2/',
        'production' => 'https://tripay.id/api/v2/'
    ],
    'payment_endpoints' => [
        'dev' => 'https://tripay.co.id/api-sandbox/',
        'production' => 'https://tripay.co.id/api/',
    ],
    'ppob_callback_secret' => env('TRIPAY_PPOB_CALLBACK_SECRET'),
    'callback_secret' => env('TRIPAY_CALLBACK_SECRET'),
];