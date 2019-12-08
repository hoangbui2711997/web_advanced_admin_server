<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $id;
    public $created_at;
    public $employee_id;
    public $message;
    public $conservation_id;

    /**
     * Create a new event instance.
     *
     * @param $id
     * @param $message
     * @param $sender
     */
    public function __construct($id, $conversation)
    {
        $this->id = $id;
        $this->created_at = $conversation->created_at;
        $this->employee_id = $conversation->employee_id;
        $this->message = $conversation->message;
        $this->conservation_id = $conversation->conservation_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::warning('@broadcastOn');
        return new PrivateChannel("App.Chat.Conservation.{$this->id}");
    }
}
