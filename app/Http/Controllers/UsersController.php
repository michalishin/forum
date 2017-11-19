<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show(User $user) {
        $activities = Activity::feed($user, 50);
        return view('user.profile', compact('user', 'activities'));
    }
}
