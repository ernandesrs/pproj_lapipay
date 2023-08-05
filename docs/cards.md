# Criando cartão
Valide e salve um cartão na sua base de dados. O cartão pertencerá ao usuário injetado.
```php

$user = \App\Models\User::where("id", 1)->first();
$card = (new Lapipay())->card()
    ->create($user, $user->customerName(), '4716380053580331', '1029', '918');

var_dump($card);

```

# Capturando erros de validação
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

# Obtendo cartões de um cliente
Obtenha todos os cartões salvos na sua base de dados para o cliente atual.
```php

$user = \App\Models\User::where("id", 1)->first();
$cards = $user->cards()->get();

var_dump($cards);

```