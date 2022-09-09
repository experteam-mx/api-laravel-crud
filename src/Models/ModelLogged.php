<?php

namespace Experteam\ApiLaravelCrud\Models;

use Experteam\ApiLaravelCrud\Events\ModelChanged;

trait ModelLogged
{
    protected static function booted()
    {
        static::saving(function ($model) {
            ModelChanged::dispatchIf($model->isDirty(), $model);
        });
    }
}
