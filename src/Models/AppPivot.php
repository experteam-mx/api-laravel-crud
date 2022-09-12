<?php

namespace Experteam\ApiLaravelCrud\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AppPivot extends Pivot
{
    use ModelPaginate;

    use ModelEvents {
        ModelEvents::booted as modelEventsBooted;
    }

    use ModelLogged {
        ModelLogged::booted as modelLoggedBooted;
    }

    protected static function booted()
    {
        self::modelEventsBooted();
        self::modelLoggedBooted();
    }
}
