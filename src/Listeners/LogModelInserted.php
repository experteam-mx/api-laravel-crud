<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Experteam\ApiLaravelCrud\Events\ModelInserted;

class LogModelInserted extends BaseLogModel
{
    protected ?string $action = 'INSERT';

    public function handle(ModelInserted $event): void
    {
        $this->register($event);
    }
}
