<?php

namespace Ernandesrs\Lapipay\Services;

use \Ernandesrs\Lapipay\Services\Pagarme\Pagarme;
use \Ernandesrs\Lapipay\Models\Card as CardModel;
use Ernandesrs\Lapipay\Exceptions\InvalidDataException;

class Card
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
     * Create card
     *
     * @param \App\Models\User $user
     * @param string $holderName
     * @param string $number
     * @param string $expiration
     * @param string $cvv
     * @return CardModel
     * @throws InvalidDataException
     */
    public function create(\App\Models\User $user, string $holderName, string $number, string $expiration, string $cvv)
    {
        $validated = \Ernandesrs\Lapipay\Services\Validator::validateCard($holderName, $number, $cvv, $expiration);

        $card = $this->gatewayInstance->createCard($validated['holder_name'], $validated['number'], $validated['expiration'], $validated['cvv']);

        return !$card ? null : $user->cards()->firstOrCreate([
            'hash' => $card->card_id,
            'holder_name' => $card->holder_name,
            'last_digits' => $card->last_digits,
            'expiration_date' => $card->expiration_date,
            'country' => $card->country ?? null,
            'gateway' => config('lapipay.default_gateway'),
            'brand' => $card->brand,
            'valid' => $card->valid
        ]);
    }
}