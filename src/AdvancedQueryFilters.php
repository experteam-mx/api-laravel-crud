<?php

namespace Experteam\ApiLaravelCrud;

use Experteam\ApiLaravelCrud\Models\HasNestedParam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait AdvancedQueryFilters
{
    use HasNestedParam;

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

        if ($query->getModel() instanceof Model)
            $query->select("$table.*");

        foreach ($params as $param) {
            if (!isset($filters[$param]))
                continue;

            $value = $filters[$param];

            if (is_array($value)) {
                $param = $this->isNestedParam($param)
                    ? $this->getNestedParam($query, $param)
                    : (($query->getModel() instanceof Model) ? "$table.$param" : $param);

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
                $query->where((($query->getModel() instanceof Model) ? "$table.$param" : $param), is_numeric($value) ? (float)$value : $value);
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

    private function setQueryGroup($query, $filter, $param, $value)
    {
        $value = is_numeric($value) ? (float)$value : $value;
        switch ($filter) {
            case 'lk':
                $query->where($param, 'like', "%{$value}%");
                break;
            case 'in':
                $query->WhereIn($param, array_map(function($value) {
                    return is_numeric($value) ? (float)$value : $value;
                }, explode(',', $value)));
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
                $query->Where($param, $value);
                break;
            case 'oeq':
                $query->orWhere($param, $value);
                break;
        }

        return $query;
    }

}
