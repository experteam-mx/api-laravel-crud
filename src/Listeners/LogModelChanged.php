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
        $fqn = get_class($event->model);
        $className = class_basename($event->model);

        $allowedModels = config('experteam-crud.logger.models', []);

        if (empty($allowedModels)) {
            return;
        }

        $coincidences = array_filter($allowedModels, function ($model) use ($fqn) {
            return $model === $fqn;
        });

        if (!empty($coincidences)) {
            \ESLog::notice("Model [$className] changed!", [
                'model' => $className,
                'changes' => $event->changed,
                'old' => $event->old,
                'new' => $event->new,
            ]);
        }
    }
}
