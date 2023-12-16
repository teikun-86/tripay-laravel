<?php

namespace Teikun86\Tripay\Actions;

use Teikun86\Tripay\Client;
use Teikun86\Tripay\Entities\OpenPaymentTransaction;

class OpenPayment
{
    public Client $client;

    public ?string $baseUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->baseUrl = $client->paymentBaseUrl;
    }

    public function createTransaction(string $method, string $merchantRef = null, string $customerName = null)
    {
        $response = $this->client->paymentRequest()->send("post", "open-payment/create", [
            'json' => [
                'method' => $method,
                'merchant_ref' => $merchantRef,
                'customer_name' => $customerName,
                'signature' => $this->__createSignature($method, $merchantRef)
            ]
        ]);

        if (!$response->ok()) {
            throw new \Exception("Failed to create open payment transaction. Error code: {$response->status()} {$response->reason()}");
        }

        return new OpenPaymentTransaction($response->json('data'));
    }

    public function transactionDetail(string $uuid)
    {
        $response = $this->client->paymentRequest()->send("get", "open-payment/{$uuid}/detail");

        if (!$response->ok()) {
            throw new \Exception("Failed to retrieve transaction detail. Error code: {$response->status()} {$response->reason()}");
        }

        return new OpenPaymentTransaction($response->json('data'));
    }

    public function getTransactionList(string $uuid, array $options = [])
    {
        $availableOptions = [
            'reference',
            'merchant_ref',
            'start_date',
            'end_date',
            'per_page',
            'page',
        ];

        $options = collect($options)->only($availableOptions)->toArray();

        $response = $this->client->paymentRequest()->send("GET", "open-payment/{$uuid}/transactions?" . http_build_query($options));

        if (!$response->ok()) {
            throw new \Exception("Failed to retrieve transaction list. Error code: {$response->status()} {$response->reason()}");
        }

        return collect($response->json('data'))->map(fn ($item) => new OpenPaymentTransaction($item));
    }

    private function __createSignature(string $channel, string $merchantRef)
    {
        return hash_hmac("sha256", $this->client->merchantCode . $channel . $merchantRef, $this->client->privateKey);
    }
}
