<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Redis::del('trending_threads');
    }

    /** @test */
    public function it_increment_a_thread_score_each_time_it_is_read()
    {
        $this->assertCount(0, Redis::zrevrange('trending_threads', 0, -1));

        $thread = create(Thread::class);

        $this->get($thread->getRouteUrl());

        $trending = Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, json_decode($trending[0])->title);
    }
}
