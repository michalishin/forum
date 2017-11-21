<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store ($channelID, Thread $thread) {
        $thread->subscribe();
    }

    public function destroy ($channelID, Thread $thread) {
        $thread->unsubscribe();
    }
}
