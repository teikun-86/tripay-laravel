## Basic

### Creating a Client

`Teikun86\Tripay\Client::class` class
is a root class for any transactions in tripay-laravel.
To make this, simply call `tripayClient()` method or use `Teikun86\Tripay\Tripay::class` facade.
For example:

```php
<?php
...
// using tripayClient() function.
$client = tripayClient();
$payment = $client->createPayment();
$ppob = $client->ppob();

// using facade
$payment = Tripay::createPayment();
$ppob = Tripay::ppob();
```

From the code above, you can see that you can use `Tripay` facade to call the functions inside the `Client` class.

More docs:
- [Payment](https://github.com/teikun-86/tripay-laravel/tree/main/docs/Payment.md)
- [PPOB](https://github.com/teikun-86/tripay-laravel/tree/main/docs/PPOB.md)
- [Handling Callback](https://github.com/teikun-86/tripay-laravel/tree/main/docs/Callback.md)