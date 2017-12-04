<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_te_best_reply ()
    {
        $this->signIn();

        $thread = create(Thread::class, [
            'user_id' => auth()->id()
        ]);

        $replies = create(Reply::class, [
           'thread_id' => $thread->id
        ], 2);

        $this->postJson(route('best-replies.store', $replies[1]->id));

        $this->assertFalse($replies[0]->fresh()->is_best);

        $this->assertTrue($replies[1]->fresh()->is_best);
    }

    /** @test */
    public function only_the_thread_creator_can_set_best_reply () {
        $thread = create(Thread::class);
        $reply= create(Reply::class, ['thread_id' => $thread->id]);

        $this->postJson(route('best-replies.store', $reply))
            ->assertStatus(401);
        $this->assertFalse($reply->fresh()->is_best);

        $this->signIn();
        $this->postJson(route('best-replies.store', $reply))
            ->assertStatus(403);
        $this->assertFalse($reply->fresh()->is_best);

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->postJson(route('best-replies.store', $reply))
            ->assertStatus(200);
        $this->assertTrue($reply->fresh()->is_best);
    }
}
