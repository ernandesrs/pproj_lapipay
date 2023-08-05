# LAPIPAY
Este é um projeto de estudo de criação de pacotes Laravel e uso de APIs de pagamento.

Este pacote fará, inicialmente, integração com a API da Pagarme.

# GATEWAYS IMPLEMENTADAS
Pagarme: pagarme

# INSTALAÇÃO
> composer require ernandesrs/lapipay

# CONFIGURAÇÃO
Guia de configuração do pacote LAPIPAY.

## Variáveis ambiente
No arquivo .env do seu projeto, adicione as seguintes variáveis:
```

PAYMENT_TESTING=true
PAYMENT_DEFAULT_GATEWAY=pagarme
PAYMENT_POSTBACK_URL_TEST=

PAYMENT_PAGARME_API_TEST=
PAYMENT_PAGARME_API_LIVE=
PAYMENT_PAGARME_API_ANTIFRAUD=false

```
Veja uma breve explicação das chaves acima.
| CHAVE | DESCRIÇÃO |
| --- | --- |
| PAYMENT_TESTING | Define se o sistema de cobrança está em testes. Se definido como false, o sistema de cobrança irá efetuar cobranças reais. |
| PAYMENT_DEFAULT_GATEWAY | Define a gateway que será utilizada. Veja o início da documentação as [gateways implementadas](#gateways-implementadas). |
| PAYMENT_POSTBACK_URL_TEST | Url de postback para teste local. Veja o [arquivo de configuração](src/config/lapipay.php) para mais detalhes |
| PAYMENT_PAGARME_API_TEST | Chave de teste da api(cobranças falsas para testes). |
| PAYMENT_PAGARME_API_LIVE | Chave de produção da api(cobranças reais). |
| PAYMENT_PAGARME_API_ANTIFRAUD | Define se o recurso de antifraude está habilitado na Pagar.me. Quando habilitado, alguns dados extras são obrigatórios em cobranças com cartão de crédito. |

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

## (Opcional) Publique os arquivos de idiomas
Na raiz do seu projeto Laravel, publique os arquivos de idiomas com o seguinte comando:
> php artisan vendor:publish --tag=lapipay-lang

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

Alguns métodos da trait são importantes e seus retornos precisam ser adaptadas ao seu modelo User. Copie, cole e adapte ao seu modelo.
```php

    /**
     * Customer id
     *
     * @return string
     */
    public function customerId(): string
    {
        return $this->id;
    }

    /**
     * Customer first name
     *
     * @return string
     */
    public function customerFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * Customer last name
     *
     * @return string
     */
    public function customerLastName(): string
    {
        return $this->last_name;
    }

    /**
     * Customer email
     *
     * @return string
     */
    public function customerEmail(): string
    {
        return $this->email;
    }

    /**
     * Customer country
     *
     * @return string
     */
    public function customerCountry(): string
    {
        return 'br';
    }

    /**
     * Customer type
     * Tipo de pessoa, individual ou corporation
     *
     * @return string
     */
    public function customerType(): string
    {
        return 'individual';
    }

    /**
     * Customer phone number
     *
     * @return \Ernandesrs\Lapipay\Models\Phone
     */
    public function customerPhone(): \Ernandesrs\Lapipay\Models\Phone
    {
        return new \Ernandesrs\Lapipay\Models\Phone(55, 00000000000);
    }

    /**
     * Customer document
     *
     * @return \Ernandesrs\Lapipay\Models\Document
     */
    public function customerDocument(): \Ernandesrs\Lapipay\Models\Document
    {
        // return \Ernandesrs\Lapipay\Models\Document::cnpj(12345678910);
        // return \Ernandesrs\Lapipay\Models\Document::cnh(12345678910);
        return \Ernandesrs\Lapipay\Models\Document::cpf(12345678910);
    }

    /**
     * Customer address
     *
     * @return \Ernandesrs\Lapipay\Models\Address
     */
    public function customerAddress(): \Ernandesrs\Lapipay\Models\Address
    {
        return new \Ernandesrs\Lapipay\Models\Address(
            'Rua Street',
            7822,
            '29315000',
            'br',
            'sp',
            'São Paulo',
            'Centro',
            'Apartamento 98 Andar 12'
        );
    }

```

# USO
O uso é bastante simples, veja:

## Clientes
Ao criar um cliente, os dados do cliente serão salvos na base dados da gateway que gerará um ID para o cliente. Este ID será salvo na sua base dados e então poderá ser utilizado futuramente para facilitar outros processos(transações), bastando passar o ID.

Documentação | [PT-BR](docs/customers.md)

## Cartões
Valida e salva um hash seguro do cartão gerado pela gateway na base de dados, isso facilitará cobranças de maneira segura.

Documentação | [PT-BR](docs/cards.md)

## Pagamentos/cobranças
Todo processo para realização de pagamento/cobrança: adicionar cliente, produtos e efetuação de cobrança/pagamento.

Documentação | [PT-BR](docs/payments.md)