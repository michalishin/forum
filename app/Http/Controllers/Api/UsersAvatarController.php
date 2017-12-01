<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersAvatarController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth');
    }

    /**
     * @param User $user
     * @param Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store (User $user, Request $request) {
        $this->authorize($user);
        $request->validate([
            'avatar' => 'required|image'
        ]);
        $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        $user->save();
    }
}
