<?php

namespace Tests\Unit;

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
}
