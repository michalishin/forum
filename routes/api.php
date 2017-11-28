<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

app('router')->get('/users', 'Api\UsersController@index')
    ->name('username.search');

app('router')->post('/users/{user}/avatar', 'Api\UsersAvatarController@store')
    ->name('user.avatar.store');


