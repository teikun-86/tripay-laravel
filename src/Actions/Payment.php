<?php

namespace Teikun86\Tripay\Actions;

use Teikun86\Tripay\Client;

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
        string $payment_code,
        float|int|null $amount = null,
        bool $allowHtml = false
    ) {
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

        return $this->client->paymentRequest()->send("GET", "payment/instruction?" . http_build_query($payloads));
    }
}
