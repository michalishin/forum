<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Collection favorites
 * @property Thread thread
 * @property integer id
 * @property integer user_id
 * @property User owner
 */
class Reply extends Model
{
    use Favoritable, RecordsActivity;

    public static function boot() {
        self::created(function (self $reply) {
            $reply
                ->load('thread.subscriptions.user')
                ->thread
                ->subscriptions
                ->filter(function (ThreadSubscription $subscription) use ($reply) {
                    return $subscription->user_id != $reply->user_id;
                })
                ->each
                ->notify($reply);
        });
    }

    protected $with = ['owner', 'thread', 'favorites'];
    protected $fillable = ['body', 'user_id'];
    protected $appends = ['favorites_count', 'is_favorited'];

    public function owner () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread () {
        return $this->belongsTo(Thread::class);
    }

    public function getRouteUrl () {
        return $this->thread->getRouteUrl() . '#reply-' . $this->id;
    }

}
