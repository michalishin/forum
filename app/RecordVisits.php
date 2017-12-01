<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 02.12.17
 * Time: 0:29
 */

namespace App;


use Illuminate\Support\Facades\Redis;

trait RecordVisits
{

    public function getVisitsCountAttribute(): int
    {
        return Redis::get($this->getVisitsCacheKey()) ?? 0;
    }

    public function recordVisit(): Thread
    {
        Redis::incr($this->getVisitsCacheKey());

        return $this;
    }

    public function resetVisits()
    {
        Redis::del($this->getVisitsCacheKey());

        return $this;
    }

    /**
     * @return string
     */
    protected function getVisitsCacheKey(): string
    {
        return app()->environment('testing') ? "test.threads.{$this->id}.visits" : "threads.{$this->id}.visits";
    }
}