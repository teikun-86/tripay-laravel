<?php

namespace Teikun86\Tripay\Entities;

class OrderItem extends Entity
{
    public function __construct(
        ?string $sku = null,
        ?string $name = null,
        float|int|null $price = 0,
        ?int $quantity = 1,
        ?string $product_url = null,
        ?string $image_url = null
    ) {
        $this->fill([
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'product_url' => $product_url,
            'image_url' => $image_url,
        ]);
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->product_url = $product_url;
        $this->image_url = $image_url;
    }

    public function sku(?string $sku): OrderItem
    {
        $this->sku = $sku;
        return $this;
    }

    public function name(?string $name): OrderItem
    {
        $this->name = $name;
        return $this;
    }

    public function price(float $price): OrderItem
    {
        $this->price = $price;
        return $this;
    }

    public function quantity(int $quantity = 1): OrderItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function productUrl(?string $product_url): OrderItem
    {
        $this->product_url = $product_url;
        return $this;
    }

    public function imageUrl(?string $image_url): OrderItem
    {
        $this->image_url = $image_url;
        return $this;
    }
}
