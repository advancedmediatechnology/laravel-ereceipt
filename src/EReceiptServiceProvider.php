<?php
namespace YourVendor\EReceipt;

use Illuminate\Support\ServiceProvider;

class EReceiptServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ereceipt.php' => config_path('ereceipt.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/ereceipt.php',
            'ereceipt'
        );

        $this->app->singleton(EReceipt::class, function ($app) {
            $cfg = $app['config']['ereceipt'];
            return new EReceipt(
                $cfg['base_url'],
                $cfg['api_key'],
                $cfg['api_secret']
            );
        });

        $this->app->alias(EReceipt::class, 'ereceipt');
    }
}
