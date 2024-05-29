<?php

namespace Experteam\ApiLaravelCrud\Facades;

class ApiClientFacade extends \Illuminate\Support\Facades\Facade
{

    protected static function getFacadeAccessor()
    {
        return 'api-client';
    }

}
