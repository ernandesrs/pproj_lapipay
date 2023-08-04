<?php

namespace Ernandesrs\Lapipay\Test;

use Ernandesrs\Lapipay\Services\Lapipay;

class CardController
{
    public function create()
    {
        $user = \App\Models\User::where("id", 2)->first();

        // $card = (new Lapipay())->card()->create($user, $user->customerName(), '5476240809148923', '0326', '779');
        $card = (new Lapipay())->card()->create($user, $user->customerName(), '4716380053580331', '1029', '918');

        var_dump($card);
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