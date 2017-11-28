<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_activities()
    {
        $user = create(User::class);
        $this->signIn($user);
        $thread = create(Thread::class, [
            'user_id' => $user
        ]);
        $this->assertTrue($user->activities()->first()->subject->id === $thread->id);
    }

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply () {
        $user = create(User::class);

        $reply = create(Reply::class, [
           'user_id' => $user->id
        ]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function a_user_can_determine_their_avatar_path () {
        $user = create(User::class);

        $this->assertEquals(asset('storage/avatars/default.png'), $user->avatar());

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar());
    }
}
