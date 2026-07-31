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
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $changed;
    public $old;
    public $new;
    public array $user;

    public function __construct(public Model $model, Authenticatable $user)
    {
        $this->changed = $model->getDirty();
        $this->old = $model->getRawOriginal() ?? null;
        $this->new = $model->getAttributes();
        $this->user = [
            'id' => $user->id,
            'username' => $user->username,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
