<?php

namespace Teikun86\Tripay\Actions\PPOB;

use Illuminate\Support\Collection;
use Teikun86\Tripay\Actions\PPOB;
use Teikun86\Tripay\Entities\Entity;
use Teikun86\Tripay\Entities\PPOB\Category;
use Teikun86\Tripay\Entities\PPOB\Operator;
use Teikun86\Tripay\Entities\PPOB\Product;
use Teikun86\Tripay\Entities\PPOB\PostpaidBill;
use Teikun86\Tripay\Exceptions\TransactionException;

class Postpaid
{
    public PPOB $ppob;
    public string $pin;

    public function __construct(PPOB $ppob, string $pin)
    {
        $this->ppob = $ppob;
        $this->pin = $pin;
    }

    public function getCategories(int $id = null): Collection|Entity
    {
        $response = $this->ppob->send("get", "pembayaran/category" . ($id !== null ? "?id=$id" : ""));
        if (!$response->ok()) {
            throw new \Exception("Failed to get the categories. {$response->reason()}");
        }

        $data = $response->json("data");

        if (!$id) {
            $result = collect($data)->map(fn ($item) => new Category($item));
        } else {
            $result = new Category($data[0]);
        }

        return $result;
    }

    public function getOperators(int $category_id = null, int $operator_id = null): Collection|Entity
    {
        $query = [];
        if ($category_id !== null) {
            $query['category_id'] = $category_id;
        }
        if ($operator_id !== null) {
            $query['operator_id'] = $operator_id;
        }

        $response = $this->ppob->send("get", "pembayaran/operator?" . http_build_query($query));
        if (!$response->ok()) {
            throw new \Exception("Failed to get the operators. {$response->reason()}");
        }

        $data = $response->json('data');

        return collect($data)->map(fn ($item) => new Operator($item));
    }

    public function getProducts(int $category_id = null, int $operator_id = null): Collection|Entity
    {
        $query = [];
        if ($category_id !== null) {
            $query['category_id'] = $category_id;
        }
        if ($operator_id !== null) {
            $query['operator_id'] = $operator_id;
        }

        $response = $this->ppob->send("get", "pembayaran/produk?" . http_build_query($query));
        if (!$response->ok()) {
            throw new \Exception("Failed to get the products. {$response->reason()}");
        }

        $data = $response->json('data');

        return collect($data)->map(fn ($item) => new Product($item));
    }

    public function getProductDetail(string $code)
    {
        $response = $this->ppob->send("post", "pembayaran/produk/cek?code=$code");
        if (!$response->ok()) {
            throw new \Exception("Failed to get the product. {$response->reason()}");
        }

        $data = $response->json('data');
        return new Product($data);
    }

    public function checkPostpaidBill(
        string $productCode,
        string $phone,
        string $customerId,
        string $apiTransactionId = null
    ): PostpaidBill {
        $query = [
            'product' => $productCode,
            'phone' => $phone,
            'no_pelanggan' => $customerId,
            'api_trxid' => $apiTransactionId,
            'pin' => $this->pin
        ];

        $response = $this->ppob->send("post", "pembayaran/cek-tagihan", [
            'json' => $query
        ]);

        if (!$response->ok()) {
            throw new TransactionException("Failed to check the Postpaid Bill. {$response->reason()}");
        }

        return new PostpaidBill($response->json());
    }

    public function payBill(
        string $order_id,
        string $apiTransactionId = null,
    ): PostpaidBill {
        $query = [
            'order_id' => $order_id,
            'api_trxid' => $apiTransactionId,
            'pin' => $this->pin
        ];

        $response = $this->ppob->send("post", "transaksi/pembayaran", [
            'json' => $query
        ]);

        if (!$response->ok()) {
            throw new TransactionException("Failed to create a transaction. {$response->reason()}");
        }

        return new PostpaidBill($response->json());
    }
}
