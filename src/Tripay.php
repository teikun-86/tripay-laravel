<?php

namespace Teikun86\Tripay;

use Illuminate\Support\Facades\Facade;

/**
 * Facade to access Tripay Client class easily.
 */
class Tripay extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'tripay';
    }
}