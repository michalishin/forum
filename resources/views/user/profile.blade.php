@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>{{$user->name}}</h1>
                    @can('update', $user)
                        <form method="POST"
                              action="{{ route('user.avatar.store', $user) }}"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="file" name="avatar">
                            <button class="btn btn-primary">Add avatar</button>
                        </form>
                    @endcan
                    <img src="{{ $user->avatar() }}" width="50" height="50">
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
