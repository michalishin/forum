<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_subscribe_user_must_receive_a_notify_but_not_from_current_user() {
        $this->signIn();
        $thread = create(Thread::class)
            ->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        create(Reply::class, [
            'user_id' => auth()->id(),
            'thread_id' => $thread
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        create(Reply::class, ['thread_id' => $thread]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications() {
        $this->signIn();
        $thread = create(Thread::class)
            ->subscribe();

        create(Reply::class, ['thread_id' => $thread]);

        $data = $this->getJson(route('user.notification.index', [
            auth()->user()->name
        ]))->json();

        $this->assertCount(1, $data);
    }

    /** @test */
    public function a_user_can_clear_a_notification () {
        $this->signIn();
        $thread = create(Thread::class)
            ->subscribe();

        create(Reply::class, [
            'thread_id' => $thread
        ]);

        $this->assertCount(1, auth()->user()->unreadNotifications);

        $this->delete(route('user.notification.delete', [
            auth()->user()->name,
            auth()->user()->unreadNotifications->first()
        ]));

        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }
}
