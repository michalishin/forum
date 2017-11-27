<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_user_in_a_reply_are_notified()
    {
        $this->signIn();
        $user = create(User::class);
        create(Thread::class);

        $reply = create(Reply::class, [
            'body' => 'Hello world @' . $user->name . ' 123 @test'
        ]);

        $this->assertCount(1, $user->unreadNotifications);
    }

    /** @test */
    public function it_cat_all_mentioned_user_by_async_request() {
        $user = create(User::class, ['name' => 'test']);
        $user2 = create(User::class, ['name' => 'best']);
        $users = $this->getJson(route('username.search') . '?name=t')->json();

        $this->assertContains($user->name, $users);
        $this->assertNotContains($user2->name, $users);
    }
}
