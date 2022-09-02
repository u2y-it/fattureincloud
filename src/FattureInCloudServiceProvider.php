<?php
namespace U2y\FattureInCloud;

class FattureInCloudServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $configPath = __DIR__ . '/../config/fattureincloud.php';
        $this->mergeConfigFrom($configPath, 'fattureincloud');
    }

    public function boot()
    {
        $this->loadRoutesFrom(realpath(__DIR__ . '/fattureincloud-routes.php'));
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'fattureincloud');

        $this->publishes([
            __DIR__ . '/../config/fattureincloud.php' => config_path('fattureincloud.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/fattureincloud'),
        ]);
    }
}
