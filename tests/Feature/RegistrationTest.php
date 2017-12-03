<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Mail::fake();
    }

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration ()
    {
        $this->register();

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function a_user_can_fully_confirm_their_email_addresses ()
    {
        $user = $this->register();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads.index'));

        $this->assertTrue($user->fresh()->confirmed);
    }

    /** @test */
    public function confirming_an_invalid_confirmation_token ()
    {
        $this->get(route('register.confirm', ['token' =>  'invalid']))
            ->assertStatus(404);
    }

    /**
     * @return User
     */
    private function register() : User
    {
        $user = make(User::class);

        $this->post(route('register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        return User::first();
    }
}
