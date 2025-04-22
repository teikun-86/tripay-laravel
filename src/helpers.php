<?php

use Teikun86\Tripay\Actions\Callback;
use Teikun86\Tripay\Actions\PPOB\Callback as PPOBCallback;
use Teikun86\Tripay\Client;

if (!function_exists('tripayClient')) {
    /**
     * Get the Tripay Client instance.
     */
    function tripayClient(): Client
    {
        return app('tripay');
    }
}

if (!function_exists('tripayCallback')) {
    /**
     * Get the callback instance.
     */
    function tripayCallback(bool $validateImmediately = true): Callback|PPOBCallback
    {
        return request()->header('X_CALLBACK_SECRET')
            ? new PPOBCallback(tripayClient()->ppob(), config('tripay.ppob_callback_secret'), $validateImmediately)
            : new Callback(tripayClient(), $validateImmediately);
    }
}
