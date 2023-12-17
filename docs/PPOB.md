<h2>PPOB</h2>

<h3>Initialization</h3>

Create the `PPOB` instance.

```php
<?php
...
$client = tripayClient();
$ppob = $client->ppob();
$prepaid = $ppob->prepaid();
$postpaid = $ppob->postpaid();
```

Now you can use the `$ppob` to do:

<!-- TOC -->

- [Prepaid Transactions](#prepaid-transactions)
  - [Getting Prepaid Product Categories](#getting-prepaid-product-categories)
  - [Getting Prepaid Product Operators](#getting-prepaid-product-operators)
  - [Getting Prepaid Products](#getting-prepaid-products)
  - [Getting Prepaid Product Detail](#getting-prepaid-product-detail)
  - [Buying a Product](#buying-a-product)
- [Postpaid Transaction](#postpaid-transaction)
  - [Getting Postpaid Product Categories](#getting-postpaid-product-categories)
  - [Getting Postpaid Product Operators](#getting-postpaid-product-operators)
  - [Getting Postpaid Products](#getting-postpaid-products)
  - [Getting Postpaid Product Detail](#getting-postpaid-product-detail)
  - [Check Postpaid Product Bill](#check-postpaid-product-bill)
  - [Pay the Bill](#pay-the-bill)
- [Transaction History](#transaction-history)
  - [Get the Transaction History](#get-the-transaction-history)
  - [Get the transaction History by Date](#get-the-transaction-history-by-date)

### Prepaid Transactions

#### Getting Prepaid Product Categories

You can get the Product Categories by calling `getCategories()`. You can pass `(int) $id` as the parameter if you want to get the category with `id` = `$id`.
Returns a collection of `Teikun86\Tripay\Entities\PPOB\Category` or single `Teikun86\Tripay\Entities\PPOB\Category` if the `$id` is provided.
For example:

```php
<?php
...
$categories = $prepaid->getCategories(); // getting all the categories.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Category`
$category1 = $prepaid->getCategories(1); // getting the category with id = 1.
// Returns a single `Teikun86\Tripay\Entities\PPOB\Category` filled with the information of the Category.
```

#### Getting Prepaid Product Operators

You can get the Product Categories by calling `getOperators()`.
Below is the parameters available for `getOperators` function.

| Parameter     | Example | Type | Required | Description         |
| ------------- | ------- | ---- | -------- | ------------------- |
| `category_id` | 1       | int  | NO       | Product Category ID |
| `operator_id` | 2       | int  | NO       | Product Operator ID |

Returns a collection of `Teikun86\Tripay\Entities\PPOB\Operator` or single `Teikun86\Tripay\Entities\PPOB\Operator` if the `$operator_id` is provided.
For example:

```php
<?php
...
$operators = $prepaid->getOperators(); // getting all the operators.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Operator`

$operatorCat1 = $prepaid->getOperators(1); // getting the operators with category_id = 1.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Operator`.

$operator2 = $prepaid->getOperators(null, 2); // getting the operator with id = 2
// Returns a single `Teikun86\Tripay\Entities\PPOB\Operator` filled with the information of the Operator.
```

#### Getting Prepaid Products

You can get the Product Categories by calling `getProducts()`.
Below is the parameters available for `getProducts` function.

| Parameter     | Example | Type | Required | Description         |
| ------------- | ------- | ---- | -------- | ------------------- |
| `category_id` | 1       | int  | NO       | Product Category ID |
| `operator_id` | 2       | int  | NO       | Product Operator ID |

Returns a collection of `Teikun86\Tripay\Entities\PPOB\Product`.

For example:

```php
<?php
...
$products = $prepaid->getProducts(); // getting all the products.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Product`

$productCat1 = $prepaid->getProducts(1); // getting the products with category_id = 1.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Product`.

$productOp2 = $prepaid->getProducts(1, 2); // getting the products with category_id = 1 and operator_id = 2
// Returns a collections `Teikun86\Tripay\Entities\PPOB\Product`.
```

#### Getting Prepaid Product Detail

You can get the Product detail using `getProductDetail($productCode)` method. The `$productCode` parameter is a `string` and is **required**.
For example:

```php
<?php
...
$product = $prepaid->getProductDetail('MLD2010'); // Getting Product detail with code = "MLD2010"
// Returns a single `Teikun86\Tripay\Entities\PPOB\Product`.
```

#### Buying a Product

You can buy the product by calling the `buy()` method. Below is the parameters for the `buy()` method.

| Parameter          | Example      | Type    | Required | Description                                                           |
| ------------------ | ------------ | ------- | -------- | --------------------------------------------------------------------- |
| `productCode`      | MLD2010      | string  | YES      | Product code from the `getProducts()` or `getProductDetail()`         |
| `customerId`       | 1234567890   | string  | YES      | Customer ID, for example: Phone number, etc                           |
| `apiTransactionId` | INV0001124   | string  | NO       | Transaction ID from your server.                                      |
| `isPLN`            | false        | boolean | NO       | If the transaction is a PLN's product, pass `true`, `false` otherwise |
| `plnMeter`         | 491295192510 | string  | NO       | The number of the customer's PLN meter                                |

For example:

```php
<?php
...
$transaction = $prepaid->buy('MLD2010', '488912571924', 'INV00012412', false, ''); // buying a product with code `MLD2010`.
// Returns a `Teikun86\Tripay\Entities\Transaction` filled with the transaction detail.
```

### Postpaid Transaction

#### Getting Postpaid Product Categories

You can get the Product Categories by calling `getCategories()`. You can pass `(int) $id` as the parameter if you want to get the category with `id` = `$id`.
Returns a collection of `Teikun86\Tripay\Entities\PPOB\Category` or single `Teikun86\Tripay\Entities\PPOB\Category` if the `$id` is provided.
For example:

```php
<?php
...
$categories = $prepaid->getCategories(); // getting all the categories.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Category`
$category1 = $prepaid->getCategories(1); // getting the category with id = 1.
// Returns a single `Teikun86\Tripay\Entities\PPOB\Category` filled with the information of the Category.
```

#### Getting Postpaid Product Operators

You can get the Product Categories by calling `getOperators()`.
Below is the parameters available for `getOperators` function.

| Parameter     | Example | Type | Required | Description         |
| ------------- | ------- | ---- | -------- | ------------------- |
| `category_id` | 1       | int  | NO       | Product Category ID |
| `operator_id` | 2       | int  | NO       | Product Operator ID |

Returns a collection of `Teikun86\Tripay\Entities\PPOB\Operator` or single `Teikun86\Tripay\Entities\PPOB\Operator` if the `$operator_id` is provided.
For example:

```php
<?php
...
$operators = $postpaid->getOperators(); // getting all the operators.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Operator`

$operatorCat1 = $postpaid->getOperators(1); // getting the operators with category_id = 1.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Operator`.

$operator2 = $postpaid->getOperators(null, 2); // getting the operator with id = 2
// Returns a single `Teikun86\Tripay\Entities\PPOB\Operator` filled with the information of the Operator.
```

#### Getting Postpaid Products

You can get the Product Categories by calling `getProducts()`.
Below is the parameters available for `getProducts` function.

| Parameter     | Example | Type | Required | Description         |
| ------------- | ------- | ---- | -------- | ------------------- |
| `category_id` | 1       | int  | NO       | Product Category ID |
| `operator_id` | 2       | int  | NO       | Product Operator ID |

Returns a collection of `Teikun86\Tripay\Entities\PPOB\Product`.

For example:

```php
<?php
...
$products = $postpaid->getProducts(); // getting all the products.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Product`

$productCat1 = $postpaid->getProducts(1); // getting the products with category_id = 1.
// Returns a collection of `Teikun86\Tripay\Entities\PPOB\Product`.

$productOp2 = $postpaid->getProducts(1, 2); // getting the products with category_id = 1 and operator_id = 2
// Returns a collections `Teikun86\Tripay\Entities\PPOB\Product`.
```

#### Getting Postpaid Product Detail

You can get the Product detail using `getProductDetail($productCode)` method. The `$productCode` parameter is a `string` and is **required**.
For example:

```php
<?php
...
$product = $postpaid->getProductDetail('PLNPASCH'); // Getting Product detail with code = "PLNPASCH"
// Returns a single `Teikun86\Tripay\Entities\PPOB\Product`.
```

#### Check Postpaid Product Bill

You can check the bill for the product by calling `checkPostpaidBill()`. Below is the parameters for the `checkPostpaidBill()`:

| Parameter          | Example     | Type   | Required | Description                                                   |
| ------------------ | ----------- | ------ | -------- | ------------------------------------------------------------- |
| `productCode`      | PLNPASCH    | string | YES      | Product code from the `getProducts()` or `getProductDetail()` |
| `phone`            | 08123456789 | string | YES      | Customer's Phone Number                                       |
| `customerId`       | 412941925   | string | YES      | Customer's ID for the product.                                |
| `apiTransactionId` | INV021052   | string | NO       | The transaction ID from your end.                             |

For example:

```php
<?php
...
$bill = $postpaid->checkPostpaidBill(
    'PLNPASCH',
    '08123456789',
    '412941925',
    'INV021052',
);
// Returns a single `Teikun86\Tripay\Entities\PPOB\PostpaidBill` filled with the Bill's information.
```

#### Pay the Bill

You can pay the bill by calling the `payBill()` method. Below is the parameters for the `payBill()`:

| Parameter          | Example   | Type   | Required | Description                                                   |
| ------------------ | --------- | ------ | -------- | ------------------------------------------------------------- |
| `order_id`         | 412941925 | string | YES      | The Order ID is value of `tagihan_id` from the `checkBill()`. |
| `apiTransactionId` | INV021052 | string | NO       | The transaction ID from your end.                             |

For example:

```php
<?php
...
$pay = $postpaid->payBill(4123129, 'INV021052');
```

### Transaction History

#### Get the Transaction History

You can get the Transaction history by calling `transactionHistory()` function. Below is the parameters for the `transactionHistory()`:

| Parameter    | Example   | Type   | Required | Description                             |
| ------------ | --------- | ------ | -------- | --------------------------------------- |
| `trx_id`     | 412941925 | string | NO       | The transaction ID returned from TriPay |
| `api_trx_id` | INV021052 | string | NO       | The transaction ID from your end.       |

For example:

```php
<?php
...
$trxHistory = $ppob->transactionHistory(); // getting all the transaction history
// Returns a collection of `Teikun86\Tripay\Entities\Transaction`

$trx2 = $ppob->transactionHistory(2942); // getting a single transaction with id = 2942
// Return a single `Teikun86\Tripay\Entities\Transaction`

$trx3 = $ppob->transactionHistory(null, 'INV284192'); // getting the transaction history associated with the given $api_trx_id
// Returns a collection of `Teikun86\Tripay\Entities\Transaction`
```

#### Get the transaction History by Date

You can get the Transaction history by date by calling `transactionHistoryByDate()` function. Below is the parameters for the `transactionHistoryByDate()`:

| Parameter    | Example    | Type   | Required | Description                       |
| ------------ | ---------- | ------ | -------- | --------------------------------- |
| `start_date` | 2023-01-01 | string | YES      | Starting date. Format: YYYY-MM-DD |
| `end_date`   | 2023-01-20 | string | YES      | Ending date. Format: YYYY-MM-DD   |

For example:

```php
<?php
...
$trxHistory = $ppob->transactionHistoryByDate('2023-01-01', '2023-01-20'); // getting all the transaction history from 1 January 2023 to 20 January 2023
// Returns a collection of `Teikun86\Tripay\Entities\Transaction`
```