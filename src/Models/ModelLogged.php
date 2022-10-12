<?php

namespace Experteam\ApiLaravelCrud\Models;

use Experteam\ApiLaravelCrud\Events\ModelChanged;
use Illuminate\Support\Facades\Auth;

trait ModelLogged
{
    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->isClean()) {
                return;
            }

            if (!$user = Auth::user()) {
                return;
            }

            $fqn = get_class($model);
            $allowedModels = config('experteam-crud.logger.models', []);

            if (empty($allowedModels)) {
                return;
            }

            $coincidences = array_filter($allowedModels, function ($model) use ($fqn) {
                return $model === $fqn;
            });

            ModelChanged::dispatchUnless(empty($coincidences), $model, $user);
        });
    }
}
