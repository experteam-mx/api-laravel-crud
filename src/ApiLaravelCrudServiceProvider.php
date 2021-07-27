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
        //
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
    }
}
