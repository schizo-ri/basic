<?php

namespace App\Listeners;

use App\Events\MessageSendEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;

class SendMessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSendEvent  $event
     * @return void
     */
    public function handle(MessageSendEvent $event)
    {
        $message = $event->comment->employee->user->last_name . ' just send message.';
        Storage::put('comment_send.txt', $message);
    }
}
