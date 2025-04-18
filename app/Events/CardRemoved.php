<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardRemoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $cardId;
    public int $columnId;

    public function __construct(int $cardId, int $columnId)
    {
        $this->cardId = $cardId;
        $this->columnId = $columnId;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('kanban-channel');
    }

    public function broadcastWith(): array
    {
        return ['cardId' => $this->cardId, 'columnId' => $this->columnId];
    }

    public function broadcastAs(): string
    {
        return 'card-removed';
    }
}