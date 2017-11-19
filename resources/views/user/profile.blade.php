@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>{{$user->name}}</h1>
                </div>

                @foreach($activities as $date => $activity)
                    <h3 class="page-header">{{$date}}</h3>
                    @foreach($activity as $item)
                        @if (view()->exists("user.activities.{$item->type}"))
                            @include("user.activities.{$item->type}", ['subject' => $item->subject])
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
@endsection
