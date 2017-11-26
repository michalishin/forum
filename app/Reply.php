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
 */
class Reply extends Model
{
    use Favoritable, RecordsActivity;

    public static function boot() {
        parent::boot();
        static::created(function ($reply) {
            event(new ThreadHasNewReply($reply));
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

    public function wasJustPublished () {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     * @return Collection
     */
    public function mentionedUsers () {
        preg_match_all('/@([^\s\.]+)/',$this->body, $matches);
        $names = $matches[1];
        return User::whereIn('name', $names)->get();
    }
}
