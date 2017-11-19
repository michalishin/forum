@component('user.activities.activity')

    @slot('header')
        <a href="{{$subject->favorited->getRouteUrl()}}"> {{$user->name}}
        favorited a reply:
        </a>
    @endslot

    @slot('body')
       {{$subject->favorited->body}}
    @endslot

@endcomponent
