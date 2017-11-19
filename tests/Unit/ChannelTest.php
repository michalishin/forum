<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function it_has_a_thread()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, [
            'channel_id' => $channel->id
        ]);
        $this->assertTrue($channel->threads->contains($thread));
    }
}
