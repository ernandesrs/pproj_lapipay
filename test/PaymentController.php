<?php

namespace Ernandesrs\Lapipay\Test;

use Ernandesrs\Lapipay\Services\Lapipay;

class PaymentController
{
    public function payWithCard()
    {
        $user = \App\Models\User::where("id", 2)->first();
        $card = $user->cards()->first();

        try {
            $pay = (new Lapipay())->payment()
                ->addCustomer($user)
                ->addBilling($user)
                ->addProduct('9032', 'Produto top', 99.99, 1, false)
                ->payWithCard($card, 99.99, 1);

            var_dump($pay);
        } catch (\Ernandesrs\Lapipay\Exceptions\InvalidDataException $e) {
            var_dump((new Lapipay())->errorMessages());
        }

    }
}