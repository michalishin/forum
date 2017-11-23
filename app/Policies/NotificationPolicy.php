<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, DatabaseNotification $notification)
    {
        if ($notification->notifiable instanceof User) {
            return $notification->notifiable->id == $user->id;
        }
        return false;
    }
}
