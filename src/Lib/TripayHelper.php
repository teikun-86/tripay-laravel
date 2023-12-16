<?php

namespace Teikun86\Tripay\Lib;

use Teikun86\Tripay\Client;

class TripayHelper
{
    /**
     * Create a signature for transaction
     */
    public static function createSignature(Client $client, array $payloads): string
    {
        $merchantRef = isset($payloads['merchant_ref']) ? $payloads['merchant_ref'] : null;
        $amount = self::formatAmount($payloads['amount']);

        return hash_hmac('sha256', $client->merchantCode . $merchantRef . $amount, $client->privateKey);
    }

    /**
     * Create a signature for transaction with JSON Payload
     */
    public static function createSignatureFromJSON(Client $client, string $payload): string
    {
        return hash_hmac('sha256', $payload, $client->privateKey);
    }

    /**
     * Format the given amount to integer.
     */
    public static function formatAmount(int|float $amount): int
    {
        if (!is_numeric($amount)) {
            throw new \Exception('Amount must be numeric value');
        }

        return (int) number_format($amount, 0, '', '');
    }

    /**
     * Create Signature for Open Payment
     */
    public static function createOpenPaymentSignature(Client $client, array $payloads): string
    {
        $method = isset($payloads['method']) ? $payloads['method'] : null;
        $merchantRef = isset($payloads['merchant_ref']) ? $payloads['merchant_ref'] : null;

        return hash_hmac('sha256', $client->merchantCode . $method . $merchantRef, $client->privateKey);
    }

}