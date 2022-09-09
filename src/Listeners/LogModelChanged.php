<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Experteam\ApiLaravelCrud\Events\ModelChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogModelChanged implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param ModelChanged $event
     * @return void
     */
    public function handle(ModelChanged $event)
    {
        $model = $event->model;
        $class = class_basename($model);

        dump([
            'model' => $class,
            'changes' => $model->getDirty(),
            'old' => $model->getRawOriginal(),
            'new' => $model->getAttributes(),
        ]);

        \ESLog::notice("Model [$class] changed!", [
            'model' => $class,
            'changes' => $model->getDirty(),
            'old' => $model->getRawOriginal(),
            'new' => $model->getAttributes(),
        ]);
    }
}
