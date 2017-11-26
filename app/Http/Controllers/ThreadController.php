<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadsFilters;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ThreadController extends Controller
{

    /**
     * ThreadController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadsFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadsFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }
        return view('threads.index' ,compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|spam_free',
            'body' => 'required|spam_free',
            'channel_id' => 'required|exists:channels,id'
        ]);
        $thread = Thread::create(array_merge(
            $request->all(),
            ['user_id' => auth()->id()]
        ));
        return redirect($thread->getRouteUrl())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Channel $channel
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread)
    {
        $thread->visit()->append('is_subscribed_to');

        return view('threads.show', compact('thread'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->delete();
        if (request()->wantsJson()) {
            return response('OK', 204);
        }
        return redirect(route('threads.index'));
    }

    /**
     * @param Channel $channel
     * @param ThreadsFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadsFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->get();
        return $threads;
    }
}
