<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardMoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $cardId;
    public int $fromColumnId;
    public int $toColumnId;
    public int $position;

    /**
     * Create a new event instance.
     */
    public function __construct(int $cardId, int $fromColumnId, int $toColumnId, int $position)
    {
        $this->cardId = $cardId;
        $this->fromColumnId = $fromColumnId;
        $this->toColumnId = $toColumnId;
        $this->position = $position;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('kanban-channel');
    }

    /**
     * Data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'cardId' => $this->cardId,
            'fromColumnId' => $this->fromColumnId,
            'toColumnId' => $this->toColumnId,
            'position' => $this->position,
        ];
    }

    /**
     * Get the event name for broadcasting.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'card-moved';
    }
}