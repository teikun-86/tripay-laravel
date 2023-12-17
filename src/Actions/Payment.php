<?php

namespace Teikun86\Tripay\Actions;

use Illuminate\Support\Collection;
use Teikun86\Tripay\Client;
use Teikun86\Tripay\Entities\Fee;
use Teikun86\Tripay\Entities\PaymentChannel;
use Teikun86\Tripay\Entities\PaymentInstruction;

class Payment
{
    public Client $client;

    public ?string $baseUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->baseUrl = $client->paymentBaseUrl;
    }

    public function instruction(
        string $code,
        string $payment_code = null,
        float|int|null $amount = null,
        bool $allowHtml = false
    ): Collection {
        $payloads = [
            'code' => $code,
        ];

        if (!empty($payment_code)) {
            $payloads['pay_code'] = $payment_code;
        }

        if (!empty($amount)) {
            $payloads['amount'] = $amount;
        }

        if ($allowHtml) {
            $payloads['allow_html'] = $allowHtml ? 1 : 0;
        }

        $response = $this->client->paymentRequest()->send("GET", "payment/instruction?" . http_build_query($payloads));

        if (!$response->ok()) {
            throw new \Exception("Failed to retrieve payment instruction. Error code: {$response->status()} {$response->reason()}");
        }

        return collect($response->json('data'))->map(fn($item) => new PaymentInstruction($item));
    }

    public function channels(bool $grouped = false): Collection
    {
        $response = $this->client->paymentRequest()->send("GET", "merchant/payment-channel");
        if (!$response->ok()) {
            throw new \Exception("Failed to retrieve payment channels. Error code: {$response->status()} {$response->reason()}");
        }
        $results = collect($response->json('data'))->map(fn ($item) => new PaymentChannel($item));

        if ($grouped) {
            $results = $results->groupBy(fn($item) => $item->group);
        }
        return $results;
    }

    public function calculateFees(int|float $amount, string $code = null): Collection
    {
        $response = $this->client->paymentRequest()->send("GET", "merchant/fee-calculator?".http_build_query([
            'amount' => $amount,
            'code' => $code
        ]));
        if (!$response->ok()) {
            throw new \Exception("Failed to calculate fee. Error code: {$response->status()} {$response->reason()}");
        }
        return collect($response->json('data'))->map(fn ($item) => new Fee($item));
    }
}
