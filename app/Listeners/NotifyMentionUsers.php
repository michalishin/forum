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
        $event->reply
            ->mentionedUsers()
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify(new YouWereMentioned($event->reply));
    }
}
