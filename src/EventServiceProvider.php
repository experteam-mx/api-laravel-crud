<?php

namespace Experteam\ApiLaravelCrud;

use Experteam\ApiLaravelCrud\Events\ModelChanged;
use Experteam\ApiLaravelCrud\Events\ModelDeleted;
use Experteam\ApiLaravelCrud\Events\ModelInserted;
use Experteam\ApiLaravelCrud\Events\ModelSaved;
use Experteam\ApiLaravelCrud\Listeners\DeleteModel;
use Experteam\ApiLaravelCrud\Listeners\LogModelChanged;
use Experteam\ApiLaravelCrud\Listeners\LogModelDeleted;
use Experteam\ApiLaravelCrud\Listeners\LogModelInserted;
use Experteam\ApiLaravelCrud\Listeners\SaveModel;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ModelChanged::class => [
            LogModelChanged::class,
        ],
        ModelDeleted::class => [
            LogModelDeleted::class,
            DeleteModel::class,
        ],
        ModelInserted::class => [
            LogModelInserted::class,
        ],
        ModelSaved::class => [
            SaveModel::class,
        ],
    ];
}
