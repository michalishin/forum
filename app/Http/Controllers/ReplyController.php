<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReplyRequest;
use App\Http\Requests\DeleteReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Reply;
use App\Rules\SpamRule;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Thread $thread)
    {
        return $thread->replies()->latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Thread $thread
     * @param CreateReplyRequest|Request $request
     * @return \Illuminate\Database\Eloquent\Model
     * @internal param CreateReplyRequest $createPostForm
     * @internal param AuthManager $auth
     */
    public function store(Thread $thread, CreateReplyRequest $request)
    {
        return $request->persist($thread)->load('owner');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateReplyRequest|Request $request
     * @param  \App\Reply $reply
     * @return Reply
     */
    public function update(UpdateReplyRequest $request, Reply $reply)
    {
        return $request->persist($reply)->load('owner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteReplyRequest $request
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteReplyRequest $request,Reply $reply)
    {
        $request->persist($reply);

        return response('OK');
    }
}
