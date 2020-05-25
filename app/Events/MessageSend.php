<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $comment;
    public $show_alert_to_employee;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $message, Comment $comment, $show_alert_to_employee)
    {
        $this->message = $message;
        $this->comment = $comment;
        $this->show_alert_to_employee = $show_alert_to_employee;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        /* return new PrivateChannel('message_receive'); */
        return ['message_receive'];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
