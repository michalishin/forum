<?php

namespace Tests;

use App\Exceptions\Handler;
use App\User;
use Exception;
use Faker\Generator as Faker;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
        \Schema::enableForeignKeyConstraints();
    }


    public function signIn ($user = null) {
        $user = $user ?  : create(User::class);
        $this->be($user);
        return $user;
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getFaker()
    {
        $faker = app(Faker::class);
        return $faker;
    }
}
