<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads() {
        $this->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class);

        $this->post(route('replies.store', $thread), $reply->toArray())
            ->assertRedirect();

        $this->get($thread->getRouteUrl())
            ->assertSee($reply->body);
    }

    /** @test */
    public function an_unauthenticated_user_may_not_participate_in_forum_threads() {
        $thread = create(Thread::class);
        $this->enableExceptionHandling()
            ->post(route('replies.store', $thread))
            ->assertRedirect(route('login'));
    }
    /** @test */
    public function a_thread_validation_test () {
        $this->publishThread(['body' => ''])
            ->assertSessionHasErrors('body');
    }

    public function publishThread($data = []) {
        $this->enableExceptionHandling()->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class, $data);
        return $this->post(route('replies.store', $thread), $reply->toArray());
    }

    /** @test */
    public function unatorized_users_cannot_delete_replies () {
        $reply =  create('App\Reply');
        $this->enableExceptionHandling()
            ->delete(route('replies.destroy', $reply))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('replies.destroy', $reply))
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_delete_own_replies () {
        $this->signIn();

        $reply =  create(Reply::class, [
           'user_id' => auth()->id()
        ]);
        $this->delete(route('replies.destroy', $reply))
            ->assertStatus(302);
        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);
    }
}
