<?php

namespace Ernandesrs\Lapipay\Services;

use \Ernandesrs\Lapipay\Services\Pagarme\Pagarme;

class Lapipay
{
    /**
     * Gateways
     *
     * @var array
     */
    private $gateways = [
        'pagarme' => Pagarme::class
    ];

    /**
     * Gateway instance
     *
     * @var Pagarme
     */
    protected $gatewayInstance;

    /**
     * Constructor
     */
    public function __construct()
    {
        try {
            $this->gatewayInstance = new $this->gateways[config('lapipay.default_gateway')];
        } catch (\Exception $e) {
            throw new \Exception("'" . config('lapipay.default_gateway') . "' is a invalid gateway");
        }
    }

    /**
     * Customer
     *
     * @return Customer
     */
    public function customer()
    {
        return new Customer($this->gatewayInstance);
    }

    /**
     * Card
     *
     * @return Card
     */
    public function card()
    {
        return new Card($this->gatewayInstance);
    }

    /**
     * Payment
     *
     * @return Payment
     */
    public function payment()
    {
        return new Payment($this->gatewayInstance);
    }

    /**
     * Get error messages
     *
     * @return null|array
     */
    public function errorMessages()
    {
        return \Ernandesrs\Lapipay\Services\Validator::errorMessages();
    }
}