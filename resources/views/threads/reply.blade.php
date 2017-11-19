<reply :attributes="{{$reply}}" inline-template v-cloak>
    <div id="reply-{{$reply->id}}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="{{route('user.profile', $reply->owner)}}"> {{$reply->owner->name}} </a> said
                    {{$reply->created_at->diffForHumans()}}...
                </h5>
                @auth
                    <div>
                        <favorite :reply="{{$reply}}"></favorite>
                    </div>
                @endauth
            </div>

        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        @can('delete', $reply)
            <div class="panel-footer level">
                <div class="btn btn-xs mr-1" @click="editing = true">Edit</div>
                <div class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</div>
            </div>
        @endcan
    </div>
</reply>