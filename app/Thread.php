<?php

namespace App;

use App\Filters\ThreadsFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Thread
 * @property Channel channel
 * @property integer id
 * @property User creator
 * @property int user_id
 * @property Collection replies
 * @property Collection subscriptions
 * @property string title
 * @property Carbon updated_at
 * @property string body
 * @property string slug
 * @method static self create(array $data)
 * @package App
 */
class Thread extends Model
{
    use RecordsActivity;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'channel_id',
        'updated_at',
        'slug'
    ];

    protected $withCount = ['replies'];

    protected $with = ['creator', 'channel'];

    protected $appends = ['path'];

    /**
     * @var Visits
     */
    protected $visits;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->visits = new Visits($this);
    }

    protected static function boot () {
        parent::boot();
        static::deleting(function (self $thread) {
            $thread->replies->each->delete();
        });
    }

    /* Relations */
    public function replies () {
        return $this->hasMany(Reply::class);
    }

    public function creator () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel () {
        return $this->belongsTo(Channel::class);
    }

    /* Scopes */
    public function scopeFilter($query, ThreadsFilters $filters) {
        return $filters->apply($query);
    }

    /* Subscriptions */
    public function subscribe($userId = null) : self {
        $subscribed = $this->subscriptions()
            ->where(["user_id" => $userId ?: auth()->id()])
            ->exists();
        if ($subscribed) return $this;
        $this->subscriptions()->create([
           "user_id" => $userId ?: auth()->id()
        ]);
        return $this;
    }

    public function unsubscribe($userId = null) {
        $this->subscriptions()->delete([
            "user_id" => $userId ?: auth()->id()
        ]);
    }

    public function subscriptions() {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute() : bool {
        return $this->subscriptions()
            ->where(["user_id" => auth()->id()])
            ->exists();
    }

    /* Last Read */

    /**
     * @param User|null $user
     * @return bool
     * @throws \Exception
     */
    public function hasUpdatesFor (User $user = null) : bool {
        $user = $user ?: auth()->user();
        if (!$user) return true;
        /** @var User $user */
        $key = $this->getReadCacheKey($user);

        return $this->updated_at > cache($key);
    }

    /**
     * @param User|null $user
     * @return $this
     * @throws \Exception
     */
    public function read (User $user = null) : self {
        $user = $user ?: auth()->user();
        if (!$user) return $this;

        /** @var User $user */
        $key = $this->getReadCacheKey($user);

        cache()->forever($key, Carbon::now());

        return $this;
    }

    /**
     * @param User $user
     * @return string
     */
    protected function getReadCacheKey(User $user): string
    {
        $key = sprintf("users.%s.visits.%s", $user->id, $this->id);
        return $key;
    }

    /* Visits */
    public function visits() {
        return $this->visits;
    }

    public function getVisitsCountAttribute(): int
    {
        return $this->visits->count();
    }

    /* Path */

    /**
     * @return string
     */
    public function getPathAttribute () : string {
        return  $this->channel ? route('threads.show', [
            $this->channel->slug,
            $this->slug
        ]) : '';
    }

    /**
     * Determine how to thread been resolve from url
     *
     * @return string
     */
    public function getRouteKeyName() : string
    {
        return 'slug';
    }
}
