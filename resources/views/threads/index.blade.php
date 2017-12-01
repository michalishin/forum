@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @forelse($threads as $thread)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <div class="flex">
                                    <h4>
                                        <a href="{{$thread->getRouteUrl()}}">
                                            @if ($thread->hasUpdatesFor(auth()->user()))
                                                <strong>
                                                    {{$thread->title}}
                                                </strong>
                                            @else
                                                {{$thread->title}}
                                            @endif
                                        </a>
                                    </h4>
                                    <h5>Posted By:
                                        <a href="{{ route('user.profile', $thread->creator->name) }}">
                                            {{ $thread->creator->name }}
                                        </a>
                                    </h5>
                                </div>
                                <a href="{{$thread->getRouteUrl()}}">
                                    {{$thread->replies_count}} {{str_plural('reply',$thread->replies_count)}}
                                </a>
                            </div>
                        </div>
                        <article class="panel-body">
                            {{$thread->body}}
                        </article>
                        <div class="panel-footer">
                            {{$thread->visits_count}} {{str_plural('Visit', $thread->visits_count)}}
                        </div>
                    </div>
                @empty
                    <p>There are no relevant results in this time</p>
                @endforelse
                <div class="text-center">
                    {{$threads->links()}}
                </div>
            </div>
            <div class="col-md-4">
                @if (count($trends))
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Trending Threads
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                @foreach($trends as $trend)
                                    <li class="list-group-item">
                                        <a href="{{ $trend->path }}">
                                            {{ $trend->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
