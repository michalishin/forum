<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Thread
     */
    protected $thread;

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $thread = create(Thread::class);
        $response = $this->get(route('threads.index'));

        $response->assertSee($thread->title);
        $response->assertSee($thread->body);
    }

    /** @test */
    public function a_user_can_view_single_thread()
    {
        $thread = create(Thread::class,[
           'user_id' => create(User::class, [
             'name' => 'test_user'
           ])
        ]);
        $response = $this->get($thread->getRouteUrl());

        $response->assertSee($thread->title);
        $response->assertSee($thread->body);
        $response->assertSee($thread->creator->name);

    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread () {
        $thread = create(Thread::class);
        $reply = create(Reply::class, [
           'thread_id' => $thread
        ]);
        $response = $this->get($thread->getRouteUrl());
        $response->assertSee($reply->body);
        $response->assertSee($reply->created_at->diffForHumans());
        $response->assertSee($reply->owner->name);
    }

    /** @test */
    public function a_user_can_filter_threads_by_channel () {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, [
            "channel_id" => $channel
        ]);
        $threadNotInChannel = create(Thread::class, [
            "channel_id" => create(Channel::class)
        ]);
        $this->get(route('channel', $channel))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }
    /** @test */
    public function a_user_can_filter_threads_by_user () {
        $user = create(User::class);
        $threadByUser = create(Thread::class, [
            'user_id' => $user
        ]);
        $threadNotByUser = create(Thread::class);
        $this->get(route('threads.index') . '?by=' . $user->name)
            ->assertSee($threadByUser->title)
            ->assertDontSee($threadNotByUser->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity() {
        foreach ([0,3,2] as $count) {
            create(Reply::class,[
                'thread_id' => create(Thread::class)
            ], $count);
        }
        $response = $this->getJson(route('threads.index') . '?popularity=1')->json();
        $this->assertEquals([3,2,0], array_column($response,'replies_count'));
        $response = $this->getJson(route('threads.index') . '?popularity=0')->json();
        $this->assertEquals([0,2,3], array_column($response,'replies_count'));
    }

    /** @test */
    public function a_guest_can_not_delete_thread () {
        $user = create(User::class);
        $thread = create(Thread::class);

        $this->enableExceptionHandling()
            ->delete(route('threads.destroy', $thread))
            ->assertRedirect('login');
        $this->assertDatabaseHas('threads', $thread->toArray());
    }

    /** @test */
    public function a_thread_can_be_delete_by_owner () {
        $this->signIn();

        $thread = create(Thread::class, [
            'user_id' => auth()->user()
        ]);

        $reply = create(Reply::class, [
            'thread_id' => $thread->id,
            'user_id' => auth()->user()
        ]);



        $this->json('DELETE', route('threads.destroy', $thread))
            ->assertStatus(204);
        $this->assertDatabaseMissing('threads', $thread->toArray());
        $this->assertDatabaseMissing('replies', $reply->toArray());
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);
    }

    /** @test */
    public function a_thread_can_not_be_delete_by_not_owner () {
        $user = create(User::class);
        $thread = create(Thread::class);

        $this->signIn($user);
        $this->enableExceptionHandling()
            ->delete(route('threads.destroy', $thread))
            ->assertStatus(403);
        $this->assertDatabaseHas('threads', $thread->only(['id']));
    }
}
