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
 * @property boolean confirmed
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
        'name', 'email', 'password', 'avatar_path', 'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'avatar_path', 'confirmation_token'
    ];

    /**
     * Custom attributes
     * @var array
     */
    protected $appends = ['avatar'];

    /**
     * Casts database attributes
     * @var array
     */
    protected $casts = [
      'confirmed' => 'boolean'
    ];

    public function activities () {
        return $this->hasMany(Activity::class)->with('subject');
    }

    public function lastReply () {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarAttribute () {
        return asset('storage/' . ($this->avatar_path ?: 'avatars/default.png')) ;
    }

    public function confirm ()
    {
        $this->confirmed = true;

        $this->save();
    }
}
