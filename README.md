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

## Clientes
Ao criar um cliente, os dados do cliente serão salvos na base dados da gateway que gerará um ID para o cliente. Este ID será salvo na sua base dados e então poderá ser utilizado futuramente para facilitar outros processos(transações), bastando passar o ID.

## Criando cliente
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

## Cartões
Valida e salva um hash seguro do cartão gerado pela gateway na base de dados, isso facilitará cobranças de maneira segura.

## Criando cartão
Valide e salve um cartão na sua base de dados. O cartão pertencerá ao usuário injetado.
```php

$user = \App\Models\User::where("id", 1)->first();
$card = (new Lapipay())->card()
    ->create($user, $user->customerName(), '4716380053580331', '1029', '918');

var_dump($card);

```

## Capturando erros de validação
Ao tentar criar um cartão, os dados passarão por uma validação inicial antes de serem enviados de fato á gateway; ao ocorrer um erro de validação uma exceção será lançada e um array de erros será armazenado na sessão do usuário.

Capture assim a exceção lançada e também os erros:
```php

$user = \App\Models\User::where("id", 1)->first();
try {
    $card = (new Lapipay())->card()
        ->create($user, $user->customerName(), '14716380053580331', '1029', '918');

    var_dump($card);
} catch (\Ernandesrs\Lapipay\Exceptions\InvalidDataException $e) {
    var_dump((new Lapipay())->errorMessages());
}

```

## Obtendo cartões de um cliente
Obtenha todos os cartões salvos na sua base de dados para o cliente atual.
```php

$user = \App\Models\User::where("id", 1)->first();
$cards = $user->cards()->get();

var_dump($cards);

```

## Pagamentos/cobranças
### Adicionando um cliente
Adicione um cliente a ser cobrado. Serão obtidos do usuário injetado: nome, email, documentos, telefone e endereço; certifique-se de [fazer o uso da trait](#faça-uso-da-trait-ascustomertrait) <i>[AsCustomerTrait](src/Models/AsCustomerTrait.php)</i> e implementar seus métodos obrigatórios.

```php

$customer = \App\Models\User::where("id", 2)->first();
$pay = new \Ernandesrs\Lapipay\Lapipay();
$pay->addCustomer($customer);

```

### Adicionando billing
Adicione os dados de cobrança. Este método irá extrair o nome e endereço do usuário injetado. Se for nulo ou nunca for chamado, nome e endereço serão obtidos do usuário injetado com o método <i>addCustomer</i>.
```php

$pay->addBilling($customer);

```

### Adicionando produtos
Adicione os produtos que estão sendo cobrados. Você pode chamar o método <i>addProduct</i> quantas vezes forem necessários.
```php

$productId = '#0932';
$productTitle = 'Digital Product';
$productUnitPrice = 9.99;
$productQuantity = 1;
$productTangible = false;

$pay->addProduct($productId, $productTitle, $productUnitPrice, $productQuantity, $productTangible);

```
Atenção: o total a ser cobrado não é calculado automaticamente.

### Efetuando um pagamento/cobrança com cartão
Efetue um pagamento/cobrança com o cartão de crédito cadastrado, conforme o exemplo abaixo:
```php

// get a registered card for the customer
$card = $customer->cards()->first();

$amount = 9.99;
$installments = 1;
$metadata  = [
    'product_id' => '#9023',
    'extra' => 'data'
];

$payment = $pay->payWithCard($card, $amount, $installments, $metadata);

var_dump($payment);

```

## Capturando erros de validação
Alguns campos de um pagamento/cobrança passarão por validação previa. Uma exceção será lançada e um array de erros será armazenado na sessão do usuário, veja como capturar:
```php

$card = $customer->cards()->first();
$lapipay = (new \Ernandesrs\Lapipay\Services\Lapipay())->payment()
    ->addCustomer($customer)
    ->addBilling($customer)
    ->addProduct('#8923', 'Product', 10.00, 1, false);

try {
    $payment = $lapipay->payWithCard($card, 10.00, 1);

    var_dump($payment);
} catch (\Ernandesrs\Lapipay\Exceptions\InvalidDataException $e) {
    var_dump((new Lapipay())->errorMessages());
}

```