@component('user.activities.activity')

    @slot('header')
        <a href="{{route('user.profile', $subject->creator)}}"> {{$subject->creator->name}} </a>
        posted thread:
        <a href="{{$subject->getRouteUrl()}}">{{$subject->title}}</a>
    @endslot

    @slot('body')
        {{$subject->body}}
    @endslot

@endcomponent
