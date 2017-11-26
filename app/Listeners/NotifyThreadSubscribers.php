<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;

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
            ->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->pluck('user')
            ->each
            ->notify(new ThreadWasUpdated($event->reply));
    }
}
