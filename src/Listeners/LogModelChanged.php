<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Experteam\ApiLaravelCrud\Events\ModelChanged;

class LogModelChanged
{
    /**
     * Handle the event.
     *
     * @param ModelChanged $event
     * @return void
     */
    public function handle(ModelChanged $event): void
    {
        $className = class_basename($event->model);

        \ESLog::notice("Model [$className] changed!", [
            'user' => $event->user,
            'model' => $className,
            'changes' => $event->changed,
            'old' => $event->old,
            'new' => $event->new,
        ]);
    }
}
