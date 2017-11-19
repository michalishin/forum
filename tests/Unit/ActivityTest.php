<?php

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_record_activity_when_a_thread_is_created () {
        $this->signIn();
        $thread = create(Thread::class);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $activity = Activity::first();
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_record_activity_when_a_reply_is_created () {
        $this->signIn();
        $reply = create(Reply::class);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_reply',
            'user_id' => auth()->id(),
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }

    /** @test */
    public function it_fetches_a_activity_feed_for_any_user () {
        $this->signIn();

        create(Thread::class, ['user_id' => auth()->id()], 2);

        $activity = Activity::first();
        $activity->created_at = Carbon::now()->subWeek();
        $activity->save();


        $feed = Activity::feed(auth()->user(), 50);
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->toDateString()
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->toDateString()
        ));
    }
}
