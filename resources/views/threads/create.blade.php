@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new threads</div>
                    <div class="panel-body">
                        <form action="{{route('threads.store')}}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="channel_id">Choose a channel:</label>
                                <select class="form-control"
                                        name="channel_id"
                                        id="channel_id">
                                    <option value="">
                                        Choose one...
                                    </option>
                                    @foreach($channels as $channel)
                                        <option value="{{$channel->id}}"
                                                {{ $channel->id == old('channel_id') ? 'selected' : '' }}>
                                            {{$channel->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="thread-title">Title:</label>
                                <input type="text"
                                       class="form-control"
                                       name="title"
                                       value="{{old('title')}}"
                                       id="thread-title"
                                       placeholder="Title">
                            </div>
                            <div class="form-group">
                                <label for="thread-body">Body:</label>
                                <textarea name="body"
                                          id="thread-body"
                                          rows="8"
                                          class="form-control">{{old('body')}}</textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Publish</button>
                            </div>
                            @if(count($errors))
                                <ul class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
