<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @var  Trending */
    private $trending;

    public function setUp()
    {
        parent::setUp();

        $this->trending = resolve(Trending::class);

        $this->trending->reset();
    }

    /** @test */
    public function it_increment_a_thread_score_each_time_it_is_read()
    {
        $this->assertCount(0, $this->trending->get());

        $thread = create(Thread::class);

        $this->get($thread->path);

        $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
