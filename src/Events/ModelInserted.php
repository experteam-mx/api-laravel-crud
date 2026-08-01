<?php

namespace Experteam\ApiLaravelCrud\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelInserted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $changed;
    public $old = null;
    public $new;
    public array $user;

    public function __construct(public Model $model, mixed $user = null)
    {
        $this->changed = $model->toArray();
        $this->new = $model->toArray();
        $this->user = [
            'id' => $user->id ?? null,
            'username' => $user->username ?? null,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
