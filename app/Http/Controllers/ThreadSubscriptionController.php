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

    public function store (Thread $thread) {
        $thread->subscribe();
    }

    public function destroy (Thread $thread) {
        $thread->unsubscribe();
    }
}
