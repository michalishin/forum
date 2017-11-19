<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function a_user_has_a_profile()
    {
        $user = create(User::class);
        $this->get(route('user.profile', $user))
            ->assertSee($user->name);
    }

    /** @test */
    public function profiles_display_all_threads_created_by_the_associated_user() {
        $user = create(User::class);
        $this->signIn($user);
        $thread = create(Thread::class, [
           'user_id' => $user
        ]);
        $this->get(route('user.profile', $user))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
