<?php
namespace Hanuas\CentralizedLog;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $configPath = __DIR__ . '/config/centralized_log.php';

        $this->publishes([
            $configPath => config_path('centralized_log.php'),
        ]);
    }
}