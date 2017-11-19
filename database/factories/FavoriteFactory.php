<?php

use Faker\Generator as Faker;

$factory->define(App\Favorite::class, function (Faker $faker) {
    $reply = factory(\App\Reply::class)->create();
    return [
        'user_id' => function () {
            return factory(\App\User::class)->create();
        },
        'favorited_id' => function () use ($reply) {
            return $reply;
        },
        'favorited_type' => function () use ($reply) {
            return get_class($reply);
        }
    ];
});
