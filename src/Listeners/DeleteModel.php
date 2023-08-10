<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Experteam\ApiLaravelCrud\Events\ModelDeleted;

class DeleteModel extends ModelListener
{

    /**
     * Handle the event.
     *
     * @param ModelDeleted $event
     * @return void
     */
    public function handle(ModelDeleted $event): void
    {
        $this->process(
            $event->model,
            config('experteam-crud.listener.map', []),
            self::DELETE_MODEL
        );
    }
}
