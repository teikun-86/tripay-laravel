<?php

namespace Teikun86\Tripay;

use Illuminate\Http\Client\PendingRequest as HttpClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Teikun86\Tripay\Actions\Payment;
use Teikun86\Tripay\Actions\PPOB;
use Teikun86\Tripay\Actions\Transaction;

class Client
{
    public ?string $merchantCode;

    public ?string $apiKey;

    public ?string $ppobApiKey;

    public ?string $privateKey;

    public array $options;

    public string $ppobBaseUrl;

    public string $paymentBaseUrl;

    public string $ppobPin;

    public HttpClient $httpClient;

    public array $debugs = [
        'request' => null,
        'response' => null
    ];

    public function __construct(
        string $merchantCode,
        string $apiKey,
        string $ppobApiKey,
        string $privateKey,
        string $ppobBaseUrl,
        string $paymentBaseUrl,
        string $ppobPin
    ) {
        $this->merchantCode = $merchantCode;
        $this->apiKey = $apiKey;
        $this->ppobApiKey = $ppobApiKey;
        $this->privateKey = $privateKey;
        $this->ppobBaseUrl = $ppobBaseUrl;
        $this->paymentBaseUrl = $paymentBaseUrl;
        $this->ppobPin = $ppobPin;
        $this->httpClient = $this->createHttpClient();
    }

    public function createHttpClient(): PendingRequest
    {
        return Http::acceptJson()
            ->asJson()
            ->withToken($this->apiKey)
            ->withOptions([
                'http_errors' => false,
                'on_stats' => function(\GuzzleHttp\TransferStats $stats) {
                    $hasResponse = $stats->hasResponse();
                    $this->debugs = array_merge($this->debugs, [
                        'request' => [
                            'url' => $stats->getEffectiveUri(),
                            'method' => $stats->getRequest()->getMethod(),
                            'headers' => $stats->getRequest()->getHeaders(),
                            'body' => $stats->getRequest()->getBody(),
                        ],
                        'response' => [
                            'status' => ($hasResponse ? $stats->getResponse()->getStatusCode() : 0),
                            'headers' => ($hasResponse ? $stats->getResponse()->getHeaders() : []),
                            'body' => ($hasResponse ? $stats->getResponse()->getBody() : "")
                        ],
                    ]);
                }
            ])
            ->withUserAgent('teikun-86/tripay-laravel');
    }

    public function createPayment(): Payment
    {
        return new Payment($this);
    }

    public function createTransaction(): Transaction
    {
        return new Transaction($this);
    }

    public function ppob(): PPOB
    {
        return new PPOB($this);
    }

    public function ppobRequest(): PendingRequest
    {
        return $this->createHttpClient()->withToken($this->ppobApiKey)->baseUrl($this->ppobBaseUrl);
    }

    public function paymentRequest(): PendingRequest
    {
        return $this->createHttpClient()->baseUrl($this->paymentBaseUrl);
    }

    public function send(string $url, string $method = "GET", array $options = [])
    {
        return $this->httpClient->send($method, $url, $options);
    }

    public function checkServer(): bool
    {
        $response = $this->ppobRequest()->send("GET", "cekserver");
        return $response->ok() && $response->json('success', false);
    }

    public function checkBalance(): int|float
    {
        $response = $this->ppobRequest()->send("GET", "ceksaldo");
        if (!$response->ok()) {
            throw new \Exception("Failed to get balance. Error code {$response->status()} {$response->reason()}");
        }
        return $response->json('data');
    }

    public function getDebug(): array
    {
        return $this->debugs;
    }
}
