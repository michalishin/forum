<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string slug
 * @property Collection threads
 * @property integer id
 */
class Channel extends Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function threads() {
        return $this->hasMany(Thread::class);
    }
}
