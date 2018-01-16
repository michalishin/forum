<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreads extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_lock_any_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->lock();

        $this->post(route('replies.store', $thread), [
            'body' => 'test'
        ])->assertStatus(422);
    }
}
