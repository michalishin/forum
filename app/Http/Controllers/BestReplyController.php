<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BestReplyController extends Controller
{
    /**
     * BestReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Set reply as best
     *
     * @param Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store (Reply $reply) {
        $this->authorize('update', $reply->thread);

        $reply->thread->markBestReply($reply);
    }
}
