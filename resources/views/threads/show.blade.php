@extends('layouts.app')

@section('content')
    <thread-view inline-template
                 v-cloak
                 :initial-replies-count="{{$thread->replies_count}}">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                            <span class="flex">
                                <a href="{{route('user.profile', $thread->creator)}}"> {{$thread->creator->name}} </a>
                                posted:
                                {{$thread->title}}
                            </span>

                                @can('update', $thread)
                                    <form action="{{route('threads.destroy', $thread)}}" method="POST">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <button class="btn btn-link">
                                            Delete thread
                                        </button>
                                    </form>
                                @endcan

                            </div>
                        </div>

                        <div class="panel-body">
                            <article>
                                <h4> {{$thread->title}}</h4>
                                <div class="body">
                                    {{$thread->body}}
                                </div>
                            </article>
                        </div>
                    </div>

                    <replies :data="{{$thread->replies}}" @removed="repliesCount--"></replies>

                    {{--{{$replies->links()}}--}}

                    @auth
                        <form method="POST" action="{{route('replies.store', $thread)}}">
                            {{csrf_field()}}
                            <div class="form-group">
                    <textarea name="body"
                              id="reply-body"
                              placeholder="Have something to say?"
                              rows="5"
                              class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-default">Post</button>
                        </form>
                    @endauth
                    @guest
                        <p class="text-center">Please <a href="{{route('login')}}">sign in</a> to participate in this discussion</p>
                    @endguest
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            This thread wash publish {{$thread->created_at->diffForHumans()}}
                            by<a href="{{route('user.profile',$thread->creator)}}"> {{$thread->creator->name}} </a>
                            and currently has
                            <span v-text="repliesCount"></span>
                            {{str_plural('comment', $thread->replies_count)}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
