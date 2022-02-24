<?php

namespace Experteam\ApiLaravelCrud;

use Illuminate\Database\Eloquent\Builder;

trait AdvancedQueryFilters
{

    /**
     * @param Builder $query
     * @param array $params
     * @return Builder|mixed
     */
    public function queryFilter(Builder $query, array $params)
    {
        $filters = request()->query
            ->all();

        $pendingFilters = [];
        $table = $query->getModel()->getTable();
        $query->select("$table.*");

        foreach ($params as $param) {
            if (!isset($filters[$param]))
                continue;

            $value = $filters[$param];

            if (is_array($value)) {
                $param = $this->isNestedParam($param)
                    ? $this->getNestedParam($query, $param)
                    : "$table.$param";

                foreach ($value as $filter => $_value) {
                    if (is_numeric($filter)) {
                        $pendingFilters[$filter][] = [$param, $_value];
                    } else {
                        $query = $this->setQueryGroup($query, $filter, $param, $_value);
                    }
                }
            } elseif ($this->isNestedParam($param)) {
                $query->where($this->getNestedParam($query, $param), $value);
            } else {
                $query->where("$table.$param", $value);
            }
        }

        if (!empty($pendingFilters)) {
            foreach ($pendingFilters as $pendingFilter) {
                $query->where(function ($query) use ($pendingFilter) {
                    foreach ($pendingFilter as $param) {
                        foreach ($param[1] as $filter => $value)
                            $this->setQueryGroup($query, $filter, $param[0], $value);
                    }
                });
            }
        }

        return $query;
    }

    /**
     * @param $param
     * @return string
     */
    private function getNestedParam(Builder $query, $param): string
    {
        [$relations, $field] = $this->splitParamParts($param);

        $table = $this->addJoinForRelations($query, $relations, false);

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
            $modelRelated = $model->$relation()->getRelated();

            if (!$this->joinExists($query, $modelRelated->getTable())) {

                $first = sprintf('%s.%s', $model->getTable(), $model->$relation()->getForeignKeyName());
                $second = sprintf('%s.%s', $modelRelated->getTable(), $modelRelated->getKeyName());

                if ($leftJoin)
                    $query->leftJoin($modelRelated->getTable(), $first, $second);
                else
                    $query->join($modelRelated->getTable(), $first, $second);
            }

            $model = $modelRelated;
        }

        return $model->getTable();
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

    private function setQueryGroup($query, $filter, $param, $value)
    {
        switch ($filter) {
            case 'lk':
                $query->where($param, 'like', "%{$value}%");
                break;
            case 'olk':
                $query->orWhere($param, 'like', "%{$value}%");
                break;
            case 'gt':
                $query->where($param, '>', $value);
                break;
            case 'ogt':
                $query->orWhere($param, '>', $value);
                break;
            case 'gte':
                $query->where($param, '>=', $value);
                break;
            case 'ogte':
                $query->orWhere($param, '>=', $value);
                break;
            case 'lt':
                $query->where($param, '<', $value);
                break;
            case 'olt':
                $query->orWhere($param, '<', $value);
                break;
            case 'lte':
                $query->where($param, '<=', $value);
                break;
            case 'olte':
                $query->orWhere($param, '<=', $value);
                break;
            case 'eq':
                $query->orWhere($param, $value);
                break;
        }

        return $query;
    }

}