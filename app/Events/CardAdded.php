<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $cardId;
    public int $columnId;
    public string $name;
    public ?string $description;
    public int $position;

    public function __construct(int $cardId, int $columnId, string $name, ?string $description, int $position)
    {
        $this->cardId = $cardId;
        $this->columnId = $columnId;
        $this->name = $name;
        $this->description = $description;
        $this->position = $position;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('kanban-channel');
    }

    public function broadcastWith(): array
    {
        return [
            'cardId' => $this->cardId,
            'columnId' => $this->columnId,
            'name' => $this->name,
            'description' => $this->description,
            'position' => $this->position,
        ];
    }

    public function broadcastAs(): string
    {
        return 'card-added';
    }
}