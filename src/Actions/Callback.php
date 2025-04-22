<?php

namespace Teikun86\Tripay\Actions;

use Teikun86\Tripay\Client;
use Teikun86\Tripay\Entities\CallbackPayload;
use Teikun86\Tripay\Exceptions\InvalidSignatureException;
use Teikun86\Tripay\Lib\TripayHelper;
use UnexpectedValueException;

class Callback
{
    public Client $client;

    protected string $json;

    protected CallbackPayload $payloads;

    public function __construct(Client $client, bool $validateImmediately = true)
    {
        $this->client = $client;
        $input = request()->input();
        $this->json = json_encode($input);
        $this->payloads = new CallbackPayload($input);
        if ($validateImmediately) {
            $this->validate();
        }
    }

    public function localSignature(): string
    {
        return TripayHelper::createSignatureFromJSON($this->client, $this->json);
    }

    public function incomingSignature(): string
    {
        return request()->header('HTTP_X_CALLBACK_SIGNATURE', '');
    }

    public function payloads(): CallbackPayload
    {
        return $this->payloads;
    }

    public function validate(): bool
    {
        $local = $this->localSignature();
        $incoming = $this->incomingSignature();
        $valid = hash_equals($local, $incoming);
        
        if (!$valid) {
            $msg = "The incoming signature does not match local signature.";
            if (!app()->isProduction()) {
                $msg .= " `Local Signature: {$local}` & `Incoming Signature: {$incoming}`";
            }
            throw new InvalidSignatureException($msg);
        }

        if ($this->payloads->isEmpty()) {
            throw new UnexpectedValueException("The Payload is empty.");
        }

        return $valid;
    }
}