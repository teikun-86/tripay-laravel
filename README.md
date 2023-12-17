<h1 align="center">teikun86/tripay-laravel</h1>

<h6 align="center"> Unofficial TriPay Payment Gateway & PPOB integration for Laravel.</h6>

<p align="center">
    <img src="https://img.shields.io/github/v/release/teikun-86/tripay-laravel?include_prereleases=" alt="release"/>
    <img src="https://img.shields.io/github/languages/top/teikun-86/tripay-laravel" alt="languages"/>
    <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/teikun-86/tripay-laravel">
    <img alt="GitHub code size in bytes" src="https://img.shields.io/github/languages/code-size/teikun-86/tripay-laravel">
    <img alt="GitHub License" src="https://img.shields.io/github/license/teikun-86/tripay-laravel">
    <img alt="PR Welcome" src="https://img.shields.io/badge/PRs-Welcome-blue">
</p>

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
    TRIPAY_PPOB_SECRET_CALLBACK=""
    ```
4. Happy Coding~

## Usage
See [`/docs`](https://github.com/teikun-86/tripay-laravel/tree/main/docs) for more documentations.

<p align="center">
    Made with 💓 by <a href="https://github.com/teikun-86">teikun-86</a>
</p>
