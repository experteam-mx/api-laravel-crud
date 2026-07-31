<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Experteam\ApiLaravelCrud\Events\ModelChanged;

class LogModelChanged extends BaseLogModel
{
    protected ?string $action = 'UPDATE';

    public function handle(ModelChanged $event): void
    {
        $this->register($event);
    }
}
