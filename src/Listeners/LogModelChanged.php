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
        $class = class_basename($event->model);

        \ESLog::notice("Model [$class] changed!", [
            'model' => $class,
            'changes' => $event->changed,
            'old' => $event->old,
            'new' => $event->new,
        ]);
    }
}
