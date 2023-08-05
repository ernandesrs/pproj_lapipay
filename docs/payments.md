# Adicionando um cliente
Adicione um cliente a ser cobrado. Serão obtidos do usuário injetado: nome, email, documentos, telefone e endereço; certifique-se de [fazer o uso da trait](../README.md#faça-uso-da-trait-ascustomertrait) <i>[AsCustomerTrait](../src/Models/AsCustomerTrait.php)</i> e implementar seus métodos obrigatórios.

```php

$customer = \App\Models\User::where("id", 2)->first();
$pay = new \Ernandesrs\Lapipay\Lapipay();
$pay->addCustomer($customer);

```

# Adicionando billing
Adicione os dados de cobrança. Este método irá extrair o nome e endereço do usuário injetado. Se for nulo ou nunca for chamado, nome e endereço serão obtidos do usuário injetado com o método <i>addCustomer</i>.
```php

$pay->addBilling($customer);

```

# Adicionando produtos
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

# Efetuando um pagamento/cobrança com cartão
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

# Capturando erros de validação
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