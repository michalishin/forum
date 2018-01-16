<?php

namespace Tests\Unit;

use App\Reply;
use App\Favorite;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function it_has_an_owner()
    {
        $reply = create(Reply::class);
        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function it_has_a_favorite () {
        $reply = create(Reply::class);
        $favorite = create(Favorite::class, [
            'favorited_id' => $reply,
            'favorited_type' => get_class($reply)
        ]);
        $this->assertTrue($reply->favorites->contains($favorite));
    }

    /** @test */
    public function it_knows_if_it_was_just_published () {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_mentioned_users () {
        $user = create(User::class, [], 3);

        $reply = create(Reply::class,[
            'body' => '@' . $user[0]->name . ' @' . $user[1]->name . ' @' . $user[2]->name
        ]);

        $this->assertCount(3, $reply->mentionedUsers());
        $this->assertEquals($user->first()->id, $reply->mentionedUsers()->first()->id);
    }

    /** @test */
    public function it_wraps_mentioned_users_in_the_body_within_anchor_tags () {
        $user = create(User::class, []);

        $reply = create(Reply::class,[
            'body' => 'Hello @' . $user->name
        ]);

        $this->assertEquals(
            $reply->body,
            "Hello <a href=\"/profiles/{$user->name}\">@{$user->name}</a>"
        );
    }

    /** @test */
    public function it_knows_if_it_is_the_best () {
        $reply = create(Reply::class);

        $this->assertFalse($reply->is_best);

        $reply->thread->update([
           'best_reply_id' => $reply->id
        ]);


        $this->assertTrue($reply->fresh()->is_best);
    }
}
