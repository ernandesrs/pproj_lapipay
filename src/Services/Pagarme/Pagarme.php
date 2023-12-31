<?php

namespace Ernandesrs\Lapipay\Services\Pagarme;

use Ernandesrs\Lapipay\Models\Document;
use Ernandesrs\Lapipay\Models\Phone;

class Pagarme
{
    use Payment, Postback;

    /**
     * Pagarme instance
     *
     * @var \PagarMe\Client
     */
    private $pagarme;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagarme = new \PagarMe\Client($this->apiKey());
    }

    /**
     * Create customer
     *
     * @param string $id
     * @param string $name
     * @param string $email
     * @param string $country
     * @param string $type
     * @param Document $document
     * @param Phone $phone
     * @return null|\ArrayObject
     */
    public function createCustomer(string $id, string $name, string $email, string $country, string $type, Document $document, Phone $phone)
    {
        $customer = $this->pagarme->customers()->create([
            'external_id' => $id,
            'name' => $name,
            'email' => $email,
            'country' => $country,
            'type' => $type,
            'documents' => [
                [
                    'type' => $document->type,
                    'number' => $document->number . ''
                ]
            ],
            'phone_numbers' => [
                $phone->full()
            ]
        ]);

        if (!$customer->id ?? null) {
            return null;
        }

        $customer->customer_id = $customer->id;

        return $customer;
    }

    /**
     * Customer details
     *
     * @param string $customerId
     * @return null|\ArrayObject
     */
    public function detailCustomer(string $customerId)
    {
        $customer = $this->pagarme->customers()->get([
            'id' => $customerId
        ]);

        return $customer->id ?? null ? $customer : null;
    }

    /**
     * Create card
     *
     * @param string $holderName
     * @param string $number
     * @param string $expiration
     * @param string $cvv
     * @return null|\ArrayObject
     */
    public function createCard(string $holderName, string $number, string $expiration, string $cvv)
    {
        $card = $this->pagarme->cards()->create([
            'holder_name' => $holderName,
            'number' => $number,
            'expiration_date' => $expiration,
            'cvv' => $cvv
        ]);

        if (!($card->id ?? null)) {
            return null;
        }

        $card->card_id = $card->id;

        return $card;
    }

    /**
     * Get api key
     *
     * @return ?string
     */
    private function apiKey(): ?string
    {
        return config('lapipay.testing') ?
            config('lapipay.gateways.pagarme.test_api_key') :
            config('lapipay.gateways.pagarme.live_api_key');
    }

    /**
     * Get
     *
     * @param string $key
     * @return null|mixed
     */
    public function __get(string $key)
    {
        return $this->$key ?? null;
    }
}