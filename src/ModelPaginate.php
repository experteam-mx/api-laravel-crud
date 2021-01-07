<?php


namespace Experteam\ApiLaravelCrud;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait ModelPaginate
{

    /**
     * @param Builder $query
     * @return Builder
     * @throws \Exception
     */
    public function scopeCustomPaginate(Builder $query)
    {

        $offset = request()->query
            ->get('offset', 0);

        $limit = request()->query
            ->get('limit', 50);

        if($limit > 1000)
            throw new \Exception(__('apiCrud.more_than_1000'));

        $order = request()->query
            ->get('order', []);

        if (!is_array($order))
            throw new \Exception(__('apiCrud.order_invalid_format'));

        foreach ($order as $field => $direction) {
            if (!in_array(strtoupper($direction), ['ASC', 'DESC']))
                throw new \Exception(__('apiCrud.order_invalid_direction', ['value' => $direction]));

            if (!Schema::hasColumn($query->getModel()->getTable(), $field))
                throw new \Exception(__('apiCrud.order_invalid_field', ['field' => $field]));

            $query->orderBy($field, $direction);
        }

        // For Location header
        request()->query->add([
            'offset' => $offset,
            'limit' => $limit
        ]);

        return $query->offset($offset)
            ->limit($limit);

    }

}
