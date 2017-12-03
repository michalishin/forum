<?php

namespace App\Providers;

use App\Events\ThreadHasNewReply;
use App\Listeners\NotifyMentionUsers;
use App\Listeners\NotifyThreadSubscribers;
use App\Listeners\SendEmailConfirmationRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ThreadHasNewReply::class => [
            NotifyThreadSubscribers::class,
            NotifyMentionUsers::class
        ],
        Registered::class => [
            SendEmailConfirmationRequest::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
