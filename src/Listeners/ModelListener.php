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
        array  $map,
        int    $event,
        bool   $toRedis = true,
        bool   $dispatchMessage = true,
        bool   $toStreamCompute = true
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
                switch ($event) {
                    case self::SAVE_MODEL:
                        Redis::xadd(
                            "streamCompute.$appPrefix.{$map['prefix']}",
                            '*',
                            ['message' => json_encode(
                                self::withTranslations($model
                                    ->load($map['relations'] ?? [])
                                    ->setAppends($map['appends'] ?? [])
                                ))
                            ]
                        );
                        break;
                    case self::DELETE_MODEL:
                        Redis::xadd(
                            "streamCompute.$appPrefix.{$map['prefix']}.delete",
                            '*',
                            ['message' => json_encode($model->setAppends($map['appends'] ?? [])
                                ->load($map['relations'] ?? [])->toArray())]
                        );
                        break;
                }

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
                    Redis::hset($key, $model->$id,
                        json_encode(
                            self::withTranslations($model
                                ->load($map['relations'] ?? [])
                                ->setAppends($map['appends'] ?? [])
                            )
                        )
                    );
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

        Redis::xadd($key, '*', [
            'message' => json_encode([
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode(['data' => self::withTranslations($model
                    ->load($map['relations'] ?? [])
                    ->setAppends($map['appends'] ?? [])
                )])
            ])
        ]);
    }

    public static function withTranslations($model): array
    {
        $attributes = $model->toArray();

        if (in_array('Nevadskiy\Translatable\Strategies\SingleTableExtended\HasTranslations', class_uses_recursive($model), true)) {
            $locales = array_column(array_map(fn ($v) => json_decode($v),
                Redis::hgetall('catalogs.language')), 'code');

            $translations = [];

            foreach ($model->getTranslatable() as $field) {
                $translations[$field] = [];

                foreach ($locales as $locale) {
                    $value = $model->translator()->get($field, strtolower($locale));

                    if (!is_null($value)) {
                        $translations[$field][$locale] = $value;
                    }
                }
            }

            $attributes['translations'] = $translations;
        }

        return $attributes;
    }
}
