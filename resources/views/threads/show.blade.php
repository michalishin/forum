@extends('layouts.app')

@section('content')
    <thread-view inline-template
                 v-cloak
                 :lock="{{json_encode($thread->locked)}}"
                 :initial-replies-count="{{$thread->replies_count}}">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <img src="{{ $thread->creator->avatar }}" width="25" height="25" class="mr-1">

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

                    <replies tread_slug="{{$thread->slug}}"
                             :locked="locked"
                             @removed="repliesCount --"
                             @added="repliesCount ++">
                    </replies>

                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>
                                This thread wash publish {{$thread->created_at->diffForHumans()}}
                                by<a href="{{route('user.profile',$thread->creator)}}"> {{$thread->creator->name}} </a>
                                and currently has
                                <span v-text="repliesCount"></span>
                                {{str_plural('comment', $thread->replies_count)}}
                            </p>
                            <p>
                                <subscribe-button
                                        id="{{$thread->id}}"
                                        :active="{{$thread->is_subscribed_to ? 'true' : 'false'}}"
                                ></subscribe-button>

                                <lock-button
                                        @lock="locked = $event"
                                        :thread="{{$thread}}"
                                ></lock-button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
