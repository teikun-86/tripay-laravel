<h2>Payment</h2>

### Initialization
Create the `Payment` instance.
```php
<?php
...
$client = tripayClient();
$payment = $client->createPayment();
```
Now you can use the `$payment` to get:
- [Initialization](#initialization)
- [Getting Payment Channels](#getting-payment-channels)
- [Getting Payment Instructions](#getting-payment-instructions)
- [Calculate Fees](#calculate-fees)


### Getting Payment Channels
To get the payment channels, you can call `channels(bool $grouped = false)` function, you can pass `true` to `channels($grouped)` if you want the payment channels to be grouped by their type.
Example:
```php
<?php
...
$notGrouped = $payment->channels(); // not grouped
$grouped = $payment->channels(true); // grouped

// example output as array. Real output returns a collection instance.
[
    "Virtual Account" => [
        ... // payment channels in Virtual Account category.
    ],
    "E-Wallet" => [
        ... // payment channels in E-Wallet category
    ],
    ...
]
```
 
### Getting Payment Instructions
To get the Payment Instructions, you can call the `instruction()` method. Below is the parameters for the `instruction()` method.
| Parameter      | Example    | Type    | Required | Description                                                                         |
| -------------- | ---------- | ------- | -------- | ----------------------------------------------------------------------------------- |
| `code`         | BRIVA      | string  | YES      | Payment Method Code ([See more](https://tripay.co.id/developer?tab=channels))       |
| `payment_code` | 1234567890 | string  | NO       | Payment Code, example for VA code                                                   |
| `amount`       | 10000      | int     | NO       | Amount to pay                                                                       |
| `allowHtml`    | false      | boolean | NO       | To allow HTML tag insertions on the instruction. Allow = 1, Disallow = 0, Default 0 |

For example:
```php
<?php
...
$instructions = $payment->instruction('BRIVA', '1234567890', 10000, false);
// Returns a collection of Teikun86\Tripay\Entities\PaymentInstruction::class
```

### Calculate Fees
You can calculate fees for the defined payment method by calling `calculateFees()` method. Below is the parameters for the `calculateFees()` method:
| Parameter      | Example    | Type    | Required | Description                                                                         |
| -------------- | ---------- | ------- | -------- | ----------------------------------------------------------------------------------- |
| `amount`       | 10000      | int     | NO       | Amount to pay                                                                       |
| `code`         | BRIVA      | string  | YES      | Payment Method Code ([See more](https://tripay.co.id/developer?tab=channels))       |

For example:
```php
<?php
...
$fees = $payment->calculateFees(10000, 'BRIVA');
// Returns a collection of Teikun86\Tripay\Entities\Fee::class
```