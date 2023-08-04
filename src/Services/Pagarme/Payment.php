<?php

namespace Ernandesrs\Lapipay\Services\Pagarme;

use Ernandesrs\Lapipay\Models\Card;

trait Payment
{
    /**
     * Customer
     *
     * @var \App\Models\User
     */
    private $customer;

    /**
     * Billing
     *
     * @var \App\Models\User
     */
    private $billing;

    /**
     * Products
     *
     * @var array
     */
    private $products = [];

    /**
     * Data
     *
     * @var array
     */
    private $data = [];

    /**
     * Add customer
     *
     * @param \App\Models\User $customer
     * @return Pagarme
     */
    public function addCustomer(\App\Models\User $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Add billing
     *
     * @param \App\Models\User|null $billing when null, get billing name and address from $customer added with addCustomer method
     * @return Pagarme
     */
    public function addBilling(?\App\Models\User $billing = null)
    {
        $this->billing = $billing;
        return $this;
    }

    /**
     * Add product
     *
     * @param string $id
     * @param string $title
     * @param float $unitPrice
     * @param int $quantity
     * @param boolean $isTangible true if the product is not a digital product
     * @return Pagarme
     */
    public function addProduct(string $id, string $title, float $unitPrice, int $quantity, bool $isTangible)
    {
        array_push($this->products, [
            'id' => $id,
            'title' => $title,
            'unit_price' => $unitPrice * 100,
            'quantity' => $quantity,
            'tangible' => $isTangible
        ]);
        return $this;
    }

    /**
     * Pay with card
     *
     * @param Card $card
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\ArrayObject
     */
    public function payWithCard(Card $card, float $amount, int $installments, array $metadata = [])
    {
        return $this->pay($card->hash, $amount, $installments, 'credit_card', $metadata);
    }

    /**
     * Pay
     *
     * @param string $cardHash
     * @param float $amount
     * @param integer $installments
     * @param string $method
     * @param array $metadata
     * @return null|\ArrayObject
     */
    protected function pay(string $cardHash, float $amount, int $installments, string $method, array $metadata = [])
    {
        $this->data = [
            'card_id' => $cardHash,
            'installments' => $installments,
            'amount' => $amount * 100,
            'payment_method' => $method,
            'metadata' => $metadata
        ];

        $this->antifraudFields($method);

        $trasaction = $this->pagarme->transactions()->create($this->data);
        if (!($trasaction->id ?? null)) {
            return null;
        }

        $trasaction->transaction_id = $trasaction->id;

        return $trasaction;
    }

    /**
     * Antifraud fields check and set
     *
     * @param string $method
     * @return void
     * @exception \Exception
     */
    private function antifraudFields(string $method)
    {
        if (
            $method == 'credit_card' &&
            config('lapipay.gateways.pagarme.antifraud_active') &&
            (
                !($this->customer->id ?? null) ||
                count($this->products) < 1
            )
        ) {
            throw new \Exception('Need customer, billing and products when anti-fraud is enabled');
        }

        if ($this->customer->id ?? null) {
            $this->data['customer'] = [
                'external_id' => $this->customer->customerId(),
                'name' => $this->customer->customerName(),
                'email' => $this->customer->customerEmail(),
                'country' => $this->customer->customerCountry(),
                'type' => $this->customer->customerType(),
                'documents' => [
                    [
                        'type' => $this->customer->customerDocument()->type,
                        'number' => $this->customer->customerDocument()->number . ''
                    ]
                ],
                'phone_numbers' => [
                    $this->customer->customerPhone()->full()
                ]
            ];
        }

        if ($this->billing->id ?? null) {
            $this->data['billing'] = [
                'name' => $this->billing->customerName(),
                'address' => [
                    'street' => $this->billing->customerAddress()->street,
                    'street_number' => $this->billing->customerAddress()->streetNumber,
                    'zipcode' => $this->billing->customerAddress()->zipcode,
                    'country' => $this->billing->customerAddress()->country,
                    'state' => $this->billing->customerAddress()->state,
                    'city' => $this->billing->customerAddress()->city,
                    'neighborhood' => $this->billing->customerAddress()->neighborhood,
                    'complementary' => $this->billing->customerAddress()->complementary
                ]
            ];
        } else {
            if (!($this->customer->id ?? null)) {
                throw new \Exception('Need customer, add with addCustomer method');
            }

            $this->data['billing'] = [
                'name' => $this->customer->customerName(),
                'address' => [
                    'street' => $this->customer->customerAddress()->street,
                    'street_number' => $this->customer->customerAddress()->streetNumber,
                    'zipcode' => $this->customer->customerAddress()->zipcode,
                    'country' => $this->customer->customerAddress()->country,
                    'state' => $this->customer->customerAddress()->state,
                    'city' => $this->customer->customerAddress()->city,
                    'neighborhood' => $this->customer->customerAddress()->neighborhood,
                    'complementary' => $this->customer->customerAddress()->complementary
                ]
            ];
        }

        if (count($this->products)) {
            $this->data['items'] = $this->products;
        }
    }
}