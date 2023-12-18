<?php

namespace Teikun86\Tripay\Entities;

class Order extends Entity
{
    public function __construct(
        array $order_items = [],
        int|float $amount = 0,
        string $customer_name = "",
        string $customer_email = "",
        string $customer_phone = "",
        string $method = "",
        string $merchant_ref = "",
        string $signature = "",
        string $expired_time = "",
        string $return_url = ""
    )
    {
        $this->fill([
            'order_items' => $order_items,
            'amount' => $amount,
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'method' => $method,
            'merchant_ref' => $merchant_ref,
            'signature' => $signature,
            'expired_time' => $expired_time,
            'return_url' => $return_url,
        ]);
    }

    public function amount(float $amount = 0): Order
    {
        $this->amount = $amount;
        return $this;
    }

    public function customerName(string $customer_name): Order
    {
        $this->customer_name = $customer_name;
        return $this;
    }

    public function customerPhone(string $customer_phone): Order
    {
        $this->customer_phone = $customer_phone;
        return $this;
    }

    public function customerEmail(string $customer_email): Order
    {
        $this->customer_email = $customer_email;
        return $this;
    }

    public function paymentMethod(string $method): Order
    {
        $this->method = $method;
        return $this;
    }

    public function reference(string $merchant_ref): Order
    {
        $this->merchant_ref = $merchant_ref;
        return $this;
    }

    public function redirectUrl(string $return_url): Order
    {
        $this->return_url = $return_url;
        return $this;
    }

    public function expiresAt(\Illuminate\Support\Carbon $expiresAt): Order
    {
        $this->expired_time = $expiresAt->getTimestamp();
        return $this;
    }

    public function addItem(
        ?string $sku = null,
        ?string $name = null,
        float|int|null $price = 0,
        ?int $quantity = 1,
        ?string $product_url = null,
        ?string $image_url = null
    ): OrderItem {
        $orderItem = new OrderItem(
            $sku,
            $name,
            $price,
            $quantity,
            $product_url,
            $image_url
        );
        $items = $this->order_items;
        array_push($items, $orderItem);
        $this->order_items = $items;
        return $orderItem;
    }

    public function setSignature(string $signature): Order
    {
        $this->signature = $signature;
        return $this;
    }
}
