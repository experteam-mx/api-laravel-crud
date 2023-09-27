<?php

namespace Experteam\ApiLaravelCrud\Listeners;

use Illuminate\Support\Facades\Redis;

abstract class ModelListener
{
    const SAVE_MODEL = 0;
    const DELETE_MODEL = 1;

    /**
     * Process the event.
     *
     * @param object $model
     * @param array $map
     * @param int $event
     * @param bool $toRedis
     * @param bool $dispatchMessage
     * @param bool $toStreamCompute
     * @return void
     */
    public function process(
        object $model,
        array $map,
        int $event,
        bool $toRedis = true,
        bool $dispatchMessage = true,
        bool $toStreamCompute = true
    ): void
    {
        $appPrefix = config('experteam-crud.listener.prefix', 'companies');

        $maps = array_filter($map, function ($m) use ($model) {
            return is_a($model, $m['class']) || class_basename($model) == $m['class'];
        });

        foreach ($maps as $map) {
            if ($toRedis && $map['toRedis']) {
                self::toRedis($model, $map, $event, $appPrefix);
            }

            if ($dispatchMessage && $map['dispatchMessage'] && env('APP_ENV') !== 'testing') {
                self::dispatchMessage($model, $map, $event, $appPrefix);
            }

            if ($toStreamCompute && $map['toStreamCompute'] && env('APP_ENV') !== 'testing') {
                Redis::xadd(
                    "streamCompute.$appPrefix.{$map['prefix']}",
                    '*',
                    ['message' => json_encode($model->setAppends($map['appends'] ?? [])
                        ->load($map['relations'] ?? [])->toArray())]
                );
            }
        }
    }

    /**
     * @param object $model
     * @param array $map
     * @param int $event
     * @param string $appPrefix
     */
    public static function toRedis(object $model, array $map, int $event, string $appPrefix): void
    {
        $key = "$appPrefix.{$map['prefix']}";

        $entityConfig = $map['entityConfig'] ?? false;

        if ($entityConfig) {
            self::toRedisEntityConfig($model, $key, $event);
        } else {
            $id = $map['toRedisId'] ?? 'id';
            $suffix = $map['toRedisSuffix'] ?? null;

            if (!empty($suffix)) {
                $key .= $model->$suffix;
            }

            switch ($event) {
                case self::SAVE_MODEL:
                    Redis::hset($key, $model->$id, json_encode($model
                        ->load($map['relations'] ?? [])
                        ->setAppends($map['appends'] ?? [])
                        ->toArray()));
                    break;
                case self::DELETE_MODEL:
                    Redis::hdel($key, $model->$id);
                    break;
            }
        }
    }

    /**
     * @param $model
     * @param string $prefix
     * @param int $event
     */
    public static function toRedisEntityConfig($model, string $prefix, int $event): void
    {
        $entity = $model->entity;
        $key = "$prefix:$entity->model_type";

        if (!method_exists($model, 'valueToRedis')) {
            return;
        }

        $value = $model->valueToRedis();
        $data = json_decode(Redis::hget($key, $entity->model_id), true);

        switch ($event) {
            case self::SAVE_MODEL:
                $_data = $data ?? [];
                $_data[$value] = $model['is_active'];
                break;

            case self::DELETE_MODEL:
                $_data = $data ?? [];
                unset($_data[$value]);
                break;

            default:
                return;
        }

        if (!empty($_data)) {
            Redis::hset($key, $entity->model_id, json_encode($_data));
        } else {
            Redis::hdel($key, $entity->model_id);
        }
    }

    /**
     * @param object $model
     * @param array $map
     * @param int $event
     * @param string $appPrefix
     */
    public static function dispatchMessage(object $model, array $map, int $event, string $appPrefix): void
    {
        $prefix = "$appPrefix.{$map['prefix']}";
        $key = "messages.$prefix";

        if ($event == self::DELETE_MODEL) {
            $key .= ".deleted";
        }

        if (isset($map['appends'])) {
            $model = $model->setAppends($map['appends'])
                ->load($map['relations']);
        }

        Redis::xadd($key, '*', [
            'message' => json_encode([
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode(['data' => $model])
            ])
        ]);
    }
}
