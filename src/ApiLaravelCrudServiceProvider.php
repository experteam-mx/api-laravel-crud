<?php

namespace Experteam\ApiLaravelCrud;

use Illuminate\Support\ServiceProvider;

class ApiLaravelCrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Event service provider
        app()->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/lang/en' => resource_path('/lang/en'),
        ], 'lang');

        $this->publishes([
            __DIR__ . '/../config/experteam-crud.php' => config_path('experteam-crud.php'),
        ], 'config');
    }
}
