<h1 align="center">teikun86/tripay-laravel</h1>

<h6 align="center"> Unofficial TriPay Payment Gateway & PPOB integration for Laravel.</h6>

<p align="center">
    <img src="https://img.shields.io/github/v/release/teikun-86/tripay-laravel?include_prereleases" alt="release"/>
    <img src="https://img.shields.io/github/languages/top/teikun-86/tripay-laravel" alt="languages"/>
    <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/teikun-86/tripay-laravel">
    <img alt="GitHub code size in bytes" src="https://img.shields.io/github/languages/code-size/teikun-86/tripay-laravel">
    <img alt="GitHub License" src="https://img.shields.io/github/license/teikun-86/tripay-laravel">
    <img alt="PR Welcome" src="https://img.shields.io/badge/PRs-Welcome-blue">
</p>

<p align="center">~Under Development. More Docs soon~</p>

### Table Of Contents
- [Requirements](#requirements)
- [Instalation](#instalation)
- [Usage](#usage)
  - [Basic Usage](#basic-usage)
    - [1. Creating Tripay Client](#1-creating-tripay-client)
  - [Payment Gateway](#payment-gateway)
    - [1. Getting Payment Instruction](#1-getting-payment-instruction)


## Requirements

-   PHP v8.1+
-   PHP JSON Extension
-   PHP cURL Extension
-   Laravel v10+

## Instalation

1. Run composer require command

    ```bash
    composer require teikun-86/tripay-laravel
    ```

2. Publish Configuration file

    ```bash
    php artisan vendor:publish --provider=Teikun86\Tripay\Providers\TripayServiceProvider --tag=tripay-config
    ```

3. Add these config to your `.env` file
    ```conf
     TRIPAY_MERCHANT_CODE=""
     TRIPAY_API_KEY=""
     TRIPAY_PPOB_API_KEY=""
     TRIPAY_PRIVATE_KEY=""
     TRIPAY_PPOB_PIN=""
    ```
4. Happy Coding~

## Usage

### Basic Usage

#### 1. Creating Tripay Client
    ```php
    <?php
    ...
    $client = tripayClient();
    ```

### Payment Gateway

#### 1. Getting Payment Instruction

    <b>Parameters<b>

    | Parameter      | Example    | Type    | Required | Description                                                                         |
    | -------------- | ---------- | ------- | -------- | ----------------------------------------------------------------------------------- |
    | `code`         | BRIVA      | string  | YES      | Payment Method Code ([See more](https://tripay.co.id/developer?tab=channels))       |
    | `payment_code` | 1234567890 | string  | NO       | Payment Code, example for VA code                                                   |
    | `amount`       | 10000      | int     | NO       | Amount to pay                                                                       |
    | `allowHtml`    | false      | boolean | NO       | To allow HTML tag insertions on the instruction. Allow = 1, Disallow = 0, Default 0 |

    ```php
    <?php
    ... // rest of your codes
    $code = "BRIVA"; // Payment method code
    $payment_code = "1234567890"; // optional
    $amount = 10000; // optional
    $allowHtml = false;
    $instructions = $client->createPayment()->instruction($code, $payment_code, $amount, $allowHtml);
    ```

    The code above will return:

    ```
    Illuminate\Support\Collection {#6396
    all: [
      Teikun86\Tripay\Entities\PaymentInstruction {#6384},
      Teikun86\Tripay\Entities\PaymentInstruction {#6411},
      Teikun86\Tripay\Entities\PaymentInstruction {#6410},
    ],
    }
    ```

<p align="center">
    Made with 💓 by <a href="https://github.com/teikun-86">teikun-86</a>
</p>