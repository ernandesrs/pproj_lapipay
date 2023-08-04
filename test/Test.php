<?php

namespace Ernandesrs\Lapipay\Test;

use Ernandesrs\Lapipay\Services\Lapipay;

class Test
{
    public function __construct()
    {
        // (new CustomerController())->create();
        // (new CustomerController())->details();

        // (new CardController())->create();
        // (new CardController())->cards();

        (new PaymentController())->payWithCard();
    }
}