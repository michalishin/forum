<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 02.12.17
 * Time: 0:42
 */

namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    /**
     * @var Thread
     */
    private $thread;

    /**
     * Visits constructor.
     * @param Thread $thread
     */
    public function __construct(Thread $thread)
    {

        $this->thread = $thread;
    }

    /**
     * @return Visits
     */
    public function record () : self {
        Redis::incr($this->cacheKey());

        return $this;
    }

    /**
     * @return Visits
     */
    public function reset () : self {
        Redis::del($this->cacheKey());

        return $this;
    }

    /**
     * @return int
     */
    public function count () : int {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function cacheKey () {
        return app()->environment('testing')
            ? "test.threads.{$this->thread->id}.visits"
            : "threads.{$this->thread->id}.visits";
    }
}