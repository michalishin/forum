<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Exceptions\ThreadLockException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    public static function boot() {
        parent::boot();
        static::created(function (self $reply) {
            event(new ThreadHasNewReply($reply));
        });
        static::creating(function (self $reply) {
            if ($reply->thread->locked) {
                throw new ThreadLockException('Thread is locked');
            }
        });
    }

    protected $with = ['owner', 'thread', 'favorites'];
    protected $fillable = ['body', 'user_id'];
    protected $appends = ['favorites_count', 'is_favorited', 'is_best'];
    protected $touches = ['thread'];

    public function owner () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread () {
        return $this->belongsTo(Thread::class);
    }

    public function getRouteUrl () {
        return $this->thread->path . '#reply-' . $this->id;
    }

    public function wasJustPublished () {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     * @return Collection
     */
    public function mentionedUsers () {
        $names = $this->getMentionedUsersNames();
        return User::whereIn('name', $names)->get();
    }


    public function getIsBestAttribute () {
        return $this->id == $this->thread->best_reply_id;
    }

    public function setBodyAttribute ($body) {
        $this->attributes['body'] = preg_replace(
            '/@([\w\-]+)/',
            '<a href="/profiles/$1">$0</a>',
            $body
        );
    }

    /**
     * @return mixed
     * @internal param $matches
     */
    protected function getMentionedUsersNames()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);
        $names = $matches[1];
        return $names;
    }
}
