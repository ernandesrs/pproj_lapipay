<?php

namespace Ernandesrs\Lapipay\Test;

class Test
{
    public function __construct($r = null)
    {
        // (new CustomerController())->create();
        // (new CustomerController())->details();

        // (new CardController())->create();
        // (new CardController())->cards();

        // (new PaymentController())->payWithCard();

        /**
         * 
         * testes: não comentar para deixa-lo sempre disponível para receber alterações/chamadas da gateway
         * 
         */
        (new PaymentController())->postback($r);

        // 
    }
}