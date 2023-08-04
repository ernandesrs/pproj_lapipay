<?php

namespace Ernandesrs\Lapipay\Test;

use Ernandesrs\Lapipay\Services\Lapipay;

class CardController
{
    public function create()
    {
        $user = \App\Models\User::where("id", 2)->first();

        try {
            // $card = (new Lapipay())->card()->create($user, $user->customerName(), '5476240809148923', '0326', '779');
            $card = (new Lapipay())->card()->create($user, $user->customerName(), '14716380053580331', '1029', '918');

            var_dump($card);
        } catch (\Ernandesrs\Lapipay\Exceptions\InvalidDataException $e) {
            var_dump((new Lapipay())->errorMessages());
        }

    }

    public function cards()
    {
        $user = \App\Models\User::where("id", 2)->first();

        $cards = $user->cards()->get([
            'hash',
            'user_id',
            'id'
        ]);

        var_dump($cards);
    }
}