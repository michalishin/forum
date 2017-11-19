<?php

namespace Tests;

use App\Exceptions\Handler;
use App\User;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $oldException;
    public function setUp() {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    public function signIn ($user = null) {
        $user = $user ?  : create(User::class);
        $this->be($user);
        return $user;
    }

    public function disableExceptionHandling () {
        $this->oldException = app(ExceptionHandler::class);
        $container = app(Container::class);
        $this->app->instance(ExceptionHandler::class, new class ($container) extends Handler {
            public function report(Exception $exception) {}
            public function render($request, Exception $exception) {throw $exception;}
        });
        return $this;
    }

    public function enableExceptionHandling () {
        $this->app->instance(ExceptionHandler::class, $this->oldException);
        return $this;
    }
}
