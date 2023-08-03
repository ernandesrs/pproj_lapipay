# LAPIPAY
Este é um projeto de estudo de criação de pacotes Laravel e uso de APIs de pagamento.

Este pacote fará, inicialmente, integração com a API da Pagarme.

# INSTALAÇÃO
> composer require ernandesrs/lapipay

# CONFIGURAÇÃO
Guia de configuração do pacote LAPIPAY.

## Variáveis ambiente
No arquivo .env do seu projeto, adicione as seguintes variáveis:
```

PAYMENT_TESTING=true
PAYMENT_DEFAULT_GATEWAY=pagarme

PAYMENT_PAGARME_API_TEST=ak_test_CDvEEWzPj0vuszTsFUqfmoTutky7dr
PAYMENT_PAGARME_API_LIVE=
PAYMENT_PAGARME_API_ANTIFRAUD=false

```

## Adicione o ServiceProvider
Em /config/app.php adicione Ernandesrs\Lapipay\LapipayServiceProvider::class no item 'providers'. Vai ficar assim:
```php

<?php

return [
    
    // outras configurações... 

    'providers' => [
        // outros providers...
        App\Providers\RouteServiceProvider::class,

        Ernandesrs\Lapipay\LapipayServiceProvider::class
    ],

    // outras configurações
];

```

## Publique o arquivo de configuração
Na raiz do seu projeto Laravel, publique o arquivo de configuração com o seguinte comando:
> php artisan vendor:publish --tag=lapipay-config
O arquivo de configuração possui campos que podem ser modificados no arquivo de variáveis <i>.env</i>, veja a seção acima '[Variáveis ambientes](#variáveis-ambiente)'. [Veja o arquivo de configurações](src/config/lapipay.php) para mais detalhes.

## Faça uso da trait AsCustomerTrait
Na seu modelo de usuário <i>\App\Models\User</i>, faça o uso da trait <i>AsCustomerTrait</i>, seu modelo ficará parecido com isso:
```php

<?php

namespace App\Models;

// outras importações...
use Ernandesrs\Lapipay\Models\AsCustomerTrait;

class User extends Authenticatable
{
    use AsCustomerTrait;

```

# USO
O uso é bastante simples, veja:

### Clientes
Ao criar um cliente, os dados do cliente serão salvos na base dados da gateway que gerará um ID para o cliente. Este ID será salvo na sua base dados e então poderá ser utilizado futuramente para facilitar outros processos(transações), bastando passar o ID.

#### Criando cliente
Passando os dados do cliente manualmente:
```php

$user = \App\Models\User::where("id", 1)->first();
$customer = (new Ernandesrs\Lapipay\Services\Lapipay())->customer()
    ->create($user, Ernandesrs\Lapipay\Models\Document::cpf(9203923), new Ernandesrs\Lapipay\Models\Phone(55, 83232988), '9023', 'Customer Name', 'customer@mail.com', 'br', 'individual');
var_dump($customer);

```
Se você [fez uso da trait AsCustomerTrait](#faça-uso-da-trait-ascustomertrait), você pode optar por passar apenas uma instância de \App\Models\User no parâmetro $user que os campos serão obtidos a partir dele, veja:
```php

$user = \App\Models\User::where("id", 1)->first();
$customer = (new Ernandesrs\Lapipay\Services\Lapipay())->customer()
    ->create($user);
var_dump($customer);

```