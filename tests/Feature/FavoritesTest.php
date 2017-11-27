<?php

namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply () {
        $this->signIn();
        $reply = create(Reply::class);
        $this->post(route('replies.favorite', $reply));
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply_once () {
        $this->signIn();
        $reply = create(Reply::class);
        $this->post(route('replies.favorite', $reply));
        $this->assertCount(1, $reply->favorites);
        $this->post(route('replies.favorite', $reply));
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function a_guest_user_can_not_favorite_any_reply () {
        $reply = create(Reply::class);
        $this->withExceptionHandling()
            ->post(route('replies.favorite', $reply))
            ->assertRedirect(route('login'));

        $this->assertCount(0, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_un_favorite_any_reply () {
        $this->signIn();
        $reply = create(Reply::class);
        $reply->favorite();
        $this->delete(route('replies.un_favorite', $reply));
        $this->assertCount(0, $reply->fresh()->favorites);
    }
}
