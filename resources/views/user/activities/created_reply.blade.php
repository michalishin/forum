@component('user.activities.activity')

    @slot('header')
        <a href="{{route('user.profile', $subject->owner)}}"> {{$subject->owner->name}} </a>
        reply to
        "<a href="{{$subject->thread->path}}">{{$subject->thread->title}}</a>"
    @endslot

    @slot('body')
        {{$subject->body}}
    @endslot

@endcomponent