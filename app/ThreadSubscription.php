<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

/**
 * @property User user
 * @property int user_id
 */
class ThreadSubscription extends Model
{
    protected $fillable = ['user_id'];

    public function user () {
        return $this->belongsTo(User::class);
    }
}
