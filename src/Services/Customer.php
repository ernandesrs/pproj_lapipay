<?php

namespace Ernandesrs\Lapipay\Services;

use \Ernandesrs\Lapipay\Services\Pagarme\Pagarme;
use \Ernandesrs\Lapipay\Models\Document;
use \Ernandesrs\Lapipay\Models\Phone;
use \Ernandesrs\Lapipay\Models\AsCustomer;

class Customer
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
     * Create a customer
     *
     * @param \App\Models\User $user
     * @param Document|null $document when null, get document from $user
     * @param Phone|null $phone when null, get phone from $user
     * @param string|null $id when null, get id from $user
     * @param string|null $name when null, get name from $user
     * @param string|null $email when null, get email from $user
     * @param string|null $country when null, get country from $user
     * @param string|null $type when null, get type from $user
     * @return AsCustomer
     */
    public function create(
        \App\Models\User $user,
        ?Document $document = null,
        ?Phone $phone = null,
        ?string $id = null, ?string $name = null, ?string $email = null, ?string $country = null, ?string $type = null
    ) {
        $customer = $this->gatewayInstance
            ->createCustomer(
                $id ?? $user->customerId(),
                $name ?? $user->customerName(),
                $email ?? $user->customerEmail(),
                $country ?? $user->customerCountry(),
                $type ?? $user->customerType(),
                $document ?? $user->customerDocument(),
                $phone ?? $user->customerPhone()
            );
        return $customer ? $user->customer()->create([
            'gateway' => config('lapipay.default_gateway'),
            'customer_id' => $customer->customer_id
        ]) : null;
    }

    public function details()
    {

    }
}