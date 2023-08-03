<?php

namespace Ernandesrs\Lapipay\Models;

trait AsCustomerTrait
{
    /**
     * Get all of the customer for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AsCustomer::class, 'user_id', 'id')->where('gateway', config('lapipay.default_gateway'));
    }

    /**
     * Check if this user is a customer
     *
     * @return boolean
     */
    public function isCustomer(): bool
    {
        return $this->customer()->count();
    }

    /**
     * Get all of the cards for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Card::class, 'user_id', 'id')->where('gateway', config('lapipay.default_gateway'));
    }

    /**
     * Get all of the payments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class, 'user_id', 'id')->where('gateway', config('lapipay.default_gateway'));
    }

    /**
     * Customer id
     *
     * @return string
     */
    public function customerId(): string
    {
        return $this->id;
    }

    /**
     * Customer first name
     *
     * @return string
     */
    public function customerFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * Customer last name
     *
     * @return string
     */
    public function customerLastName(): string
    {
        return $this->last_name;
    }

    /**
     * Customer full name
     *
     * @return string
     */
    public function customerName(): string
    {
        return $this->customerFirstName() . ' ' . $this->customerLastName();
    }

    /**
     * Customer email
     *
     * @return string
     */
    public function customerEmail(): string
    {
        return $this->email;
    }

    /**
     * Customer country
     *
     * @return string
     */
    public function customerCountry(): string
    {
        return 'br';
    }

    /**
     * Customer type
     * Tipo de pessoa, individual ou corporation
     *
     * @return string
     */
    public function customerType(): string
    {
        return 'individual';
    }

    /**
     * Customer phone number
     *
     * @return \Ernandesrs\Lapipay\Models\Phone
     */
    abstract public function customerPhone(): \Ernandesrs\Lapipay\Models\Phone;

    /**
     * Customer document
     *
     * @return \Ernandesrs\Lapipay\Models\Document
     */
    abstract public function customerDocument(): \Ernandesrs\Lapipay\Models\Document;

    /**
     * Customer adress
     *
     * @return \Ernandesrs\Lapipay\Models\Address
     */
    abstract public function customerAddress(): \Ernandesrs\Lapipay\Models\Address;
}