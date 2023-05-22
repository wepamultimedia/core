<?php

namespace Wepa\Core\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

/**
 * @property Model $model
 * @property array $params
 */
class SeoModelCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public Model $model;
    public array $params = [];
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model, $params = [])
    {
        $this->model = $model;
        $this->params = $params;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
