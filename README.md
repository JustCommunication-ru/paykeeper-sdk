# paykeeper.ru PHP SDK

[![Latest Stable Version](https://poser.pugx.org/justcommunication-ru/paykeeper-sdk/v)](//packagist.org/packages/justcommunication-ru/tinkoff-acquiring-api-client)
[![Latest Unstable Version](http://poser.pugx.org/justcommunication-ru/paykeeper-sdk/v/unstable)](https://packagist.org/packages/justcommunication-ru/tinkoff-acquiring-api-client)
[![Total Downloads](https://poser.pugx.org/justcommunication-ru/paykeeper-sdk/downloads)](//packagist.org/packages/justcommunication-ru/tinkoff-acquiring-api-client)
[![License](http://poser.pugx.org/justcommunication-ru/paykeeper-sdk/license)](https://packagist.org/packages/justcommunication-ru/tinkoff-acquiring-api-client)


PHP SDK для сервиса https://paykeeper.ru

- [Установка](#установка)
- [Использование](#использование)
- [Методы](#методы)
- [Настройка HTTP клиента](#настройка-http-клиента)
- [Логирование](#логирование)
- [Тесты](#тесты)

## Установка

`composer require justcommunication-ru/paykeeper-sdk`

## Использование

```php
use JustCommunication\PaykeeperSDK\PaykeeperAPIClient;

$client = new PaykeeperAPIClient($url, $username, $password, $tokenHandler);
```

`$url` — Адрес вашего кабинета Paykeeper. Выдается каждому клиенту индивидуально. Например: `https://somename.server.paykeeper.ru`

`$username` — имя пользователя

`$password` — пароль пользователя

`$tokenHandler` — необязательный аргумент – хендлер токена.

### Хендлеры токена

Токен безопасности выдается на ограниченное время, не лишним будет сохранять и обновлять его автоматически. В случае, если API выдало ошибку
проверки токена, то будет произведена попытка обновить этот токен и повторить запрос автоматически.

Данный аргумент является необязательным, если его не передавать, то будет использоваться InMemory хендлер.

#### Доступные хендлеры:

##### InMemoryTokenHandler

Хранит хендлер в памяти со всеми вытекающими последствиями. Если php скрипт завершил работу, то при новом запросе будет запрошен новый токен.

##### FileTokenHandler

Хранит токен на файловой системе

```php
use JustCommunication\PaykeeperSDK\TokenHandler\FileTokenHandler;

$tokenHandler = new FileTokenHandler('/path/to/token.txt');
```

##### CallbackTokenHandler

Хендлер, который позволяет читать и сохранять токен по колбэкам

```php
use JustCommunication\PaykeeperSDK\TokenHandler\CallbackTokenHandler;

$tokenHandler = new CallbackTokenHandler(function () use ($db) {
    return $db->fetchColumn('SELECT token FROM tokens WHERE name = "paykeeper"');
}, function ($new_token) {
    $db->update('UPDATE tokens SET token = :token WHERE name = "paykeeper"', [
        'token' => $new_token
    ]);
});
```

##### Свой хендлер

Для реализации своего хендлера реализуйте интерфейс `TokenHandlerInterface`


## Методы

[Подготовка счета](#подготовка-счета)

[Получение информации о счете](#получение-информации-о-счете)

[История счетов](#история-счетов)

### Подготовка счета

```php
use JustCommunication\PaykeeperSDK\API\Invoice\InvoicePreviewRequest;

$invoicePreviewRequest = new InvoicePreviewRequest();
$invoicePreviewRequest
    ->setOrderId(123)
    ->setServiceName('Test service')
    ->setAmount(100)
;

try {
    $response = $client->sendInvoicePreviewRequest($invoicePreviewRequest);

    header('Location: ' . $response->getInvoiceUrl()); // перенаправляем пользователя на страницу оплаты
    exit;
} catch (PaykeeperAPIException $e) {
    // обработка ошибки
}
```

### Получение информации о счете

```php
$invoice = $client->getInvoice($invoice_id);

$invoice->getId();
$invoice->getStatus();
$invoice->getCreatedAt();
$invoice->getPaidAt();
$invoice->getPayAmount();
```

### История счетов

```php
use JustCommunication\PaykeeperSDK\API\Invoice\InvoiceListRequest;

$invoiceListRequest = new InvoiceListRequest();
$invoiceListRequest->setStatuses([
    $invoiceListRequest::STATUS_SENT,
    $invoiceListRequest::STATUS_PAID,
    $invoiceListRequest::STATUS_EXPIRED
])
    ->setStartDate(new \DateTime('-10 days'))
    ->setEndDate(new \DateTime('+10 days'))
;

$response = $client->sendInvoiceListRequest($invoiceListRequest);

foreach ($response->getInvoices() as $invoice) {
    $invoice->getId();
    $invoice->getStatus();
    $invoice->getCreatedAt();
    $invoice->getPaidAt();
    $invoice->getPayAmount();
}
```

## Настройка HTTP клиента

### Способ №1: передача массива параметров

```php
use JustCommunication\PaykeeperSDK\PaykeeperAPIClient;

$client = new PaykeeperAPIClient($url, $username, $password, $tokenHandler, [
    'proxy' => 'tcp://localhost:8125',
    'timeout' => 6,
    'connect_timeout' => 4
]);
```

Список доступных параметров: https://docs.guzzlephp.org/en/stable/request-options.html

### Способ №2: передача своего `\GuzzleHttp\Client`

Настройте своего http клиента:

```php
// Http клиент с логгированием всех запросов

$stack = HandlerStack::create();
$stack->push(Middleware::log($logger, new MessageFormatter(MessageFormatter::DEBUG)));

$httpClient = new \GuzzleHttp\Client([
    'handler' => $stack,
    'timeout' => 6
]);
```

и передайте его аргументом конструктора:

```php
use JustCommunication\PaykeeperSDK\PaykeeperAPIClient;

$client = new PaykeeperAPIClient($url, $username, $password, $tokenHandler, $httpClient);
```

либо сеттером:

```php
use JustCommunication\PaykeeperSDK\PaykeeperAPIClient;

$client = new PaykeeperAPIClient($url, $username, $password, $tokenHandler);
$client->setHttpClient($httpClient);
```

## Логирование

В `$client` можно передать свой `Psr\Logger`.

```php
$client->setLogger($someLogger);
```

По-умолчанию логирование отключено (NullLogger)

## Тесты

Запустить тесты можно командой:

`vendor/bin/phpunit`