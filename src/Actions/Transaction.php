<?php

namespace Teikun86\Tripay\Actions;

use Illuminate\Http\Client\Response;
use Teikun86\Tripay\Client;
use Teikun86\Tripay\Entities\Order;
use Teikun86\Tripay\Entities\OrderItem;
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

    public function getTransactions(array $options = [
        'page' => 1,
        'per_page' => 50
    ]): array
    {
        $availableOptions = [
            'page', 'per_page', 'sort', 'reference', 'merchant_ref', 'method', 'status'
        ];
        $options = collect($options)->only($availableOptions);
        $response = $this->client->paymentRequest()->send("GET", "merchant/transactions?".http_build_query($options->toArray()));
        if (!$response->ok()) {
            throw new TransactionException("Failed to get the transactions. Error code {$response->status()} {$response->reason()}");
        }

        $pagination = $response->json('pagination');
        $results = collect($response->json('data'))->map(function($item) {
            $oItems = [];
            foreach($item['order_items'] as $oItem) {
                $oItem[] = (new OrderItem())->fill($oItem);
            }
            $order = new Order();
            $order->fill($item);
            $order->order_items = $oItems;
            return $order;
        });
        return [
            'data' => $results,
            'pagination' => $pagination
        ];
    }

    public function getTransactionDetail(string $reference): Order
    {
        $response = $this->client->paymentRequest()->send("GET", "merchant/transactions?reference=$reference");

        if (!$response->ok()) {
            throw new TransactionException("Failed to get the transactions. Error code {$response->status()} {$response->reason()}");
        }

        $order = $response->json('data');
        $oItems = [];
        foreach($order['order_items'] as $item) {
            $oItems[] = (new OrderItem())->fill($item);
        }
        $order['order_items'] = $oItems;
        
        return (new Order())->fill($order);
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
