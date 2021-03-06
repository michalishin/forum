<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads() {
        $this->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class);

        $this->postJson(route('replies.store', $thread), $reply->toArray())
            ->assertStatus(200);

        $this->assertDatabaseHas('replies', [
           'body' =>  $reply->body
        ]);

        $this->getJson(route('replies.index', $thread))
            ->assertJsonFragment(['body' => $reply->body]);
    }

    /** @test */
    public function an_unauthenticated_user_may_not_participate_in_forum_threads() {
        $thread = create(Thread::class);
        $this->withExceptionHandling()
            ->post(route('replies.store', $thread))
            ->assertRedirect(route('login'));
    }
    /** @test */
    public function a_thread_validation_test () {
        $this->publishThread(['body' => ''])
            ->assertSessionHasErrors('body');
    }

    public function publishThread($data = []) {
        $this->withExceptionHandling()->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class, $data);
        return $this->post(route('replies.store', $thread), $reply->toArray());
    }

    /** @test */
    public function unatorized_users_cannot_delete_replies () {
        $reply =  create('App\Reply');
        $this->withExceptionHandling()
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
            ->assertStatus(200);
        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);
    }

    /** @test */
    public function unatorized_users_cannot_update_replies () {
        $reply =  create('App\Reply');
        $this->withExceptionHandling()
            ->put(route('replies.update', $reply))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('replies.destroy', $reply))
            ->assertStatus(403);
    }

    /** @test */
    public function users_can_update_own_replies () {
        $faker = $this->getFaker();
        $this->signIn();

        $reply =  create(Reply::class, [
            'user_id' => auth()->id()
        ]);
        $body = $faker->paragraph();
        $this->put(route('replies.update', $reply), compact('body'))
            ->assertStatus(200);
        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $body
        ]);
    }

    /** @test */
    public function a_thread_update_validation_test () {
        $this->signIn();

        $this->withExceptionHandling()->signIn();
        $reply = create(Reply::class, [
            'user_id' => auth()->id()
        ]);

        $this->put(route('replies.update', $reply), [
            'body' => null
        ])->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_reply_that_contains_spam_may_not_be_created () {
        $this->withExceptionHandling()->signIn();
        $thread = create(Thread::class);
        $reply =  make(Reply::class, [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->postJson(route('replies.store', $thread), $reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function user_cant_save_one_reply_per_minute () {
        $user = $this->withExceptionHandling()->signIn();
        $thread = create(Thread::class);
        $reply =  make(Reply::class);

        $this->postJson(route('replies.store', $thread), $reply->toArray())
            ->assertStatus(200);

        $this->postJson(route('replies.store', $thread), $reply->toArray())
            ->assertStatus(429);
    }
}
