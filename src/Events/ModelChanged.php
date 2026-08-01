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

    public function __construct(public Model $model, mixed $user)
    {
        $this->changed = $this->model->getDirty();
        $this->old = array_filter($model->getOriginal(),
            fn ($value) => in_array($value, array_keys($this->changed)), ARRAY_FILTER_USE_KEY);
        $this->new = $this->model->getDirty();
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
