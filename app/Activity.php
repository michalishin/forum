<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['user_id', 'type'];

    public function subject () {
        return $this->morphTo();
    }

    public static function feed ($user, $take = 50) {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->toDateString();
            });
    }
}
