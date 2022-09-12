<?php

namespace Experteam\ApiLaravelCrud\Models;

use Experteam\ApiLaravelCrud\Events\ModelDeleted;
use Experteam\ApiLaravelCrud\Events\ModelSaved;

trait ModelEvents
{
    protected static function booted()
    {
        static::saved(function ($model) {
            event(new ModelSaved($model));
        });

        static::deleted(function ($model) {
            event(new ModelDeleted($model));
        });
    }
}
