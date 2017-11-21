<?php

namespace App;

use App\Filters\ThreadsFilters;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Thread
 * @property Channel channel
 * @property integer id
 * @property User creator
 * @property int user_id
 * @property Collection replies
 * @method static self create(array $data)
 * @package App
 */
class Thread extends Model
{
    use RecordsActivity;

    protected $fillable = ['user_id', 'title', 'body', 'channel_id'];
    protected $withCount = ['replies'];
    protected $with = ['creator', 'channel'];

    protected static function boot () {
        parent::boot();
        static::deleting(function (self $thread) {

            $thread->replies->each->delete();
        });
    }

    public function replies () {
        return $this->hasMany(Reply::class);
    }

    public function creator () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel () {
        return $this->belongsTo(Channel::class);
    }

    public function getRouteUrl () {
        return route('threads.show', [
            $this->channel->slug,
            $this->id
        ]);
    }

    public function scopeFilter($query, ThreadsFilters $filters) {
        return $filters->apply($query);
    }

    public function subscribe($userId = null) {
        return $this->subscriptions()->create([
           "user_id" => $userId ?: auth()->id()
        ]);
    }

    public function unsubscribe($userId = null) {
        $this->subscriptions()->delete([
            "user_id" => $userId ?: auth()->id()
        ]);
    }

    public function subscriptions() {
        return $this->hasMany(ThreadSubscription::class);
    }
}
