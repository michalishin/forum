<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
   public function a_user_can_subscribe_to_thread() {
        $thread = create(Thread::class);
        $this->signIn();

        $this->post(route('threads.subscribe', $thread))
            ->assertStatus(200);

        $this->assertDatabaseHas('thread_subscriptions', [
            'thread_id' => $thread->id,
            'user_id' => auth()->id()
        ]);
   }

    /** @test */
    public function a_user_can_unsubscribe_to_thread() {
        $thread = create(Thread::class);
        $this->signIn();

        $thread->subscribe();

        $this->delete(route('threads.unsubscribe', $thread))
            ->assertStatus(200);

        $this->assertDatabaseMissing('thread_subscriptions', [
            'thread_id' => $thread->id,
            'user_id' => auth()->id()
        ]);
    }
}
