<?php

namespace Experteam\ApiLaravelCrud;

use Experteam\ApiLaravelCrud\Events\ModelChanged;
use Experteam\ApiLaravelCrud\Events\ModelDeleted;
use Experteam\ApiLaravelCrud\Events\ModelSaved;
use Experteam\ApiLaravelCrud\Listeners\DeleteModel;
use Experteam\ApiLaravelCrud\Listeners\LogModelChanged;
use Experteam\ApiLaravelCrud\Listeners\SaveModel;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ModelSaved::class => [
            SaveModel::class,
        ],
        ModelChanged::class => [
            LogModelChanged::class,
        ],
        ModelDeleted::class => [
            DeleteModel::class,
        ]
    ];
}
