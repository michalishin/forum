<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Auth\AuthManager;
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
     * @return \Illuminate\Http\Response
     */
    public function index(Thread $thread)
    {
        return $thread->replies()->latest()->paginate(5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Thread  $thread
     * @param  AuthManager $auth
     * @return \Illuminate\Http\Response
     */
    public function store(Thread $thread, Request $request, AuthManager $auth)
    {
        $request->validate([
            'body' => 'required'
        ]);
        $reply = $thread->replies()->create([
            'body' => $request->body,
            'user_id' => $auth->id()
        ]);

        if (request()->expectsJson()) {
            $reply->load('owner');
            return $reply;
        }

        return back()
            ->with('flash', 'Your reply has been left.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $request->validate([
            'body' => 'required'
        ]);

        $reply->update($request->all());
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

        if (request()->expectsJson()) {
            return response('OK');
        }
        return back();
    }
}
