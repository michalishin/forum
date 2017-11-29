<?php


namespace App;


use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get () {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function reset () {
        Redis::del($this->cacheKey());
    }

    public function push (Thread $thread) {
        return  Redis::zincrby($this->cacheKey(), 1, json_encode([
            'id' => $thread->id,
            'title' => $thread->title,
            'path' => $thread->getRouteUrl()
        ]));
    }

    protected function cacheKey () {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }
}