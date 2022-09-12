<?php

namespace Experteam\ApiLaravelCrud\Models;

trait ModelActive
{
    public function getIsActiveAttribute($value): bool
    {
        return boolval($value);
    }
}
