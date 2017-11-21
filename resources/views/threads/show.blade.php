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

                    <replies tread_id="{{$thread->id}}"
                             @removed="repliesCount --"
                             @added="repliesCount ++"
                    ></replies>

                    {{--{{$replies->links()}}--}}


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
