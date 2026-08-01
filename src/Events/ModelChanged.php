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
        $this->changed = $this->model->getChanges();
        $this->old = $this->model->getPrevious();
        $this->new = $this->model->getChanges();
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
