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
}
