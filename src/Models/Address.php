<?php

namespace Ernandesrs\Lapipay\Models;

class Address
{
    public function __construct(
        private string $street,
        private string $streetNumber,
        private string $zipcode,
        private string $country,
        private string $state,
        private string $city,
        private string $neighborhood,
        private string $complementary,
    ) {
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