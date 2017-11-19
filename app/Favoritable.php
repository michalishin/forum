<?php
/**
 * Created by PhpStorm.
 * User: vmikhalishinamd
 * Date: 23.10.17
 * Time: 20:54
 */

namespace App;


trait Favoritable
{

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        if (!$this->is_favorited) {
            return $this->favorites()->create(['user_id' => auth()->id()]);
        }
    }

    public function unFavorite()
    {
        if ($this->is_favorited) {
            return $this->favorites()->delete(['user_id' => auth()->id()]);
        }
    }

    public function getIsFavoritedAttribute(): bool
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute () {
        return $this->favorites->count();
    }
}