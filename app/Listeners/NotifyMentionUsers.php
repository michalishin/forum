<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionUsers
{
    /**
     * Handle the event.
     *
     * @param ThreadHasNewReply|object $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        $matches = [];
        preg_match_all('/@([^\s\.]+)/',$event->reply->body, $matches);
        $names = $matches[1];
        User::whereIn('name',$names)
            ->get()
            ->each
            ->notify(new YouWereMentioned($event->reply));
    }
}
