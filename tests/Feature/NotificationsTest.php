<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_subscribe_user_must_receive_a_notify_but_not_from_current_user() {
        $this->signIn();
        $thread = create(Thread::class)
            ->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        create(Reply::class, [
            'user_id' => auth()->id(),
            'thread_id' => $thread
        ]);


        $this->assertCount(0, auth()->user()->fresh()->notifications);

        create(Reply::class, ['thread_id' => $thread]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
}
