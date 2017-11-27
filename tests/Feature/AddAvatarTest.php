<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_members_can_upload_avatar()
    {
        $user = create(User::class);
        $this->postJson(route('user.avatar.store', $user), [])
            ->assertStatus(401);
    }

    /** @test */
    public function only_user_can_upload_own_avatar()
    {
        $this->signIn();
        $user = create(User::class);
        $this->postJson(route('user.avatar.store', $user), [])
            ->assertStatus(403);
    }

    /** @test */
    public function a_only_valid_avatar_must_be_provided()
    {
        $this->signIn();
        $this->postJson(route('user.avatar.store', auth()->user()), [
            'avatar' => 'not-an_image'
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_may_add_an_avatar () {
        $this->signIn();
        Storage::fake('public');

        $this->postJson(route('user.avatar.store', auth()->user()), [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ])->assertStatus(200);

        Storage::disk('public')->assertExists(auth()->user()->fresh()->avatar_path);
    }
}
