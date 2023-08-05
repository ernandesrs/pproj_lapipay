<?php

namespace Ernandesrs\Lapipay\Services;

use Ernandesrs\Lapipay\Models\Card;
use Ernandesrs\Lapipay\Services\Pagarme\Pagarme;
use Ernandesrs\Lapipay\Exceptions\InvalidDataException;

class Payment
{
    /**
     * Constructor
     *
     * @param Pagarme $gatewayInstance
     */
    public function __construct(private $gatewayInstance)
    {
    }

    /**
     * Add customer
     *
     * @param \App\Models\User $customer
     * @return Payment
     */
    public function addCustomer(\App\Models\User $customer)
    {
        $this->gatewayInstance->addCustomer($customer);
        return $this;
    }

    /**
     * Add billing
     *
     * @param \App\Models\User $customer
     * @return Payment
     */
    public function addBilling(\App\Models\User $customer)
    {
        $this->gatewayInstance->addBilling($customer);
        return $this;
    }

    /**
     * Add product
     *
     * @param string $id
     * @param string $title
     * @param float $unitPrice
     * @param integer $quantity
     * @param boolean $isTangible
     * @return Payment
     */
    public function addProduct(string $id, string $title, float $unitPrice, int $quantity, bool $isTangible)
    {
        $this->gatewayInstance->addProduct($id, $title, $unitPrice, $quantity, $isTangible);
        return $this;
    }

    /**
     * Pay with card
     *
     * @param Card $card
     * @param float $amount
     * @param int $installments
     * @param array $metadata
     * @return null|\Ernandesrs\Lapipay\Models\Payment
     * @throws InvalidDataException
     */
    public function payWithCard(Card $card, float $amount, int $installments, array $metadata = [])
    {
        $validated = \Ernandesrs\Lapipay\Services\Validator::validatePayData($amount, $installments);

        $payment = $this->gatewayInstance->payWithCard($card, $validated['amount'], $validated['installments'], $metadata);

        return $payment ? $this->gatewayInstance->customer->payments()->create([
            'transaction_id' => $payment->transaction_id,
            'card_id' => $card->id,
            'gateway' => config('lapipay.default_gateway'),
            'method' => $payment->payment_method,
            'amount' => $amount,
            'installments' => $installments,
            'status' => $payment->status
        ]) : null;
    }

    /**
     * Postback
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function postback(\Illuminate\Http\Request $request)
    {
        return $this->gatewayInstance->postback($request);
    }
}