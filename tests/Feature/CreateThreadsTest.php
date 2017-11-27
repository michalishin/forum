<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;
   /** @test */
   public function an_authenticated_user_may_create_new_threads() {
        $thread = factory(Thread::class)->raw();
        $response = $this->publishThread($thread);
        $response->assertRedirect($response->headers->get('Location'));

        $this->get(route('threads.index'))
            ->assertSee($thread['title'])
            ->assertSee($thread['body']);

   }
    /** @test */
   public function guest_may_not_create_threads () {
       $this->withExceptionHandling();

       $this->get(route('threads.create'))
           ->assertRedirect(route('login'));

       $this->post(route('threads.store'))
           ->assertRedirect(route('login'));
   }

   /** @test */
   public function an_authenticated_user_may_visit_create_thread_page () {
       $this->signIn();
       $this->get(route('threads.create'))
           ->assertStatus(200);
   }

   /** @test */
   public function a_thread_validation_test () {
       $this->publishThread(['title' => ''])
           ->assertSessionHasErrors('title');

       $this->publishThread(['body' => ''])
           ->assertSessionHasErrors('body');

       $this->publishThread(['channel_id' => ''])
           ->assertSessionHasErrors('channel_id');

       $this->publishThread(['channel_id' => 0])
           ->assertSessionHasErrors('channel_id');
   }

   public function publishThread($data = []) {
       $this->withExceptionHandling()->signIn();
       $thread = make(Thread::class,$data);
       return $this->post(route('threads.store'), $thread->toArray());
   }
}
