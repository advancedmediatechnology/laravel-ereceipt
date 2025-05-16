# Laravel EReceipt

Wrapper Laravel per Fiskaly eReceipt API.

## Installazione

```bash
composer require advancedmediatechnology/laravel-ereceipt
php artisan vendor:publish --tag=config
```

Aggiungi nel `.env`:

```
ERECEIPT_BASE_URL=https://receipt.fiskaly.com/api/v1
ERECEIPT_API_KEY=your_key
ERECEIPT_API_SECRET=your_secret
```

## Uso

```php
use YourVendor\EReceipt\Facades\EReceipt;

$receipt = EReceipt::createReceipt($guid, $payload);
```
