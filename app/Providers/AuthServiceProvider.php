<?php

namespace App\Providers;

use App\Policies\NotificationPolicy;
use App\Policies\ReplyPolicy;
use App\Policies\ThreadsPolicy;
use App\Reply;
use App\Thread;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Thread::class => ThreadsPolicy::class,
        Reply::class => ReplyPolicy::class,
        DatabaseNotification::class => NotificationPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
