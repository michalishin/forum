<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;

class NotifyThreadSubscribers
{
    /**
     * Handle the event.
     *
     * @param ThreadHasNewReply|object $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        $event->reply
            ->thread
            ->notifySubscribers( $event->reply);
    }
}
