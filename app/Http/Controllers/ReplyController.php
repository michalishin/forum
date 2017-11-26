<?php

namespace App\Http\Controllers;

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model
     * @internal param AuthManager $auth
     */
    public function store(Thread $thread, Request $request)
    {
        $request->validate([
            'body' => 'required|spam_free'
        ]);

        $reply = $thread->replies()->create([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return $reply->load('owner');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return Reply
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $request->validate([
            'body' => 'required|spam_free'
        ]);

        try {
            $reply->update($request->all());
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

        return $reply->load('owner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        return response('OK');
    }
}
