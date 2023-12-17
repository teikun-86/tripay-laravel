<h2>Payment</h2>

<h3> Initialization</h3>

Create the `Payment` instance.

```php
<?php
...
$client = tripayClient();
$payment = $client->createPayment();
```

Now you can use the `$payment` to get:

- [Getting Payment Channels](#getting-payment-channels)
- [Getting Payment Instructions](#getting-payment-instructions)
- [Calculate Fees](#calculate-fees)
- [Create Transaction](#create-transaction)
  - [Instances](#instances)
  - [Closed Payment](#closed-payment)
  - [Open Payment](#open-payment)

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
| Parameter | Example | Type | Required | Description |
| -------------- | ---------- | ------- | -------- | ----------------------------------------------------------------------------------- |
| `code` | BRIVA | string | YES | Payment Method Code ([See more](https://tripay.co.id/developer?tab=channels)) |
| `payment_code` | 1234567890 | string | NO | Payment Code, example for VA code |
| `amount` | 10000 | int | NO | Amount to pay |
| `allowHtml` | false | boolean | NO | To allow HTML tag insertions on the instruction. Allow = 1, Disallow = 0, Default 0 |

For example:

```php
<?php
...
$instructions = $payment->instruction('BRIVA', '1234567890', 10000, false);
// Returns a collection of Teikun86\Tripay\Entities\PaymentInstruction::class
```

### Calculate Fees

You can calculate fees for the defined payment method by calling `calculateFees()` method. Below is the parameters for the `calculateFees()` method:
| Parameter | Example | Type | Required | Description |
| -------------- | ---------- | ------- | -------- | ----------------------------------------------------------------------------------- |
| `amount` | 10000 | int | NO | Amount to pay |
| `code` | BRIVA | string | YES | Payment Method Code ([See more](https://tripay.co.id/developer?tab=channels)) |

For example:

```php
<?php
...
$fees = $payment->calculateFees(10000, 'BRIVA');
// Returns a collection of Teikun86\Tripay\Entities\Fee::class
```

### Create Transaction

#### Instances

1. Order Class (Teikun86\Tripay\Entities\Order)<br>
   Order class is an instance to store the order data that will be send to TriPay API. Below are the methods for this Order class:
    1. `amount(float $amount)` to set the transaction amount in IDR. For example:
        ```php
        <?php
        $order->amount(10000); // set the amount to 10000 IDR.
        ```
    2. `customerName(string $customer_name)` to set the customer name for the transaction. For example:
        ```php
        <?php
        $order->customerName('udin'); // set the customer name to 'udin'.
        ```
    3. `customerPhone(string $customer_phone)` to set the customer phone number for the transaction. For example:
        ```php
        <?php
        $order->customerPhone('08123456789'); // set the customer phone number to '08123456789'.
        ```
    4. `customerEmail(string $customer_email)` to set the customer email address for the transaction. For example:
        ```php
        <?php
        $order->customerEmail('udin123@gmail.com'); // set the customer email address to 'udin123@gmail.com'.
        ```
    5. `paymentMethod(string $method)` to set the payment method used in the transaction. For example:
        ```php
        <?php
        $order->paymentMethod('BRIVA'); // set the payment method to BRI Virtual Account.
        ```
    6. `redirectUrl(string $return_url)` to set the redirect url after the user finish the transaction. For example:
        ```php
        <?php
        $order->redirectUrl('https://my-store.com/tripay-rdr');
        ```
    7. `reference(string $merchant_ref)` to set the merchant ref, or invoice number on your server for the transaction. For example:
        ```php
        <?php
        $order->reference('INV04991288');
        ```
    8. `expiresAt(\Illuminate\Support\Carbon $expiresAt)` to set the transaction's expiration time. For example:
        ```php
        <?php
        $in3hours = now()->addHours(3);
        $order->expiresAt($in3Hours);
        ```
    9. `addItem(?string $sku = null, ?string $name = null, float|int|null $price = 0, ?int $quantity = 1, ?string $product_url = null, ?string $image_url = null)` add an Item to the Order. This function returns [`OrderItem`]() class, so you can modify the `OrderItem` later or just directly fill the `OrderItem`'s details in one line. You can add more than 1 item to the order. For example:
        ```php
        <?php
        $newItem = $order->addItem(); // Create empty item, then fill the item's detail later
        // Fill the item detail.
        $newItem->sku("TKG-492981");
        ...
        // Create complete item
        $order->addItem("TKG-492981", "Item Name", 100000, 1, 'https://my-store.com/my-product', 'https://my-store.com/my-product/image.jpg');
        ```
    10. `setSignature(string $signature)` set the signature for the transaction. But you don't have to do it manually, you can ignore the existence of this method because the signature will be filled automatically when you execute the transaction.
2. OrderItem Class (Teikun86\Tripay\Entities\OrderItem)<br>
   An instance to store items that inside the Order class. Below are the methods for the OrderItem class:
    1. `sku(?string $sku)` to set the Item's SKU, for example:
        ```php
        ...
        $item->sku("TKG-42194");
        ```
    2. `name(?string $name)` to set the Item's name, for example:
        ```php
            ...
            $item->name("Item name");
        ```
    3. `price(int $price)` to set the Item's price, for example:
        ```php
            ...
            $item->price(10000);
        ```
    4. `quantity(int $quantity = 1)` to set the Item's quantity, for example:
        ```php
            ...
            $item->quantity(1);
        ```
    5. `productUrl(string $product_url = 1)` to set the Item's url on your website, for example:
        ```php
            ...
            $item->productUrl("https://my-store.com/my-product");
        ```
    6. `imageUrl(string $image_url = 1)` to set the Item's image, for example:
        ```php
            ...
            $item->imageUrl("https://my-store.com/my-product/image.jpg");
        ```

#### Closed Payment

Here's the steps to create a closed payment transaction:

1. Create a Transaction instance from `$client`:
    ```php
    <?php
    ...
    $transaction = $client->createTransaction();
    ```
2. Add Order to Transaction instance:
    ```php
    <?php
    ...
    $order = $transaction->addOrder(); // returns new Teikun86\Tripay\Entities\Order::class
    ```
3. Fill the Order as you wish, like add items, etc.
4. Send the Transaction
    ```php
    <?php
    ...
    $result = $transaction->send();
    // The $result will contain response data from TriPay in form of Order instance.
    ```
    The `$response` will be in a form of `Order` class, but there are example `$response` as JSON:
    ```json
    {
    	"reference": "T0001000000000000006",
    	"merchant_ref": "INV345675",
    	"payment_selection_type": "static",
    	"payment_method": "BRIVA",
    	"payment_name": "BRI Virtual Account",
    	"customer_name": "Nama Pelanggan",
    	"customer_email": "emailpelanggan@domain.com",
    	"customer_phone": "081234567890",
    	"callback_url": "https://domainanda.com/callback",
    	"return_url": "https://domainanda.com/redirect",
    	"amount": 1000000,
    	"fee_merchant": 1500,
    	"fee_customer": 0,
    	"total_fee": 1500,
    	"amount_received": 998500,
    	"pay_code": "57585748548596587",
    	"pay_url": null,
    	"checkout_url": "https://tripay.co.id/checkout/T0001000000000000006",
    	"status": "UNPAID",
    	"expired_time": 1582855837,
    	"order_items": [
    		{
    			"sku": "PRODUK1",
    			"name": "Nama Produk 1",
    			"price": 500000,
    			"quantity": 1,
    			"subtotal": 500000,
    			"product_url": "https://tokokamu.com/product/nama-produk-1",
    			"image_url": "https://tokokamu.com/product/nama-produk-1.jpg"
    		},
    		{
    			"sku": "PRODUK2",
    			"name": "Nama Produk 2",
    			"price": 500000,
    			"quantity": 1,
    			"subtotal": 500000,
    			"product_url": "https://tokokamu.com/product/nama-produk-2",
    			"image_url": "https://tokokamu.com/product/nama-produk-2.jpg"
    		}
    	],
    	"instructions": [
    		{
    			"title": "Internet Banking",
    			"steps": [
    				"Login ke internet banking Bank BRI Anda",
    				"Pilih menu <b>Pembayaran</b> lalu klik menu <b>BRIVA</b>",
    				"Pilih rekening sumber dan masukkan Kode Bayar (<b>57585748548596587</b>) lalu klik <b>Kirim</b>",
    				"Detail transaksi akan ditampilkan, pastikan data sudah sesuai",
    				"Masukkan kata sandi ibanking lalu klik <b>Request</b> untuk mengirim m-PIN ke nomor HP Anda",
    				"Periksa HP Anda dan masukkan m-PIN yang diterima lalu klik <b>Kirim</b>",
    				"Transaksi sukses, simpan bukti transaksi Anda"
    			]
    		}
    	],
    	"qr_string": null,
    	"qr_url": null
    }
    ```
5. Finish.

#### Open Payment
Open payment is usually used for top up in-app balance. One payment code can be used multiple times.

1.  Creating an `OpenPayment` instance
    ```php
    <?php
    ...
    $openPayment = $client->createOpenPayment();
    ```
2.  Creating Transaction<br>
    Below are the parameters for the `createTransaction()` method in `OpenPayment` class

    | Parameter      | Example   | Type   | Required | Description                   |
    | -------------- | --------- | ------ | -------- | ----------------------------- |
    | `method`       | BRIVA     | string | YES      | Payment Method                |
    | `merchantRef`  | INV012949 | string | NO       | Transaction ID from your end. |
    | `customerName` | Udin      | string | NO       | Customer's name.              |

    Here's how to create Open Payment transaction:
    ```php
    <?php
    ...
    $method = "BRIVA";
    $merchantRef = "INV012949";
    $customerName = "Udin";
    $tx = $openPayment->createTransaction($method, $merchantRef, $customerName);
    ```
    The code above will return a `Teikun86\Tripay\Entities\OpenPaymentTransaction` instance. And will throw an exception if the transaction creation is failed.

3. Getting a Transaction Detail<br>
    To get the transaction detail, first, you need the `uuid` of the transaction, you can get the `uuid` from `createTransaction()` before.
    ```php
    <?php
    ...
    $uuid = $tx->uuid;
    $detail = $openPayment->getTransactionDetail($uuid);
    ```
    The code above will return a `Teikun86\Tripay\Entities\OpenPaymentTransaction` instance. And will throw an exception if the request is failed.

4. Getting Transaction List<br>
    We will use the `getTransactionList()` method for this, and there's 2 parameters for the method, first is `uuid`, `uuid` is required for this method. Next we have an array of `options` as an optional parameter, below is the available options:

    | Parameter      | Example              | Type   | Required | Description                                              |
    |----------------|----------------------|--------|----------|----------------------------------------------------------|
    | `reference`    | T0001000000000000006 | string | NO       | Transaction Reference from TriPay                        |
    | `merchant_ref` | INV012949            | string | NO       | Transaction ID from your end.                            |
    | `start_date`   | 2023-01-01 00:00:00  | string | NO       | Filter transaction by starting date. Format: Y-m-d H:i:s |
    | `end_date`     | 2023-01-01 23:59:59  | string | NO       | Filter transaction by end date. Format: Y-m-d H:i:s      |
    | `per_page`     | 50                   | int    | NO       | Transaction amount per page.                             |
    | `page`         | 1                    | int    | NO       | The current page for pagination.                         |

    Here's the example:
    ```php
    <?php
    ...
    $uuid = $tx->uuid;
    $options = [
        'start_date' => '2023-01-01 00:00:00',
        'end_date' => '2023-12-31 23:59:59'
    ];
    $history = $openPayment->getTransactionList($uuid, $options);
    ```
    The code above will return a collection of `Teikun86\Tripay\Entities\OpenPaymentTransaction` instances. And will throw an exception if the request is failed.