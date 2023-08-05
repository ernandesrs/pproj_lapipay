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
     * Postback url for local tests using tools like:
     * https://requestbin.com/
     * https://ngrok.com/
     * 
     * When null, empty, or testing is set to false, route route('lapipay.postback') will be used.
     * 
     */
    'postback_url' => env('PAYMENT_POSTBACK_URL_TEST', null),

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