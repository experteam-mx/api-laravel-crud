<?php

namespace Experteam\ApiLaravelCrud\Listeners;


use Experteam\ApiLaravelCrud\Events\ModelSaved;

class SaveModel extends ModelListener
{
    /**
     * Handle the event.
     *
     * @param ModelSaved $event
     * @return void
     */
    public function handle(ModelSaved $event): void
    {
        $this->process(
            $event->model,
            config('experteam-crud.listener.map', []),
            self::SAVE_MODEL
        );
    }
}
