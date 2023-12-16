<?php

namespace Teikun86\Tripay\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Teikun86\Tripay\Client;

class TripayServiceProvider extends ServiceProvider
{
    /**
     * Boot the TripayServiceProvider
     */
    public function boot()
    {
        // 
    }

    /**
     * Register the Teikun86\Tripay services.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->packagePath('Config/config.php'), 'tripay');
        $this->registerClient();
        $this->publish();
    }

    /**
     * Register the Tripay Client singleton.
     */
    public function registerClient()
    {
        $this->app->singleton('tripay', function (\Illuminate\Contracts\Foundation\Application $app) {
            $env = app()->isProduction() ? "production": "dev";
            return new Client(
                config('tripay.merchant_code'),
                config('tripay.api_key'),
                config('tripay.ppob_api_key'),
                config('tripay.private_key'),
                config('tripay.ppob_endpoints.'.$env),
                config('tripay.payment_endpoints.'.$env),
                config('tripay.ppob_pin')
            );
        });
    }

    /**
     * Publish the publishable resources.
     */
    public function publish()
    {
        $this->publishes([
            $this->packagePath('Config/config.php') => config_path('tripay.php')
        ], 'tripay-config');
    }

    /**
     * Get the package path.
     */
    protected function packagePath($path = '')
    {
        if (str($path)->startsWith(DIRECTORY_SEPARATOR)) {
            $path = substr($path, 1);
        }
        return __DIR__ . '/../' . $path;
    }
}
