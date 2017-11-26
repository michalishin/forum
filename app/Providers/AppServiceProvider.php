<?php

namespace App\Providers;

use App\Channel;
use App\Rules\SpamRule;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->initViewsCompose();

        $this->initValidators();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    protected function initValidators()
    {
        app('validator')->extend('spam_free', 'App\Rules\SpamRule@passes');
    }

    protected function initViewsCompose()
    {
        view()->composer(['*'], function (View $view) {
            $channels = app('cache')->rememberForever('channels', function () {
                return Channel::all(['slug', 'name', 'id']);
            });
            return $view->with('channels', $channels);
        });
    }
}
