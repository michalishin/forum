<?php

namespace Tests\Unit;

use App\Reply;
use App\Favorite;
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
}
