<?php

namespace Experteam\ApiLaravelCrud;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasNestedParam
{

    /**
     * @param Builder $query
     * @param $param
     * @param bool $leftJoin
     * @return string
     */
    private function getNestedParam(Builder $query, $param, bool $leftJoin = false): string
    {
        [$relations, $field] = $this->splitParamParts($param);

        $table = $this->addJoinForRelations($query, $relations, $leftJoin);

        return "$table.$field";
    }

    /**
     * @param Builder $query
     * @param string $table
     * @return bool
     */
    private function joinExists(Builder $query, string $table): bool
    {
        $joins = $query->getQuery()->joins;

        if (is_null($joins))
            return false;

        foreach ($joins as $join)
            if ($join->table == $table)
                return true;

        return false;
    }

    /**
     * @param Builder $query
     * @param array $relations
     * @param bool $leftJoin
     * @return string
     */
    private function addJoinForRelations(Builder $query, array $relations, bool $leftJoin): string
    {
        $model = $query->getModel();

        foreach ($relations as $relation) {
            $method = Str::camel($relation);
            $relationObject = $model->$method();
            $modelRelated = $relationObject->getRelated();

            if (!$this->joinExists($query, $modelRelated->getTable())) {

                if ($relationObject instanceof BelongsTo)
                    $this->joinBelongsTo($query, $model, $modelRelated, $relationObject, $leftJoin);

                if ($relationObject instanceof BelongsToMany)
                    $this->joinBelongsToMany($query, $model, $modelRelated, $relationObject, $leftJoin);
            }

            $model = $modelRelated;
        }

        return $model->getTable();
    }

    /**
     * @param Builder $query
     * @param $model
     * @param $modelRelated
     * @param BelongsTo $relationObject
     * @param bool $leftJoin
     */
    private function joinBelongsTo(Builder $query, $model, $modelRelated, BelongsTo $relationObject, bool $leftJoin)
    {
        $first = sprintf('%s.%s', $model->getTable(), $relationObject->getForeignKeyName());
        $second = sprintf('%s.%s', $modelRelated->getTable(), $modelRelated->getKeyName());

        if ($leftJoin)
            $query->leftJoin($modelRelated->getTable(), $first, $second);
        else
            $query->join($modelRelated->getTable(), $first, $second);
    }

    /**
     * @param Builder $query
     * @param $model
     * @param $modelRelated
     * @param BelongsToMany $relationObject
     * @param bool $leftJoin
     */
    private function joinBelongsToMany(Builder $query, $model, $modelRelated, BelongsToMany $relationObject, bool $leftJoin)
    {
        $pivotFirst = sprintf('%s.%s', $model->getTable(), $model->getKeyName());
        $pivotSecond = sprintf('%s.%s', $relationObject->getTable(), $relationObject->getForeignPivotKeyName());

        $first = sprintf('%s.%s', $relationObject->getTable(), $relationObject->getRelatedPivotKeyName());
        $second = sprintf('%s.%s', $modelRelated->getTable(), $modelRelated->getKeyName());

        if ($leftJoin) {
            $query->leftJoin($relationObject->getTable(), $pivotFirst, $pivotSecond);
            $query->leftJoin($modelRelated->getTable(), $first, $second);
        } else {
            $query->join($relationObject->getTable(), $pivotFirst, $pivotSecond);
            $query->join($modelRelated->getTable(), $first, $second);
        }
    }

    /**
     * @param Builder $query
     * @param string $param
     * @return bool
     */
    private function isValidParam(Builder $query, string $param): bool
    {
        $model = $query->getModel();
        $field = $param;

        if ($this->isNestedParam($param)) {

            [$relations, $field] = $this->splitParamParts($param);

            foreach ($relations as $relation) {
                $_relation = Str::camel($relation);

                if (!method_exists($model, $_relation))
                    return false;

                $model = $model->$_relation()->getRelated();
            }
        }

        return Schema::hasColumn($model->getTable(), $field);
    }

    /**
     * @return string
     */
    private function getNestedSeparator(): string
    {
        return '@';
    }

    /**
     * @param $param
     * @return array
     */
    private function splitParamParts($param): array
    {
        $parts = explode($this->getNestedSeparator(), $param, 2);
        $field = array_pop($parts);
        return [$parts, $field];
    }

    /**
     * @param $param
     * @return bool
     */
    private function isNestedParam($param): bool
    {
        return strpos($param, $this->getNestedSeparator()) !== false;
    }

}
