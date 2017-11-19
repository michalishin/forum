<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Collection favorites
 * @property Thread thread
 * @property integer id
 * @property integer user_id
 */
class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $with = ['owner', 'thread', 'favorites'];
    protected $fillable = ['body', 'user_id'];

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
