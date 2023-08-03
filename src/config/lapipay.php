<?php

return [
    'testing' => true,
    'db_prefix' => 'lapi',
    'default_gateway' => 'pagarme',

    'gateways' => [
        'pagarme' => [
            'live_api_key' => env('PAYMENT_PAGARME_API_LIVE'),
            'test_api_key' => env('PAYMENT_PAGARME_API_TEST'),
            'antifraud_active' => env('PAYMENT_PAGARME_API_ANTIFRAUD')
        ]
    ]
];