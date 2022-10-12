<?php

namespace Experteam\ApiLaravelCrud\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelChanged
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $model;
    public $changed;
    public $old;
    public $new;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model, Authenticatable $user) {
        $this->model = $model;
        $this->changed = $model->getDirty();
        $this->old = $model->getRawOriginal();
        $this->new = $model->getAttributes();
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
