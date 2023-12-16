<?php

namespace Teikun86\Tripay\Actions;

use Illuminate\Http\Client\Response;
use Teikun86\Tripay\Client;
use Teikun86\Tripay\Entities\Order;
use Teikun86\Tripay\Exceptions\TransactionException;
use Teikun86\Tripay\Lib\TripayHelper;

class Transaction
{
    public Client $client;

    public Order $order;

    public function __construct(Client $client, ?Order $order = null)
    {
        $this->client = $client;
        if ($order !== null) {
            $this->order = $order;
        }
    }

    public function addOrder(): Order
    {
        $this->order = new Order();
        return $this->order;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): Transaction
    {
        $this->order = $order;
        return $this;
    }

    public function send(): Response
    {
        if (!$this->order) {
            throw new TransactionException("Can't make a transaction without Order. Create an order by calling `addOrder()`.");
        }
        $this->order->setSignature(TripayHelper::createSignature($this->client, [
            'merchant_ref' => $this->order->merchant_ref,
            'amount' => $this->order->amount
        ]));
        $response = $this->client->paymentRequest()->send("POST", "transaction/create", [
            'json' => $this->order->toArray()
        ]);

        if ($response->failed()) {
            throw new TransactionException("Transaction creation failed with code: {$response->status()}. Reason: {$response->reason()}.");
        }

        return $response->json();
    }
}
