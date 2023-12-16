<?php

namespace Teikun86\Tripay\Actions;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Teikun86\Tripay\Actions\PPOB\Postpaid;
use Teikun86\Tripay\Actions\PPOB\Prepaid;
use Teikun86\Tripay\Client;
use Teikun86\Tripay\Entities\Transaction;

class PPOB
{
    public Client $client;

    public ?string $baseUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->baseUrl = $client->ppobBaseUrl;
    }

    public function prepaid(): Prepaid
    {
        return new Prepaid($this, $this->client->ppobPin);
    }

    public function postpaid(): Postpaid
    {
        return new Postpaid($this, $this->client->ppobPin);
    }

    public function send(string $method, string $url, array $options = []): Response
    {
        return $this->client->ppobRequest()->send($method, $url, $options);
    }

    public function transactionHistory(string $trx_id = null, string $api_trx_id = null): Collection|Transaction
    {
        $single = $trx_id !== null;
        $query = [];
        $endpoint = "histori/transaksi/" . ($single ? 'detail' : 'all');
        if ($single) {
            $query['trxid'] = $trx_id;
            if ($api_trx_id !== null) $query['api_trxid'] = $api_trx_id;
        }
        $response = $this->send($single ? "post" : "get", $endpoint, [
            'json' => $query
        ]);
        $data = $response->json('data');
        return $single
            ? new Transaction($data)
            : collect($data)->map(fn ($item) => new Transaction($item));
    }

    public function transactionHistoryByDate(string $start_date, string $end_date): Collection
    {
        $query = ['start_date' => $start_date, 'end_date' => $end_date];

        $response = $this->send("post", "histori/transaksi/bydate", [
            'json' => $query
        ]);

        $data = $response->json('data');
        return collect($data)->map(fn ($item) => new Transaction($item));
    }
}
