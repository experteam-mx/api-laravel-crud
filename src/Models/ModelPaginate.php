<?php


namespace Experteam\ApiLaravelCrud\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait ModelPaginate
{
    use HasNestedParam;

    /**
     * @param Builder $query
     * @return Builder
     * @throws Exception
     */
    public function scopeCustomPaginate(Builder $query)
    {

        $offset = request()->query
            ->get('offset', 0);

        $limit = request()->query
            ->get('limit', 50);

        if($limit > 1000)
            throw new Exception(sprintf('You can\'t request more than 1000 records at the same time.'));

        $order = request()->query
            ->get('order', []);

        if (!is_array($order))
            throw new Exception('Invalid parameter order, incorrect format.');

        foreach ($order as $field => $direction) {
            if (!in_array(strtoupper($direction), ['ASC', 'DESC']))
                throw new Exception(sprintf('Invalid parameter order, value "%s" is not allowed', $direction));

            if (!$this->isValidParam($query, $field))
                throw new Exception(sprintf('Invalid parameter order, field "%s" not found or is not allowed.', $field));

            $query->orderBy($this->getNestedParam($query, $field, true), $direction);
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
