<h2>Callback</h2>

There is 2 type of callbacks, PPOB Callback, and Payment Callback. But we only need 1 method to get the callback. That is `tripayCallback(bool $validateImmediately = true)` function that can be called globally, inside the method, we check the callback type, if the callback type is PPOB, then the instance returned is `PPOBCallback`, otherwise, it will return `Callback`.

Let's start with creating the instance.

```php
<?php
...
$callback = tripayCallback(); // this will validate the callback and return the Callback instance if validation is success, and throw an exception if validation failed
// or
$callback = tripayCallback(false); // this will return the Callback instance without validating the callback.
```

To manually validate the callback, we can use the `validate()` function on both `PPOBCallback` and `Callback`, it will validate the callback signature and payload, and return `true` if the validation is success, and throw an Exception if validation failed. The best practice is to wrap the `validate()` function with try-catch block, so you can handle the exception if the validation failed.


```php
<?php
...
$callback = tripayCallback(false); // return the callback instance without validating.
try {
	$valid = $callback->validate(); // validate the callback signature and payload
} catch (\Throwable $e) {
	// handle the exception here
    echo "Error: " . $e->getMessage();
}
```

Both `PPOBCallback` and `Callback` have `payloads()` method that will return the Callback's payload.
For example:

```php
<?php
...
$payloads = $callback->payloads();
```

Callback example as JSON:

```json
{
	"trxid": "158561****",
	"api_trxid": "INV45769",
	"via": "API",
	"code": "S100",
	"produk": "Telkomsel 100",
	"harga": "97765",
	"target": "08522083****",
	"mtrpln": "-",
	"note": "Trx S100 08522083**** SUKSES. SN: 845392759476503****",
	"token": "845392759476503****",
	"status": "1",
	"saldo_before_trx": "100000",
	"saldo_after_trx": "5894",
	"created_at": "2019-11-06 12:07:48",
	"updated_at": "2019-11-15 20:59:10",
	"tagihan": null
}
```
