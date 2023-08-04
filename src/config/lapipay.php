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
     * Initial installments
     * 
     */
    'default_installments' => 1,

    /**
     * 
     * Minimum number of installments
     * 
     */
    'allowed_min_installments' => 1,

    /**
     * 
     * Maximum number of installments
     * 
     */
    'allowed_max_installments' => 10,

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