<?php

return [
    /**
     * 
     * Testing payment
     * 
     */
    'testing' => env('PAYMENT_TESTING', true),

    /**
     * 
     * The default gateway
     * See the implemented gateways:
     * https://github.com/ernandesrs/lapipay
     * 
     */
    'default_gateway' => env('PAYMENT_DEFAULT_GATEWAY', 'pagarme'),

    /**
     * 
     * * * * * * * * * * * * * * * * * * * * * *
     * GATEWAYS CONFIGURATIONS
     * * * * * * * * * * * * * * * * * * * * * *
     * 
     */

    'gateways' => [
        /**
         * 
         * Pagarme
         * 
         */
        'pagarme' => [
            'live_api_key' => env('PAYMENT_PAGARME_API_LIVE'),
            'test_api_key' => env('PAYMENT_PAGARME_API_TEST'),
            'antifraud_active' => env('PAYMENT_PAGARME_API_ANTIFRAUD')
        ]
    ]
];