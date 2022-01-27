<?php

namespace Experteam\ApiLaravelCrud;

use Illuminate\Database\Eloquent\Builder;

trait AdvancedQueryFilters
{
    public function queryFilter(Builder $query, $params)
    {
        $filters = request()->query
            ->all();

        $pendingFilters = [];

        foreach ($params as $param) {
            if (isset($filters[$param])) {
                if (is_array($filters[$param])) {
                    foreach ($filters[$param] as $filter => $value) {
                        if (is_numeric($filter)) {
                            $pendingFilters[$filter][] = [$param, $value];
                        } else {
                            $query = $this->SetQueryGroup($query, $filter, $param, $value);
                        }
                    }
                } else {
                    $query->where($param, $filters[$param]);
                }
            }
        }

        if(!empty($pendingFilters)) {
            foreach ($pendingFilters as $pendingFilter) {
                $query->where(function ($query) use ($pendingFilter) {
                    foreach ($pendingFilter as $param) {
                        foreach ($param[1] as $filter => $value)
                            $this->SetQueryGroup($query, $filter, $param[0], $value);
                    }
                });
            }
        }

        return $query;

    }

    private function SetQueryGroup($query, $filter, $param, $value)
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