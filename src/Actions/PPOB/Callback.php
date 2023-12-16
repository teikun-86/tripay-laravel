<?php

namespace Teikun86\Tripay\Actions\PPOB;

use Teikun86\Tripay\Actions\PPOB;
use Teikun86\Tripay\Entities\CallbackPayload;
use Teikun86\Tripay\Exceptions\InvalidCallbackSecretException;

class Callback
{
    public PPOB $ppob;

    protected string $callbackSecret;

    protected string $incomingSecret;

    protected CallbackPayload $payload;

    public function __construct(PPOB $ppob, string $callbackSecret)
    {
        $this->ppob = $ppob;
        $this->callbackSecret = $callbackSecret;
        $this->payload = new CallbackPayload(request()->all());
        $this->incomingSecret = request()->header('X_CALLBACK_SECRET');
        $valid = $this->validateSecret();
        if (!$valid) {
            throw new InvalidCallbackSecretException("The given callback secret is invalid.");
        }
    }

    public function payload(): CallbackPayload
    {
        return $this->payload;
    }

    public function validateSecret(): bool
    {
        return hash_equals($this->callbackSecret, $this->incomingSecret);
    }
}
