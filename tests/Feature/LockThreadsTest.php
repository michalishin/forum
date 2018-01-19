<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_administrator_may_not_lock_test ()
    {
        $this->signIn();

        /** @var $thread Thread */
        $thread = create(Thread::class);

        $this->patchJson($thread->path, [
            'locked' => true
        ])->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function an_administrator_can_lock_test ()
    {
        $this->signIn(create(User::class, [
            'is_admin' => true
        ]));

        /** @var $thread Thread */
        $thread = create(Thread::class);

        $this->patchJson($thread->path, [
            'locked' => true
        ])->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_reply ()
    {
        $this->signIn();

        /** @var $thread Thread */
        $thread = create(Thread::class);

        $thread->lock();

        $this->post(route('replies.store', $thread), [
            'body' => 'test'
        ])->assertStatus(422);
    }
}
