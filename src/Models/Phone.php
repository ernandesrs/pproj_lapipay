<?php

namespace Ernandesrs\Lapipay\Models;

class Phone
{
    /**
     * Constructor
     *
     * @param integer $countryCode
     * @param integer $number
     */
    public function __construct(private int $countryCode, private int $number)
    {
    }

    /**
     * Full number
     *
     * @return string
     */
    public function full(): string
    {
        return '+' . $this->countryCode . '' . $this->number;
    }

    /**
     * Get
     *
     * @param string $key
     * @return null|int
     */
    public function __get(string $key)
    {
        return $this->$key ?? null;
    }
}