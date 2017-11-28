<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

app('router')->get('/', function () {
    return view('welcome');
});


app('router')->get('/threads/{thread}/replies', 'ReplyController@index')
    ->name('replies.index');

app('router')
    ->get('threads/{channel}/{thread}', 'ThreadController@show')
    ->name('threads.show');

app('router')
    ->post('threads/{thread}/subscriptions', 'ThreadSubscriptionController@store')
    ->name('threads.subscribe');

app('router')
    ->delete('threads/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')
    ->name('threads.unsubscribe');

app('router')
    ->resource('threads', 'ThreadController')
    ->only(['index', 'create', 'store', 'destroy']);



app('router')
    ->get('threads/{channel}', 'ThreadController@index')
    ->name('channel');

app('router')->post('/threads/{thread}/replies', 'ReplyController@store')
    ->name('replies.store');


app('router')->delete('/replies/{reply}/favorite', 'FavoriteController@destroy')
    ->name('replies.un_favorite');

app('router')->delete('/replies/{reply}', 'ReplyController@destroy')
    ->name('replies.destroy');

app('router')->put('/replies/{reply}', 'ReplyController@update')
    ->name('replies.update');

app('router')->post('/replies/{reply}/favorite', 'FavoriteController@store')
    ->name('replies.favorite');

app('router')->get('/profiles/{user}', 'UsersController@show')
    ->name('user.profile');

app('router')
    ->delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy')
    ->name('user.notification.delete');

app('router')
    ->get('/profiles/{user}/notifications', 'UserNotificationsController@index')
    ->name('user.notification.index');

app('router')->auth();

app('router')->get('home', 'HomeController@index')->name('home');

app('router')->post('/api/users/{user}/avatar', 'Api\UsersAvatarController@store')
    ->name('user.avatar.store');
