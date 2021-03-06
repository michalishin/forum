<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * A route for favorite replies
     * @param Reply $reply
     * @return mixed
     */
    public function store(Reply $reply) {
        $reply->favorite();
    }

    /**
     * A route for un_favorite replies
     * @param Reply $reply
     * @return mixed
     */
    public function destroy(Reply $reply) {
        $reply->unFavorite();
    }
}
