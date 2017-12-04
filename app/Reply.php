<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Collection favorites
 * @property Thread thread
 * @property integer id
 * @property integer user_id
 * @property User owner
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Reply extends Model
{
    use Favoritable, RecordsActivity;

    public static function boot() {
        parent::boot();
        static::created(function (self $reply) {
            event(new ThreadHasNewReply($reply));

            $reply->thread->update([
                'updated_at' => Carbon::now()
            ]);
        });
    }

    protected $with = ['owner', 'thread', 'favorites'];
    protected $fillable = ['body', 'user_id'];
    protected $appends = ['favorites_count', 'is_favorited', 'is_best'];

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
