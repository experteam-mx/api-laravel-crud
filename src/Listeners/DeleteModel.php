<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Experteam\ApiLaravelCrud\Events\ModelDeleted;

class DeleteModel extends ModelListener
{
    const MAP = [
        [
            'class' => 'ProductEntity',
            'prefix' => 'productEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'ExtraChargeEntity',
            'prefix' => 'extraChargeEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'SupplyEntity',
            'prefix' => 'supplyEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'SystemEntity',
            'prefix' => 'systemEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
        [
            'class' => 'AccountEntity',
            'prefix' => 'accountEntity',
            'toRedis' => true,
            'toStreamCompute' => false,
            'dispatchMessage' => false,
            'entityConfig' => true
        ],
    ];

    /**
     * Handle the event.
     *
     * @param ModelDeleted $event
     * @return void
     */
    public function handle(ModelDeleted $event)
    {
        $this->proccess($event->model, self::MAP, self::DELETE_MODEL);
    }
}
