<?php

namespace Ernandesrs\Lapipay\Models;

class Document
{
    /**
     * Type
     *
     * @var string
     */
    private string $type;

    /**
     * Number
     *
     * @var integer
     */
    private int $number;

    /**
     * Cpf
     *
     * @param integer $number
     * @return Document
     */
    public static function cpf(int $number)
    {
        $doc = new Document;
        $doc->type = 'cpf';
        $doc->number = $number;
        return $doc;
    }

    /**
     * Cnh
     *
     * @param integer $number
     * @return Document
     */
    public static function cnh(int $number)
    {
        $doc = new Document;
        $doc->type = 'cnh';
        $doc->number = $number;
        return $doc;
    }

    /**
     * Cnpj
     *
     * @param integer $number
     * @return Document
     */
    public static function cnpj(int $number)
    {
        $doc = new Document;
        $doc->type = 'cnpj';
        $doc->number = $number;
        return $doc;
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