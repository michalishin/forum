<?php

namespace Tests\Unit;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function it_can_have_an_replies()
    {
        $thread = create(Thread::class);

        $reply = create(Reply::class, [
            'thread_id' => $thread
        ]);

        $this->assertTrue($thread->replies->contains($reply));
    }

    /** @test  */
    public function it_has_an_creator ()
    {
        $reply = create(Thread::class);
        $this->assertInstanceOf(User::class, $reply->creator);
    }

    /** @test */
    public function it_belongs_to_a_channel ()
    {
        $thread = create(Thread::class);
        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    public function it_has_route_params () {
        $thread = create(Thread::class);
        $this->assertEquals(
            $thread->getRouteUrl(),
            route('threads.show', [
                $thread->channel->slug,
                $thread->id
            ])
        );

    }
}