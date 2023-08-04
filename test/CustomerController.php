<?php

namespace Ernandesrs\Lapipay\Test;

use Ernandesrs\Lapipay\Services\Lapipay;

class CustomerController
{
    public function create()
    {
        $user = \App\Models\User::where("id", 2)->first();

        // (new Lapipay())->customer()->create($user, \Ernandesrs\Lapipay\Models\Document::cpf(9203923), new \Ernandesrs\Lapipay\Models\Phone(55, 83232988), '9023', 'Nome', 'email@mal.com', 'br', 'individual');
        $customer = (new Lapipay())->customer()->create($user);

        var_dump($customer);
    }
}