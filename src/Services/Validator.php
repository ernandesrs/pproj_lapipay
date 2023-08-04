<?php

namespace Ernandesrs\Lapipay\Services;

use Ernandesrs\Lapipay\Exceptions\InvalidDataException;

class Validator
{
    /**
     * Validate card
     *
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return array validated data
     * @throws InvalidDataException
     */
    public static function validateCard(string $holderName, string $number, string $cvv, string $expiration)
    {
        return self::validate([
            'holder_name' => $holderName,
            'number' => $number,
            'cvv' => $cvv,
            'expiration' => $expiration
        ], [
            'holder_name' => ['required'],
            'number' => ['required', 'numeric', 'digits:16'],
            'cvv' => ['required', 'numeric', 'digits:3'],
            'expiration' => ['required', 'numeric', 'digits:4']
        ], [
            'number' => __('lapipay-lang::lapipay.card.number'),
            'cvv' => __('lapipay-lang::lapipay.card.cvv'),
            'expiration' => __('lapipay-lang::lapipay.card.expiration')
        ]);
    }

    /**
     * Validate pay data
     *
     * @param float $amount
     * @param integer $installments
     * @return array validated data
     * @throws InvalidDataException
     */
    public static function validatePayData(float $amount, int $installments)
    {
        return self::validate([
            'amount' => $amount,
            'installments' => $installments
        ], [
            'amount' => ['required', 'numeric'],
            'installments' => [
                'required',
                'integer',
                'between:' . config('lapipay.allowed_min_installments') . ',' . config('lapipay.allowed_max_installments')
            ]
        ], [
            'amount.decimal' => __('lapipay-lang::lapipay.charge.amount.decimal'),

            'installments.integer' => __('lapipay-lang::lapipay.charge.installments.integer'),
            'installments.between' => __(
                'lapipay-lang::lapipay.charge.installments.between',
                [
                    'min' => config('lapipay.allowed_min_installments'),
                    'max' => config('lapipay.allowed_max_installments')
                ]
            )
        ]);
    }

    /**
     * Validate
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return array validated data
     * @throws InvalidDataException
     */
    public static function validate(array $data, array $rules, array $messages)
    {
        $validator = \Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            \Session::flash('lapipay_errors_messages', $validator->errors()->getMessages());
            throw new InvalidDataException();
        }

        return $validator->validated();
    }

    /**
     * Error messages
     *
     * @return null|array
     */
    public static function errorMessages(): ?array
    {
        return \Session::get('lapipay_errors_messages');
    }
}