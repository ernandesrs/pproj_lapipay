# Clientes
Ao criar um cliente, os dados do cliente serão salvos na base dados da gateway que gerará um ID para o cliente. Este ID será salvo na sua base dados e então poderá ser utilizado futuramente para facilitar outros processos(transações), bastando passar o ID.

# Criando cliente
Passando os dados do cliente manualmente:
```php

use Ernandesrs\Lapipay\Services\Lapipay;
use Ernandesrs\Lapipay\Models\Document;
use Ernandesrs\Lapipay\Models\Phone;

$user = \App\Models\User::where("id", 1)->first();
$customer = (new Lapipay())->customer()
    ->create($user, Document::cpf(9203923), new Phone(55, 83232988), '9023', 'Customer Name', 'customer@mail.com', 'br', 'individual');

var_dump($customer);

```
Se você [fez uso da trait AsCustomerTrait](#faça-uso-da-trait-ascustomertrait), você pode optar por passar apenas a instância de \App\Models\User no parâmetro $user que os campos serão obtidos a partir dele, veja:
```php

use Ernandesrs\Lapipay\Services\Lapipay;

$user = \App\Models\User::where("id", 1)->first();
$customer = (new Lapipay())->customer()
    ->create($user);

var_dump($customer);

```