<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int id
 * @property Activity activity
 * @property Collection unreadNotifications
 * @property string name
 * @property Reply lastReply
 * @property null|string avatar_path
 */
class User extends Authenticatable
{
    use Notifiable;

    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function activities () {
        return $this->hasMany(Activity::class)->with('subject');
    }

    public function lastReply () {
        return $this->hasOne(Reply::class)->latest();
    }
}
