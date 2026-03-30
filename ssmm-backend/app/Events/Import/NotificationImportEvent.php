<?php

namespace App\Events\Import;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationImportEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $status;
    public $flag;


    /**
     * Create a new event instance.
     */
    public function __construct($status, $message, $flag)
    {
        $this->status = $status;
        $this->message = $message;
        $this->flag = $flag;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            'notification-channel'
        ];
    }


    public function broadcastAs()
    {
        return 'notification-event';
    }
}
