<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Experteam\ApiLaravelCrud\Events\ModelDeleted;

class LogModelDeleted extends BaseLogModel
{
    protected ?string $action = 'DELETE';

    public function handle(ModelDeleted $event): void
    {
        $this->register($event);
    }
}
