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
        $langDir =  __DIR__.'/../resources/lang/en';

        $this->publishes([
            $langDir => resource_path('/lang/en'),
        ], 'lang');
    }
}
