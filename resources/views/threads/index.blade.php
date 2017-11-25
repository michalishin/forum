@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @forelse($threads as $thread)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <h4 class="flex">
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

                                <a href="{{$thread->getRouteUrl()}}">
                                    {{$thread->replies_count}} {{str_plural('reply',$thread->replies_count)}}
                                </a>
                            </div>
                        </div>
                        <article class="panel-body">
                            {{$thread->body}}
                        </article>
                    </div>
                @empty
                    <p>There are no relevant results in this time</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
