<?php

namespace Teikun86\Tripay\Actions\PPOB;

use Illuminate\Support\Collection;
use Teikun86\Tripay\Actions\PPOB;
use Teikun86\Tripay\Entities\Entity;
use Teikun86\Tripay\Entities\PPOB\Category;
use Teikun86\Tripay\Entities\PPOB\Operator;
use Teikun86\Tripay\Entities\PPOB\Product;
use Teikun86\Tripay\Entities\Transaction;
use Teikun86\Tripay\Exceptions\TransactionException;

class Prepaid
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
        $response = $this->ppob->send("get", "pembelian/category" . ($id !== null ? "?id=$id" : ""));
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

        $response = $this->ppob->send("get", "pembelian/operator?" . http_build_query($query));
        if (!$response->ok()) {
            throw new \Exception("Failed to get the operators. {$response->reason()}");
        }

        $data = $response->json('data');

        if ($operator_id !== null && !empty($data)) {
            return new Operator($data[0]);
        }

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

        $response = $this->ppob->send("get", "pembelian/produk?" . http_build_query($query));
        if (!$response->ok()) {
            throw new \Exception("Failed to get the products. {$response->reason()}");
        }

        $data = $response->json('data');

        return collect($data)->map(fn ($item) => new Product($item));
    }

    public function getProductDetail(string $code)
    {
        $response = $this->ppob->send("post", "pembelian/produk/cek?code=$code");
        if (!$response->ok()) {
            throw new \Exception("Failed to get the product. {$response->reason()}");
        }

        $data = $response->json('data');
        return new Product($data);
    }

    public function buy(
        string $productCode,
        string $customerId,
        string $apiTransactionId = null,
        bool $isPLN = false,
        string $plnMeter = null
    ): Transaction {
        $query = [
            'code' => $productCode,
            'phone' => $customerId,
            'api_trxid' => $apiTransactionId,
            'inquiry' => $isPLN ? 'PLN' : 'I',
            'no_meter_pln' => $plnMeter,
            'pin' => $this->pin
        ];

        $response = $this->ppob->send("post", "transaksi/pembelian", [
            'json' => $query
        ]);

        if (!$response->ok()) {
            throw new TransactionException("Failed to create a transaction. {$response->reason()}");
        }

        return new Transaction($response->json());
    }
}
